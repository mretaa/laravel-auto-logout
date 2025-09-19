<div id="inactivity-modal" class="hidden fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50">
    <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full text-center">
        <h2 class="text-xl font-bold mb-4">Session inactive</h2>
        <p class="mb-4">
            Votre session va expirer dans
            <span id="logout-countdown">{{ config('idle-guard.logout_time') }}</span> secondes.
        </p>
        <div class="flex justify-center gap-4">
            <button id="continue-session"
                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                Rester connecté
            </button>
            <button id="logout-session"
                    class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                Déconnexion
            </button>
        </div>
    </div>
</div>
