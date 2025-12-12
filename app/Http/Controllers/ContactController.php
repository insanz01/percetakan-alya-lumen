<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    /**
     * Submit contact form (Public)
     */
    public function submit(Request $request): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:20|max:5000',
        ]);

        $message = ContactMessage::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'subject' => $request->input('subject'),
            'message' => $request->input('message'),
            'status' => 'new',
        ]);

        // TODO: Send email notification to admin

        return $this->success([
            'id' => $message->id,
        ], 'Pesan berhasil dikirim. Kami akan merespons dalam 24 jam kerja.');
    }

    /**
     * Get all messages (Admin)
     */
    public function index(Request $request): JsonResponse
    {
        $query = ContactMessage::query();

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        // Search
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');
        $query->orderBy($sortBy, $sortDir);

        // Paginate
        $perPage = $request->input('per_page', 15);
        $messages = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $messages->items(),
            'meta' => [
                'current_page' => $messages->currentPage(),
                'last_page' => $messages->lastPage(),
                'per_page' => $messages->perPage(),
                'total' => $messages->total(),
            ],
        ]);
    }

    /**
     * Get single message (Admin)
     */
    public function show(string $id): JsonResponse
    {
        $message = ContactMessage::find($id);

        if (!$message) {
            return $this->error('Message not found', 404);
        }

        // Mark as read
        $message->markAsRead();

        return $this->success($message);
    }

    /**
     * Update message status (Admin)
     */
    public function updateStatus(Request $request, string $id): JsonResponse
    {
        $this->validate($request, [
            'status' => 'required|in:new,read,replied,archived',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $message = ContactMessage::find($id);

        if (!$message) {
            return $this->error('Message not found', 404);
        }

        $updateData = ['status' => $request->input('status')];

        if ($request->has('admin_notes')) {
            $updateData['admin_notes'] = $request->input('admin_notes');
        }

        if ($request->input('status') === 'replied') {
            $updateData['replied_at'] = Carbon::now();
        }

        $message->update($updateData);

        return $this->success($message, 'Status updated successfully');
    }

    /**
     * Delete message (Admin)
     */
    public function destroy(string $id): JsonResponse
    {
        $message = ContactMessage::find($id);

        if (!$message) {
            return $this->error('Message not found', 404);
        }

        $message->delete();

        return $this->success(null, 'Message deleted successfully');
    }

    /**
     * Get message statistics (Admin)
     */
    public function statistics(): JsonResponse
    {
        $stats = [
            'total' => ContactMessage::count(),
            'new' => ContactMessage::where('status', 'new')->count(),
            'read' => ContactMessage::where('status', 'read')->count(),
            'replied' => ContactMessage::where('status', 'replied')->count(),
            'archived' => ContactMessage::where('status', 'archived')->count(),
            'today' => ContactMessage::whereDate('created_at', Carbon::today())->count(),
            'this_week' => ContactMessage::whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek(),
            ])->count(),
        ];

        return $this->success($stats);
    }
}
