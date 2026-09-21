<?php

namespace App\Http\Controllers;

use App\Models\OrderOfService;
use App\Models\WeddingPartyMember;
use Illuminate\Contracts\View\View;

class WeddingPartyController extends Controller
{
    public function __invoke(): View
    {
        $members = WeddingPartyMember::query()->orderBy('name')->orderBy('id')->get();
        $groups = [
            ['title' => 'Bride', 'members' => $members->where('role', 'Bride'), 'position' => 'lg:col-start-1 lg:row-start-1'],
            ['title' => 'Groom', 'members' => $members->where('role', 'Groom'), 'position' => 'lg:col-start-2 lg:row-start-1'],
            ['title' => 'Parents of the Bride', 'members' => $members->where('role', 'Parent')->where('parent_side', 'Bride'), 'position' => 'lg:col-start-1 lg:row-start-2'],
            ['title' => 'Parents of the Groom', 'members' => $members->where('role', 'Parent')->where('parent_side', 'Groom'), 'position' => 'lg:col-start-2 lg:row-start-2'],
            ['title' => 'Maid of Honour', 'members' => $members->where('role', 'Maid of Honour'), 'position' => 'lg:col-start-1 lg:row-start-3'],
            ['title' => 'Best Man', 'members' => $members->where('role', 'Best Man'), 'position' => 'lg:col-start-2 lg:row-start-3'],
            ['title' => 'Bridesmaids', 'members' => $members->where('role', 'Bridesmaids'), 'position' => 'lg:col-start-1 lg:row-start-4'],
            ['title' => 'Groomsmen', 'members' => $members->where('role', 'Groomsmen'), 'position' => 'lg:col-start-2 lg:row-start-4'],
        ];

        $otherGroups = [
            ['title' => 'Parents', 'members' => $members->where('role', 'Parent')->whereNull('parent_side')],
        ];

        $finalGroups = [
            ['title' => 'Flower Girl', 'members' => $members->where('role', 'Flower Girl'), 'position' => 'lg:col-start-1 lg:row-start-1'],
            ['title' => 'Page Boy', 'members' => $members->where('role', 'Page Boy'), 'position' => 'lg:col-start-2 lg:row-start-1'],
        ];

        return view('pages.wedding-party', [
            'pageSettings' => OrderOfService::settings(),
            'groups' => $groups,
            'otherGroups' => $otherGroups,
            'finalGroups' => $finalGroups,
            'membersByRole' => $members->groupBy('role'),
        ]);
    }
}
