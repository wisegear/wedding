@extends('layouts.admin')

@section('content')
    <section class="rounded-[2rem] border border-stone-200 bg-white p-8 shadow-sm">
        <p class="text-xs uppercase tracking-[0.35em] text-rose-700">Guests</p>
        <h2 class="font-editorial mt-4 text-4xl">Guest management</h2>
        <p class="mt-4 text-sm leading-7 text-stone-600">
            Placeholder page for guest and household management. Current guest records: {{ $guestCount }}.
        </p>
    </section>
@endsection
