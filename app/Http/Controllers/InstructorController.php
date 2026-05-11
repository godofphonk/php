<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InstructorController extends Controller
{
    public function dashboard(): View
    {
        if (! Auth::check() || ! Auth::user()->isInstructor()) {
            abort(403);
        }

        $instructor = Auth::user();
        $masterclasses = $instructor->masterclasses()
            ->with('category', 'registrations.user')
            ->orderBy('date')
            ->orderBy('time')
            ->get();

        return view('instructor.dashboard', compact('instructor', 'masterclasses'));
    }
}
