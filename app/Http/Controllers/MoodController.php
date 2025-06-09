<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class MoodController extends Controller
{
    /**
     * Menyimpan atau memperbarui mood harian pengguna.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validasi Input
        $request->validate([
            'level' => ['required', 'in:senang,biasa,capek,stres'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $user = Auth::user();

        // 2. Simpan atau Update Data
        // Ini akan membuat entri baru jika belum ada untuk hari ini,
        // atau memperbaruinya jika pengguna mengubah mood di hari yang sama.
        $user->moods()->updateOrCreate(
            [
                'log_date' => now()->today() // Kunci unik untuk pencarian
            ],
            [
                'level' => $request->level, // Nilai yang akan diisi/diupdate
                'notes' => $request->notes
            ]
        );

        // 3. Redirect kembali ke Dashboard
        return redirect()->route('dashboard')->with('status', 'Mood hari ini berhasil disimpan!');
    }
}

