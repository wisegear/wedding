@extends('layouts.admin')

@section('content')
    <section class="space-y-6">
        <div class="flex flex-col gap-4 rounded-[2rem] border border-stone-200 bg-white p-8 shadow-sm lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.35em] text-rose-700">Guests</p>
                <h2 class="font-editorial mt-4 text-4xl">Guest groups</h2>
                <p class="mt-4 max-w-2xl text-sm leading-7 text-stone-600">
                    Create invitation groups, assign guests, and control who can register.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a
                    class="inline-flex items-center justify-center rounded-full border border-stone-300 bg-white px-5 py-3 text-sm font-medium text-stone-700 hover:bg-stone-50"
                    href="{{ route('admin.guest-groups.guest-list') }}"
                >
                    Guest list
                </a>
                <a
                    class="inline-flex items-center justify-center rounded-full bg-stone-900 px-5 py-3 text-sm font-medium text-white hover:bg-stone-700"
                    href="{{ route('admin.guest-groups.create') }}"
                >
                    Add guest group
                </a>
            </div>
        </div>

        <div class="overflow-hidden rounded-[2rem] border border-stone-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-stone-200 text-sm">
                <thead class="bg-stone-50 text-left text-stone-500">
                    <tr>
                        <th class="px-6 py-4 font-medium">Group</th>
                        <th class="px-6 py-4 font-medium">Guests</th>
                        <th class="px-6 py-4 font-medium">Notes</th>
                        <th class="px-6 py-4 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse ($guestGroups as $guestGroup)
                        <tr>
                            <td class="px-6 py-4">
                                <p class="font-medium text-stone-900">{{ $guestGroup->group_name }}</p>
                            </td>
                            <td class="px-6 py-4 text-stone-600">
                                <p>{{ $guestGroup->guests_count }}</p>

                                @if ($guestGroup->guests->isNotEmpty())
                                    <p class="mt-2 text-xs leading-6 text-stone-500">
                                        {{ $guestGroup->guests->pluck('display_name')->join(', ') }}
                                    </p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-stone-600">{{ $guestGroup->notes ?: '—' }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-3">
                                    <a class="text-rose-700 hover:text-rose-900" href="{{ route('admin.guest-groups.edit', $guestGroup) }}">
                                        Edit
                                    </a>

                                    <form method="POST" action="{{ route('admin.guest-groups.destroy', $guestGroup) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-stone-500 hover:text-stone-900" type="submit">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-6 py-8 text-stone-500" colspan="4">
                                No guest groups yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
