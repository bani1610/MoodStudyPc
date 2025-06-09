<x-app-layout>
    <div class="space-y-8">
        <h2 class="text-3xl font-bold text-gray-800">
            Selamat Datang Kembali, <span class="text-indigo-600">{{ Auth::user()->name }}</span>!
        </h2>

        @if (session('status'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md" role="alert">
                <p>{{ session('status') }}</p>
            </div>
        @endif

        <div class="bg-white p-6 rounded-lg shadow-md">
            @if (!$todayMood)
                <form action="{{ route('moods.store') }}" method="POST">
                    @csrf
                    <h3 class="text-xl font-semibold mb-4 text-gray-700">Bagaimana perasaanmu hari ini?</h3>
                    <div class="flex justify-around items-center mb-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="level" value="senang" class="sr-only peer" required>
                            <span class="text-5xl peer-checked:grayscale-0 grayscale transition-all hover:scale-110">😄</span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="level" value="biasa" class="sr-only peer">
                            <span class="text-5xl peer-checked:grayscale-0 grayscale transition-all hover:scale-110">😐</span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="level" value="capek" class="sr-only peer">
                            <span class="text-5xl peer-checked:grayscale-0 grayscale transition-all hover:scale-110">😫</span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="level" value="stres" class="sr-only peer">
                            <span class="text-5xl peer-checked:grayscale-0 grayscale transition-all hover:scale-110">😠</span>
                        </label>
                    </div>
                    <textarea name="notes" rows="2" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ada cerita apa hari ini? (Opsional)"></textarea>
                    <button type="submit" class="mt-4 w-full bg-indigo-600 text-white font-bold py-2 px-4 rounded-md hover:bg-indigo-700 transition">Simpan Mood</button>
                </form>
            @else
                <div class="text-center">
                    <h3 class="text-xl font-semibold text-gray-700">Mood Kamu Hari Ini</h3>
                    <p class="text-6xl my-4">
                        @if($todayMood->level == 'senang') 😄
                        @elseif($todayMood->level == 'biasa') 😐
                        @elseif($todayMood->level == 'capek') 😫
                        @else 😠
                        @endif
                    </p>
                    <p class="text-gray-600 italic">"{{ $todayMood->notes ?? 'Tidak ada catatan.' }}"</p>
                </div>
            @endif
        </div>

        @if($suggestion)
        <div class="
            @if($suggestion['category_suggestion'] == 'berat') bg-green-100 border-green-500 text-green-800
            @elseif($suggestion['category_suggestion'] == 'sedang') bg-blue-100 border-blue-500 text-blue-800
            @elseif($suggestion['category_suggestion'] == 'ringan') bg-yellow-100 border-yellow-500 text-yellow-800
            @else bg-gray-100 border-gray-400 text-gray-800
            @endif
            p-6 rounded-lg border-l-4">
            <h4 class="font-bold text-lg">{{ $suggestion['title'] }}</h4>
            <p>{{ $suggestion['advice'] }}</p>
            <a href="{{ route('tasks.index') }}" class="inline-block mt-3 text-sm font-semibold hover:underline">Lihat Daftar Tugas →</a>
        </div>
        @endif

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold mb-4 text-gray-700">Tugas Hari Ini ({{ $tasksToday->count() }})</h3>
            <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
                <div class="bg-green-500 h-2.5 rounded-full" style="width: {{ $progress }}%"></div>
            </div>
            <ul class="space-y-2">
                @forelse($tasksToday as $task)
                    <li class="flex items-center p-2 rounded-md {{ $task->is_completed ? 'bg-gray-100 text-gray-500 line-through' : 'bg-indigo-50' }}">
                        <span class="font-medium">{{ $task->title }}</span>
                    </li>
                @empty
                    <li class="text-gray-500">🎉 Yey! Tidak ada tugas untuk hari ini.</li>
                @endforelse
            </ul>

            @if($overdueTasks->isNotEmpty())
            <h3 class="text-xl font-semibold mt-6 mb-4 text-red-600">Tugas Terlewat ({{ $overdueTasks->count() }})</h3>
             <ul class="space-y-2">
                @foreach($overdueTasks as $task)
                    <li class="flex items-center p-2 rounded-md bg-red-50 text-red-700">
                        <span class="font-medium">{{ $task->title }}</span>
                        <span class="ml-auto text-xs">{{ \Carbon\Carbon::parse($task->due_date)->diffForHumans() }}</span>
                    </li>
                @endforeach
            </ul>
            @endif
        </div>
    </div>
</x-app-layout>
