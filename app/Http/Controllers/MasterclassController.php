<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Masterclass;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MasterclassController extends Controller
{
    public function create(): View
    {
        if (! Auth::check() || ! Auth::user()->isInstructor()) {
            abort(403);
        }

        $categories = Category::all();
        $timeSlots = ['09:00', '11:00', '13:00', '15:00'];
        $occupiedSlots = Masterclass::where('instructor_id', Auth::id())
            ->where('date', '>=', now())
            ->get()
            ->map(fn ($mc) => $mc->date->format('Y-m-d').'_'.$mc->time)
            ->toArray();

        return view('masterclass.create', compact('categories', 'timeSlots', 'occupiedSlots'));
    }

    public function store(Request $request): RedirectResponse
    {
        if (! Auth::check() || ! Auth::user()->isInstructor()) {
            abort(403);
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date|after:today',
            'time' => 'required|in:09:00,11:00,13:00,15:00',
            'max_participants' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ], [
            'category_id.required' => 'Пожалуйста, выберите вид творчества',
            'category_id.exists' => 'Выбранный вид творчества не существует',
            'title.required' => 'Пожалуйста, введите название мастер-класса',
            'title.max' => 'Название не должно превышать 255 символов',
            'description.required' => 'Пожалуйста, введите описание мастер-класса',
            'date.required' => 'Пожалуйста, выберите дату',
            'date.date' => 'Пожалуйста, введите корректную дату',
            'date.after' => 'Дата должна быть в будущем',
            'time.required' => 'Пожалуйста, выберите время',
            'time.in' => 'Пожалуйста, выберите время из предложенных вариантов (9:00, 11:00, 13:00, 15:00)',
            'max_participants.required' => 'Пожалуйста, укажите количество участников',
            'max_participants.integer' => 'Количество участников должно быть целым числом',
            'max_participants.min' => 'Количество участников должно быть не менее 1',
            'price.required' => 'Пожалуйста, укажите стоимость',
            'price.numeric' => 'Стоимость должна быть числом',
            'price.min' => 'Стоимость не может быть отрицательной',
        ]);

        $exists = Masterclass::where('date', $validated['date'])
            ->where('time', $validated['time'])
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'time' => 'Это время уже занято. Выберите другое время.',
            ])->withInput();
        }

        Masterclass::create([
            'category_id' => $validated['category_id'],
            'instructor_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'date' => $validated['date'],
            'time' => $validated['time'],
            'max_participants' => $validated['max_participants'],
            'price' => $validated['price'],
        ]);

        return redirect()->route('instructor.dashboard')
            ->with('success', 'Мастер-класс успешно добавлен');
    }

    public function edit(Masterclass $masterclass): View
    {
        if (! Auth::check() || Auth::id() !== $masterclass->instructor_id) {
            abort(403);
        }

        $categories = Category::all();

        return view('masterclass.edit', compact('masterclass', 'categories'));
    }

    public function update(Request $request, Masterclass $masterclass): RedirectResponse
    {
        if (! Auth::check() || Auth::id() !== $masterclass->instructor_id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
        ], [
            'title.required' => 'Пожалуйста, введите название мастер-класса',
            'title.max' => 'Название не должно превышать 255 символов',
            'description.required' => 'Пожалуйста, введите описание мастер-класса',
            'price.required' => 'Пожалуйста, укажите стоимость',
            'price.numeric' => 'Стоимость должна быть числом',
            'price.min' => 'Стоимость не может быть отрицательной',
        ]);

        $masterclass->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'price' => $validated['price'],
        ]);

        return redirect()->route('instructor.dashboard')
            ->with('success', 'Мастер-класс успешно обновлен');
    }

    public function show(Masterclass $masterclass): View
    {
        $masterclass->load('category', 'instructor', 'registrations.user');

        return view('masterclass.show', compact('masterclass'));
    }
}
