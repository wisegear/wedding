<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveWeddingPartyMemberRequest;
use App\Http\Requests\SaveWeddingPartyPageRequest;
use App\Models\OrderOfService;
use App\Models\WeddingPartyMember;
use App\Services\WeddingPartyPhotoProcessor;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Throwable;

class WeddingPartyController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.wedding-party.index', [
            'pageSettings' => OrderOfService::settings(),
            'members' => WeddingPartyMember::query()->orderBy('name')->orderBy('id')->get(),
            'roles' => WeddingPartyMember::ROLES,
            'editingMember' => $request->integer('edit') ? WeddingPartyMember::query()->findOrFail($request->integer('edit')) : null,
        ]);
    }

    public function savePage(SaveWeddingPartyPageRequest $request): RedirectResponse
    {
        OrderOfService::query()->updateOrCreate(['id' => 1], $request->validated());

        return redirect()->route('admin.wedding-party.index')->with('status', 'Wedding party title and introduction updated.');
    }

    public function store(SaveWeddingPartyMemberRequest $request, WeddingPartyPhotoProcessor $photos): RedirectResponse
    {
        return $this->save($request, new WeddingPartyMember, $photos);
    }

    public function update(SaveWeddingPartyMemberRequest $request, WeddingPartyMember $weddingPartyMember, WeddingPartyPhotoProcessor $photos): RedirectResponse
    {
        return $this->save($request, $weddingPartyMember, $photos);
    }

    public function confirmDelete(WeddingPartyMember $weddingPartyMember): View
    {
        return view('admin.wedding-party.delete', ['member' => $weddingPartyMember]);
    }

    public function destroy(WeddingPartyMember $weddingPartyMember): RedirectResponse
    {
        $path = $weddingPartyMember->photo_path;
        $weddingPartyMember->delete();

        if ($path) {
            Storage::disk('public')->delete($path);
        }

        return redirect()->route('admin.wedding-party.index')->with('status', 'Wedding party member deleted.');
    }

    private function save(SaveWeddingPartyMemberRequest $request, WeddingPartyMember $member, WeddingPartyPhotoProcessor $photos): RedirectResponse
    {
        $oldPath = $member->photo_path;
        $newPath = $request->hasFile('photo') ? $photos->store($request->file('photo')) : null;

        try {
            $member->fill($request->safe()->only(['name', 'role', 'description']));
            $member->parent_side = $request->validated('parent_side');
            $member->photo_path = $newPath ?? ($request->boolean('remove_photo') ? null : $oldPath);
            $member->save();
        } catch (Throwable $exception) {
            if ($newPath) {
                Storage::disk('public')->delete($newPath);
            }

            throw $exception;
        }

        if ($oldPath && $oldPath !== $member->photo_path) {
            Storage::disk('public')->delete($oldPath);
        }

        return redirect()->route('admin.wedding-party.index')->with('status', 'Wedding party member saved.');
    }
}
