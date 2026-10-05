<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <button type="submit"
            class="w-full bg-gradient-to-r from-orange-500 to-green-600 text-white font-bold py-3 rounded-lg shadow-md hover:opacity-90 transition">
            Login Karein
        </button>

        <div class="flex items-center justify-between text-sm pt-2">
            @if (Route::has('password.request'))
                <a class="text-gray-500 hover:text-orange-600 underline" href="{{ route('password.request') }}">
                    Password bhool gaye?
                </a>
            @endif

            <a class="text-gray-500 hover:text-orange-600 underline" href="{{ route('register') }}">
                Naya account banayen
            </a>
        </div>
    </form>
</x-guest-layout>
