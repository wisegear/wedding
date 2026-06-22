@extends('layouts.admin')

@section('content')
    @php
        $groupedOptions = $options->groupBy('course');
        $isEditing = $editingOption !== null;
    @endphp

    <section class="space-y-6">
        <div class="rounded-[2rem] border border-stone-200 bg-white p-8 shadow-sm">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.35em] text-rose-700">Dining</p>
                    <h2 class="font-editorial mt-4 text-4xl">Meal options</h2>
                    <p class="mt-4 max-w-3xl text-sm leading-7 text-stone-600">
                        Add the wedding menu options here. Each item belongs to a course and can include a short description.
                    </p>
                </div>

                <a class="inline-flex items-center justify-center rounded-full border border-stone-300 px-5 py-3 text-sm font-medium text-stone-700 hover:border-stone-400 hover:text-stone-900" href="{{ route('admin.dining.selections') }}">
                    View guest selections
                </a>
            </div>
        </div>

        <section class="rounded-[2rem] border border-stone-200 bg-white p-8 shadow-sm">
            <p class="text-xs uppercase tracking-[0.35em] text-rose-700">Add Option</p>
            <div class="mt-4 flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <h3 class="font-editorial text-3xl">{{ $isEditing ? 'Edit menu item' : 'Create menu item' }}</h3>

                @if ($isEditing)
                    <a class="inline-flex items-center justify-center rounded-full border border-stone-300 px-5 py-3 text-sm font-medium text-stone-700 hover:border-stone-400 hover:text-stone-900" href="{{ route('admin.dining.index') }}">
                        Cancel edit
                    </a>
                @endif
            </div>

            <form class="mt-8 grid gap-6 xl:grid-cols-[16rem_minmax(0,1fr)_minmax(0,1.2fr)] xl:items-start" method="POST" action="{{ $isEditing ? route('admin.dining.update', $editingOption) : route('admin.dining.store') }}">
                @csrf
                @if ($isEditing)
                    @method('PATCH')
                @endif

                <div>
                    <label class="mb-2 block text-sm font-medium text-stone-700" for="course">Course</label>
                    <select
                        class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm shadow-sm focus:border-rose-500 focus:outline-none"
                        id="course"
                        name="course"
                        required
                    >
                        <option value="">Select a course</option>
                        <option value="starter" @selected(old('course', $editingOption?->course) === 'starter')>Starter</option>
                        <option value="main" @selected(old('course', $editingOption?->course) === 'main')>Main</option>
                        <option value="dessert" @selected(old('course', $editingOption?->course) === 'dessert')>Dessert</option>
                    </select>
                    @error('course')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-stone-700" for="name">Food item</label>
                    <input
                        class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm shadow-sm focus:border-rose-500 focus:outline-none"
                        id="name"
                        name="name"
                        required
                        type="text"
                        value="{{ old('name', $editingOption?->name) }}"
                    >
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-stone-700" for="description">Description</label>
                    <textarea
                        class="min-h-32 w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm shadow-sm focus:border-rose-500 focus:outline-none"
                        id="description"
                        name="description"
                    >{{ old('description', $editingOption?->description) }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="xl:col-span-3 flex justify-end">
                    <button class="rounded-full bg-stone-900 px-6 py-3 text-sm font-medium text-white hover:bg-stone-700" type="submit">
                        {{ $isEditing ? 'Save changes' : 'Add meal option' }}
                    </button>
                </div>
            </form>
        </section>

        <section class="rounded-[2rem] border border-stone-200 bg-white p-8 shadow-sm">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.35em] text-rose-700">Current Menu</p>
                    <h3 class="font-editorial mt-4 text-3xl">Saved options</h3>
                </div>

                <p class="text-sm text-stone-500">{{ $options->count() }} option{{ $options->count() === 1 ? '' : 's' }}</p>
            </div>

            @if ($options->isEmpty())
                <p class="mt-8 rounded-[1.75rem] border border-dashed border-stone-300 bg-stone-50 px-5 py-8 text-sm text-stone-500">
                    No meal options have been added yet.
                </p>
            @else
                <div class="mt-8 grid gap-6 xl:grid-cols-3">
                    @foreach (['starter' => 'Starter', 'main' => 'Main', 'dessert' => 'Dessert'] as $courseKey => $courseLabel)
                        <section class="rounded-[1.75rem] border border-stone-200 bg-stone-50 p-5">
                            <div class="flex items-center justify-between gap-4">
                                <p class="text-xs uppercase tracking-[0.35em] text-rose-700">{{ $courseLabel }}</p>
                                <p class="text-sm text-stone-500">{{ $groupedOptions->get($courseKey, collect())->count() }}</p>
                            </div>

                            <div class="mt-4 space-y-4">
                                @forelse ($groupedOptions->get($courseKey, collect()) as $option)
                                    <article class="rounded-[1.5rem] border border-stone-200 bg-white p-4">
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <h4 class="text-lg font-semibold text-stone-900">{{ $option->name }}</h4>
                                                <p class="mt-2 text-sm leading-6 text-stone-600">{{ $option->description ?: 'No description added.' }}</p>
                                            </div>

                                            <div class="flex items-center gap-3">
                                                <a class="text-sm font-medium text-rose-700 hover:text-rose-900" href="{{ route('admin.dining.index', ['edit' => $option->id]) }}">
                                                    Edit
                                                </a>

                                                <form method="POST" action="{{ route('admin.dining.destroy', $option) }}" onsubmit="return confirm('Delete {{ addslashes($option->name) }}? This cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="text-sm font-medium text-stone-500 hover:text-stone-900" type="submit">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </article>
                                @empty
                                    <p class="rounded-[1.5rem] border border-dashed border-stone-300 bg-white px-4 py-6 text-sm text-stone-500">
                                        No {{ strtolower($courseLabel) }} options yet.
                                    </p>
                                @endforelse
                            </div>
                        </section>
                    @endforeach
                </div>
            @endif
        </section>
    </section>
@endsection
