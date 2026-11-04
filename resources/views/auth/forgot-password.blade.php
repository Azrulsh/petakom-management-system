<x-guest-layout>
    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">

         <!-- Left: PETAKOM Branding -->
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
                    Forgot your
                    <span style="color: #fecaca;">
                         password?
                    </span>
                </h1>

                <p class="mt-6 text-lg leading-relaxed"
                   style="color: #fee2e2;">
                    No worries. We will help you regain access to your
                    PETAKOM Management System account.
                </p>
            </div>

            <!-- Footer -->
            <p class="relative z-10 text-sm"
               style="color: #fecaca;">
                PETAKOM Management System
            </p>
        </div>

        <!-- RIGHT: Reset Password Form -->
        <div class="flex min-h-screen flex-col items-center justify-center px-6 py-12 sm:px-10 lg:px-16"
             style="background: linear-gradient(135deg, #fff1f2 0%, #ffe4e6 55%, #fecdd3 100%);">

            <div class="w-full max-w-md">

                <!-- Mobile Branding -->
                <div class="mb-10 text-center lg:hidden">
                    <a href="{{ url('/') }}" class="text-3xl font-extrabold tracking-wide"
                       style="color: #991b1b;">
                        PETAKOM
                    </a>
                </div>

                <div class="mb-8">

                    <h2 class="mt-5 text-3xl font-extrabold"
                        style="color: #7f1d1d;">
                        Reset Password
                    </h2>

                    <p class="mt-3 text-sm leading-relaxed"
                       style="color: #7f1d1d;">
                        Enter your registered email address and we will send
                        you a password reset link.
                    </p>
                </div>

                <!-- Success Status -->
                @if (session('status'))
                    <div class="mb-6 rounded-lg border border-green-300 bg-green-50 p-4 text-sm text-green-800"
                         role="status">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Jetstream Validation Component -->
                <x-validation-errors class="mb-6" />

                <form method="POST" action="{{ route('password.email') }}"
                      class="space-y-6">
                    @csrf

                    <div>
                        <label for="email"
                               class="mb-2 block text-sm font-semibold"
                               style="color: #7f1d1d;">
                            Email Address
                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="email"
                            placeholder="Enter your email address"
                            class="block w-full rounded-xl border border-rose-200 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-red-700 focus:ring-red-700"
                        />
                    </div>

                    <button type="submit"
                            class="flex w-full items-center justify-center rounded-xl px-5 py-3 text-sm font-bold text-white shadow-lg transition duration-200 hover:opacity-90"
                            style="background-color: #991b1b;">
                        Email Password Reset Link
                    </button>
                </form>

                <div class="mt-8 text-center text-sm"
                     style="color: #7f1d1d;">
                    Remember your password?
                    <a href="{{ route('login') }}"
                       class="font-bold hover:underline"
                       style="color: #991b1b;">
                        Sign In
                    </a>
                </div>

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
