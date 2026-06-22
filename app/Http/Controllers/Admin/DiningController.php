<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiningOption;
use App\Models\Guest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class DiningController extends Controller
{
    public function index(): View
    {
        $editingOption = null;
        $editingOptionId = request()->integer('edit');

        if ($editingOptionId !== 0) {
            $editingOption = DiningOption::query()->find($editingOptionId);
        }

        return view('admin.dining.index', [
            'options' => DiningOption::query()
                ->orderByRaw("CASE course WHEN 'starter' THEN 1 WHEN 'main' THEN 2 WHEN 'dessert' THEN 3 ELSE 4 END")
                ->orderBy('name')
                ->get(),
            'editingOption' => $editingOption,
        ]);
    }

    public function selections(): View
    {
        $options = DiningOption::query()
            ->orderByRaw("CASE course WHEN 'starter' THEN 1 WHEN 'main' THEN 2 WHEN 'dessert' THEN 3 ELSE 4 END")
            ->orderBy('name')
            ->get();

        $countByOptionId = $options->mapWithKeys(fn (DiningOption $option) => [$option->id => 0])->all();

        $guests = Guest::query()
            ->with('group')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        foreach ($guests as $guest) {
            foreach (['starter', 'main', 'dessert'] as $course) {
                $optionId = $guest->dinner_choice[$course] ?? null;

                if ($optionId !== null && array_key_exists($optionId, $countByOptionId)) {
                    $countByOptionId[$optionId]++;
                }
            }
        }

        return view('admin.dining.selections', [
            'guests' => $guests,
            'groupedOptionCounts' => $options
                ->groupBy('course')
                ->map(fn ($courseOptions) => $courseOptions->map(function (DiningOption $option) use ($countByOptionId): array {
                    return [
                        'option' => $option,
                        'count' => $countByOptionId[$option->id] ?? 0,
                    ];
                })),
            'optionLookup' => $options->keyBy('id'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'course' => ['required', 'in:starter,main,dessert'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        DiningOption::query()->create([
            'course' => $validated['course'],
            'name' => trim($validated['name']),
            'description' => $this->nullableTrim($validated['description'] ?? null),
        ]);

        return redirect()
            ->route('admin.dining.index')
            ->with('status', 'Meal option added.');
    }

    public function destroy(DiningOption $diningOption): RedirectResponse
    {
        $diningOption->delete();

        return redirect()
            ->route('admin.dining.index')
            ->with('status', 'Meal option deleted.');
    }

    public function update(Request $request, DiningOption $diningOption): RedirectResponse
    {
        $validated = $request->validate([
            'course' => ['required', 'in:starter,main,dessert'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $diningOption->update([
            'course' => $validated['course'],
            'name' => trim($validated['name']),
            'description' => $this->nullableTrim($validated['description'] ?? null),
        ]);

        return redirect()
            ->route('admin.dining.index')
            ->with('status', 'Meal option updated.');
    }

    private function nullableTrim(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $trimmed = trim($value);

        return $trimmed === '' ? null : $trimmed;
    }
}
