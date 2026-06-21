@extends('layouts.public')

@section('content')
    <section class="space-y-8">
        <div class="shadow-garden rounded-[2rem] border border-white/80 bg-white/92 p-8">
            <p class="text-sage text-xs uppercase tracking-[0.35em]">Venue</p>
            <h1 class="font-display mt-4 text-5xl text-[#33463b]">{{ $venue['name'] }}</h1>
            <p class="mt-4 max-w-3xl text-base leading-7 text-stone-600">
                {{ $venue['name'] }} will host the full celebration. The setting now leans beautifully into a softer garden feeling:
                old stone, open air, and natural detail without anything too heavy.
            </p>
            <a class="bg-sage-deep mt-6 inline-flex rounded-full px-5 py-3 text-sm font-semibold text-white hover:opacity-95" href="{{ $venue['url'] }}" target="_blank" rel="noreferrer">
                Visit falside.co.uk
            </a>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @for ($i = 1; $i <= 4; $i++)
                <div class="aspect-[4/3] rounded-[1.75rem] border border-dashed border-[#d8e1d1] bg-[linear-gradient(145deg,#eef3e8,#f8f1e7)]"></div>
            @endfor
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <article class="shadow-garden rounded-[2rem] border border-white/80 bg-white/92 p-6">
                <p class="text-sage text-xs uppercase tracking-[0.35em]">Venue Intro</p>
                <p class="mt-4 text-sm leading-7 text-stone-600">
                    Expect this page to grow into the central guide for the ceremony and celebration spaces, with the tone shaped more by flowers, greenery, and natural texture than by generic wedding styling.
                </p>
            </article>

            <article class="rounded-[2rem] border border-[#e7ddd0] bg-[#f8f1e7] p-6">
                <p class="text-xs uppercase tracking-[0.35em] text-[#9a7756]">Directions</p>
                <p class="mt-4 text-sm leading-7 text-stone-600">
                    Directions, parking notes, and arrival guidance will be published here. For now, the venue is listed as {{ $venue['location'] }}, within easy reach of the St Andrews area and East Lothian coast.
                </p>
            </article>
        </div>
    </section>
@endsection
