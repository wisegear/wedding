@extends('layouts.public')

@section('content')
    <section class="space-y-8">
        <div class="shadow-garden rounded-[2rem] border border-white/80 bg-white/92 p-8">
            <p class="text-sage text-xs uppercase tracking-[0.35em]">Gallery</p>
            <h1 class="font-display mt-4 text-5xl text-[#4d513f]">Shared photos from the wedding</h1>
            <p class="mt-4 max-w-3xl text-base leading-7 text-stone-600">
                Approved guest uploads will appear here. Choose one or more photos from your phone or computer. Uploaded photos are reviewed before appearing in the gallery.
            </p>
        </div>

        @auth
            <div class="bg-garden-blend rounded-[2rem] border border-[#d8d8ca] p-6">
                <h2 class="text-xl font-semibold text-stone-900">Upload photos</h2>
                <form class="mt-4 grid gap-4 md:grid-cols-[1fr_auto]" method="POST" action="{{ route('gallery.upload') }}" enctype="multipart/form-data">
                    @csrf
                    <div>
                        <label class="mb-2 block text-sm font-medium text-stone-700" for="images">Photos</label>
                        <input class="block w-full rounded-2xl border border-[#d8d8ca] bg-white px-4 py-3 text-sm" id="images" name="images[]" type="file" accept="image/*" multiple required>
                        @error('images')
                            <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
                        @enderror
                        @if ($errors->has('images.*'))
                            <p class="mt-2 text-sm text-red-700">{{ $errors->first('images.*') }}</p>
                        @endif
                        <p class="mt-2 text-sm text-stone-600">
                            Choose one or more photos from your phone or computer. Uploaded photos are reviewed before appearing in the gallery.
                        </p>
                    </div>

                    <div class="flex items-end">
                        <button class="bg-sage-deep w-full rounded-full px-5 py-3 text-sm font-semibold text-white hover:opacity-95 md:w-auto" type="submit">
                            Upload photos
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="bg-garden-blush rounded-[2rem] border border-[#e6c9c7] p-6">
                <p class="text-sm text-stone-700">
                    Sign in to upload wedding photos. Uploaded images are reviewed by an admin before appearing publicly.
                </p>
            </div>
        @endauth

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($uploads as $upload)
                <button
                    class="shadow-garden overflow-hidden rounded-[1.75rem] border border-white/80 bg-white/92 text-left transition hover:scale-[1.01] hover:shadow-lg"
                    data-gallery-image="{{ asset('storage/'.$upload->display_path) }}"
                    type="button"
                >
                    <img class="aspect-[4/3] w-full object-cover" src="{{ asset('storage/'.$upload->display_path) }}" alt="Wedding gallery photo">
                </button>
            @empty
                @for ($i = 1; $i <= 6; $i++)
                    <div class="aspect-[4/3] rounded-[1.75rem] border border-dashed border-[#d8d8ca] bg-[#ededdf]"></div>
                @endfor
            @endforelse
        </div>

        @if ($uploads->hasPages())
            <div class="flex justify-center">
                <div class="rounded-[1.75rem] border border-white/80 bg-white/92 px-4 py-3 shadow-garden">
                    {{ $uploads->links() }}
                </div>
            </div>
        @endif

        <div class="fixed inset-0 z-50 hidden items-center justify-center bg-stone-950/85 p-6" id="gallery-lightbox">
            <button class="absolute right-6 top-6 rounded-full bg-white/90 px-4 py-2 text-sm font-medium text-stone-900 hover:bg-white" id="gallery-lightbox-close" type="button">
                Close
            </button>

            <img class="max-h-[85vh] w-auto max-w-full rounded-[1.75rem] object-contain shadow-2xl" id="gallery-lightbox-image" src="" alt="Expanded wedding gallery photo">
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const lightbox = document.querySelector('#gallery-lightbox');
            const lightboxImage = document.querySelector('#gallery-lightbox-image');
            const closeButton = document.querySelector('#gallery-lightbox-close');
            const triggers = document.querySelectorAll('[data-gallery-image]');

            const closeLightbox = () => {
                lightbox?.classList.add('hidden');
                lightbox?.classList.remove('flex');
                if (lightboxImage) {
                    lightboxImage.src = '';
                }
            };

            triggers.forEach((trigger) => {
                trigger.addEventListener('click', () => {
                    const imageSrc = trigger.getAttribute('data-gallery-image');

                    if (!lightbox || !lightboxImage || !imageSrc) {
                        return;
                    }

                    lightboxImage.src = imageSrc;
                    lightbox.classList.remove('hidden');
                    lightbox.classList.add('flex');
                });
            });

            closeButton?.addEventListener('click', closeLightbox);

            lightbox?.addEventListener('click', (event) => {
                if (event.target === lightbox) {
                    closeLightbox();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeLightbox();
                }
            });
        });
    </script>
@endpush
