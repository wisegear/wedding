<x-layouts::app :title="__('Dining Choices')">
    @php
        $optionLookup = $options->flatten()->keyBy('id');
    @endphp

    <div class="space-y-6">
        @if (session('status'))
            <section class="rounded-3xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 shadow-sm">
                {{ session('status') }}
            </section>
        @endif

        <section class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-rose-700">Dining</p>
            <h1 class="mt-3 text-3xl font-semibold text-stone-900">{{ $guestGroup->group_name }}</h1>
            <p class="mt-2 max-w-3xl text-sm leading-6 text-stone-600">
                Choose one starter, one main, and one dessert for each guest. You can come back later to edit these choices.
            </p>
        </section>

        @if ($isEditMode)
            <section class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
                <form class="space-y-6" method="POST" action="{{ route('dining.update') }}">
                    @csrf
                    @method('PUT')

                    @foreach ($guestGroup->guests as $guest)
                        <article class="rounded-[2rem] border border-stone-200 bg-stone-50 p-6">
                            <p class="text-xs uppercase tracking-[0.35em] text-rose-700">Guest</p>
                            <h2 class="mt-3 text-2xl font-semibold text-stone-900">{{ $guest->display_name ?: $guest->first_name.' '.$guest->last_name }}</h2>

                            <div class="mt-6 grid gap-6 lg:grid-cols-3">
                                @foreach (['starter' => 'Starter', 'main' => 'Main', 'dessert' => 'Dessert'] as $courseKey => $courseLabel)
                                    <section class="rounded-[1.75rem] border border-stone-200 bg-white p-5">
                                        <p class="text-xs uppercase tracking-[0.35em] text-rose-700">{{ $courseLabel }}</p>
                                        <div class="mt-4 space-y-3">
                                            @foreach ($options->get($courseKey, collect()) as $option)
                                                <label class="flex items-start gap-3 rounded-2xl border border-stone-200 px-4 py-3">
                                                    <input
                                                        @checked((int) old("choices.{$guest->id}.{$courseKey}", $guest->dinner_choice[$courseKey] ?? 0) === $option->id)
                                                        class="mt-1 h-4 w-4 border-stone-300 text-rose-700 focus:ring-rose-500"
                                                        name="choices[{{ $guest->id }}][{{ $courseKey }}]"
                                                        required
                                                        type="radio"
                                                        value="{{ $option->id }}"
                                                    >
                                                    <span>
                                                        <span class="block font-medium text-stone-900">{{ $option->name }}</span>
                                                        <span class="mt-1 block text-sm text-stone-500">{{ $option->description ?: 'No description provided.' }}</span>
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                        @error("choices.{$guest->id}.{$courseKey}")
                                            <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </section>
                                @endforeach
                            </div>

                            <div class="mt-6">
                                <label class="mb-2 block text-sm font-medium text-stone-700" for="dietary-{{ $guest->id }}">Allergies and special requirements</label>
                                <p class="mb-3 text-sm text-stone-500">
                                    If you have any allergies or special dietary requirements, please tell us about them below.
                                </p>
                                <textarea
                                    class="min-h-28 w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-rose-500 focus:outline-none"
                                    id="dietary-{{ $guest->id }}"
                                    name="dietary_requirements[{{ $guest->id }}]"
                                >{{ old("dietary_requirements.{$guest->id}", $guest->dietary_requirements) }}</textarea>
                            </div>
                        </article>
                    @endforeach

                    <div class="flex justify-end">
                        <button class="rounded-full bg-stone-900 px-6 py-3 text-sm font-medium text-white hover:bg-stone-700" type="submit">
                            Save dining choices
                        </button>
                    </div>
                </form>
            </section>
        @else
            <section class="space-y-6">
                <div class="flex justify-end">
                    <a class="rounded-full border border-stone-300 px-5 py-3 text-sm font-medium text-stone-700 hover:border-stone-400 hover:text-stone-900" href="{{ route('dining.edit', ['edit' => 1]) }}">
                        Edit choices
                    </a>
                </div>

                @foreach ($guestGroup->guests as $guest)
                    <article class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
                        <p class="text-xs uppercase tracking-[0.35em] text-rose-700">Guest</p>
                        <h2 class="mt-3 text-2xl font-semibold text-stone-900">{{ $guest->display_name ?: $guest->first_name.' '.$guest->last_name }}</h2>

                        <div class="mt-6 grid gap-4 lg:grid-cols-3">
                            @foreach (['starter' => 'Starter', 'main' => 'Main', 'dessert' => 'Dessert'] as $courseKey => $courseLabel)
                                @php
                                    $selectedOption = $optionLookup->get($guest->dinner_choice[$courseKey] ?? null);
                                @endphp
                                <section class="rounded-[1.75rem] border border-stone-200 bg-stone-50 p-5">
                                    <p class="text-xs uppercase tracking-[0.35em] text-rose-700">{{ $courseLabel }}</p>
                                    <h3 class="mt-3 text-lg font-semibold text-stone-900">{{ $selectedOption?->name ?: 'Not selected' }}</h3>
                                    <p class="mt-2 text-sm leading-6 text-stone-600">{{ $selectedOption?->description ?: 'No choice saved.' }}</p>
                                </section>
                            @endforeach
                        </div>

                        <div class="mt-6 rounded-[1.75rem] border border-stone-200 bg-stone-50 p-5">
                            <p class="text-xs uppercase tracking-[0.35em] text-rose-700">Allergies / Special Requirements</p>
                            <p class="mt-3 text-sm leading-6 text-stone-700">{{ $guest->dietary_requirements ?: 'None provided.' }}</p>
                        </div>
                    </article>
                @endforeach
            </section>
        @endif
    </div>
</x-layouts::app>
