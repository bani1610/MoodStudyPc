<x-app-layout>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Daftar Tugas Kamu</h2>
        <a href="{{ route('tasks.create') }}" class="bg-indigo-600 text-white font-bold py-2 px-4 rounded-md hover:bg-indigo-700 transition">
            + Tambah Tugas
        </a>
    </div>

    @if (session('status'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-6" role="alert">
            <p>{{ session('status') }}</p>
        </div>
    @endif

    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="space-y-4">
            @forelse ($tasks as $task)
                <div class="flex items-center p-4 border rounded-lg {{ $task->is_completed ? 'bg-gray-50' : '' }}">
                    <form action="{{ route('tasks.update', $task) }}" method="POST" class="mr-4">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="is_completed" value="{{ $task->is_completed ? 0 : 1 }}">
                        <input type="checkbox" onchange="this.form.submit()" class="h-6 w-6 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" {{ $task->is_completed ? 'checked' : '' }}>
                    </form>

                    <div class="flex-grow">
                        <p class="font-semibold text-lg {{ $task->is_completed ? 'line-through text-gray-500' : 'text-gray-800' }}">
                            {{ $task->title }}
                        </p>
                        <div class="flex items-center space-x-4 text-sm text-gray-500 mt-1">
                            <span>📅 {{ \Carbon\Carbon::parse($task->due_date)->isoFormat('dddd, D MMMM YYYY') }}</span>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                @if($task->category == 'berat') bg-red-100 text-red-800 @endif
                                @if($task->category == 'sedang') bg-blue-100 text-blue-800 @endif
                                @if($task->category == 'ringan') bg-green-100 text-green-800 @endif
                            ">{{ ucfirst($task->category) }}</span>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                @if($task->priority == 'tinggi') bg-red-200 text-red-800 @endif
                                @if($task->priority == 'sedang') bg-yellow-200 text-yellow-800 @endif
                                @if($task->priority == 'rendah') bg-gray-200 text-gray-800 @endif
                            ">Prioritas {{ ucfirst($task->priority) }}</span>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2">
                        <a href="{{ route('tasks.edit', $task) }}" class="text-blue-500 hover:text-blue-700">Edit</a>
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus tugas ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700">Hapus</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <p class="text-gray-500 text-lg">Kamu belum punya tugas. Ayo tambahkan sekarang!</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
