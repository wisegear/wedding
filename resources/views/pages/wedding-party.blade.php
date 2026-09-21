@extends('layouts.public')

@section('content')
    <section class="space-y-8">
        <div class="shadow-garden rounded-[2rem] border border-white/80 bg-white/92 p-8">
            <p class="text-sage text-xs uppercase tracking-[0.35em]">Wedding Party</p>
            <h1 class="font-display mt-4 text-5xl text-[#4d513f]">{{ $pageSettings->wedding_party_title }}</h1>
            <p class="mt-4 max-w-3xl text-base leading-7 text-stone-600">
                {{ $pageSettings->wedding_party_intro }}
            </p>
        </div>

        <div class="grid gap-x-10 gap-y-12 lg:grid-cols-2">
            @foreach ($groups as $group)
                @if ($group['members']->isNotEmpty())
                    @php($prominent = in_array($group['title'], ['Bride', 'Groom']))
                    <section class="relative min-w-0 space-y-4 {{ $group['position'] }}">
                        @unless ($prominent)
                            <span aria-hidden="true" class="absolute -top-12 left-1/2 hidden h-12 w-px bg-[#b8beaa] lg:block"></span>
                            <span aria-hidden="true" class="absolute -top-2 left-1/2 hidden size-2 -translate-x-1/2 rounded-full bg-[#8d8e7c] lg:block"></span>
                        @endunless
                        <h2 class="font-display text-center text-2xl font-bold text-[#4d513f]">{{ $group['title'] }}</h2>
                        <div @class([
                            'relative grid gap-4',
                            'sm:grid-cols-2' => str_starts_with($group['title'], 'Parents'),
                            'mx-auto max-w-sm' => ! $prominent && ! str_starts_with($group['title'], 'Parents'),
                        ])>
                            @include('pages._wedding-party-members', ['members' => $group['members'], 'prominent' => $prominent])
                        </div>
                    </section>
                @endif
            @endforeach
        </div>
        @foreach ($otherGroups as $group)
            @if ($group['members']->isNotEmpty())
                <section class="space-y-4">
                    <h2 class="font-display text-center text-2xl font-bold text-[#4d513f]">{{ $group['title'] }}</h2>
                    <div class="grid gap-6 lg:grid-cols-2">
                        @include('pages._wedding-party-members', ['members' => $group['members'], 'prominent' => false])
                    </div>
                </section>
            @endif
        @endforeach
        @if ($membersByRole->has('Flower Girl') || $membersByRole->has('Page Boy'))
            <div class="grid items-start gap-8 lg:grid-cols-2">
                @foreach ($finalGroups as $group)
                    @if ($group['members']->isNotEmpty())
                        <section class="space-y-4 {{ $group['position'] }}">
                            <h2 class="font-display text-center text-2xl font-bold text-[#4d513f]">{{ $group['title'] }}</h2>
                            <div class="mx-auto grid max-w-sm gap-4">
                                @include('pages._wedding-party-members', ['members' => $group['members'], 'prominent' => false])
                            </div>
                        </section>
                    @endif
                @endforeach
            </div>
        @endif
        @if ($membersByRole->isEmpty())
            <p class="bg-garden-soft rounded-[2rem] p-8 text-stone-600">We will introduce our wedding party here soon.</p>
        @endif
    </section>
@endsection
