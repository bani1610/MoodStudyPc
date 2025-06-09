<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class FocusSessionController extends Controller
{
    /**
     * Menampilkan halaman Fokus Mode dengan timer.
     */
    public function index(): View
    {
        return view('fokus.index');
    }

    /**
     * Menyimpan data sesi fokus setelah timer selesai.
     * Fungsi ini diharapkan dipanggil melalui JavaScript (Fetch API).
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'duration_minutes' => 'required|integer|min:1',
            'reflection' => 'nullable|string|max:500',
            'was_focused' => 'required|boolean',
        ]);

        Auth::user()->focusSessions()->create($request->all());

        return response()->json([
            'message' => 'Sesi fokus berhasil disimpan!'
        ]);
    }
}
