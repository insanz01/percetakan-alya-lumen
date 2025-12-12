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
            'type' => 'required|in:design,payment_proof',
            'related_id' => 'nullable|string',
            'related_type' => 'nullable|string',
        ]);

        $file = $request->file('file');
        $type = $request->input('type');

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
            'user_id' => $request->user()->id ?? null,
            'original_name' => $file->getClientOriginalName(),
            'stored_name' => $storedName,
            'path' => $fullPath,
            'disk' => $disk,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'type' => $type,
            'related_id' => $request->input('related_id'),
            'related_type' => $request->input('related_type'),
        ]);

        return $this->success([
            'id' => $uploadedFile->id,
            'name' => $uploadedFile->original_name,
            'size' => $uploadedFile->size,
            'human_size' => $uploadedFile->human_size,
            'mime_type' => $uploadedFile->mime_type,
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
            'name' => $file->original_name,
            'size' => $file->size,
            'human_size' => $file->human_size,
            'mime_type' => $file->mime_type,
            'type' => $file->type,
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

        $storage = Storage::disk($file->disk);

        if (!$storage->exists($file->path)) {
            return response()->json(['success' => false, 'message' => 'File not found on storage'], 404);
        }

        // Get the full path to the file
        $fullPath = storage_path('app/' . $file->path);

        // Return download response with original filename and proper headers
        return response()->download($fullPath, $file->original_name, [
            'Content-Type' => $file->mime_type,
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
        $user = $request->user();
        if ($user && $file->user_id && $file->user_id !== $user->id) {
            // Only allow delete own files unless admin
            if ($user->role !== 'admin' && $user->role !== 'super_admin') {
                return $this->error('Unauthorized', 403);
            }
        }

        // Delete from storage
        if (Storage::disk($file->disk)->exists($file->path)) {
            Storage::disk($file->disk)->delete($file->path);
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
            'related_type' => 'required|string',
            'related_id' => 'required|string',
        ]);

        $files = UploadedFile::forRelated(
            $request->input('related_type'),
            $request->input('related_id')
        )->get();

        return $this->success($files->map(function ($file) {
            return [
                'id' => $file->id,
                'name' => $file->original_name,
                'size' => $file->size,
                'human_size' => $file->human_size,
                'mime_type' => $file->mime_type,
                'type' => $file->type,
                'url' => $file->url,
                'created_at' => $file->created_at,
            ];
        }));
    }
}
