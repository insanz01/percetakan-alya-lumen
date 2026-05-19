<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NewsletterController extends Controller
{
    /**
     * Subscribe to newsletter (Public)
     */
    public function subscribe(Request $request): JsonResponse
    {
        $this->validate($request, [
            'email' => 'required|email|max:255',
        ]);

        $email = strtolower($request->input('email'));

        // Check if already subscribed
        $existing = NewsletterSubscriber::where('email', $email)->first();

        if ($existing) {
            if ($existing->aktif) {
                return $this->success(null, 'Email sudah terdaftar di newsletter kami.');
            } else {
                // Resubscribe
                $existing->resubscribe();
                return $this->success(null, 'Berhasil berlangganan kembali newsletter kami!');
            }
        }

        // Create new subscriber
        NewsletterSubscriber::create([
            'email' => $email,
            'aktif' => true,
        ]);

        return $this->success(null, 'Terima kasih! Email Anda berhasil didaftarkan untuk newsletter.');
    }

    /**
     * Unsubscribe from newsletter (Public)
     */
    public function unsubscribe(Request $request, string $token): JsonResponse
    {
        $subscriber = NewsletterSubscriber::where('token_berhenti', $token)->first();

        if (!$subscriber) {
            return $this->error('Link unsubscribe tidak valid.', 404);
        }

        if (!$subscriber->aktif) {
            return $this->success(null, 'Email sudah tidak berlangganan newsletter.');
        }

        $subscriber->unsubscribe();

        return $this->success(null, 'Email berhasil dihapus dari newsletter.');
    }

    /**
     * Get all subscribers (Admin)
     */
    public function index(Request $request): JsonResponse
    {
        $query = NewsletterSubscriber::query();

        // Filter by active status
        if ($request->has('active')) {
            $isActive = filter_var($request->input('active'), FILTER_VALIDATE_BOOLEAN);
            $query->where('aktif', $isActive);
        }

        // Search
        if ($request->has('search')) {
            $query->where('email', 'like', '%' . $request->input('search') . '%');
        }

        // Sort
        $sortBy = $request->input('sort_by', 'berlangganan_pada');
        $sortDir = $request->input('sort_dir', 'desc');
        $query->orderBy($sortBy, $sortDir);

        // Paginate
        $perPage = $request->input('per_page', 20);
        $subscribers = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $subscribers->items(),
            'meta' => [
                'current_page' => $subscribers->currentPage(),
                'last_page' => $subscribers->lastPage(),
                'per_page' => $subscribers->perPage(),
                'total' => $subscribers->total(),
            ],
        ]);
    }

    /**
     * Delete subscriber (Admin)
     */
    public function destroy(string $id): JsonResponse
    {
        $subscriber = NewsletterSubscriber::find($id);

        if (!$subscriber) {
            return $this->error('Subscriber not found', 404);
        }

        $subscriber->delete();

        return $this->success(null, 'Subscriber deleted successfully');
    }

    /**
     * Get statistics (Admin)
     */
    public function statistics(): JsonResponse
    {
        $stats = [
            'total' => NewsletterSubscriber::count(),
            'active' => NewsletterSubscriber::active()->count(),
            'unsubscribed' => NewsletterSubscriber::where('aktif', false)->count(),
            'today' => NewsletterSubscriber::whereDate('berlangganan_pada', Carbon::today())->count(),
            'this_month' => NewsletterSubscriber::whereBetween('berlangganan_pada', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth(),
            ])->count(),
        ];

        return $this->success($stats);
    }

    /**
     * Export subscribers (Admin)
     */
    public function export(): JsonResponse
    {
        $subscribers = NewsletterSubscriber::active()
            ->select('email', 'berlangganan_pada')
            ->orderBy('berlangganan_pada', 'desc')
            ->get();

        return $this->success([
            'subscribers' => $subscribers,
            'count' => $subscribers->count(),
            'exported_at' => Carbon::now()->toIso8601String(),
        ]);
    }
}
