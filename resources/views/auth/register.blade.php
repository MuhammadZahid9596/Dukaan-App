<x-guest-layout>
    <h2 class="text-2xl font-bold text-gray-800 mb-1 text-center">Apni Dukaan Banayen</h2>
    <p class="text-sm text-gray-500 mb-6 text-center">Free trial se shuru karein — 7 din bilkul muft</p>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Apka Naam')" />
            <x-text-input id="name" class="block mt-1 w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Shop Name -->
        <div>
            <x-input-label for="shop_name" :value="__('Shop Ka Naam')" />
            <x-text-input id="shop_name" class="block mt-1 w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500" type="text" name="shop_name" :value="old('shop_name')" required placeholder="e.g. Al-Karam Store" />
            <x-input-error :messages="$errors->get('shop_name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Password Dobara')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button type="submit"
            class="w-full bg-gradient-to-r from-orange-500 to-green-600 text-white font-bold py-3 rounded-lg shadow-md hover:opacity-90 transition">
            Free Trial Shuru Karein
        </button>

        <p class="text-center text-sm text-gray-500">
            Pehle se account hai?
            <a href="{{ route('login') }}" class="text-orange-600 font-medium underline">Login karein</a>
        </p>
    </form>
</x-guest-layout>