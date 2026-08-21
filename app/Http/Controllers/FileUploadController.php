<?php

namespace App\Http\Controllers;

use App\Models\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadController extends Controller
{
    /**
     * Allowed file types and their MIME types
     */
    protected $allowedTypes = [
        'design' => [
            'application/pdf',
            'image/jpeg',
            'image/png',
            'image/gif',
            'application/postscript', // AI, EPS
            'image/vnd.adobe.photoshop', // PSD
            'application/x-coreldraw', // CDR
            'application/zip',
            'application/x-rar-compressed',
        ],
        'payment_proof' => [
            'image/jpeg',
            'image/png',
            'application/pdf',
        ],
    ];

    /**
     * Maximum file sizes in bytes
     */
    protected $maxSizes = [
        'design' => 50 * 1024 * 1024, // 50 MB
        'payment_proof' => 5 * 1024 * 1024, // 5 MB
    ];

    /**
     * Upload file
     */
    public function upload(Request $request): JsonResponse
    {
        $this->validate($request, [
            'file' => 'required|file',
            'tipe' => 'required|in:design,payment_proof',
            'terkait_id' => 'nullable|string',
            'terkait_tipe' => 'nullable|string',
        ]);

        $file = $request->file('file');
        $type = $request->input('tipe');

        // Validate file type
        if (!in_array($file->getMimeType(), $this->allowedTypes[$type])) {
            return $this->error('Tipe file tidak diizinkan untuk ' . $type, 422);
        }

        // Validate file size
        if ($file->getSize() > $this->maxSizes[$type]) {
            $maxMB = $this->maxSizes[$type] / 1024 / 1024;
            return $this->error("Ukuran file maksimal {$maxMB} MB", 422);
        }

        // Generate unique filename
        $extension = $file->getClientOriginalExtension();
        $storedName = Str::uuid() . '.' . $extension;

        // Determine path
        $path = $type . '/' . date('Y/m');

        // Store file
        $disk = 'local';
        $fullPath = $file->storeAs($path, $storedName, $disk);

        // Create database record
        $uploadedFile = UploadedFile::create([
            'pengguna_id' => $request->auth->id ?? null,
            'nama_asli' => $file->getClientOriginalName(),
            'nama_disimpan' => $storedName,
            'jalur' => $fullPath,
            'penyimpanan' => $disk,
            'jenis_mime' => $file->getMimeType(),
            'ukuran' => $file->getSize(),
            'tipe' => $type,
            'terkait_id' => $request->input('terkait_id'),
            'terkait_tipe' => $request->input('terkait_tipe'),
        ]);

        return $this->success([
            'id' => $uploadedFile->id,
            'nama_asli' => $uploadedFile->nama_asli,
            'ukuran' => $uploadedFile->ukuran,
            'human_size' => $uploadedFile->human_size,
            'jenis_mime' => $uploadedFile->jenis_mime,
            'url' => $uploadedFile->url,
        ], 'File berhasil diupload');
    }

    /**
     * Get file info
     */
    public function show(string $id): JsonResponse
    {
        $file = UploadedFile::find($id);

        if (!$file) {
            return $this->error('File not found', 404);
        }

        return $this->success([
            'id' => $file->id,
            'nama_asli' => $file->nama_asli,
            'ukuran' => $file->ukuran,
            'human_size' => $file->human_size,
            'jenis_mime' => $file->jenis_mime,
            'tipe' => $file->tipe,
            'url' => $file->url,
            'created_at' => $file->created_at,
        ]);
    }

    /**
     * Download file
     */
    public function download(string $id)
    {
        $file = UploadedFile::find($id);

        if (!$file) {
            return response()->json(['success' => false, 'message' => 'File not found'], 404);
        }

        $storage = Storage::disk($file->penyimpanan);

        if (!$storage->exists($file->jalur)) {
            return response()->json(['success' => false, 'message' => 'File not found on storage'], 404);
        }

        // Get the full path to the file
        $fullPath = storage_path('app/' . $file->jalur);

        // Return download response with original filename and proper headers
        return response()->download($fullPath, $file->nama_asli, [
            'Content-Type' => $file->jenis_mime,
        ]);
    }

    /**
     * Delete file
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $file = UploadedFile::find($id);

        if (!$file) {
            return $this->error('File not found', 404);
        }

        // Check ownership (if user is logged in)
        $user = $request->auth;
        if ($user && $file->pengguna_id && $file->pengguna_id !== $user->id) {
            // Only allow delete own files unless admin
            if ($user->peran !== 'admin' && $user->peran !== 'super_admin') {
                return $this->error('Unauthorized', 403);
            }
        }

        // Delete from storage
        if (Storage::disk($file->penyimpanan)->exists($file->jalur)) {
            Storage::disk($file->penyimpanan)->delete($file->jalur);
        }

        // Delete database record
        $file->delete();

        return $this->success(null, 'File deleted successfully');
    }

    /**
     * Get files for related entity (Admin)
     */
    public function forRelated(Request $request): JsonResponse
    {
        $this->validate($request, [
            'terkait_tipe' => 'required|string',
            'terkait_id' => 'required|string',
        ]);

        $files = UploadedFile::forRelated(
            $request->input('terkait_tipe'),
            $request->input('terkait_id')
        )->get();

        return $this->success($files->map(function ($file) {
            return [
                'id' => $file->id,
                'nama_asli' => $file->nama_asli,
                'ukuran' => $file->ukuran,
                'human_size' => $file->human_size,
                'jenis_mime' => $file->jenis_mime,
                'tipe' => $file->tipe,
                'url' => $file->url,
                'created_at' => $file->created_at,
            ];
        }));
    }
}
