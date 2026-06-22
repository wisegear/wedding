@extends('layouts.admin')

@section('content')
    <section class="space-y-6">
        <div class="rounded-[2rem] border border-stone-200 bg-white p-8 shadow-sm">
            <p class="text-xs uppercase tracking-[0.35em] text-rose-700">RSVPs</p>
            <h2 class="font-editorial mt-4 text-4xl">Response tracking</h2>
            <p class="mt-4 max-w-3xl text-sm leading-7 text-stone-600">
                Review every guest response in one place and see who is attending, who has declined, and who has not replied yet.
            </p>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-[1.75rem] border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-xs uppercase tracking-[0.35em] text-rose-700">Total Guests</p>
                <p class="mt-3 text-2xl font-semibold">{{ $totalCount }}</p>
            </article>

            <article class="rounded-[1.75rem] border border-emerald-200 bg-emerald-50 p-5 shadow-sm">
                <p class="text-xs uppercase tracking-[0.35em] text-emerald-700">Attending</p>
                <p class="mt-3 text-2xl font-semibold text-emerald-900">{{ $attendingCount }}</p>
            </article>

            <article class="rounded-[1.75rem] border border-amber-200 bg-amber-50 p-5 shadow-sm">
                <p class="text-xs uppercase tracking-[0.35em] text-amber-700">Not Attending</p>
                <p class="mt-3 text-2xl font-semibold text-amber-900">{{ $notAttendingCount }}</p>
            </article>

            <article class="rounded-[1.75rem] border border-stone-200 bg-stone-100 p-5 shadow-sm">
                <p class="text-xs uppercase tracking-[0.35em] text-stone-600">No Response</p>
                <p class="mt-3 text-2xl font-semibold text-stone-900">{{ $noResponseCount }}</p>
            </article>
        </div>

        <div class="overflow-hidden rounded-[2rem] border border-stone-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-stone-200 text-sm">
                <thead class="bg-stone-50 text-left text-stone-500">
                    <tr>
                        <th class="px-6 py-4 font-medium">Guest</th>
                        <th class="px-6 py-4 font-medium">Group</th>
                        <th class="px-6 py-4 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse ($guests as $guest)
                        <tr>
                            <td class="px-6 py-4">
                                <p class="font-medium text-stone-900">{{ $guest->display_name ?: $guest->first_name.' '.$guest->last_name }}</p>
                            </td>
                            <td class="px-6 py-4 text-stone-600">{{ $guest->group?->group_name ?: '—' }}</td>
                            <td class="px-6 py-4">
                                @if ($guest->rsvp_status === 'attending')
                                    <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium uppercase tracking-[0.2em] text-emerald-800">
                                        Attending
                                    </span>
                                @elseif ($guest->rsvp_status === 'not_attending')
                                    <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-medium uppercase tracking-[0.2em] text-amber-800">
                                        Not attending
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-stone-200 px-3 py-1 text-xs font-medium uppercase tracking-[0.2em] text-stone-700">
                                        No response
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-6 py-8 text-stone-500" colspan="3">
                                No guests found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
