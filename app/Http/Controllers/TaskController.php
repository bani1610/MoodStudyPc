<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $tasks = Auth::user()->tasks()->orderBy('is_completed', 'asc')->orderBy('due_date', 'asc')->get();
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'nullable|date',
            'category' => 'required|in:ringan,sedang,berat',
            'priority' => 'required|in:rendah,sedang,tinggi',
        ]);

        Auth::user()->tasks()->create($request->all());

        return redirect()->route('tasks.index')->with('status', 'Tugas berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task): View
    {
        // Pastikan pengguna hanya bisa mengedit tugas miliknya
        abort_if(Auth::id() !== $task->user_id, 403, 'Tindakan tidak diizinkan.');

        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task): RedirectResponse
    {
        // Pastikan pengguna hanya bisa mengupdate tugas miliknya
        abort_if(Auth::id() !== $task->user_id, 403);

        // Bagian ini adalah yang terpenting untuk masalah Anda
        if ($request->has('is_completed')) {
            $task->update([
                'is_completed' => $request->is_completed,
                'completed_at' => $request->boolean('is_completed') ? now() : null,
            ]);
        } else {
            // ... logika untuk form edit lengkap
            $request->validate([
                'title' => 'required|string|max:255',
                'due_date' => 'nullable|date',
                'category' => 'required|in:ringan,sedang,berat',
                'priority' => 'required|in:rendah,sedang,tinggi',
            ]);
            $task->update($request->all());
        }

        return redirect()->route('tasks.index')->with('status', 'Tugas berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): RedirectResponse
    {
        // Pastikan pengguna hanya bisa menghapus tugas miliknya
        abort_if(Auth::id() !== $task->user_id, 403);

        $task->delete();

        return redirect()->route('tasks.index')->with('status', 'Tugas berhasil dihapus!');
    }
}
