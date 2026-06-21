<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head', ['title' => $title ?? 'Admin'])
    </head>
    <body class="min-h-screen bg-stone-100 text-stone-900 antialiased">
        <div class="mx-auto flex min-h-screen max-w-7xl flex-col px-6 py-6 lg:px-8">
            <header class="rounded-[2rem] border border-stone-200 bg-white px-5 py-4 shadow-sm">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.35em] text-rose-700">Admin Area</p>
                        <h1 class="font-editorial text-3xl">The Wisener Wedding</h1>
                    </div>

                    <nav class="flex flex-wrap items-center gap-2 text-sm">
                        <a class="rounded-full px-4 py-2 hover:bg-stone-100" href="{{ route('admin.dashboard') }}">Dashboard</a>
                        <a class="rounded-full px-4 py-2 hover:bg-stone-100" href="{{ route('admin.guests.index') }}">Guests</a>
                        <a class="rounded-full px-4 py-2 hover:bg-stone-100" href="{{ route('admin.rsvps.index') }}">RSVPs</a>
                        <a class="rounded-full px-4 py-2 hover:bg-stone-100" href="{{ route('admin.dining.index') }}">Dining</a>
                        <a class="rounded-full px-4 py-2 hover:bg-stone-100" href="{{ route('admin.gallery.index') }}">Gallery</a>
                        <a class="rounded-full bg-stone-900 px-4 py-2 text-white hover:bg-stone-700" href="{{ route('home') }}">Public site</a>
                    </nav>
                </div>
            </header>

            <main class="flex-1 py-8">
                @yield('content')
            </main>
        </div>
    </body>
</html>
