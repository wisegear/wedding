<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class QuestionAnswerController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.q-and-a', [
            'questions' => $this->questions(),
        ]);
    }

    /**
     * @return array<int, array{question: string, answer: string, accent: string}>
     */
    private function questions(): array
    {
        return [
            [
                'question' => 'When should I RSVP by?',
                'answer' => 'Please RSVP as soon as you can once invitations arrive, so we can keep plans with the venue moving smoothly.',
                'accent' => 'text-sage',
            ],
            [
                'question' => 'Can I bring a plus one?',
                'answer' => 'Please check the names listed on your invitation or guest dashboard. If anything looks wrong, let us know and we can help.',
                'accent' => 'text-taupe',
            ],
            [
                'question' => 'What should I wear?',
                'answer' => 'Wear something you feel comfortable celebrating in. We will add any final dress-code notes here once the day plans are confirmed.',
                'accent' => 'text-sage',
            ],
            [
                'question' => 'Where is the ceremony and reception?',
                'answer' => 'Both are planned around Falside Mill. The venue page has the address, map, photos, and directions links.',
                'accent' => 'text-taupe',
            ],
            [
                'question' => 'Will food choices be collected?',
                'answer' => 'Yes. Once logged in, guests can use the dining page to tell us meal preferences and dietary requirements.',
                'accent' => 'text-sage',
            ],
            [
                'question' => 'Can guests upload photos?',
                'answer' => 'Yes. Signed-in guests can upload photos to the gallery, and approved photos will appear publicly afterwards.',
                'accent' => 'text-taupe',
            ],
        ];
    }
}
