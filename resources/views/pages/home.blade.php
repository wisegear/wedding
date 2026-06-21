@extends('layouts.public')

@section('content')
    <section class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr] lg:items-end">
        <div>
            <p class="text-sm uppercase tracking-[0.45em] text-rose-700">24 May 2027 · Falside</p>
            <h1 class="font-editorial mt-4 text-5xl leading-none sm:text-6xl lg:text-7xl">
                {{ $couple['partner_one'] }} & {{ $couple['partner_two'] }}
            </h1>
            <p class="mt-6 max-w-2xl text-lg leading-8 text-stone-600">
                {{ $intro }}
            </p>
            <p class="mt-4 max-w-2xl text-base leading-7 text-stone-500">
                Join us at {{ $venue['name'] }} for a warm, relaxed celebration in East Lothian. This site will be the
                place for guest details, travel notes, uploads, and wedding-day updates.
            </p>

            <div class="mt-8 flex flex-wrap gap-3">
                <a class="rounded-full bg-stone-900 px-5 py-3 text-sm font-semibold text-white hover:bg-stone-700" href="{{ route('venue') }}">
                    Venue
                </a>
                <a class="rounded-full bg-rose-100 px-5 py-3 text-sm font-semibold text-rose-900 hover:bg-rose-200" href="{{ auth()->check() ? route('dashboard') : route('login') }}">
                    RSVP / Login
                </a>
                <a class="rounded-full border border-stone-300 px-5 py-3 text-sm font-semibold hover:bg-white" href="{{ route('gallery') }}">
                    Gallery
                </a>
            </div>
        </div>

        <div class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
            <p class="text-xs uppercase tracking-[0.35em] text-rose-700">Countdown</p>
            <div class="mt-6 grid grid-cols-3 gap-3">
                <div class="rounded-3xl bg-stone-100 p-4">
                    <p class="text-3xl font-semibold">{{ number_format($countdown['days']) }}</p>
                    <p class="mt-1 text-xs uppercase tracking-[0.3em] text-stone-500">Days</p>
                </div>
                <div class="rounded-3xl bg-stone-100 p-4">
                    <p class="text-3xl font-semibold">{{ number_format($countdown['weeks']) }}</p>
                    <p class="mt-1 text-xs uppercase tracking-[0.3em] text-stone-500">Weeks</p>
                </div>
                <div class="rounded-3xl bg-stone-100 p-4">
                    <p class="text-3xl font-semibold">{{ number_format($countdown['months']) }}</p>
                    <p class="mt-1 text-xs uppercase tracking-[0.3em] text-stone-500">Months</p>
                </div>
            </div>

            <div class="mt-6 rounded-3xl bg-amber-50 p-5">
                <p class="text-sm text-stone-600">Wedding date</p>
                <p class="mt-2 text-2xl font-semibold">{{ $weddingDate->format('l, j F Y') }}</p>
                <p class="mt-2 text-sm text-stone-500">{{ $venue['name'] }} · {{ $venue['location'] }}</p>
            </div>
        </div>
    </section>

    <section class="mt-12 grid gap-6 lg:grid-cols-3">
        <article class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
            <p class="text-xs uppercase tracking-[0.35em] text-rose-700">The Day</p>
            <h2 class="font-editorial mt-4 text-3xl">A calm day with plenty of time to celebrate.</h2>
            <p class="mt-4 text-sm leading-7 text-stone-600">
                We are aiming for something elegant and relaxed: a beautiful venue, great food, and enough space for everyone to enjoy it without fuss.
            </p>
        </article>

        <article class="rounded-[2rem] border border-stone-200 bg-rose-50 p-6">
            <p class="text-xs uppercase tracking-[0.35em] text-rose-700">Venue</p>
            <h2 class="font-editorial mt-4 text-3xl">{{ $venue['name'] }}</h2>
            <p class="mt-4 text-sm leading-7 text-stone-600">
                Historic, warm, and full of character. The venue page will hold directions, timings, and local information as plans firm up.
            </p>
        </article>

        <article class="rounded-[2rem] border border-stone-200 bg-amber-50 p-6">
            <p class="text-xs uppercase tracking-[0.35em] text-amber-700">Guest Info</p>
            <h2 class="font-editorial mt-4 text-3xl">Travel, taxis, and photo sharing</h2>
            <p class="mt-4 text-sm leading-7 text-stone-600">
                The site already includes the first public sections for travel planning and gallery uploads, with the admin side ready for moderation and guest management.
            </p>
        </article>
    </section>
@endsection
