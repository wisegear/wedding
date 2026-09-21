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
                {{ $guestIntro }}
            </p>

            <div class="mt-8 flex flex-wrap gap-3">
                <a class="bg-sage-deep rounded-full px-5 py-3 text-sm font-semibold text-white hover:opacity-95" href="{{ route('venue') }}">
                    Venue
                </a>
                <a class="bg-blush-button rounded-full px-5 py-3 text-sm font-semibold text-[#4d513f] hover:bg-[#f6d6d4]" href="{{ auth()->check() ? route('dashboard') : route('login') }}">
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

            <div class="shadow-garden relative z-10 rounded-[2rem] border border-white/80 bg-[#f7f8f1]/92 p-6">
                <p class="text-sage text-xs uppercase tracking-[0.35em]">Countdown</p>
                <div class="mt-6 grid grid-cols-3 gap-3">
                    <div class="bg-garden-blend rounded-3xl p-4">
                        <p class="text-3xl font-semibold">{{ number_format($countdown['days']) }}</p>
                        <p class="mt-1 text-xs uppercase tracking-[0.3em] text-stone-500">Days</p>
                    </div>
                    <div class="rounded-3xl bg-[#e4e5d6] p-4">
                        <p class="text-3xl font-semibold">{{ number_format($countdown['weeks']) }}</p>
                        <p class="mt-1 text-xs uppercase tracking-[0.3em] text-stone-500">Weeks</p>
                    </div>
                    <div class="bg-garden-blush rounded-3xl p-4">
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

    <section class="mt-12 grid gap-6 lg:grid-cols-4">
        <article class="shadow-garden col-span-full rounded-[2rem] border border-white/80 bg-white/92 p-6">
            <div class="relative pr-24 sm:pr-32">
                <h2 class="font-display break-words text-3xl text-[#4d513f]">{{ $orderOfService->heading }}</h2>
                @if ($orderOfService->tagline)
                    <p class="mt-1 whitespace-pre-line break-words text-sm leading-7 text-stone-600">{{ $orderOfService->tagline }}</p>
                @endif
                <img
                    class="absolute inset-y-0 right-0 h-full w-20 object-contain object-right mix-blend-multiply sm:w-28"
                    src="{{ asset('images/order-of-service.png') }}"
                    alt="Illustrated wedding programme with botanical details, ribbon and wedding rings"
                    width="1254"
                    height="1254"
                    loading="lazy"
                >
            </div>
            <ol class="mt-8 space-y-4">
                @forelse ($orderOfServiceItems as $item)
                    <li class="relative pl-7 sm:pl-9">
                        @unless ($loop->last)
                            <span aria-hidden="true" class="absolute top-5 bottom-[-1.5rem] left-[5px] w-px bg-[#d8d8ca]"></span>
                        @endunless
                        <span aria-hidden="true" @class([
                            'absolute top-7 left-0 size-3 rounded-full ring-4 ring-white',
                            'bg-[#8d8e7c]' => $loop->odd,
                            'bg-[#d6a39e]' => $loop->even,
                        ])></span>
                        <div @class([
                            'flex flex-col items-start gap-3 rounded-3xl border p-4 sm:flex-row sm:items-center sm:gap-6 sm:px-6 sm:py-5',
                            'bg-garden-blend border-[#d8d8ca]' => $loop->odd,
                            'bg-garden-blush border-[#e6c9c7]' => $loop->even,
                        ])>
                            <time @class([
                                'inline-flex min-w-24 shrink-0 justify-center rounded-full px-4 py-2 text-sm font-semibold tabular-nums',
                                'bg-sage-deep text-white' => $loop->odd,
                                'bg-[#f6d6d4] text-[#6f4945]' => $loop->even,
                            ]) datetime="{{ $item->formTime() }}">{{ $item->displayTime() }}</time>
                            <span class="font-display min-w-0 break-words text-2xl leading-snug text-[#4d513f]">{{ $item->title }}</span>
                        </div>
                    </li>
                @empty
                    <li class="bg-garden-soft rounded-3xl border border-[#d8d8ca] px-6 py-5 text-stone-600">Timings will be shared soon.</li>
                @endforelse
            </ol>
        </article>

        <article class="shadow-garden col-span-full flex items-center justify-between gap-4 rounded-2xl border border-white/80 bg-white/92 px-5 py-3">
            <h2 class="font-display text-xl text-[#4d513f]">Share QR Code</h2>
            <div class="shrink-0 rounded-xl bg-white p-2 ring-1 ring-[#666956]/15">
                <img
                    class="size-20 object-contain"
                    src="{{ asset('images/wedding-qr.png') }}"
                    alt="QR code for the wedding website"
                >
            </div>
        </article>
    </section>
@endsection
