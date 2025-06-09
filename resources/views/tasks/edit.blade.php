<x-app-layout>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Tugas</h2>

            <form method="POST" action="{{ route('tasks.update', $task) }}">
                @method('PATCH')
                @include('tasks._form')
            </form>
        </div>
    </div>
</x-app-layout>
