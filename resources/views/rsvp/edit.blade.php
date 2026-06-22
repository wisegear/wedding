<x-layouts::app :title="__('RSVP')">
    <div class="space-y-6">
        @if (session('status'))
            <section class="rounded-3xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 shadow-sm">
                {{ session('status') }}
            </section>
        @endif

        <section class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-rose-700">RSVP</p>
            <h1 class="mt-3 text-3xl font-semibold text-stone-900">{{ $guestGroup->group_name }}</h1>
            <p class="mt-2 max-w-3xl text-sm leading-6 text-stone-600">
                Let us know who will be attending. You can return later and update this RSVP at any time.
            </p>
        </section>

        <section class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <form class="space-y-6" method="POST" action="{{ route('rsvp.update') }}">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    @foreach ($guestGroup->guests as $guest)
                        <label class="flex items-center justify-between gap-4 rounded-2xl border border-stone-200 bg-stone-50 px-5 py-4">
                            <div>
                                <p class="text-lg font-medium text-stone-900">{{ $guest->display_name ?: $guest->first_name.' '.$guest->last_name }}</p>
                                <p class="text-sm text-stone-500">
                                    {{ $guest->rsvp_status === 'attending' ? 'Currently attending' : ($guest->rsvp_status === 'not_attending' ? 'Currently not attending' : 'No RSVP submitted yet') }}
                                </p>
                            </div>

                            <div class="flex items-center gap-3">
                                <input name="attending[{{ $guest->id }}]" type="hidden" value="0">
                                <span class="text-sm font-medium text-stone-700">Attending</span>
                                <input
                                    @checked($guest->rsvp_status === 'attending')
                                    class="h-5 w-5 rounded border-stone-300 text-rose-700 focus:ring-rose-500"
                                    name="attending[{{ $guest->id }}]"
                                    type="checkbox"
                                    value="1"
                                >
                            </div>
                        </label>
                    @endforeach
                </div>

                <div class="flex justify-end">
                    <button class="rounded-full bg-stone-900 px-6 py-3 text-sm font-medium text-white hover:bg-stone-700" type="submit">
                        Save RSVP
                    </button>
                </div>
            </form>
        </section>
    </div>
</x-layouts::app>
