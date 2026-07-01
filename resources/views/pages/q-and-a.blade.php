@extends('layouts.public')

@section('content')
    <section class="space-y-8">
        <div class="shadow-garden rounded-[2rem] border border-white/80 bg-white/92 p-8">
            <p class="text-sage text-xs uppercase tracking-[0.35em]">Q & A</p>
            <h1 class="font-display mt-4 text-5xl text-[#4d513f]">Wedding questions</h1>
            <p class="mt-4 max-w-3xl text-base leading-7 text-stone-600">
                A place for the practical details guests are likely to need before the day. We will keep adding answers here as plans are confirmed.
            </p>
        </div>

        <div class="grid gap-5">
            @foreach ($questions as $item)
                <article @class([
                    'rounded-[2rem] border p-6',
                    'bg-garden-blend border-[#d8d8ca]' => $loop->odd,
                    'bg-garden-blush border-[#e6c9c7]' => $loop->even,
                ])>
                    <p class="{{ $item['accent'] }} text-xs uppercase tracking-[0.35em]">Question</p>
                    <h2 class="font-display mt-3 text-3xl text-[#4d513f]">{{ $item['question'] }}</h2>
                    <p class="mt-4 text-sm leading-7 text-stone-600">{{ $item['answer'] }}</p>
                </article>
            @endforeach
        </div>
    </section>
@endsection
