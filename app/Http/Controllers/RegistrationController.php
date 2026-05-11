<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Masterclass;
use App\Models\Registration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function create(Masterclass $masterclass): RedirectResponse|View
    {
        if (! Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Для записи на мастер-класс необходимо авторизоваться');
        }

        $masterclass->load('category', 'instructor');

        $alreadyRegistered = Registration::where('user_id', Auth::id())
            ->whereHas('masterclass', function ($query) use ($masterclass) {
                $query->where('date', $masterclass->date)
                    ->where('time', $masterclass->time);
            })
            ->exists();

        if ($alreadyRegistered) {
            return redirect()->route('category.show', $masterclass->category_id)
                ->with('error', 'Вы уже записаны на этот мастер-класс');
        }

        if (! $masterclass->hasAvailableSpots()) {
            return redirect()->route('category.show', $masterclass->category_id)
                ->with('error', 'Нет свободных мест на этот мастер-класс');
        }

        return view('registration.confirm', compact('masterclass'));
    }

    public function store(Request $request, Masterclass $masterclass): RedirectResponse
    {
        if (! Auth::check()) {
            abort(403);
        }

        $alreadyRegistered = Registration::where('user_id', Auth::id())
            ->whereHas('masterclass', function ($query) use ($masterclass) {
                $query->where('date', $masterclass->date)
                    ->where('time', $masterclass->time);
            })
            ->exists();

        if ($alreadyRegistered) {
            return redirect()->route('category.show', $masterclass->category_id)
                ->with('error', 'Вы уже записаны на этот мастер-класс');
        }

        if (! $masterclass->hasAvailableSpots()) {
            return redirect()->route('category.show', $masterclass->category_id)
                ->with('error', 'Нет свободных мест на этот мастер-класс');
        }

        Registration::create([
            'user_id' => Auth::id(),
            'masterclass_id' => $masterclass->id,
        ]);

        return redirect()->route('category.show', $masterclass->category_id)
            ->with('success', 'Вы успешно записались на мастер-класс');
    }
}
