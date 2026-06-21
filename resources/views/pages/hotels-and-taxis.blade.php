@extends('layouts.public')

@section('content')
    <section class="space-y-8">
        <div class="shadow-garden rounded-[2rem] border border-white/80 bg-white/92 p-8">
            <p class="text-sage text-xs uppercase tracking-[0.35em]">Hotels & Taxis</p>
            <h1 class="font-display mt-4 text-5xl text-[#33463b]">Travel planning for the wedding</h1>
            <p class="mt-4 max-w-3xl text-base leading-7 text-stone-600">
                This section will become the guest guide for where to stay, how to get around, and what to plan for on the day, with recommendations shaped around the St Andrews coast and nearby routes.
            </p>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <article class="shadow-garden rounded-[2rem] border border-white/80 bg-white/92 p-6">
                <p class="text-sage text-xs uppercase tracking-[0.35em]">Local Hotels</p>
                <p class="mt-4 text-sm leading-7 text-stone-600">
                    Placeholder cards for nearby accommodation options will appear here, with distances, booking notes, and the best bases for guests staying around St Andrews and the East Neuk.
                </p>
            </article>

            <article class="rounded-[2rem] border border-[#d8e1d1] bg-[#eef3e8] p-6">
                <p class="text-sage text-xs uppercase tracking-[0.35em]">Taxi Numbers</p>
                <p class="mt-4 text-sm leading-7 text-stone-600">
                    Trusted local taxi firms and evening return options will be listed here once confirmed.
                </p>
            </article>

            <article class="rounded-[2rem] border border-[#e7ddd0] bg-[#f8f1e7] p-6">
                <p class="text-xs uppercase tracking-[0.35em] text-[#9a7756]">Travel Tips</p>
                <p class="mt-4 text-sm leading-7 text-stone-600">
                    Practical notes such as journey times, nearby stations, and late-night travel advice will live here.
                </p>
            </article>
        </div>
    </section>
@endsection
