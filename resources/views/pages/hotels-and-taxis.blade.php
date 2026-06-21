@extends('layouts.public')

@section('content')
    <section class="space-y-8">
        <div class="rounded-[2rem] border border-stone-200 bg-white p-8 shadow-sm">
            <p class="text-xs uppercase tracking-[0.35em] text-rose-700">Hotels & Taxis</p>
            <h1 class="font-editorial mt-4 text-5xl">Travel planning for the wedding</h1>
            <p class="mt-4 max-w-3xl text-base leading-7 text-stone-600">
                This section will become the guest guide for where to stay, how to get around, and what to plan for on the day.
            </p>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <article class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                <p class="text-xs uppercase tracking-[0.35em] text-rose-700">Local Hotels</p>
                <p class="mt-4 text-sm leading-7 text-stone-600">
                    Placeholder cards for nearby accommodation options will appear here, with distances and booking notes.
                </p>
            </article>

            <article class="rounded-[2rem] border border-stone-200 bg-rose-50 p-6">
                <p class="text-xs uppercase tracking-[0.35em] text-rose-700">Taxi Numbers</p>
                <p class="mt-4 text-sm leading-7 text-stone-600">
                    Trusted local taxi firms and evening return options will be listed here once confirmed.
                </p>
            </article>

            <article class="rounded-[2rem] border border-stone-200 bg-amber-50 p-6">
                <p class="text-xs uppercase tracking-[0.35em] text-amber-700">Travel Tips</p>
                <p class="mt-4 text-sm leading-7 text-stone-600">
                    Practical notes such as journey times, nearby stations, and late-night travel advice will live here.
                </p>
            </article>
        </div>
    </section>
@endsection
