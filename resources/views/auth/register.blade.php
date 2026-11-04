<x-guest-layout>
    <div class="grid min-h-screen grid-cols-1 lg:grid-cols-2">

        <!-- Left Panel -->
        <div
            class="relative hidden overflow-hidden px-12 py-16 lg:flex lg:flex-col lg:justify-between"
            style="background: linear-gradient(145deg, #7f1d1d 0%, #991b1b 50%, #450a0a 100%); color: #ffffff;"
        >
            <!-- Decorative Elements -->
            <div class="absolute -right-24 -top-24 h-80 w-80 rounded-full bg-red-400/10 blur-3xl"></div>
            <div class="absolute -bottom-32 -left-24 h-96 w-96 rounded-full bg-red-300/10 blur-3xl"></div>

            <!-- Logo / Branding -->
            <div class="relative z-10">
                <div class="inline-flex items-center gap-3">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-xl text-xl font-bold"
                        style="background-color: #dc2626; color: #ffffff;"
                    >
                        P
                    </div>

                    <div>
                        <p class="text-xl font-bold tracking-wide"
                           style="color: #ffffff;">
                            PETAKOM
                        </p>
                        <p class="text-xs"
                           style="color: #fecaca;">
                            Management System
                        </p>
                    </div>
                </div>
            </div>

            <!-- Main Branding Content -->
            <div class="relative z-10 max-w-lg">
                <div
                    class="mb-6 h-1 w-14 rounded-full"
                    style="background-color: #fca5a5;"
                ></div>

                <h1 class="text-4xl font-bold leading-tight xl:text-5xl"
                    style="color: #ffffff;">
                    Create Your
                    <span style="color: #fecaca;">
                         Account
                    </span>
                </h1>

                <p class="mt-6 text-lg leading-relaxed"
                   style="color: #fee2e2;">
                    Join the PETAKOM Management System to access and manage PETAKOM activities and services.
                </p>
            </div>

            <!-- Footer -->
            <p class="relative z-10 text-sm"
               style="color: #fecaca;">
                PETAKOM Management System
            </p>
        </div>

        {{-- RIGHT PANEL --}}
        <div class="flex min-h-screen flex-col items-center justify-center px-6 py-12 sm:px-10 lg:px-16"
             style="background: linear-gradient(135deg, #fff1f2 0%, #ffe4e6 55%, #fecdd3 100%);">

            <div class="w-full max-w-md">

                {{-- Mobile Branding --}}
                <div class="mb-8 text-center lg:hidden">
                    <a href="{{ url('/') }}"
                       class="text-3xl font-extrabold"
                       style="color: #991b1b;">
                        PETAKOM
                    </a>
                </div>

                <div class="mb-7">
                    <h2 class="text-3xl font-extrabold"
                        style="color: #7f1d1d;">
                        Create an Account
                    </h2>

                    <p class="mt-2 text-sm"
                       style="color: #7f1d1d;">
                        Register to access the PETAKOM Management System.
                    </p>
                </div>

                {{-- Jetstream Validation --}}
                <x-validation-errors class="mb-5" />

                <form method="POST"
                      action="{{ route('register') }}"
                      class="space-y-4">
                    @csrf

                    <div>
                        <label for="name" class="mb-1 block text-sm font-semibold text-red-900">
                            Full Name
                        </label>
                        <input id="name" name="name" type="text"
                               value="{{ old('name') }}"
                               required autofocus autocomplete="name"
                               placeholder="Enter your full name"
                               class="block w-full rounded-xl border border-rose-200 bg-white px-4 py-3 text-sm text-gray-900 focus:border-red-700 focus:ring-red-700">
                    </div>

                    <div>
                        <label for="email" class="mb-1 block text-sm font-semibold text-red-900">
                            Email Address
                        </label>
                        <input id="email" name="email" type="email"
                               value="{{ old('email') }}"
                               required autocomplete="email"
                               placeholder="Enter your email address"
                               class="block w-full rounded-xl border border-rose-200 bg-white px-4 py-3 text-sm text-gray-900 focus:border-red-700 focus:ring-red-700">
                    </div>

                    <div>
                        <label for="password" class="mb-1 block text-sm font-semibold text-red-900">
                            Password
                        </label>
                        <input id="password" name="password" type="password"
                               required autocomplete="new-password"
                               placeholder="Create your password"
                               class="block w-full rounded-xl border border-rose-200 bg-white px-4 py-3 text-sm text-gray-900 focus:border-red-700 focus:ring-red-700">
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-1 block text-sm font-semibold text-red-900">
                            Confirm Password
                        </label>
                        <input id="password_confirmation" name="password_confirmation"
                               type="password" required
                               autocomplete="new-password"
                               placeholder="Confirm your password"
                               class="block w-full rounded-xl border border-rose-200 bg-white px-4 py-3 text-sm text-gray-900 focus:border-red-700 focus:ring-red-700">
                    </div>

                    {{-- Jetstream Terms & Privacy Policy --}}
                    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                        <div class="pt-2">
                            <label for="terms" class="flex items-start gap-3 text-sm text-red-900">
                                <x-checkbox name="terms" id="terms" required />

                                <span>
                                    I agree to the
                                    <a target="_blank" href="{{ route('terms.show') }}"
                                       class="font-bold text-red-800 underline">
                                        Terms of Service
                                    </a>
                                    and
                                    <a target="_blank" href="{{ route('policy.show') }}"
                                       class="font-bold text-red-800 underline">
                                        Privacy Policy
                                    </a>.
                                </span>
                            </label>
                        </div>
                    @endif

                    <button type="submit"
                            class="w-full rounded-xl px-5 py-3 text-sm font-bold text-white shadow-lg transition hover:opacity-90"
                            style="background-color: #991b1b;">
                        Create Account
                    </button>
                </form>

                <p class="mt-7 text-center text-sm text-red-900">
                    Already have an account?
                    <a href="{{ route('login') }}"
                       class="font-bold text-red-800 hover:underline">
                        Sign In
                    </a>
                </p>

                <!-- Footer -->
                <div class="mt-10 border-t border-rose-200 pt-6 text-center">
                    <p class="text-xs text-slate-500">
                        &copy; {{ date('Y') }} PETAKOM Management System.
                        All rights reserved.
                    </p>
                </div>
                
            </div>
        </div>
    </div>
</x-guest-layout>
