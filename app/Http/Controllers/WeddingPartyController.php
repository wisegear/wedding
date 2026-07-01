<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class WeddingPartyController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.wedding-party', [
            'groups' => $this->weddingPartyGroups(),
        ]);
    }

    /**
     * @return array<int, array{role: string, accent: string, description: string, members: array<int, string>}>
     */
    private function weddingPartyGroups(): array
    {
        return [
            [
                'role' => 'Bridesmaids',
                'accent' => 'text-taupe',
                'description' => 'The bride\'s closest people helping with the morning, the aisle, and every small moment in between.',
                'members' => ['To be confirmed'],
            ],
            [
                'role' => 'Best Man',
                'accent' => 'text-sage',
                'description' => 'Standing beside the groom, keeping everyone steady, and hopefully keeping the speech mostly kind.',
                'members' => ['To be confirmed'],
            ],
            [
                'role' => 'Groomsmen',
                'accent' => 'text-sage',
                'description' => 'Helping guests feel welcome, keeping the day moving, and making sure the groom gets where he needs to be.',
                'members' => ['To be confirmed'],
            ],
            [
                'role' => 'Parents',
                'accent' => 'text-taupe',
                'description' => 'The people who have shaped the story long before the wedding day arrives.',
                'members' => ['To be confirmed'],
            ],
            [
                'role' => 'Flower Girls & Page Boys',
                'accent' => 'text-sage',
                'description' => 'The youngest members of the party, bringing a little extra joy to the ceremony.',
                'members' => ['To be confirmed'],
            ],
        ];
    }
}
