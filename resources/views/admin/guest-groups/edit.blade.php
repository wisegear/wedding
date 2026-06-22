@extends('layouts.admin')

@section('content')
    <section class="rounded-[2rem] border border-stone-200 bg-white p-8 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.35em] text-rose-700">Guests</p>
                <h2 class="font-editorial mt-4 text-4xl">Edit {{ $guestGroup->group_name }}</h2>
                <p class="mt-4 max-w-2xl text-sm leading-7 text-stone-600">
                    Update the guest list or remove guests from this group.
                </p>
            </div>

            <a class="text-sm font-medium text-rose-700 hover:text-rose-900" href="{{ route('admin.guest-groups.index') }}">
                Back to guest groups
            </a>
        </div>

        <form class="mt-8 space-y-8" method="POST" action="{{ route('admin.guest-groups.update', $guestGroup) }}">
            @csrf
            @method('PATCH')

            @include('admin.guest-groups._form', ['submitLabel' => 'Save changes'])
        </form>
    </section>
@endsection
