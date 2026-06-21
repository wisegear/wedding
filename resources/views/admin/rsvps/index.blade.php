@extends('layouts.admin')

@section('content')
    <section class="rounded-[2rem] border border-stone-200 bg-white p-8 shadow-sm">
        <p class="text-xs uppercase tracking-[0.35em] text-rose-700">RSVPs</p>
        <h2 class="font-editorial mt-4 text-4xl">Response tracking</h2>
        <p class="mt-4 text-sm leading-7 text-stone-600">
            Placeholder page for RSVP tracking and reminders. Guests without a response: {{ $pendingCount }}.
        </p>
    </section>
@endsection
