<div x-data="{ show: true, countdown: {{ config('idle-guard.logout_time') }} }"
     x-show="show"
     class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50">
    <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full text-center">
        <h2 class="text-xl font-bold mb-4">Session inactive</h2>
        <p class="mb-4">Votre session va expirer dans <span x-text="countdown"></span> secondes.</p>
        <div class="flex justify-center gap-4">
            <button @click="show = false; $dispatch('extend-session')"
                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Rester connecté</button>
            <button @click="window.location.href='{{ config('idle-guard.redirect_url') }}'"
                    class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Déconnexion</button>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let modal = document.querySelector('[x-data]');
            let interval = setInterval(function() {
                if (modal.__x.$data.countdown > 0) {
                    modal.__x.$data.countdown--;
                } else {
                    clearInterval(interval);
                    window.location.href = '{{ config('idle-guard.redirect_url') }}';
                }
            }, 1000);
        });
    </script>
</div>
