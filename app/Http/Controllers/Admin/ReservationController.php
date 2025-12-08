<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with(['user', 'book'])
            ->whereIn('status', ['pending', 'ready'])
            ->orderBy('reserved_date', 'desc')
            ->paginate(15);

        $stats = [
            'pending' => Reservation::where('status', 'pending')->count(),
            'ready' => Reservation::where('status', 'ready')->count(),
            'expired' => Reservation::where('status', 'expired')->count(),
            'borrowed' => Reservation::where('status', 'borrowed')->count(),
        ];

        return view('admin.reservations.index', compact('reservations', 'stats'));
    }

    public function history()
    {
        $reservations = Reservation::with(['user', 'book'])
            ->whereIn('status', ['borrowed', 'cancelled', 'expired'])
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('admin.reservations.history', compact('reservations'));
    }

    public function approve(Request $request, Reservation $reservation)
    {
        if ($reservation->status !== 'pending') {
            return back()->with('error', 'Reservasi sudah diproses sebelumnya.');
        }

        DB::beginTransaction();
        try {
            // Update status reservasi menjadi ready
            $reservation->update([
                'status' => 'ready',
                'notified_at' => now(),
                'expired_date' => Carbon::now()->addDays(3), // Batas 3 hari untuk diambil
            ]);

            DB::commit();
            return back()->with('success', 'Reservasi berhasil disetujui. Anggota akan diberitahu untuk mengambil buku.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function convert(Request $request, Reservation $reservation)
    {
        if ($reservation->status !== 'ready') {
            return back()->with('error', 'Hanya reservasi yang sudah ready yang bisa dikonversi menjadi peminjaman.');
        }

        $request->validate([
            'due_date' => 'required|date|after:today',
        ]);

        DB::beginTransaction();
        try {
            // Cek ketersediaan buku
            $book = $reservation->book;
            if ($book->available_copies < 1) {
                return back()->with('error', 'Buku tidak tersedia untuk dipinjam.');
            }

            // Buat data peminjaman baru
            $borrowing = Borrowing::create([
                'user_id' => $reservation->user_id,
                'book_id' => $reservation->book_id,
                'borrow_date' => now(),
                'due_date' => $request->due_date,
                'status' => 'approved',
                'notes' => 'Konversi dari reservasi #' . $reservation->id,
            ]);

            // Update stok buku
            $book->decrement('available_copies');

            // Update status reservasi
            $reservation->update([
                'status' => 'borrowed',
            ]);

            DB::commit();
            return redirect()->route('admin.reservasi.index')
                ->with('success', 'Reservasi berhasil dikonversi menjadi peminjaman.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function cancel(Request $request, Reservation $reservation)
    {
        if (!in_array($reservation->status, ['pending', 'ready'])) {
            return back()->with('error', 'Reservasi tidak dapat dibatalkan.');
        }

        $request->validate([
            'cancel_reason' => 'required|string|max:500',
        ]);

        $reservation->update([
            'status' => 'cancelled',
            'notes' => 'Dibatalkan: ' . $request->cancel_reason,
        ]);

        return back()->with('success', 'Reservasi berhasil dibatalkan.');
    }

    public function destroy(Reservation $reservation)
    {
        if (!in_array($reservation->status, ['borrowed', 'cancelled', 'expired'])) {
            return back()->with('error', 'Hanya reservasi yang sudah selesai/dibatalkan yang dapat dihapus.');
        }

        $reservation->delete();
        return back()->with('success', 'Data reservasi berhasil dihapus.');
    }
}
