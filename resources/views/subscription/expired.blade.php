<x-layout>
    <div class="max-w-md mx-auto bg-white rounded shadow p-6 text-center mt-10">
        <div class="text-5xl mb-4">⏰</div>
        <h2 class="text-xl font-bold mb-2">Trial/Subscription Khatam Ho Gaya</h2>
        <p class="text-gray-600 mb-4">
            Apka free trial ya subscription expire ho chuka hai. Continue karne ke liye subscription activate karwayen.
        </p>
        <p class="text-sm text-gray-500 mb-4">
            Rabta karein: <strong>0300-XXXXXXX</strong> (WhatsApp/Call)
        </p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="bg-gray-700 text-white px-6 py-2 rounded font-medium">
                Logout
            </button>
        </form>
    </div>
</x-layout>