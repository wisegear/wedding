@extends('layouts.public')

@section('content')
    <section class="space-y-8">
        <div class="rounded-[2rem] border border-stone-200 bg-white p-8 shadow-sm">
            <p class="text-xs uppercase tracking-[0.35em] text-rose-700">Gallery</p>
            <h1 class="font-editorial mt-4 text-5xl">Shared photos from the wedding</h1>
            <p class="mt-4 max-w-3xl text-base leading-7 text-stone-600">
                Approved guest uploads will appear here. Guests can upload photos after signing in; uploads are held for admin approval first.
            </p>
        </div>

        @auth
            <div class="rounded-[2rem] border border-stone-200 bg-rose-50 p-6">
                <h2 class="text-xl font-semibold text-stone-900">Upload a photo</h2>
                <form class="mt-4 grid gap-4 md:grid-cols-[1fr_1fr_auto]" method="POST" action="{{ route('gallery.upload') }}" enctype="multipart/form-data">
                    @csrf
                    <div>
                        <label class="mb-2 block text-sm font-medium text-stone-700" for="image">Image</label>
                        <input class="block w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-sm" id="image" name="image" type="file" accept="image/*" required>
                        @error('image')
                            <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-stone-700" for="caption">Caption</label>
                        <input class="block w-full rounded-2xl border border-stone-300 bg-white px-4 py-3 text-sm" id="caption" name="caption" type="text" maxlength="255" value="{{ old('caption') }}">
                        @error('caption')
                            <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-end">
                        <button class="w-full rounded-full bg-stone-900 px-5 py-3 text-sm font-semibold text-white hover:bg-stone-700 md:w-auto" type="submit">
                            Upload
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="rounded-[2rem] border border-stone-200 bg-amber-50 p-6">
                <p class="text-sm text-stone-700">
                    Sign in to upload wedding photos. Uploaded images are reviewed by an admin before appearing publicly.
                </p>
            </div>
        @endauth

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($uploads as $upload)
                <article class="overflow-hidden rounded-[1.75rem] border border-stone-200 bg-white shadow-sm">
                    <img class="aspect-[4/3] w-full object-cover" src="{{ asset('storage/'.$upload->path) }}" alt="{{ $upload->caption ?? $upload->original_filename }}">
                    <div class="p-4">
                        <p class="text-sm font-medium text-stone-900">{{ $upload->caption ?: $upload->original_filename }}</p>
                    </div>
                </article>
            @empty
                @for ($i = 1; $i <= 6; $i++)
                    <div class="aspect-[4/3] rounded-[1.75rem] border border-dashed border-stone-300 bg-stone-100"></div>
                @endfor
            @endforelse
        </div>
    </section>
@endsection
