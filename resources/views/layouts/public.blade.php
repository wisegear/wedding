<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head', ['title' => $title ?? config('wedding.site_name')])
    </head>
    <body class="bg-garden-page min-h-screen text-stone-900 antialiased">
        <div class="garden-grid absolute inset-0 -z-20 opacity-35"></div>

        <div class="mx-auto flex min-h-screen max-w-6xl flex-col px-6 py-6 lg:px-8">
            <header class="bg-garden-panel rounded-[2rem] border border-[#ececdf] px-5 py-4 lg:flex lg:items-center lg:justify-between lg:gap-4">
                <details class="group lg:hidden" data-testid="mobile-navigation">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 rounded-[1.5rem] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#8d8e7c]/45 focus-visible:ring-offset-2 focus-visible:ring-offset-[#fcfcf8] [&::-webkit-details-marker]:hidden" data-testid="mobile-navigation-toggle">
                        <span class="flex min-w-0 items-center gap-3">
                            <span class="bg-garden-blend flex size-11 shrink-0 items-center justify-center rounded-full text-[#4d513f] ring-1 ring-[#666956]/15">
                                <x-app-logo-icon class="size-5" />
                            </span>
                            <span class="min-w-0">
                                <span class="text-sage block text-xs uppercase tracking-[0.35em]">The Wisener Wedding</span>
                                <span class="font-display block truncate text-xl">{{ config('wedding.app_name') }}</span>
                            </span>
                        </span>

                        <span class="bg-garden-blend flex size-11 shrink-0 items-center justify-center rounded-full border border-[#8d8e7c]/45 text-[#4d513f]" aria-hidden="true">
                            <span class="flex flex-col gap-1.5">
                                <span class="block h-0.5 w-5 rounded-full bg-current"></span>
                                <span class="block h-0.5 w-5 rounded-full bg-current"></span>
                                <span class="block h-0.5 w-5 rounded-full bg-current"></span>
                            </span>
                        </span>

                        <span class="sr-only">Open navigation</span>
                    </summary>

                    <nav class="mt-4 flex flex-col gap-2 border-t border-[#ececdf] pt-4 text-sm">
                        <a class="rounded-2xl px-4 py-3 text-stone-700 hover:bg-white/70 hover:text-[#4d513f]" href="{{ route('home') }}">Home</a>
                        <a class="rounded-2xl px-4 py-3 text-stone-700 hover:bg-white/70 hover:text-[#4d513f]" href="{{ route('venue') }}">Venue</a>
                        <a class="rounded-2xl px-4 py-3 text-stone-700 hover:bg-white/70 hover:text-[#4d513f]" href="{{ route('wedding-party') }}">Wedding Party</a>
                        <a class="rounded-2xl px-4 py-3 text-stone-700 hover:bg-white/70 hover:text-[#4d513f]" href="{{ route('q-and-a') }}">Q & A</a>
                        <a class="rounded-2xl px-4 py-3 text-stone-700 hover:bg-white/70 hover:text-[#4d513f]" href="{{ route('hotels-and-taxis') }}">Hotels & Taxis</a>
                        <a class="rounded-2xl px-4 py-3 text-stone-700 hover:bg-white/70 hover:text-[#4d513f]" href="{{ route('gallery') }}">Gallery</a>
                        @auth
                            <a class="bg-sage-deep rounded-2xl px-4 py-3 text-white hover:opacity-95" href="{{ route('dashboard') }}">Dashboard</a>
                        @else
                            <span class="grid grid-cols-2 gap-2">
                                <a class="bg-sage-button-soft rounded-2xl px-4 py-3 text-center text-[#4d513f] hover:bg-[#d8d8ca]" href="{{ route('register') }}">Register</a>
                                <a class="bg-sage-deep rounded-2xl px-4 py-3 text-center text-white hover:opacity-95" href="{{ route('login') }}">Login</a>
                            </span>
                        @endauth
                    </nav>
                </details>

                <a class="hidden shrink-0 items-center gap-3 lg:flex" href="{{ route('home') }}">
                    <span class="bg-garden-blend flex size-11 items-center justify-center rounded-full text-[#4d513f] ring-1 ring-[#666956]/15">
                        <x-app-logo-icon class="size-5" />
                    </span>
                    <div>
                        <p class="text-sage text-xs uppercase tracking-[0.35em]">The Wisener Wedding</p>
                        <p class="font-display text-xl">{{ config('wedding.app_name') }}</p>
                    </div>
                </a>

                <nav class="hidden items-center gap-2 text-sm lg:flex lg:flex-1 lg:justify-end xl:flex-nowrap xl:gap-1">
                    <a class="whitespace-nowrap rounded-full px-3 py-2 text-stone-700 hover:bg-white/70 hover:text-[#4d513f]" href="{{ route('home') }}">Home</a>
                    <a class="whitespace-nowrap rounded-full px-3 py-2 text-stone-700 hover:bg-white/70 hover:text-[#4d513f]" href="{{ route('venue') }}">Venue</a>
                    <a class="whitespace-nowrap rounded-full px-3 py-2 text-stone-700 hover:bg-white/70 hover:text-[#4d513f]" href="{{ route('wedding-party') }}">Wedding Party</a>
                    <a class="whitespace-nowrap rounded-full px-3 py-2 text-stone-700 hover:bg-white/70 hover:text-[#4d513f]" href="{{ route('q-and-a') }}">Q & A</a>
                    <a class="whitespace-nowrap rounded-full px-3 py-2 text-stone-700 hover:bg-white/70 hover:text-[#4d513f]" href="{{ route('hotels-and-taxis') }}">Hotels & Taxis</a>
                    <a class="whitespace-nowrap rounded-full px-3 py-2 text-stone-700 hover:bg-white/70 hover:text-[#4d513f]" href="{{ route('gallery') }}">Gallery</a>
                    @auth
                        <a class="bg-sage-deep whitespace-nowrap rounded-full px-3 py-2 text-white hover:opacity-95" href="{{ route('dashboard') }}">Dashboard</a>
                    @else
                        <a class="bg-sage-button-soft whitespace-nowrap rounded-full px-3 py-2 text-[#4d513f] hover:bg-[#d8d8ca]" href="{{ route('register') }}">Register</a>
                        <a class="bg-sage-deep whitespace-nowrap rounded-full px-3 py-2 text-white hover:opacity-95" href="{{ route('login') }}">Login</a>
                    @endauth
                </nav>
            </header>

            <main class="flex-1 py-10">
                @if (session('status'))
                    <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                        {{ session('status') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>

        @stack('scripts')
    </body>
</html>
