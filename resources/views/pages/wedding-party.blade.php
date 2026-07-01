@extends('layouts.public')

@section('content')
    <section class="space-y-8">
        <div class="shadow-garden rounded-[2rem] border border-white/80 bg-white/92 p-8">
            <p class="text-sage text-xs uppercase tracking-[0.35em]">Wedding Party</p>
            <h1 class="font-display mt-4 text-5xl text-[#4d513f]">Meet the wedding party</h1>
            <p class="mt-4 max-w-3xl text-base leading-7 text-stone-600">
                A guide to the family and friends standing with us on the day, from bridesmaids and groomsmen to the smaller helpers making the ceremony feel extra special.
            </p>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            @foreach ($groups as $group)
                <article @class([
                    'rounded-[2rem] border p-6',
                    'bg-garden-blush border-[#e6c9c7]' => $loop->odd,
                    'bg-garden-blend border-[#d8d8ca]' => $loop->even,
                ])>
                    <p class="{{ $group['accent'] }} text-xs uppercase tracking-[0.35em]">{{ $group['role'] }}</p>
                    <p class="mt-4 text-sm leading-7 text-stone-600">{{ $group['description'] }}</p>

                    <ul class="mt-6 grid gap-3">
                        @foreach ($group['members'] as $member)
                            <li class="rounded-2xl border border-white/80 bg-white/75 px-4 py-3 text-sm font-medium text-[#4d513f]">
                                {{ $member }}
                            </li>
                        @endforeach
                    </ul>
                </article>
            @endforeach
        </div>
    </section>
@endsection
