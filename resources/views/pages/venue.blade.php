@extends('layouts.public')

@section('content')
    <section class="space-y-8">
        <div class="rounded-[2rem] border border-stone-200 bg-white p-8 shadow-sm">
            <p class="text-xs uppercase tracking-[0.35em] text-rose-700">Venue</p>
            <h1 class="font-editorial mt-4 text-5xl">{{ $venue['name'] }}</h1>
            <p class="mt-4 max-w-3xl text-base leading-7 text-stone-600">
                {{ $venue['name'] }} will host the full celebration. This page is the placeholder for all venue-specific details, including timings, arrival notes, and practical information for guests.
            </p>
            <a class="mt-6 inline-flex rounded-full bg-stone-900 px-5 py-3 text-sm font-semibold text-white hover:bg-stone-700" href="{{ $venue['url'] }}" target="_blank" rel="noreferrer">
                Visit falside.co.uk
            </a>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @for ($i = 1; $i <= 4; $i++)
                <div class="aspect-[4/3] rounded-[1.75rem] border border-dashed border-stone-300 bg-stone-100"></div>
            @endfor
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <article class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                <p class="text-xs uppercase tracking-[0.35em] text-rose-700">Venue Intro</p>
                <p class="mt-4 text-sm leading-7 text-stone-600">
                    Expect the venue page to grow into the central guide for the ceremony and celebration spaces, with key details presented clearly for guests.
                </p>
            </article>

            <article class="rounded-[2rem] border border-stone-200 bg-rose-50 p-6">
                <p class="text-xs uppercase tracking-[0.35em] text-rose-700">Directions</p>
                <p class="mt-4 text-sm leading-7 text-stone-600">
                    Directions, parking notes, and arrival guidance will be published here. For now, the venue is listed as {{ $venue['location'] }}.
                </p>
            </article>
        </div>
    </section>
@endsection
