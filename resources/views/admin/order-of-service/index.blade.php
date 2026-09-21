@extends('layouts.admin')

@section('content')
    @php($isEditing = $editingItem !== null)

    <section class="space-y-6 text-stone-900">
        <div class="rounded-[2rem] border border-stone-200 bg-white p-8 shadow-sm">
            <h2 class="font-editorial text-4xl">Edit Homepage</h2>
            <p class="mt-4 text-sm leading-7 text-stone-600">Update the welcome text and order of service shown on the home page.</p>
        </div>

        <section class="rounded-[2rem] border border-stone-200 bg-white p-8 shadow-sm">
            <h3 class="font-editorial text-3xl">Names and welcome text</h3>
            <form class="mt-6 space-y-6" method="POST" action="{{ route('admin.order-of-service.homepage') }}">
                @csrf
                @method('PATCH')
                <div>
                    <label class="mb-2 block text-sm font-medium" for="partner_one">First partner’s name</label>
                    <input class="w-full rounded-2xl border border-stone-300 px-4 py-3" id="partner_one" name="partner_one" required maxlength="255" value="{{ old('partner_one', $orderOfService->partner_one) }}">
                    @error('partner_one')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium" for="partner_two">Second partner’s name</label>
                    <input class="w-full rounded-2xl border border-stone-300 px-4 py-3" id="partner_two" name="partner_two" required maxlength="255" value="{{ old('partner_two', $orderOfService->partner_two) }}">
                    @error('partner_two')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium" for="intro">First introduction paragraph</label>
                    <textarea class="w-full rounded-2xl border border-stone-300 px-4 py-3" id="intro" name="intro" required maxlength="5000" rows="3">{{ old('intro', $orderOfService->intro) }}</textarea>
                    @error('intro')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium" for="guest_intro">Guest welcome paragraph</label>
                    <textarea class="w-full rounded-2xl border border-stone-300 px-4 py-3" id="guest_intro" name="guest_intro" required maxlength="5000" rows="3">{{ old('guest_intro', $orderOfService->guest_intro) }}</textarea>
                    @error('guest_intro')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <button class="rounded-full bg-stone-900 px-6 py-3 text-sm font-medium text-white hover:bg-stone-700" type="submit">Save homepage text</button>
            </form>
        </section>

        <section class="rounded-[2rem] border border-stone-200 bg-white p-8 shadow-sm">
            <h3 class="font-editorial text-3xl">Heading and tagline</h3>
            <form class="mt-6 space-y-6" method="POST" action="{{ route('admin.order-of-service.settings') }}">
                @csrf
                @method('PATCH')
                <div>
                    <label class="mb-2 block text-sm font-medium" for="heading">Panel title</label>
                    <input class="w-full rounded-2xl border border-stone-300 px-4 py-3" id="heading" name="heading" required maxlength="255" value="{{ old('heading', $orderOfService->heading) }}">
                    @error('heading')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium" for="tagline">Tagline (optional)</label>
                    <textarea class="w-full rounded-2xl border border-stone-300 px-4 py-3" id="tagline" name="tagline" rows="3" maxlength="1000">{{ old('tagline', $orderOfService->tagline) }}</textarea>
                    <p class="mt-2 text-sm text-stone-500">Shown below the title on the home page. Leave blank to hide it.</p>
                    @error('tagline')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <button class="rounded-full bg-stone-900 px-6 py-3 text-sm font-medium text-white hover:bg-stone-700" type="submit">Save heading and tagline</button>
            </form>
        </section>

        <section class="rounded-[2rem] border border-stone-200 bg-white p-8 shadow-sm">
            <h3 class="font-editorial text-3xl">{{ $isEditing ? 'Edit item' : 'Add item' }}</h3>
            <form class="mt-6 grid gap-6 sm:grid-cols-[minmax(0,1fr)_10rem]" method="POST" action="{{ $isEditing ? route('admin.order-of-service.update', $editingItem) : route('admin.order-of-service.store') }}">
                @csrf
                @if ($isEditing)
                    @method('PATCH')
                @endif
                <div>
                    <label class="mb-2 block text-sm font-medium" for="title">Title</label>
                    <input class="w-full rounded-2xl border border-stone-300 px-4 py-3" id="title" name="title" type="text" required maxlength="255" value="{{ old('title', $editingItem?->title) }}">
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium" for="starts_at">Time</label>
                    <input class="w-full rounded-2xl border border-stone-300 px-4 py-3" id="starts_at" name="starts_at" type="time" required step="60" value="{{ old('starts_at', $editingItem?->formTime()) }}">
                    @error('starts_at')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex flex-wrap items-center gap-4 sm:col-span-2">
                    <button class="rounded-full bg-stone-900 px-6 py-3 text-sm font-medium text-white hover:bg-stone-700" type="submit">{{ $isEditing ? 'Save changes' : 'Add item' }}</button>
                    @if ($isEditing)
                        <a class="text-sm font-medium text-rose-700" href="{{ route('admin.order-of-service.index') }}">Cancel edit</a>
                    @endif
                </div>
            </form>
        </section>

        <section class="rounded-[2rem] border border-stone-200 bg-white p-8 shadow-sm">
            <h3 class="font-editorial text-3xl">Saved items</h3>
            <ol class="mt-6 divide-y divide-stone-200">
                @forelse ($items as $item)
                    <li class="flex items-baseline gap-4 py-4">
                        <time class="w-20 shrink-0 font-semibold" datetime="{{ $item->formTime() }}">{{ $item->displayTime() }}</time>
                        <span class="min-w-0 flex-1 break-words">{{ $item->title }}</span>
                        <a class="text-sm font-medium text-rose-700 hover:text-rose-900" href="{{ route('admin.order-of-service.index', ['edit' => $item->id]) }}" aria-label="Edit {{ $item->title }}">Edit</a>
                        <a class="text-sm font-medium text-red-700 hover:text-red-900" href="{{ route('admin.order-of-service.confirm-delete', $item) }}" aria-label="Delete {{ $item->title }}">Delete</a>
                    </li>
                @empty
                    <li class="py-4 text-sm text-stone-500">No items yet. Add the first event above.</li>
                @endforelse
            </ol>
        </section>
    </section>
@endsection
