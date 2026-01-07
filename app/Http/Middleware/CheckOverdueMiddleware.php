<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\PeminjamanWarkah;
use Carbon\Carbon;

class CheckOverdueMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek hanya di halaman peminjaman
        if ($request->routeIs('peminjaman.*')) {
            $today = Carbon::today();

            // Hitung peminjaman yang terlambat (untuk notifikasi UI saja)
            $overdueCount = PeminjamanWarkah::whereIn('status', ['Dipinjam', 'Terlambat'])
                ->whereDate('batas_peminjaman', '<', $today)
                ->count();

            if ($overdueCount > 0) {
                session()->flash('overdue_alert', [
                    'count' => $overdueCount,
                    'message' => "⚠️ Terdapat {$overdueCount} peminjaman yang terlambat!"
                ]);
            }

            // TIDAK kirim email di middleware!
            // Biarkan scheduler yang handle pengiriman email
        }

        return $next($request);
    }
}
