<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head', ['title' => $title ?? config('wedding.site_name')])
    </head>
    <body class="min-h-screen bg-stone-50 text-stone-900 antialiased">
        <div class="absolute inset-x-0 top-0 -z-10 h-80 bg-[radial-gradient(circle_at_top,rgba(251,191,36,0.18),transparent_40%),linear-gradient(180deg,#fff7ed,rgba(255,247,237,0))]"></div>

        <div class="mx-auto flex min-h-screen max-w-6xl flex-col px-6 py-6 lg:px-8">
            <header class="flex flex-col gap-4 rounded-[2rem] border border-stone-200/80 bg-white/80 px-5 py-4 shadow-sm backdrop-blur lg:flex-row lg:items-center lg:justify-between">
                <a class="flex items-center gap-3" href="{{ route('home') }}">
                    <span class="flex size-11 items-center justify-center rounded-full bg-rose-100 text-rose-900">
                        <x-app-logo-icon class="size-5" />
                    </span>
                    <div>
                        <p class="text-xs uppercase tracking-[0.35em] text-rose-700">The Wisener Wedding</p>
                        <p class="font-editorial text-xl">{{ config('wedding.app_name') }}</p>
                    </div>
                </a>

                <nav class="flex flex-wrap items-center gap-2 text-sm">
                    <a class="rounded-full px-4 py-2 hover:bg-stone-100" href="{{ route('home') }}">Home</a>
                    <a class="rounded-full px-4 py-2 hover:bg-stone-100" href="{{ route('venue') }}">Venue</a>
                    <a class="rounded-full px-4 py-2 hover:bg-stone-100" href="{{ route('hotels-and-taxis') }}">Hotels & Taxis</a>
                    <a class="rounded-full px-4 py-2 hover:bg-stone-100" href="{{ route('gallery') }}">Gallery</a>
                    @auth
                        <a class="rounded-full bg-stone-900 px-4 py-2 text-white hover:bg-stone-700" href="{{ route('dashboard') }}">Dashboard</a>
                    @else
                        <a class="rounded-full bg-stone-900 px-4 py-2 text-white hover:bg-stone-700" href="{{ route('login') }}">Login</a>
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
    </body>
</html>
