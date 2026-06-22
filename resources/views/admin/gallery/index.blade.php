@extends('layouts.admin')

@section('content')
    <section class="space-y-6">
        <div class="rounded-[2rem] border border-stone-200 bg-white p-8 shadow-sm">
            <p class="text-xs uppercase tracking-[0.35em] text-rose-700">Gallery</p>
            <h2 class="font-editorial mt-4 text-4xl">Photo moderation</h2>
            <p class="mt-4 max-w-3xl text-sm leading-7 text-stone-600">
                Review guest uploads, approve the images you want shown publicly, and remove approved images from the public gallery if needed.
            </p>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <article class="rounded-[1.75rem] border border-amber-200 bg-amber-50 p-5 shadow-sm">
                <p class="text-xs uppercase tracking-[0.35em] text-amber-700">Pending Approval</p>
                <p class="mt-3 text-2xl font-semibold text-amber-900">{{ $pendingUploads->count() }}</p>
            </article>

            <article class="rounded-[1.75rem] border border-emerald-200 bg-emerald-50 p-5 shadow-sm">
                <p class="text-xs uppercase tracking-[0.35em] text-emerald-700">Public Gallery</p>
                <p class="mt-3 text-2xl font-semibold text-emerald-900">{{ $approvedUploads->count() }}</p>
            </article>
        </div>

        <section class="rounded-[2rem] border border-stone-200 bg-white p-8 shadow-sm">
            <p class="text-xs uppercase tracking-[0.35em] text-rose-700">Pending</p>
            <h3 class="font-editorial mt-4 text-3xl">Awaiting approval</h3>

            <div class="mt-8 grid gap-6 lg:grid-cols-2 xl:grid-cols-3">
                @forelse ($pendingUploads as $upload)
                    <article class="overflow-hidden rounded-[1.75rem] border border-stone-200 bg-stone-50">
                        <img class="aspect-[4/3] w-full object-cover" src="{{ asset('storage/'.$upload->display_path) }}" alt="{{ $upload->original_filename }}">
                        <div class="space-y-4 p-5">
                            <div>
                                <h4 class="text-lg font-semibold text-stone-900">{{ $upload->original_filename }}</h4>
                                <p class="mt-2 text-sm text-stone-600">
                                    Uploaded by {{ $upload->user?->name ?: 'Unknown guest' }}
                                </p>
                            </div>

                            <form method="POST" action="{{ route('admin.gallery.approve', $upload) }}">
                                @csrf
                                @method('PATCH')
                                <button class="w-full rounded-full bg-stone-900 px-5 py-3 text-sm font-medium text-white hover:bg-stone-700" type="submit">
                                    Approve photo
                                </button>
                            </form>
                        </div>
                    </article>
                @empty
                    <p class="rounded-[1.75rem] border border-dashed border-stone-300 bg-stone-50 px-5 py-8 text-sm text-stone-500 lg:col-span-2 xl:col-span-3">
                        No photos are waiting for approval.
                    </p>
                @endforelse
            </div>
        </section>

        <section class="rounded-[2rem] border border-stone-200 bg-white p-8 shadow-sm">
            <p class="text-xs uppercase tracking-[0.35em] text-rose-700">Approved</p>
            <h3 class="font-editorial mt-4 text-3xl">Public gallery images</h3>

            <div class="mt-8 grid gap-6 lg:grid-cols-2 xl:grid-cols-3">
                @forelse ($approvedUploads as $upload)
                    <article class="overflow-hidden rounded-[1.75rem] border border-stone-200 bg-stone-50">
                        <img class="aspect-[4/3] w-full object-cover" src="{{ asset('storage/'.$upload->display_path) }}" alt="{{ $upload->original_filename }}">
                        <div class="space-y-4 p-5">
                            <div>
                                <h4 class="text-lg font-semibold text-stone-900">{{ $upload->original_filename }}</h4>
                                <p class="mt-2 text-sm text-stone-600">
                                    Uploaded by {{ $upload->user?->name ?: 'Unknown guest' }}
                                </p>
                            </div>

                            <form method="POST" action="{{ route('admin.gallery.unapprove', $upload) }}">
                                @csrf
                                @method('PATCH')
                                <button class="w-full rounded-full border border-stone-300 bg-white px-5 py-3 text-sm font-medium text-stone-700 hover:border-stone-400 hover:text-stone-900" type="submit">
                                    Remove from public gallery
                                </button>
                            </form>
                        </div>
                    </article>
                @empty
                    <p class="rounded-[1.75rem] border border-dashed border-stone-300 bg-stone-50 px-5 py-8 text-sm text-stone-500 lg:col-span-2 xl:col-span-3">
                        No approved gallery images yet.
                    </p>
                @endforelse
            </div>
        </section>
    </section>
@endsection
