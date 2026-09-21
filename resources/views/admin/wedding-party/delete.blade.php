@extends('layouts.admin')

@section('content')
    <section class="mx-auto max-w-2xl rounded-[2rem] border border-stone-200 bg-white p-8 text-stone-900 shadow-sm">
        <h2 class="font-editorial text-3xl">Delete {{ $member->name }}?</h2>
        <p class="mt-4 rounded-2xl bg-red-50 p-4 text-sm leading-7 text-red-800">This will permanently remove this person and their photo from the wedding party. This cannot be undone.</p>
        <form class="mt-6 flex flex-wrap items-center gap-4" method="POST" action="{{ route('admin.wedding-party.destroy', $member) }}">
            @csrf
            @method('DELETE')
            <a class="rounded-full border border-stone-300 px-6 py-3 text-sm" href="{{ route('admin.wedding-party.index') }}">Cancel</a>
            <button class="rounded-full bg-red-700 px-6 py-3 text-sm text-white" type="submit">Delete person permanently</button>
        </form>
    </section>
@endsection
