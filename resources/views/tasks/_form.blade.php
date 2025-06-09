@csrf

<div class="mb-4">
    <label for="title" class="block font-medium text-sm text-gray-700">Nama Tugas</label>
    <input type="text" name="title" id="title" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm"
           value="{{ old('title', $task->title ?? '') }}" required autofocus>
</div>

<div class="mb-4">
    <label for="due_date" class="block font-medium text-sm text-gray-700">Deadline</label>
    <input type="date" name="due_date" id="due_date" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm"
           value="{{ old('due_date', isset($task->due_date) ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : '') }}">
</div>

<div class="mb-4">
    <label for="category" class="block font-medium text-sm text-gray-700">Kategori</b l>
    <select name="category" id="category" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
        <option value="ringan" {{ old('category', $task->category ?? '') == 'ringan' ? 'selected' : '' }}>Ringan</option>
        <option value="sedang" {{ old('category', $task->category ?? '') == 'sedang' ? 'selected' : '' }}>Sedang</option>
        <option value="berat" {{ old('category', $task->category ?? '') == 'berat' ? 'selected' : '' }}>Berat</option>
    </select>
</div>

<div class="mb-4">
    <label for="priority" class="block font-medium text-sm text-gray-700">Prioritas</label>
    <select name="priority" id="priority" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
        <option value="rendah" {{ old('priority', $task->priority ?? '') == 'rendah' ? 'selected' : '' }}>Rendah</option>
        <option value="sedang" {{ old('priority', $task->priority ?? '') == 'sedang' ? 'selected' : '' }}>Sedang</option>
        <option value="tinggi" {{ old('priority', $task->priority ?? '') == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
    </select>
</div>

<div class="flex items-center justify-end mt-4">
    <a href="{{ route('tasks.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Batal</a>
    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700">
        Simpan Tugas
    </button>
</div>
