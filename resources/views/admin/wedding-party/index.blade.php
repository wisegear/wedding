@extends('layouts.admin')

@section('content')
    @php($isEditing = $editingMember !== null)
    <section class="space-y-6 text-stone-900">
        <div class="rounded-[2rem] border border-stone-200 bg-white p-8 shadow-sm">
            <h2 class="font-editorial text-4xl">Wedding Party</h2>
            <p class="mt-4 text-sm text-stone-600">Introduce the people sharing your day. Add as many people as you need in each role.</p>
        </div>
        <section class="rounded-[2rem] border border-stone-200 bg-white p-8 shadow-sm">
            <h3 class="font-editorial text-3xl">Page title and introduction</h3>
            <form class="mt-6 space-y-6" method="POST" action="{{ route('admin.wedding-party.page') }}">
                @csrf
                @method('PATCH')
                <div>
                    <label class="mb-2 block text-sm font-medium" for="wedding_party_title">Panel title</label>
                    <input class="w-full rounded-2xl border border-stone-300 px-4 py-3" id="wedding_party_title" name="wedding_party_title" required maxlength="255" value="{{ old('wedding_party_title', $pageSettings->wedding_party_title) }}">
                    @error('wedding_party_title') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium" for="wedding_party_intro">Introduction</label>
                    <textarea class="w-full rounded-2xl border border-stone-300 px-4 py-3" id="wedding_party_intro" name="wedding_party_intro" required maxlength="5000" rows="4">{{ old('wedding_party_intro', $pageSettings->wedding_party_intro) }}</textarea>
                    @error('wedding_party_intro') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <button class="rounded-full bg-stone-900 px-6 py-3 text-sm font-medium text-white hover:bg-stone-700" type="submit">Save page text</button>
            </form>
        </section>
        <section class="rounded-[2rem] border border-stone-200 bg-white p-8 shadow-sm">
            <h3 class="font-editorial text-3xl">{{ $isEditing ? 'Edit person' : 'Add person' }}</h3>
            <form x-data="{ role: @js(old('role', $editingMember?->role ?? '')) }" class="mt-6 grid gap-6 sm:grid-cols-2" method="POST" enctype="multipart/form-data" action="{{ $isEditing ? route('admin.wedding-party.update', $editingMember) : route('admin.wedding-party.store') }}">
                @csrf
                @if ($isEditing)
                    @method('PATCH')
                @endif
                <div>
                    <label class="mb-2 block text-sm font-medium" for="name">Name</label>
                    <input class="w-full rounded-2xl border border-stone-300 px-4 py-3" id="name" name="name" required maxlength="255" value="{{ old('name', $editingMember?->name) }}">
                    @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium" for="role">Role</label>
                    <select class="w-full rounded-2xl border border-stone-300 px-4 py-3" id="role" name="role" x-model="role" required>
                        <option value="">Choose a role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role }}" @selected(old('role', $editingMember?->role) === $role)>{{ $role }}</option>
                        @endforeach
                    </select>
                    @error('role') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div x-show="role === 'Parent'" @if(old('role', $editingMember?->role) !== 'Parent') style="display: none" @endif>
                    <label class="mb-2 block text-sm font-medium" for="parent_side">Parent of whom?</label>
                    <select class="w-full rounded-2xl border border-stone-300 px-4 py-3" id="parent_side" name="parent_side" :required="role === 'Parent'" :disabled="role !== 'Parent'">
                        <option value="">Choose bride or groom</option>
                        <option value="Bride" @selected(old('parent_side', $editingMember?->parent_side) === 'Bride')>Parent of the Bride</option>
                        <option value="Groom" @selected(old('parent_side', $editingMember?->parent_side) === 'Groom')>Parent of the Groom</option>
                    </select>
                    @error('parent_side') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-2 block text-sm font-medium" for="description">Description (optional)</label>
                    <textarea class="w-full rounded-2xl border border-stone-300 px-4 py-3" id="description" name="description" rows="4" maxlength="5000">{{ old('description', $editingMember?->description) }}</textarea>
                    @error('description') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-2 block text-sm font-medium" for="photo">Photo (optional)</label>
                    <input class="block w-full rounded-2xl border border-stone-300 p-3 text-sm" id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp">
                    <p class="mt-2 text-sm text-stone-500">JPG, PNG or WebP, up to 10 MB. Photos are cropped to a 200 × 200 square. A new upload replaces the current photo.</p>
                    @error('photo') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    @if ($editingMember?->photo_path)
                        <img class="mt-4 size-24 rounded-2xl object-cover" src="{{ Storage::disk('public')->url($editingMember->photo_path) }}" alt="Current photo of {{ $editingMember->name }}" width="200" height="200">
                        <label class="mt-3 flex items-center gap-2 text-sm"><input type="checkbox" name="remove_photo" value="1" @checked(old('remove_photo'))> Remove current photo</label>
                    @endif
                </div>
                <div class="flex items-center gap-4 sm:col-span-2">
                    <button class="rounded-full bg-stone-900 px-6 py-3 text-sm font-medium text-white hover:bg-stone-700" type="submit">{{ $isEditing ? 'Save changes' : 'Add person' }}</button>
                    @if ($isEditing)
                        <a class="text-sm text-rose-700" href="{{ route('admin.wedding-party.index') }}">Cancel edit</a>
                    @endif
                </div>
            </form>
        </section>
        <section class="rounded-[2rem] border border-stone-200 bg-white p-8 shadow-sm">
            <h3 class="font-editorial text-3xl">Saved people</h3>
            <div class="mt-6 space-y-4">
                @forelse ($members as $member)
                    <article class="flex flex-wrap items-center gap-4 rounded-2xl border border-stone-200 p-4">
                        @if ($member->photo_path)
                            <img class="size-16 rounded-xl object-cover" src="{{ Storage::disk('public')->url($member->photo_path) }}" alt="{{ $member->name }}" width="200" height="200" loading="lazy">
                        @endif
                        <div class="min-w-0 flex-1 break-words"><h4 class="font-semibold">{{ $member->name }}</h4><p class="text-sm text-stone-500">{{ $member->roleLabel() }}</p></div>
                        <a class="text-sm text-rose-700" href="{{ route('admin.wedding-party.index', ['edit' => $member->id]) }}" aria-label="Edit {{ $member->name }}">Edit</a>
                        <a class="text-sm text-red-700" href="{{ route('admin.wedding-party.confirm-delete', $member) }}" aria-label="Delete {{ $member->name }}">Delete</a>
                    </article>
                @empty
                    <p class="text-sm text-stone-500">No people added yet.</p>
                @endforelse
            </div>
        </section>
    </section>
@endsection
