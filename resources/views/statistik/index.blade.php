<x-app-layout>
    <div class="space-y-8">
        <h2 class="text-3xl font-bold text-gray-800">Statistik & Refleksi Mingguan</h2>

        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h4 class="font-bold text-lg text-gray-700">💡 Insight Produktivitas</h4>
                <p class="text-gray-600 mt-2">{!! $insights['productivity'] !!}</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h4 class="font-bold text-lg text-gray-700">💡 Insight Mood</h4>
                <p class="text-gray-600 mt-2">{!! $insights['mood'] !!}</p>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-4 text-gray-700">Grafik Mood Mingguan</h3>
                <canvas id="moodChart"></canvas>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-4 text-gray-700">Grafik Tugas Selesai</h3>
                <canvas id="taskChart"></canvas>
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Pastikan variabel ini di-decode dengan benar
    const moodLabels = @json($moodLabels);
    const moodData = @json($moodData);
    const taskLabels = @json($taskLabels);
    const taskData = @json($taskData);

    // Inisialisasi Grafik Mood
    const moodCtx = document.getElementById('moodChart');
    if (moodCtx) {
        new Chart(moodCtx, {
            type: 'line',
            data: {
                labels: moodLabels,
                datasets: [{
                    label: 'Level Mood',
                    data: moodData,
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 4, // Set nilai maksimal
                        ticks: {
                            stepSize: 1,
                            callback: function(value) {
                                return {1: 'Stres', 2: 'Capek', 3: 'Biasa', 4: 'Senang'}[value] || '';
                            }
                        }
                    }
                }
            }
        });
    }

    // Inisialisasi Grafik Tugas
    const taskCtx = document.getElementById('taskChart');
    if (taskCtx) {
        new Chart(taskCtx, {
            type: 'bar',
            data: {
                labels: taskLabels,
                datasets: [{
                    label: 'Tugas Selesai',
                    data: taskData,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        }
                    }
                }
            }
        });
    }
</script>
@endpush
