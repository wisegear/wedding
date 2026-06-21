@extends('layouts.admin')

@section('content')
    <section class="rounded-[2rem] border border-stone-200 bg-white p-8 shadow-sm">
        <p class="text-xs uppercase tracking-[0.35em] text-rose-700">Dining</p>
        <h2 class="font-editorial mt-4 text-4xl">Meal choices and notes</h2>
        <p class="mt-4 text-sm leading-7 text-stone-600">
            Placeholder page for dining options, dietary requirements, and seating-related planning. Dining options created: {{ $optionCount }}.
        </p>
    </section>
@endsection
