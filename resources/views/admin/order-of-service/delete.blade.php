@extends('layouts.admin')

@section('content')
    <section class="mx-auto max-w-2xl rounded-[2rem] border border-stone-200 bg-white p-8 text-stone-900 shadow-sm">
        <h2 class="font-editorial text-3xl">Delete this item?</h2>
        <p class="mt-4 text-lg font-semibold">{{ $item->displayTime() }} — {{ $item->title }}</p>
        <p class="mt-4 rounded-2xl bg-red-50 p-4 text-sm leading-7 text-red-800">Warning: this will permanently delete this item and remove it from the home page. This cannot be undone.</p>
        <form class="mt-6 flex flex-wrap items-center gap-4" method="POST" action="{{ route('admin.order-of-service.destroy', $item) }}">
            @csrf
            @method('DELETE')
            <a class="rounded-full border border-stone-300 px-6 py-3 text-sm font-medium" href="{{ route('admin.order-of-service.index') }}">Cancel</a>
            <button class="rounded-full bg-red-700 px-6 py-3 text-sm font-medium text-white hover:bg-red-800" type="submit">Delete item permanently</button>
        </form>
    </section>
@endsection
