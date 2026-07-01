@extends('layouts.public')

@section('content')
    <section class="grid gap-8 lg:grid-cols-[1.05fr_0.95fr] lg:items-start">
        <div>
            <p class="text-sage text-sm uppercase tracking-[0.45em]">22 May 2027 · Falside · Kingsbarns</p>
            <h1 class="font-display mt-4 text-5xl leading-none text-[#4d513f] sm:text-6xl lg:text-7xl">
                {{ $couple['partner_one'] }} & {{ $couple['partner_two'] }}
            </h1>
            <p class="mt-6 max-w-2xl text-lg leading-8 text-stone-650">
                {{ $intro }}
            </p>
            <p class="mt-4 max-w-2xl text-base leading-7 text-stone-500">
                We are looking forward to our guests joining us for a weekend of celebration. This site allows you RSVP, 
                tell us your meal preferences, and upload photos from the day. We will also be sharing travel information and local recommendations here.
            </p>

            <div class="mt-8 flex flex-wrap gap-3">
                <a class="bg-sage-deep rounded-full px-5 py-3 text-sm font-semibold text-white hover:opacity-95" href="{{ route('venue') }}">
                    Venue
                </a>
                <a class="rounded-full bg-[#ffe5e4] px-5 py-3 text-sm font-semibold text-[#4d513f] hover:bg-[#efbfbb]" href="{{ auth()->check() ? route('dashboard') : route('login') }}">
                    RSVP / Login
                </a>
                <a class="border-sage rounded-full border bg-white/80 px-5 py-3 text-sm font-semibold text-[#4d513f] hover:bg-white" href="{{ route('gallery') }}">
                    Gallery
                </a>
            </div>
        </div>

        <div class="relative pt-38 lg:pt-44">
            <div class="pointer-events-none absolute inset-x-0 top-[20px] z-0 flex justify-center">
                <img
                    class="w-[21rem] max-w-[86vw] drop-shadow-[0_22px_34px_rgba(102,105,86,0.18)] sm:w-[24rem] lg:w-[27rem]"
                    src="{{ asset('images/ring.png') }}"
                    alt="Engagement ring"
                >
            </div>

            <div class="shadow-garden relative z-10 rounded-[2rem] border border-white/80 bg-[#fff8f6]/92 p-6">
                <p class="text-sage text-xs uppercase tracking-[0.35em]">Countdown</p>
                <div class="mt-6 grid grid-cols-3 gap-3">
                    <div class="rounded-3xl bg-[#ffe5e4] p-4">
                        <p class="text-3xl font-semibold">{{ number_format($countdown['days']) }}</p>
                        <p class="mt-1 text-xs uppercase tracking-[0.3em] text-stone-500">Days</p>
                    </div>
                    <div class="rounded-3xl bg-[#f4d9d6] p-4">
                        <p class="text-3xl font-semibold">{{ number_format($countdown['weeks']) }}</p>
                        <p class="mt-1 text-xs uppercase tracking-[0.3em] text-stone-500">Weeks</p>
                    </div>
                    <div class="rounded-3xl bg-[#ededdf] p-4">
                        <p class="text-3xl font-semibold">{{ number_format($countdown['months']) }}</p>
                        <p class="mt-1 text-xs uppercase tracking-[0.3em] text-stone-500">Months</p>
                    </div>
                </div>

                <div class="bg-garden-soft mt-6 rounded-3xl p-5">
                    <p class="text-sm text-stone-600">Wedding date</p>
                    <p class="mt-2 text-2xl font-semibold">{{ $weddingDate->format('l, j F Y') }}</p>
                    <p class="mt-2 text-sm text-stone-500">{{ $venue['name'] }} · {{ $venue['location'] }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-4">
        <article class="shadow-garden rounded-[2rem] border border-white/80 bg-white/92 p-6">
            <p class="text-sage text-xs uppercase tracking-[0.35em]">The Day</p>
            <h2 class="font-display mt-4 text-3xl text-[#4d513f]">A day to remember</h2>
            <p class="mt-4 text-sm leading-7 text-stone-600">
                Martin & Lyndsey are looking forward to celebrating their wedding with friends and family. 
                The day will be filled with love, laughter, and memories to last a lifetime.
            </p>
        </article>

        <article class="rounded-[2rem] border border-[#d8d8ca] bg-[#ededdf] p-6">
            <p class="text-sage text-xs uppercase tracking-[0.35em]">Venue</p>
            <h2 class="font-display mt-4 text-3xl text-[#4d513f]">{{ $venue['name'] }}</h2>
            <p class="mt-4 text-sm leading-7 text-stone-600">
                Historic, warm, and full of character. The venue has been lovingly restored, the perfect setting for a wedding celebration. 
                The surrounding area offers stunning views plenty space for guests.
            </p>
        </article>

        <article class="rounded-[2rem] border border-[#efbfbb] bg-[#ffe5e4] p-6">
            <p class="text-xs uppercase tracking-[0.35em] text-[#b08a81]">Guest Info</p>
            <h2 class="font-display mt-4 text-3xl text-[#4d513f]">Everything you need</h2>
            <p class="mt-4 text-sm leading-7 text-stone-600">
                The site already includes the first public sections for travel planning and gallery uploads, with the admin side ready for moderation and guest management.
            </p>
        </article>

        <article class="shadow-garden rounded-[2rem] border border-white/80 bg-white/92 p-6">
            <p class="text-sage text-xs uppercase tracking-[0.35em]">QR Code</p>
            <h2 class="font-display mt-4 text-3xl text-[#4d513f]">Share QR Code</h2>
            <div class="mt-5 flex justify-center rounded-3xl bg-white p-4 ring-1 ring-[#666956]/15">
                <img
                    class="aspect-square w-full max-w-48 object-contain"
                    src="{{ asset('images/wedding-qr.png') }}"
                    alt="QR code for the wedding website"
                >
            </div>
        </article>
    </section>
@endsection
