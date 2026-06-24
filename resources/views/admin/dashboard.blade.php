@extends('layouts.admin')

@section('content')
    <section class="space-y-6">
        <div class="rounded-[2rem] border border-stone-200 bg-white p-8 shadow-sm">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.35em] text-rose-700">Dashboard</p>
                    <h2 class="font-editorial mt-4 text-4xl">Wedding admin control room</h2>
                    <p class="mt-4 max-w-3xl text-sm leading-7 text-stone-600">
                        This is the first admin scaffold for guest management, RSVP tracking, dining choices, and gallery moderation.
                    </p>
                </div>

                <a class="inline-flex items-center justify-center rounded-full bg-stone-900 px-5 py-3 text-sm font-medium text-white transition hover:bg-stone-800" href="{{ route('home') }}">
                    Homepage
                </a>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-4">
            <article class="rounded-[1.75rem] border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-xs uppercase tracking-[0.35em] text-rose-700">Wedding Date</p>
                <p class="mt-3 text-2xl font-semibold">{{ $weddingDate->format('j F Y') }}</p>
                <p class="mt-2 text-sm text-stone-500">{{ $venue['name'] }}</p>
            </article>
            <article class="rounded-[1.75rem] border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-xs uppercase tracking-[0.35em] text-rose-700">Guest Groups</p>
                <p class="mt-3 text-2xl font-semibold">{{ $guestGroupCount }}</p>
            </article>
            <article class="rounded-[1.75rem] border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-xs uppercase tracking-[0.35em] text-rose-700">Guests</p>
                <p class="mt-3 text-2xl font-semibold">{{ $guestCount }}</p>
            </article>
            <article class="rounded-[1.75rem] border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-xs uppercase tracking-[0.35em] text-rose-700">Pending Photos</p>
                <p class="mt-3 text-2xl font-semibold">{{ $pendingGalleryCount }}</p>
            </article>
        </div>
    </section>
@endsection
