<x-layouts::app :title="__('Dashboard')">
    <div class="space-y-6">
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
                <p class="mt-2 text-sm text-stone-300">Guest confirmations and attendance flow will appear here.</p>
            </article>

            <article class="rounded-3xl border border-stone-200 bg-rose-50 p-5">
                <p class="text-xs uppercase tracking-[0.35em] text-rose-700">Dining Options</p>
                <p class="mt-3 text-2xl font-semibold text-stone-900">Meal choices</p>
                <p class="mt-2 text-sm text-stone-600">Menu selections and dietary notes will be managed here.</p>
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

        @if (auth()->user()->isAdmin())
            <section class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-rose-700">Admin Access</p>
                <p class="mt-3 text-lg font-semibold text-stone-900">This account can access the admin area.</p>
                <a class="mt-4 inline-flex text-sm font-medium text-rose-700 hover:text-rose-900" href="{{ route('admin.dashboard') }}">
                    Open admin dashboard
                </a>
            </section>
        @endif
    </div>
</x-layouts::app>
