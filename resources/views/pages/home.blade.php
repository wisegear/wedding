@extends('layouts.public')

@section('content')
    <section class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr] lg:items-end">
        <div>
            <p class="text-thistle text-sm uppercase tracking-[0.45em]">24 May 2027 · Falside · East Neuk mood</p>
            <h1 class="font-display mt-4 text-5xl leading-none text-[#2f2540] sm:text-6xl lg:text-7xl">
                {{ $couple['partner_one'] }} & {{ $couple['partner_two'] }}
            </h1>
            <p class="mt-6 max-w-2xl text-lg leading-8 text-stone-650">
                {{ $intro }}
            </p>
            <p class="mt-4 max-w-2xl text-base leading-7 text-stone-500">
                Join us on Scotland's east coast for a celebration shaped by sea air, old stone, and the soft violet tones
                of the Scottish thistle. This site will hold guest details, travel notes, uploads, and wedding-day updates.
            </p>

            <div class="mt-8 flex flex-wrap gap-3">
                <a class="bg-heather-deep rounded-full px-5 py-3 text-sm font-semibold text-white hover:opacity-95" href="{{ route('venue') }}">
                    Venue
                </a>
                <a class="rounded-full bg-[#e9dff3] px-5 py-3 text-sm font-semibold text-[#4f3567] hover:bg-[#dfd0ed]" href="{{ auth()->check() ? route('dashboard') : route('login') }}">
                    RSVP / Login
                </a>
                <a class="border-thistle rounded-full border bg-white/80 px-5 py-3 text-sm font-semibold text-[#4f3567] hover:bg-white" href="{{ route('gallery') }}">
                    Gallery
                </a>
            </div>
        </div>

        <div class="shadow-thistle rounded-[2rem] border border-white/80 bg-[#fcfbfe]/92 p-6">
            <p class="text-thistle text-xs uppercase tracking-[0.35em]">Countdown</p>
            <div class="mt-6 grid grid-cols-3 gap-3">
                <div class="rounded-3xl bg-[#f1ebf7] p-4">
                    <p class="text-3xl font-semibold">{{ number_format($countdown['days']) }}</p>
                    <p class="mt-1 text-xs uppercase tracking-[0.3em] text-stone-500">Days</p>
                </div>
                <div class="rounded-3xl bg-[#eef1f7] p-4">
                    <p class="text-3xl font-semibold">{{ number_format($countdown['weeks']) }}</p>
                    <p class="mt-1 text-xs uppercase tracking-[0.3em] text-stone-500">Weeks</p>
                </div>
                <div class="rounded-3xl bg-[#f3f5f6] p-4">
                    <p class="text-3xl font-semibold">{{ number_format($countdown['months']) }}</p>
                    <p class="mt-1 text-xs uppercase tracking-[0.3em] text-stone-500">Months</p>
                </div>
            </div>

            <div class="bg-thistle-soft mt-6 rounded-3xl p-5">
                <p class="text-sm text-stone-600">Wedding date</p>
                <p class="mt-2 text-2xl font-semibold">{{ $weddingDate->format('l, j F Y') }}</p>
                <p class="mt-2 text-sm text-stone-500">{{ $venue['name'] }} · {{ $venue['location'] }}</p>
            </div>
        </div>
    </section>

    <section class="mt-12 grid gap-6 lg:grid-cols-3">
        <article class="shadow-thistle rounded-[2rem] border border-white/80 bg-white/92 p-6">
            <p class="text-thistle text-xs uppercase tracking-[0.35em]">The Day</p>
            <h2 class="font-display mt-4 text-3xl text-[#2f2540]">A calm day with sea air, stone, and heather tones.</h2>
            <p class="mt-4 text-sm leading-7 text-stone-600">
                We are aiming for something elegant and relaxed: a beautiful venue, good food, and the understated richness
                of Scotland's east coast rather than anything overly formal.
            </p>
        </article>

        <article class="rounded-[2rem] border border-[#d9cde6] bg-[#f3edf8] p-6">
            <p class="text-thistle text-xs uppercase tracking-[0.35em]">Venue</p>
            <h2 class="font-display mt-4 text-3xl text-[#2f2540]">{{ $venue['name'] }}</h2>
            <p class="mt-4 text-sm leading-7 text-stone-600">
                Historic, warm, and full of character. The venue page will hold directions, timings, and local information as plans firm up around the day.
            </p>
        </article>

        <article class="rounded-[2rem] border border-[#d7e0e5] bg-[#eef3f5] p-6">
            <p class="text-xs uppercase tracking-[0.35em] text-[#41607c]">Guest Info</p>
            <h2 class="font-display mt-4 text-3xl text-[#2f2540]">Travel, taxis, and photo sharing</h2>
            <p class="mt-4 text-sm leading-7 text-stone-600">
                The site already includes the first public sections for travel planning and gallery uploads, with the admin side ready for moderation and guest management.
            </p>
        </article>
    </section>
@endsection
