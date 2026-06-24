@extends('layouts.admin')

@section('content')
    <section class="space-y-6">
        <div class="flex flex-col gap-4 rounded-[2rem] border border-stone-200 bg-white p-8 shadow-sm lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.35em] text-rose-700">Guests</p>
                <h2 class="font-editorial mt-4 text-4xl">Guest list</h2>
                <p class="mt-4 max-w-2xl text-sm leading-7 text-stone-600">
                    A full list of every guest currently assigned to a guest group.
                </p>
            </div>

            <a
                class="inline-flex items-center justify-center rounded-full border border-stone-300 bg-white px-5 py-3 text-sm font-medium text-stone-700 hover:bg-stone-50"
                href="{{ route('admin.guest-groups.index') }}"
            >
                Back to guest groups
            </a>
        </div>

        <div class="overflow-hidden rounded-[2rem] border border-stone-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-stone-200 text-sm">
                <thead class="bg-stone-50 text-left text-stone-500">
                    <tr>
                        <th class="px-6 py-4 font-medium">Guest name</th>
                        <th class="px-6 py-4 font-medium">Group</th>
                        <th class="px-6 py-4 font-medium">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse ($guests as $guest)
                        <tr>
                            <td class="px-6 py-4">
                                <p class="font-medium text-stone-900">{{ $guest->display_name }}</p>
                            </td>
                            <td class="px-6 py-4 text-stone-600">{{ $guest->group?->group_name ?? '—' }}</td>
                            <td class="px-6 py-4 text-stone-600">{{ $guest->notes ?: '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-6 py-8 text-stone-500" colspan="3">
                                No guests yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
