<x-layouts::app :title="__('Dashboard')">
    <div class="space-y-6">
        @if (session('status'))
            <section class="rounded-3xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 shadow-sm">
                {{ session('status') }}
            </section>
        @endif

        <section class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-rose-700">Guest Dashboard</p>
            <h1 class="mt-3 text-3xl font-semibold text-stone-900">Welcome back, {{ auth()->user()->name }}</h1>
            <p class="mt-2 max-w-3xl text-sm leading-6 text-stone-600">
                This is the guest-facing home for RSVP details, dining preferences, photo uploads, and household
                information. The sections below are scaffolded and ready for the next round of build-out.
            </p>
        </section>

        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-3xl border border-stone-200 bg-stone-950 p-5 text-stone-50">
                <p class="text-xs uppercase tracking-[0.35em] text-rose-200/80">RSVP</p>
                <p class="mt-3 text-2xl font-semibold">Respond</p>
                <p class="mt-2 text-sm text-stone-300">
                    Confirm attendance for everyone in your group and come back any time to update it.
                </p>
                @if ($hasGuestGroupLink)
                    <a class="mt-4 inline-flex text-sm font-medium text-rose-200 hover:text-white" href="{{ route('rsvp.edit') }}">
                        Open RSVP form
                    </a>
                @else
                    <p class="mt-4 text-sm text-stone-400">RSVP will unlock once your account is linked to a guest group.</p>
                @endif
            </article>

            <article class="rounded-3xl border border-stone-200 bg-rose-50 p-5">
                <p class="text-xs uppercase tracking-[0.35em] text-rose-700">Dining Options</p>
                <p class="mt-3 text-2xl font-semibold text-stone-900">Meal choices</p>
                <p class="mt-2 text-sm text-stone-600">
                    Select a starter, main, and dessert for each guest in your group, and add allergies or special requirements.
                </p>
                @if ($hasGuestGroupLink && $hasDiningOptions)
                    <a class="mt-4 inline-flex text-sm font-medium text-rose-700 hover:text-rose-900" href="{{ route('dining.edit') }}">
                        Open dining form
                    </a>
                @elseif (! $hasGuestGroupLink)
                    <p class="mt-4 text-sm text-stone-500">Dining will unlock once your account is linked to a guest group.</p>
                @else
                    <p class="mt-4 text-sm text-stone-500">Dining choices will unlock once menu options have been added.</p>
                @endif
            </article>

            <article class="rounded-3xl border border-stone-200 bg-white p-5">
                <p class="text-xs uppercase tracking-[0.35em] text-rose-700">Upload Photos</p>
                <p class="mt-3 text-2xl font-semibold text-stone-900">Wedding gallery</p>
                <p class="mt-2 text-sm text-stone-600">Guests will be able to upload photos for admin approval.</p>
                <a class="mt-4 inline-flex text-sm font-medium text-rose-700 hover:text-rose-900" href="{{ route('gallery') }}">
                    Open gallery
                </a>
            </article>

            <article class="rounded-3xl border border-stone-200 bg-amber-50 p-5">
                <p class="text-xs uppercase tracking-[0.35em] text-amber-700">Guest Details</p>
                <p class="mt-3 text-2xl font-semibold text-stone-900">Group information</p>
                <p class="mt-2 text-sm text-stone-600">Household membership and contact details will live here.</p>
            </article>
        </section>
    </div>
</x-layouts::app>
