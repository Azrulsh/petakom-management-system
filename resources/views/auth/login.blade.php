<x-guest-layout>
    <div class="min-h-screen lg:grid lg:grid-cols-2"
         style="background-color: #fff1f2;">

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
                    One platform.
                    <span style="color: #fecaca;">
                        Better management.
                    </span>
                </h1>

                <p class="mt-6 text-lg leading-relaxed"
                   style="color: #fee2e2;">
                    Manage PETAKOM activities, proposals, committee elections,
                    registrations and announcements in one centralized platform.
                </p>
            </div>

            <!-- Footer -->
            <p class="relative z-10 text-sm"
               style="color: #fecaca;">
                PETAKOM Management System
            </p>
        </div>

        <!-- Right: Login Form -->
        <div
            class="flex min-h-screen items-center justify-center px-6 py-12 sm:px-10 lg:px-16"
            style="background: linear-gradient(135deg, #fff1f2 0%, #ffe4e6 55%, #fecdd3 100%);"
        >
            <div class="w-full max-w-md">

                <!-- Mobile Branding -->
                <div class="mb-10 flex items-center gap-3 lg:hidden">
                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-xl text-xl font-bold"
                        style="background-color: #b91c1c; color: #ffffff;"
                    >
                        P
                    </div>
                    <div>
                        <p class="text-lg font-bold text-slate-900">
                            PETAKOM
                        </p>
                        <p class="text-xs text-slate-500">
                            Management System
                        </p>
                    </div>
                </div>

                <!-- Form Heading -->
                <div class="mb-9">
                    <p class="mb-3 text-sm font-semibold uppercase tracking-widest"
                       style="color: #7f1d1d;">
                        Welcome back
                    </p>

                    <h2 class="text-3xl font-bold tracking-tight " style="color: #7f1d1d;">
                        Sign in to your account
                    </h2>

                    <p class="mt-3 text-sm leading-relaxed" style="color: #7f1d1d;">
                        Enter your credentials to access the PETAKOM Management System.
                    </p>
                </div>

                <!-- Validation Errors -->
                <x-validation-errors class="mb-5" />

                @if (session('status'))
                    <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email"
                               class="mb-2 block text-sm font-semibold text-slate-800">
                            Email Address
                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="Enter your email address"
                            class="block w-full rounded-xl border border-rose-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder-slate-400 shadow-sm transition focus:border-red-500 focus:ring-red-500"
                        >
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password"
                               class="mb-2 block text-sm font-semibold text-slate-800">
                            Password
                        </label>

                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Enter your password"
                            class="block w-full rounded-xl border border-rose-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder-slate-400 shadow-sm transition focus:border-red-500 focus:ring-red-500"
                        >
                    </div>

                    <!-- Remember Me / Forgot Password -->
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <label for="remember_me"
                               class="flex cursor-pointer items-center gap-2">
                            <input
                                id="remember_me"
                                type="checkbox"
                                name="remember"
                                class="rounded border-rose-300 text-red-700 focus:ring-red-500"
                            >
                            <span class="text-sm text-slate-700">
                                Remember me
                            </span>
                        </label>

                        @if (Route::has('password.request'))
                            <a
                                href="{{ route('password.request') }}"
                                class="text-sm font-semibold text-red-700 hover:text-red-800 hover:underline"
                            >
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="flex w-full items-center justify-center rounded-xl px-5 py-3.5 text-sm font-semibold shadow-sm transition focus:outline-none focus:ring-4 focus:ring-red-200"
                        style="background-color: #b91c1c; color: #ffffff;"
                        onmouseover="this.style.backgroundColor='#991b1b'"
                        onmouseout="this.style.backgroundColor='#b91c1c'"
                    >
                        Sign In
                    </button>
                </form>

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
