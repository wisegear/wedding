<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head', ['title' => $title ?? config('wedding.site_name')])
    </head>
    <body class="bg-garden-page min-h-screen text-stone-900 antialiased">
        <div class="garden-grid absolute inset-0 -z-20 opacity-45"></div>
        <div class="absolute inset-x-0 top-0 -z-10 h-[28rem] bg-[radial-gradient(circle_at_top,rgba(163,188,164,0.28),transparent_34%),linear-gradient(180deg,rgba(241,245,235,0.7),rgba(241,245,235,0))]"></div>
        <div class="absolute right-0 top-12 -z-10 h-72 w-72 rounded-full bg-[#dbe7d8]/45 blur-3xl"></div>

        <div class="mx-auto flex min-h-screen max-w-6xl flex-col px-6 py-6 lg:px-8">
            <header class="bg-garden-panel shadow-garden flex flex-col gap-4 rounded-[2rem] border border-white/70 px-5 py-4 backdrop-blur lg:flex-row lg:items-center lg:justify-between">
                <a class="flex items-center gap-3" href="{{ route('home') }}">
                    <span class="flex size-11 items-center justify-center rounded-full bg-[#e7eee0] text-[#3f5645] ring-1 ring-[#5f7f68]/15">
                        <x-app-logo-icon class="size-5" />
                    </span>
                    <div>
                        <p class="text-sage text-xs uppercase tracking-[0.35em]">The Wisener Wedding</p>
                        <p class="font-display text-xl">{{ config('wedding.app_name') }}</p>
                    </div>
                </a>

                <nav class="flex flex-wrap items-center gap-2 text-sm">
                    <a class="rounded-full px-4 py-2 text-stone-700 hover:bg-white/70 hover:text-[#3f5645]" href="{{ route('home') }}">Home</a>
                    <a class="rounded-full px-4 py-2 text-stone-700 hover:bg-white/70 hover:text-[#3f5645]" href="{{ route('venue') }}">Venue</a>
                    <a class="rounded-full px-4 py-2 text-stone-700 hover:bg-white/70 hover:text-[#3f5645]" href="{{ route('hotels-and-taxis') }}">Hotels & Taxis</a>
                    <a class="rounded-full px-4 py-2 text-stone-700 hover:bg-white/70 hover:text-[#3f5645]" href="{{ route('gallery') }}">Gallery</a>
                    @auth
                        <a class="bg-sage-deep rounded-full px-4 py-2 text-white hover:opacity-95" href="{{ route('dashboard') }}">Dashboard</a>
                    @else
                        <a class="bg-sage-deep rounded-full px-4 py-2 text-white hover:opacity-95" href="{{ route('login') }}">Login</a>
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
