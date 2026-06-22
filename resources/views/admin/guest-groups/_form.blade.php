@php
    $guestRows = collect(old('guests', isset($guestGroup) ? $guestGroup->guests->map(fn ($guest) => [
        'id' => $guest->id,
        'first_name' => $guest->first_name,
        'last_name' => $guest->last_name,
        'notes' => $guest->notes,
    ])->values()->all() : []));

    if ($guestRows->isEmpty()) {
        $guestRows = collect([[
            'first_name' => '',
            'last_name' => '',
            'notes' => '',
        ]]);
    }
@endphp

@if ($errors->has('guests'))
    <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
        {{ $errors->first('guests') }}
    </div>
@endif

<div class="grid gap-6 lg:grid-cols-2">
    <div>
        <label class="mb-2 block text-sm font-medium text-stone-700" for="group_name">Group name</label>
        <input
            class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm shadow-sm focus:border-rose-500 focus:outline-none"
            id="group_name"
            name="group_name"
            required
            type="text"
            value="{{ old('group_name', $guestGroup->group_name) }}"
        >
        @error('group_name')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-medium text-stone-700" for="notes">Notes</label>
        <textarea
            class="min-h-28 w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm shadow-sm focus:border-rose-500 focus:outline-none"
            id="notes"
            name="notes"
        >{{ old('notes', $guestGroup->notes) }}</textarea>
        @error('notes')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.35em] text-rose-700">Guests</p>
            <h3 class="font-editorial mt-2 text-2xl">Group members</h3>
        </div>

        <button
            class="rounded-full border border-stone-300 px-4 py-2 text-sm font-medium text-stone-700 hover:border-stone-400 hover:text-stone-900"
            id="add-guest-row"
            type="button"
        >
            Add another guest
        </button>
    </div>

    <div class="space-y-4" id="guest-rows">
        @foreach ($guestRows as $index => $guestRow)
            <div class="guest-row rounded-[1.75rem] border border-stone-200 bg-stone-50 p-5">
                @if (! empty($guestRow['id']))
                    <input name="guests[{{ $index }}][id]" type="hidden" value="{{ $guestRow['id'] }}">
                @endif

                <div class="grid gap-4 lg:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-stone-700">First name</label>
                        <input class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm shadow-sm" name="guests[{{ $index }}][first_name]" required type="text" value="{{ $guestRow['first_name'] ?? '' }}">
                        @error("guests.$index.first_name")
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-stone-700">Last name</label>
                        <input class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm shadow-sm" name="guests[{{ $index }}][last_name]" required type="text" value="{{ $guestRow['last_name'] ?? '' }}">
                        @error("guests.$index.last_name")
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label class="mb-2 block text-sm font-medium text-stone-700">Notes</label>
                    <textarea class="min-h-24 w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm shadow-sm" name="guests[{{ $index }}][notes]">{{ $guestRow['notes'] ?? '' }}</textarea>
                </div>

                <div class="mt-4 flex justify-end">
                    <button class="remove-guest-row text-sm font-medium text-stone-500 hover:text-stone-900" type="button">
                        Remove guest
                    </button>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="flex justify-end">
    <button class="rounded-full bg-stone-900 px-6 py-3 text-sm font-medium text-white hover:bg-stone-700" type="submit">
        {{ $submitLabel }}
    </button>
</div>

<template id="guest-row-template">
    <div class="guest-row rounded-[1.75rem] border border-stone-200 bg-stone-50 p-5">
        <div class="grid gap-4 lg:grid-cols-2">
            <div>
                <label class="mb-2 block text-sm font-medium text-stone-700">First name</label>
                <input class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm shadow-sm" data-name="first_name" required type="text">
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-stone-700">Last name</label>
                <input class="w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm shadow-sm" data-name="last_name" required type="text">
            </div>
        </div>

        <div class="mt-4">
            <label class="mb-2 block text-sm font-medium text-stone-700">Notes</label>
            <textarea class="min-h-24 w-full rounded-2xl border border-stone-300 px-4 py-3 text-sm shadow-sm" data-name="notes"></textarea>
        </div>

        <div class="mt-4 flex justify-end">
            <button class="remove-guest-row text-sm font-medium text-stone-500 hover:text-stone-900" type="button">
                Remove guest
            </button>
        </div>
    </div>
</template>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const rows = document.querySelector('#guest-rows');
        const addButton = document.querySelector('#add-guest-row');
        const template = document.querySelector('#guest-row-template');

        const renumberRows = () => {
            rows.querySelectorAll('.guest-row').forEach((row, index) => {
                row.querySelectorAll('[data-name]').forEach((field) => {
                    field.name = `guests[${index}][${field.dataset.name}]`;
                    if (field.id) {
                        field.id = `${field.dataset.name}-${index}`;
                    }
                });

            });
        };

        const ensureOneRow = () => {
            if (rows.querySelectorAll('.guest-row').length === 0) {
                const fragment = template.content.cloneNode(true);
                rows.appendChild(fragment);
                renumberRows();
            }
        };

        addButton?.addEventListener('click', () => {
            const fragment = template.content.cloneNode(true);
            rows.appendChild(fragment);
            renumberRows();
        });

        rows?.addEventListener('click', (event) => {
            const target = event.target;

            if (!(target instanceof HTMLElement) || !target.classList.contains('remove-guest-row')) {
                return;
            }

            target.closest('.guest-row')?.remove();
            ensureOneRow();
            renumberRows();
        });

        renumberRows();
    });
</script>
