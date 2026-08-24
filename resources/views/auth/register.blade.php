<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <!-- Logo / Site Name -->
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <div class="h-16 w-16 bg-blue-600 rounded-lg flex items-center justify-center shadow-lg">
                        <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">{{ setting('site_name', 'SafeX Engineering') }}</h1>
                <p class="text-sm text-gray-500 mt-1">Create your account</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('Full Name')" class="text-sm font-medium text-gray-700" />
                    <x-text-input
                        id="name"
                        class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        type="text"
                        name="name"
                        :value="old('name')"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="John Doe"
                    />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <x-input-label for="email" :value="__('Email Address')" class="text-sm font-medium text-gray-700" />
                    <x-text-input
                        id="email"
                        class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required
                        autocomplete="username"
                        placeholder="your@email.com"
                    />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" class="text-sm font-medium text-gray-700" />
                    <x-text-input
                        id="password"
                        class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        placeholder="••••••••"
                    />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />

                    <!-- Password Strength Indicator -->
                    <div class="mt-2">
                        <div class="flex items-center gap-2">
                            <div class="flex-1 h-1 bg-gray-200 rounded-full overflow-hidden">
                                <div id="passwordStrength" class="h-full w-0 bg-red-500 transition-all duration-300"></div>
                            </div>
                            <span id="passwordStrengthText" class="text-xs text-gray-500 min-w-[60px]">Weak</span>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Must be at least 8 characters</p>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-sm font-medium text-gray-700" />
                    <x-text-input
                        id="password_confirmation"
                        class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="••••••••"
                    />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Terms & Conditions -->
                <div class="mt-4">
                    <label for="terms" class="inline-flex items-start">
                        <input
                            id="terms"
                            type="checkbox"
                            class="mt-1 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                            name="terms"
                            required
                        >
                        <span class="ms-2 text-sm text-gray-600">
                            I agree to the
                            <a href="#" class="text-blue-600 hover:text-blue-900 underline">Terms of Service</a>
                            and
                            <a href="#" class="text-blue-600 hover:text-blue-900 underline">Privacy Policy</a>
                        </span>
                    </label>
                    <x-input-error :messages="$errors->get('terms')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between mt-6">
                    <a
                        class="text-sm text-blue-600 hover:text-blue-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        href="{{ route('login') }}"
                    >
                        <svg class="inline-block w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        {{ __('Back to login') }}
                    </a>

                    <x-primary-button class="py-2.5 px-6 bg-blue-600 hover:bg-blue-700 focus:ring-blue-500">
                        {{ __('Create Account') }}
                    </x-primary-button>
                </div>
            </form>

            <!-- Footer -->
            <div class="mt-6 text-center">
                <p class="text-xs text-gray-400">
                    &copy; {{ date('Y') }} {{ setting('site_name', 'SafeX Engineering') }}. All rights reserved.
                </p>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Password Strength Indicator
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const strengthBar = document.getElementById('passwordStrength');
            const strengthText = document.getElementById('passwordStrengthText');

            if (passwordInput) {
                passwordInput.addEventListener('input', function() {
                    const password = this.value;
                    let strength = 0;
                    let color = 'bg-red-500';
                    let text = 'Weak';

                    if (password.length >= 8) strength += 1;
                    if (password.match(/[a-z]+/)) strength += 1;
                    if (password.match(/[A-Z]+/)) strength += 1;
                    if (password.match(/[0-9]+/)) strength += 1;
                    if (password.match(/[$@#&!]+/)) strength += 1;

                    const percentage = (strength / 5) * 100;

                    if (strength <= 1) {
                        color = 'bg-red-500';
                        text = 'Weak';
                    } else if (strength <= 2) {
                        color = 'bg-orange-500';
                        text = 'Fair';
                    } else if (strength <= 3) {
                        color = 'bg-yellow-500';
                        text = 'Good';
                    } else if (strength <= 4) {
                        color = 'bg-blue-500';
                        text = 'Strong';
                    } else {
                        color = 'bg-green-500';
                        text = 'Very Strong';
                    }

                    strengthBar.className = `h-full ${color} transition-all duration-300`;
                    strengthBar.style.width = `${percentage}%`;
                    strengthText.textContent = text;
                    strengthText.className = `text-xs min-w-[60px] ${
                        strength <= 1 ? 'text-red-500' :
                        strength <= 2 ? 'text-orange-500' :
                        strength <= 3 ? 'text-yellow-600' :
                        strength <= 4 ? 'text-blue-500' : 'text-green-500'
                    }`;
                });
            }
        });
    </script>
    @endpush
</x-guest-layout>
