<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\PeminjamanWarkah;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CheckOverdueMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek peminjaman yang terlambat setiap request ke halaman peminjaman
        if ($request->routeIs('peminjaman.*')) {
            $today = Carbon::today();

            $overdueBorrowings = PeminjamanWarkah::where('status', 'Dipinjam')
                ->whereDate('batas_peminjaman', '<', $today)
                ->get();

            if ($overdueBorrowings->count() > 0) {
                foreach ($overdueBorrowings as $peminjaman) {
                    // Update status
                    $peminjaman->update(['status' => 'Terlambat']);

                    // Kirim email jika belum pernah dikirim hari ini
                    $lastNotification = $peminjaman->updated_at->isToday();

                    if (!$lastNotification) {
                        try {
                            Mail::to($peminjaman->email)->send(
                                new \App\Mail\OverdueReminderMail($peminjaman)
                            );
                        } catch (\Exception $e) {
                            Log::error("Failed to send overdue email: " . $e->getMessage());
                        }
                    }
                }

                // Flash message untuk notifikasi di halaman
                session()->flash('overdue_alert', [
                    'count' => $overdueBorrowings->count(),
                    'message' => "Terdapat {$overdueBorrowings->count()} peminjaman yang terlambat!"
                ]);
            }
        }

        return $next($request);
    }
}
