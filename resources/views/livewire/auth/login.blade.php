{{--
    FILE: resources/views/livewire/auth/login.blade.php
    Livewire needs ONE root element, so everything is inside this single <div>.
    Put the logo at: public/images/fudma-logo.png
--}}
<div class="w-full max-w-[470px]">

    {{-- ============================ LOGIN CARD ============================ --}}
    <div class="rounded-3xl bg-white/95 px-6 pb-7 pt-8 shadow-[0_20px_60px_-15px_rgba(10,92,46,0.25)] ring-1 ring-black/5 backdrop-blur sm:px-10 sm:pb-8 sm:pt-9">

        {{-- ---------- Logo + heading ---------- --}}
        <div class="text-center">
            <img src="{{ asset('images/logo.jpg') }}" alt="Federal University Dutsin-Ma logo"
                 class="mx-auto h-28 w-28 object-contain sm:h-32 sm:w-32">

            <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-[#0a5c2e]">Admin Login</h1>
            <p class="mt-1 text-[15px] text-slate-600">Welcome back, sign in to continue</p>
        </div>

        {{-- ---------- Form (wire:submit calls Login::login()) ---------- --}}
        <form wire:submit="login" class="mt-7 space-y-4" novalidate>

            {{-- Email --}}
            <div>
                <label for="email" class="sr-only">Email Address</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-800">
                        {{-- envelope icon --}}
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                        </svg>
                    </span>
                    <input id="email" type="email" wire:model="email" autocomplete="username" autofocus
                           placeholder="Email Address"
                           class="block w-full rounded-xl border bg-white py-3.5 pl-14 pr-4 text-[15px] text-slate-800 placeholder-slate-500 shadow-sm outline-none transition
                                  focus:border-[#0a5c2e] focus:ring-4 focus:ring-[#0a5c2e]/10
                                  {{ $errors->has('email') ? 'border-red-400' : 'border-slate-300' }}"
                           @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                </div>
                @error('email')
                    <p id="email-error" class="mt-1.5 flex items-start gap-1.5 text-sm text-red-600" role="alert">
                        <svg class="mt-0.5 h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            {{-- Password (Alpine toggles show / hide, no server call) --}}
            <div x-data="{ show: false }">
                <label for="password" class="sr-only">Password</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-800">
                        {{-- lock icon --}}
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                        </svg>
                    </span>
                    <input id="password" wire:model="password" x-bind:type="show ? 'text' : 'password'" type="password"
                           autocomplete="current-password" placeholder="Password"
                           class="block w-full rounded-xl border bg-white py-3.5 pl-14 pr-14 text-[15px] text-slate-800 placeholder-slate-500 shadow-sm outline-none transition
                                  focus:border-[#0a5c2e] focus:ring-4 focus:ring-[#0a5c2e]/10
                                  {{ $errors->has('password') ? 'border-red-400' : 'border-slate-300' }}"
                           @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>

                    <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-0 flex items-center rounded-r-xl px-4 text-slate-800 transition hover:text-[#0a5c2e] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0a5c2e]"
                            x-bind:aria-label="show ? 'Hide password' : 'Show password'">
                        {{-- eye (password hidden) --}}
                        <svg x-show="!show" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{-- eye-slash (password visible) --}}
                        <svg x-show="show" x-cloak class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p id="password-error" class="mt-1.5 flex items-start gap-1.5 text-sm text-red-600" role="alert">
                        <svg class="mt-0.5 h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            {{-- Remember me + Forgot password --}}
            <div class="flex items-center justify-between pt-0.5">
                <label for="remember" class="flex cursor-pointer select-none items-center gap-2.5 text-sm font-medium text-slate-700">
                    <input id="remember" type="checkbox" wire:model="remember"
                           class="h-5 w-5 cursor-pointer rounded border-slate-300 text-[#0a5c2e] focus:ring-2 focus:ring-[#0a5c2e]/30">
                    Remember me
                </label>

                {{-- Shown only when you have a route named password.request --}}
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" wire:navigate
                       class="text-sm font-semibold text-[#0a5c2e] transition hover:text-[#073d1f] hover:underline">
                        Forgot password?
                    </a>
                @else
                    <span class="text-sm font-semibold text-[#0a5c2e]/70" title="Contact the system administrator">Forgot password?</span>
                @endif
            </div>

            {{-- Sign In button (disabled + spinner while the request is running) --}}
            <button type="submit" wire:loading.attr="disabled" wire:target="login"
                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#0a5c2e] px-4 py-3.5 text-base font-semibold text-white shadow-lg shadow-[#0a5c2e]/25 transition
                           hover:bg-[#084d26] focus:outline-none focus-visible:ring-4 focus-visible:ring-[#0a5c2e]/30 disabled:cursor-not-allowed disabled:opacity-70">
                <span wire:loading.remove wire:target="login" class="flex items-center gap-2">
                    Sign In
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                    </svg>
                </span>
                <span wire:loading.flex wire:target="login" class="items-center gap-2">
                    <svg class="h-5 w-5 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                    </svg>
                    Signing in...
                </span>
            </button>
        </form>

        {{-- ---------- Card footer ---------- --}}
        <div class="mt-7 text-center">
            <div class="flex items-center gap-3">
                <span class="h-px flex-1 bg-[#0a5c2e]/30"></span>
                <span class="text-[15px] font-bold text-[#0a5c2e]">Federal University Dutsin-Ma</span>
                <span class="h-px flex-1 bg-[#0a5c2e]/30"></span>
            </div>
            <p class="mt-1 text-[15px] font-semibold italic text-[#b8923e]">Integrity &amp; Service</p>
        </div>
    </div>

    <p class="mt-5 text-center text-xs text-slate-500">&copy; {{ date('Y') }} Federal University Dutsin-Ma. All rights reserved.</p>
</div>