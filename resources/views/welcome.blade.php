<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head', ['title' => $siteName])
    </head>
    <body class="min-h-screen bg-stone-950 text-stone-100 antialiased">
        <div class="absolute inset-x-0 top-0 -z-10 h-[34rem] bg-[radial-gradient(circle_at_top,rgba(251,191,36,0.22),transparent_42%),linear-gradient(180deg,rgba(136,19,55,0.55),rgba(28,25,23,0)_70%)]"></div>
        <div class="absolute inset-x-0 top-24 -z-10 mx-auto h-72 max-w-6xl rounded-full bg-rose-300/10 blur-3xl"></div>

        <div class="mx-auto max-w-6xl px-6 py-6 lg:px-8">
            <header class="flex items-center justify-between rounded-full border border-white/10 bg-white/5 px-5 py-3 backdrop-blur">
                <a class="flex items-center gap-3" href="{{ route('home') }}">
                    <span class="flex size-10 items-center justify-center rounded-full bg-rose-200 text-stone-900">
                        <x-app-logo-icon class="size-5" />
                    </span>
                    <div>
                        <p class="text-xs uppercase tracking-[0.35em] text-rose-200/80">The Wisener Wedding</p>
                        <p class="font-editorial text-lg">{{ config('wedding.app_name') }}</p>
                    </div>
                </a>

                <nav class="flex items-center gap-3 text-sm">
                    @auth
                        <a class="rounded-full border border-white/15 px-4 py-2 hover:bg-white/10" href="{{ route('dashboard') }}">Dashboard</a>
                    @else
                        <a class="rounded-full border border-white/15 px-4 py-2 hover:bg-white/10" href="{{ route('login') }}">Login</a>
                    @endauth
                </nav>
            </header>

            <main class="pb-16 pt-10 lg:pt-16">
                <section class="grid gap-10 lg:grid-cols-[1.2fr_0.8fr] lg:items-end">
                    <div>
                        <p class="text-sm uppercase tracking-[0.45em] text-amber-200/80">24 May 2027 · East Lothian</p>
                        <h1 class="font-editorial mt-5 max-w-4xl text-5xl leading-none sm:text-6xl lg:text-7xl">
                            A warm Scottish wedding weekend for {{ $couple['partner_one'] }} and {{ $couple['partner_two'] }}.
                        </h1>
                        <p class="mt-6 max-w-2xl text-lg leading-8 text-stone-300">
                            We’ll be gathering at {{ $venue['name'] }} to celebrate, eat well, stay late, and make a full day of it.
                            This first version of the site will grow with schedule details, RSVP guidance, and guest information.
                        </p>

                        <div class="mt-8 flex flex-wrap gap-3">
                            <a class="rounded-full bg-rose-200 px-5 py-3 text-sm font-semibold text-stone-950 transition hover:bg-rose-100" href="{{ $venue['url'] }}" target="_blank" rel="noreferrer">
                                View venue
                            </a>
                            <a class="rounded-full border border-white/15 px-5 py-3 text-sm font-semibold transition hover:bg-white/10" href="#details">
                                Wedding details
                            </a>
                        </div>
                    </div>

                    <div class="rounded-[2rem] border border-white/10 bg-white/6 p-6 shadow-2xl shadow-stone-950/30 backdrop-blur">
                        <p class="text-xs uppercase tracking-[0.35em] text-rose-200/80">Countdown</p>
                        <div class="mt-6 grid grid-cols-3 gap-3">
                            <div class="rounded-3xl bg-white/8 p-4">
                                <p class="text-3xl font-semibold">{{ number_format($countdown['days']) }}</p>
                                <p class="mt-1 text-xs uppercase tracking-[0.3em] text-stone-300">Days</p>
                            </div>
                            <div class="rounded-3xl bg-white/8 p-4">
                                <p class="text-3xl font-semibold">{{ number_format($countdown['weeks']) }}</p>
                                <p class="mt-1 text-xs uppercase tracking-[0.3em] text-stone-300">Weeks</p>
                            </div>
                            <div class="rounded-3xl bg-white/8 p-4">
                                <p class="text-3xl font-semibold">{{ number_format($countdown['months']) }}</p>
                                <p class="mt-1 text-xs uppercase tracking-[0.3em] text-stone-300">Months</p>
                            </div>
                        </div>
                        <div class="mt-6 rounded-3xl border border-white/10 bg-stone-950/40 p-5">
                            <p class="text-sm text-stone-300">Ceremony date</p>
                            <p class="mt-2 text-2xl font-semibold">{{ $weddingDate->format('l, j F Y') }}</p>
                            <p class="mt-2 text-sm text-stone-400">{{ $venue['name'] }} · {{ $venue['location'] }}</p>
                        </div>
                    </div>
                </section>

                <section id="details" class="mt-16 grid gap-6 lg:grid-cols-3">
                    <article class="rounded-[2rem] border border-white/10 bg-white p-6 text-stone-900">
                        <p class="text-xs uppercase tracking-[0.35em] text-rose-700">Venue</p>
                        <h2 class="font-editorial mt-4 text-3xl">{{ $venue['name'] }}</h2>
                        <p class="mt-4 text-sm leading-7 text-stone-600">
                            Falside sets the tone for the day: historic, private, and close enough to the coast to feel like a proper destination without drifting far from Edinburgh.
                        </p>
                        <a class="mt-5 inline-flex text-sm font-semibold text-rose-700 hover:text-rose-900" href="{{ $venue['url'] }}" target="_blank" rel="noreferrer">
                            Explore the venue
                        </a>
                    </article>

                    <article class="rounded-[2rem] border border-white/10 bg-rose-50 p-6 text-stone-900">
                        <p class="text-xs uppercase tracking-[0.35em] text-rose-700">Plan</p>
                        <h2 class="font-editorial mt-4 text-3xl">One place for the essentials</h2>
                        <p class="mt-4 text-sm leading-7 text-stone-600">
                            This first release focuses on the public landing page, guest-facing details, and the private admin/auth structure behind it. RSVP forms and logistics can slot in next without reworking the foundation.
                        </p>
                    </article>

                    <article class="rounded-[2rem] border border-white/10 bg-amber-100 p-6 text-stone-900">
                        <p class="text-xs uppercase tracking-[0.35em] text-amber-800">Coming Next</p>
                        <h2 class="font-editorial mt-4 text-3xl">Guest information</h2>
                        <ul class="mt-4 space-y-3 text-sm leading-7 text-stone-700">
                            <li>RSVP workflow and guest management</li>
                            <li>Travel and accommodation notes</li>
                            <li>Day-of schedule and practical timings</li>
                        </ul>
                    </article>
                </section>

                <section class="mt-16 rounded-[2rem] border border-white/10 bg-white/6 p-6 backdrop-blur lg:p-8">
                    <div class="grid gap-8 lg:grid-cols-[0.9fr_1.1fr]">
                        <div>
                            <p class="text-xs uppercase tracking-[0.35em] text-rose-200/80">Schedule</p>
                            <h2 class="font-editorial mt-4 text-4xl">A full-day celebration, kept simple for now.</h2>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="rounded-3xl border border-white/10 bg-stone-950/35 p-5">
                                <p class="text-sm font-semibold text-rose-200">Ceremony</p>
                                <p class="mt-2 text-sm leading-7 text-stone-300">
                                    Formal timings will be published here once they are locked. For now, plan around a full wedding day at the venue.
                                </p>
                            </div>
                            <div class="rounded-3xl border border-white/10 bg-stone-950/35 p-5">
                                <p class="text-sm font-semibold text-rose-200">Celebration</p>
                                <p class="mt-2 text-sm leading-7 text-stone-300">
                                    Drinks, dinner, and dancing details will follow, with one clear place for every update guests need.
                                </p>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </body>
</html>
