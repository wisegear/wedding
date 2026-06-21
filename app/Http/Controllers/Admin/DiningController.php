<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiningOption;
use Illuminate\Contracts\View\View;

class DiningController extends Controller
{
    public function index(): View
    {
        return view('admin.dining.index', [
            'optionCount' => DiningOption::query()->count(),
        ]);
    }
}
