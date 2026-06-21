@extends('layouts.public')

@section('content')
    <section class="space-y-8">
        <div class="shadow-thistle rounded-[2rem] border border-white/80 bg-white/92 p-8">
            <p class="text-thistle text-xs uppercase tracking-[0.35em]">Venue</p>
            <h1 class="font-display mt-4 text-5xl text-[#2f2540]">{{ $venue['name'] }}</h1>
            <p class="mt-4 max-w-3xl text-base leading-7 text-stone-600">
                {{ $venue['name'] }} will host the full celebration. The setting feels right for a Scottish wedding near St Andrews:
                old stone, open air, and enough texture to let the day feel rooted in place.
            </p>
            <a class="bg-heather-deep mt-6 inline-flex rounded-full px-5 py-3 text-sm font-semibold text-white hover:opacity-95" href="{{ $venue['url'] }}" target="_blank" rel="noreferrer">
                Visit falside.co.uk
            </a>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @for ($i = 1; $i <= 4; $i++)
                <div class="aspect-[4/3] rounded-[1.75rem] border border-dashed border-[#cbb6de] bg-[linear-gradient(145deg,#f0e8f7,#edf2f4)]"></div>
            @endfor
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <article class="shadow-thistle rounded-[2rem] border border-white/80 bg-white/92 p-6">
                <p class="text-thistle text-xs uppercase tracking-[0.35em]">Venue Intro</p>
                <p class="mt-4 text-sm leading-7 text-stone-600">
                    Expect this page to grow into the central guide for the ceremony and celebration spaces, with the tone shaped more by Scottish landscape and understated texture than by generic wedding styling.
                </p>
            </article>

            <article class="rounded-[2rem] border border-[#d7e0e5] bg-[#eef3f5] p-6">
                <p class="text-xs uppercase tracking-[0.35em] text-[#41607c]">Directions</p>
                <p class="mt-4 text-sm leading-7 text-stone-600">
                    Directions, parking notes, and arrival guidance will be published here. For now, the venue is listed as {{ $venue['location'] }}, within easy reach of the St Andrews area and East Lothian coast.
                </p>
            </article>
        </div>
    </section>
@endsection
