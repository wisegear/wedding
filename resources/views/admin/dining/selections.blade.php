@extends('layouts.admin')

@section('content')
    <section class="space-y-6">
        <div class="rounded-[2rem] border border-stone-200 bg-white p-8 shadow-sm">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.35em] text-rose-700">Dining</p>
                    <h2 class="font-editorial mt-4 text-4xl">Guest selections</h2>
                    <p class="mt-4 max-w-3xl text-sm leading-7 text-stone-600">
                        Review how many times each menu item has been selected, then scan every guest's saved meal choices and requirements.
                    </p>
                </div>

                <a class="inline-flex items-center justify-center rounded-full border border-stone-300 px-5 py-3 text-sm font-medium text-stone-700 hover:border-stone-400 hover:text-stone-900" href="{{ route('admin.dining.index') }}">
                    Back to meal options
                </a>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-3">
            @foreach (['starter' => 'Starter', 'main' => 'Main', 'dessert' => 'Dessert'] as $courseKey => $courseLabel)
                <section class="rounded-[2rem] border border-stone-200 bg-white p-8 shadow-sm">
                    <p class="text-xs uppercase tracking-[0.35em] text-rose-700">{{ $courseLabel }}</p>
                    <div class="mt-6 space-y-4">
                        @forelse ($groupedOptionCounts->get($courseKey, collect()) as $item)
                            <article class="rounded-[1.5rem] border border-stone-200 bg-stone-50 p-5">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-stone-900">{{ $item['option']->name }}</h3>
                                        <p class="mt-2 text-sm leading-6 text-stone-600">{{ $item['option']->description ?: 'No description added.' }}</p>
                                    </div>

                                    <div class="rounded-full bg-stone-900 px-4 py-2 text-sm font-medium text-white">
                                        {{ $item['count'] }}
                                    </div>
                                </div>
                            </article>
                        @empty
                            <p class="rounded-[1.5rem] border border-dashed border-stone-300 bg-stone-50 px-5 py-8 text-sm text-stone-500">
                                No {{ strtolower($courseLabel) }} options yet.
                            </p>
                        @endforelse
                    </div>
                </section>
            @endforeach
        </div>

        <section class="rounded-[2rem] border border-stone-200 bg-white p-8 shadow-sm">
            <p class="text-xs uppercase tracking-[0.35em] text-rose-700">Guest List</p>
            <h3 class="font-editorial mt-4 text-3xl">Saved choices</h3>

            <div class="mt-8 overflow-hidden rounded-[1.75rem] border border-stone-200">
                <table class="min-w-full divide-y divide-stone-200 text-sm">
                    <thead class="bg-stone-50 text-left text-stone-500">
                        <tr>
                            <th class="px-6 py-4 font-medium">Guest</th>
                            <th class="px-6 py-4 font-medium">Group</th>
                            <th class="px-6 py-4 font-medium">Starter</th>
                            <th class="px-6 py-4 font-medium">Main</th>
                            <th class="px-6 py-4 font-medium">Dessert</th>
                            <th class="px-6 py-4 font-medium">Requirements</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @forelse ($guests as $guest)
                            @php
                                $starter = $optionLookup->get($guest->dinner_choice['starter'] ?? null);
                                $main = $optionLookup->get($guest->dinner_choice['main'] ?? null);
                                $dessert = $optionLookup->get($guest->dinner_choice['dessert'] ?? null);
                            @endphp
                            <tr>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-stone-900">{{ $guest->display_name ?: $guest->first_name.' '.$guest->last_name }}</p>
                                </td>
                                <td class="px-6 py-4 text-stone-600">{{ $guest->group?->group_name ?: '—' }}</td>
                                <td class="px-6 py-4 text-stone-600">{{ $starter?->name ?: '—' }}</td>
                                <td class="px-6 py-4 text-stone-600">{{ $main?->name ?: '—' }}</td>
                                <td class="px-6 py-4 text-stone-600">{{ $dessert?->name ?: '—' }}</td>
                                <td class="px-6 py-4 text-stone-600">{{ $guest->dietary_requirements ?: '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-8 text-stone-500" colspan="6">
                                    No guests found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </section>
@endsection
