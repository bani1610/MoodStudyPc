<x-app-layout>
    <div x-data="pomodoro()" x-cloak class="max-w-md mx-auto text-center">
        <div class="bg-white p-8 rounded-2xl shadow-lg">

            <div class="text-8xl font-bold text-gray-800 mb-4" x-text="displayTime"></div>

            <div class="flex justify-center space-x-4 mb-6">
                <button @click="start()" :disabled="isActive" class="w-24 bg-green-500 text-white font-bold py-3 px-4 rounded-lg hover:bg-green-600 disabled:bg-gray-300 transition">Mulai</button>
                <button @click="pause()" :disabled="!isActive" class="w-24 bg-yellow-500 text-white font-bold py-3 px-4 rounded-lg hover:bg-yellow-600 disabled:bg-gray-300 transition">Jeda</button>
                <button @click="reset()" class="w-24 bg-red-500 text-white font-bold py-3 px-4 rounded-lg hover:bg-red-600 transition">Reset</button>
            </div>

            <p class="text-gray-500 text-lg">Sesi ke-<span x-text="sessionCount"></span>. <span x-text="mode === 'work' ? 'Saatnya Fokus!' : 'Waktunya Istirahat!'"></span></p>
        </div>

        <div x-show="showReflectionForm" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <form @submit.prevent="submitReflection()" class="bg-white p-6 rounded-lg shadow-xl w-full max-w-lg">
                <h3 class="text-2xl font-bold mb-4">Sesi Selesai! 🎉</h3>
                <p class="mb-4">Bagaimana sesi fokusmu barusan?</p>
                <div class="mb-4">
                    <label for="reflection" class="block text-sm font-medium text-gray-700 text-left">Apa yang kamu kerjakan?</label>
                    <textarea x-model="reflectionNote" id="reflection" rows="3" class="mt-1 w-full border-gray-300 rounded-md shadow-sm"></textarea>
                </div>
                <div class="mb-6 text-left">
                     <label class="block text-sm font-medium text-gray-700">Apakah kamu merasa fokus?</label>
                     <div class="flex items-center space-x-4 mt-2"><label><input type="radio" x-model="wasFocused" value="true" class="mr-1"> Ya</label><label><input type="radio" x-model="wasFocused" value="false" class="mr-1"> Tidak</label></div>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" @click="skipReflection()" class="bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-md">Lewati</button>
                    <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-4 rounded-md">Simpan</button>
                </div>
            </form>
        </div>
    </div>

<script>
    // Definisikan logika sebagai reusable component Alpine
    document.addEventListener('alpine:init', () => {
        Alpine.data('pomodoro', () => ({
            minutes: 25,
            seconds: 0,
            isActive: false,
            timer: null,
            mode: 'work',
            sessionCount: 1,
            showReflectionForm: false,
            reflectionNote: '',
            wasFocused: true,

            get displayTime() {
                return `${String(this.minutes).padStart(2, '0')}:${String(this.seconds).padStart(2, '0')}`;
            },

            start() {
                if (this.isActive) return;
                this.isActive = true;
                this.timer = setInterval(() => {
                    if (this.seconds === 0) {
                        if (this.minutes === 0) {
                            this.finishSession();
                        } else {
                            this.minutes--;
                            this.seconds = 59;
                        }
                    } else {
                        this.seconds--;
                    }
                }, 1000);
            },

            pause() {
                clearInterval(this.timer);
                this.isActive = false;
            },

            reset() {
                this.pause();
                this.mode = 'work';
                this.minutes = 25;
                this.seconds = 0;
                this.sessionCount = 1;
            },

            finishSession() {
                this.pause();
                new Audio('https://www.soundjay.com/buttons/sounds/button-1.mp3').play();
                if (this.mode === 'work') {
                    this.showReflectionForm = true;
                } else {
                    this.switchToWorkMode();
                }
            },

            switchToWorkMode() {
                this.mode = 'work';
                this.minutes = 25;
                this.seconds = 0;
                this.sessionCount++;
                this.start();
            },

            switchToBreakMode() {
                this.mode = (this.sessionCount % 4 === 0) ? 'longBreak' : 'shortBreak';
                this.minutes = (this.mode === 'longBreak') ? 15 : 5;
                this.seconds = 0;
                this.start();
            },

            skipReflection() {
                this.showReflectionForm = false;
                this.switchToBreakMode();
            },

            async submitReflection() {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                await fetch('{{ route("fokus.store") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
                    body: JSON.stringify({
                        duration_minutes: 25,
                        reflection: this.reflectionNote,
                        was_focused: this.wasFocused === 'true'
                    })
                });
                this.reflectionNote = '';
                this.skipReflection();
            }
        }))
    })
</script>
</x-app-layout>
