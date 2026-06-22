@extends('layouts.admin')

@section('content')
    <section class="rounded-[2rem] border border-stone-200 bg-white p-8 shadow-sm">
        <p class="text-xs uppercase tracking-[0.35em] text-rose-700">Guests</p>
        <h2 class="font-editorial mt-4 text-4xl">Create guest group</h2>
        <p class="mt-4 max-w-2xl text-sm leading-7 text-stone-600">
            Add an invitation group and all of its guests in one submission.
        </p>

        <form class="mt-8 space-y-8" method="POST" action="{{ route('admin.guest-groups.store') }}">
            @csrf

            @include('admin.guest-groups._form', ['submitLabel' => 'Create guest group'])
        </form>
    </section>
@endsection
