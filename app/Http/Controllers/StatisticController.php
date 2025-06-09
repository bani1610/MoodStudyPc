<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class StatisticController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $endDate = Carbon::now('Asia/Jakarta')->today();
        $startDate = $endDate->copy()->subDays(6);

        // 1. Data untuk Grafik Mood Mingguan
        list($moodLabels, $moodData) = $this->getWeeklyMoodData($user, $startDate, $endDate);

        // 2. Data untuk Grafik Tugas Selesai Mingguan
        list($taskLabels, $taskData) = $this->getWeeklyCompletedTasksData($user, $startDate, $endDate);

        // 3. Insight Otomatis
        $insights = $this->generateInsights($taskData, $moodData, $taskLabels);

        // INI BARIS YANG DIPERBAIKI
        return view('statistik.index', compact('moodLabels', 'moodData', 'taskLabels', 'taskData', 'insights'));
    }

    private function getWeeklyMoodData($user, $startDate, $endDate)
    {
        $moods = $user->moods()
            ->whereBetween('log_date', [$startDate, $endDate])
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->log_date)->format('Y-m-d');
            });

        $moodMap = ['stres' => 1, 'capek' => 2, 'biasa' => 3, 'senang' => 4];
        $labels = [];
        $data = [];

        foreach (CarbonPeriod::create($startDate, $endDate) as $date) {
            $dateString = $date->format('Y-m-d');
            $labels[] = $date->isoFormat('dddd');
            $moodOnDate = $moods->get($dateString);
            $data[] = $moodOnDate ? $moodMap[$moodOnDate->level] : 0;
        }

        return [$labels, $data];
    }

    private function getWeeklyCompletedTasksData($user, $startDate, $endDate)
    {
        $tasks = $user->tasks()
            ->where('is_completed', true)
            ->whereBetween('completed_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->select(DB::raw('DATE(completed_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $labels = [];
        $data = [];

        foreach (CarbonPeriod::create($startDate, $endDate) as $date) {
            $dateString = $date->format('Y-m-d');
            $labels[] = $date->isoFormat('dddd');
            $taskOnDate = $tasks->get($dateString);
            $data[] = $taskOnDate ? $taskOnDate->count : 0;
        }

        return [$labels, $data];
    }

    private function generateInsights($taskData, $moodData, $labels)
    {
        // Insight Produktivitas
        if (empty($taskData) || array_sum($taskData) == 0) {
            $productivityInsight = 'Belum ada data tugas selesai minggu ini. Selesaikan tugas untuk melihat insight!';
        } else {
            $maxTasks = max($taskData);
            $dayIndex = array_search($maxTasks, $taskData);
            $mostProductiveDay = $labels[$dayIndex] ?? 'N/A';
            $productivityInsight = "Hari paling produktifmu minggu ini adalah **$mostProductiveDay** dengan menyelesaikan **$maxTasks** tugas.";
        }

        // Insight Mood
        $moodCounts = array_count_values(array_filter($moodData));
        if(empty($moodCounts)) {
            $moodInsight = 'Belum ada data mood.';
        } else {
            arsort($moodCounts);
            $mostFrequentMoodValue = key($moodCounts);
            $moodMapFlipped = [1 => 'Stres', 2 => 'Capek', 3 => 'Biasa', 4 => 'Senang'];
            $mostFrequentMood = $moodMapFlipped[$mostFrequentMoodValue] ?? 'Tidak diketahui';
            $moodInsight = "Mood yang paling sering kamu rasakan adalah **$mostFrequentMood**.";
        }

        return [
            'productivity' => $productivityInsight,
            'mood' => $moodInsight
        ];
    }
}
