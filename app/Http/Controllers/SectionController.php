<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SectionController extends Controller
{
    public function index(Request $request): View
    {
        $sections = Section::withCount('products')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->string('search')->toString().'%');
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('sections.index', compact('sections'));
    }

    public function create(): View
    {
        return view('sections.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:sections,name'],
            'description' => ['nullable', 'string'],
        ]);

        Section::create($validated);

        return redirect()->route('sections.index')->with('success', 'Sección registrada correctamente.');
    }

    public function edit(Section $section): View
    {
        return view('sections.edit', compact('section'));
    }

    public function update(Request $request, Section $section): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('sections', 'name')->ignore($section->id)],
            'description' => ['nullable', 'string'],
        ]);

        $section->update($validated);

        return redirect()->route('sections.index')->with('success', 'Sección actualizada correctamente.');
    }

    public function destroy(Section $section): RedirectResponse
    {
        if ($section->products()->exists()) {
            return redirect()->route('sections.index')
                ->with('error', 'No se puede eliminar esta sección porque tiene productos asociados.');
        }

        $section->delete();

        return redirect()->route('sections.index')->with('success', 'Sección eliminada correctamente.');
    }
}
