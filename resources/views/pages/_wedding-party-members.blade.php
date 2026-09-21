@foreach ($members as $member)
    <article @class([
        'relative flex min-w-0 flex-col items-center border text-center shadow-sm',
        'gap-5 rounded-[2rem] p-7' => $prominent,
        'gap-3 rounded-3xl p-4' => ! $prominent,
        'bg-garden-blush border-[#e6c9c7]' => $loop->odd,
        'bg-garden-blend border-[#d8d8ca]' => $loop->even,
    ])>
        @if ($member->photo_path)
            <img @class(['shrink-0 rounded-full object-cover ring-4 ring-white/80', 'size-40' => $prominent, 'size-20' => ! $prominent]) src="{{ Storage::disk('public')->url($member->photo_path) }}" alt="{{ $member->name }}" width="200" height="200" loading="lazy">
        @else
            <div aria-hidden="true" @class(['font-display flex shrink-0 items-center justify-center rounded-full bg-white/60 text-[#8d8e7c] ring-4 ring-white/80', 'size-40 text-5xl' => $prominent, 'size-20 text-3xl' => ! $prominent])>{{ mb_substr($member->name, 0, 1) }}</div>
        @endif
        <div class="min-w-0">
            <h3 @class(['font-display break-words text-[#4d513f]', 'text-3xl' => $prominent, 'text-xl' => ! $prominent])>{{ $member->name }}</h3>
            @if ($member->role === 'Parent' && $member->parent_side)
                <p class="text-sage mt-2 text-sm font-medium">{{ $member->roleLabel() }}</p>
            @endif
            @if ($member->description)
                <p class="mt-2 whitespace-pre-line break-words text-sm leading-6 text-stone-600">{{ $member->description }}</p>
            @endif
        </div>
    </article>
@endforeach
