<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = now('Asia/Jakarta')->today();

        // 1. Ambil Mood Hari Ini
        $todayMood = $user->moods()->whereDate('log_date', $today)->first();

        // 2. Dapatkan Saran Belajar Berdasarkan Mood
        $suggestion = $this->getStudySuggestion($todayMood);

        // 3. Ambil Tugas untuk Hari Ini dan yang Terlewat
        $tasksToday = $user->tasks()
            ->where('is_completed', false)
            ->whereDate('due_date', $today)
            ->orderBy('priority', 'desc')
            ->get();

        $overdueTasks = $user->tasks()
            ->where('is_completed', false)
            ->where('due_date', '<', $today)
            ->get();

        // 4. Hitung Progress Bar Tugas Hari Ini
        $totalTasksCount = $user->tasks()->whereDate('due_date', $today)->count();
        $completedTasksCount = $user->tasks()->whereDate('completed_at', $today)->where('is_completed', true)->count();
        $progressPercentage = ($totalTasksCount > 0) ? ($completedTasksCount / $totalTasksCount) * 100 : 0;

        // Kirim semua data yang dibutuhkan ke view
        return view('dashboard', [
            'todayMood' => $todayMood,
            'suggestion' => $suggestion,
            'tasksToday' => $tasksToday,
            'overdueTasks' => $overdueTasks,
            'progress' => round($progressPercentage),
        ]);
    }

    /**
     * Menghasilkan saran belajar berdasarkan objek mood.
     *
     * @param \App\Models\Mood|null $mood
     * @return array|null
     */
    private function getStudySuggestion($mood): ?array
    {
        if (!$mood) {
            return [
                'title' => 'Bagaimana Perasaanmu Hari Ini?',
                'advice' => 'Isi mood harianmu untuk mendapatkan saran belajar yang dipersonalisasi.',
                'category_suggestion' => 'neutral'
            ];
        }

        switch ($mood->level) {
            case 'senang':
                return [
                    'title' => 'Luar Biasa! 🔥 Semangatmu Sedang di Puncak!',
                    'advice' => 'Ini adalah waktu terbaik untuk mengerjakan tugas berat, memulai bab baru yang menantang, atau melakukan brainstorming proyek besar.',
                    'category_suggestion' => 'berat'
                ];
            case 'biasa':
                return [
                    'title' => 'Stabil dan Siap! 👍',
                    'advice' => 'Jaga momentum belajarmu. Coba review catatan, kerjakan tugas-tugas sedang, atau diskusikan materi dengan teman.',
                    'category_suggestion' => 'sedang'
                ];
            case 'capek':
            case 'stres':
                return [
                    'title' => 'Tenang, Jangan Terlalu Keras pada Diri Sendiri 🧘',
                    'advice' => 'Belajar ringan saja sudah merupakan sebuah kemajuan. Coba tonton video penjelasan singkat, baca ringkasan, atau atur jadwal belajarmu.',
                    'category_suggestion' => 'ringan'
                ];
            default:
                return null;
        }
    }
}
