<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AboutContentController extends Controller
{
    public function index(): View
    {
        $contents = AboutContent::query()
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('admin.about-contents.index', compact('contents'));
    }

    public function create(): View
    {
        return view('admin.about-contents.form', [
            'content' => new AboutContent([
                'sort_order' => 0,
                'is_active' => true,
            ]),
            'formTitle' => 'Create About Content',
            'formAction' => route('admin.about-contents.store'),
            'submitLabel' => 'Create Content',
            'isEdit' => false,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateContent($request);

        $content = AboutContent::create($validated);

        return redirect()
            ->route('admin.about-contents.show', $content)
            ->with('success', 'About content created successfully.');
    }

    public function show(AboutContent $content): View
    {
        return view('admin.about-contents.show', compact('content'));
    }

    public function edit(AboutContent $content): View
    {
        return view('admin.about-contents.form', [
            'content' => $content,
            'formTitle' => 'Edit About Content',
            'formAction' => route('admin.about-contents.update', $content),
            'submitLabel' => 'Update Content',
            'isEdit' => true,
        ]);
    }

    public function update(Request $request, AboutContent $content): RedirectResponse
    {
        $validated = $this->validateContent($request, $content);

        $content->update($validated);

        return redirect()
            ->route('admin.about-contents.show', $content)
            ->with('success', 'About content updated successfully.');
    }

    public function destroy(AboutContent $content): RedirectResponse
    {
        $content->delete();

        return redirect()
            ->route('admin.about-contents.index')
            ->with('success', 'About content deleted successfully.');
    }

    protected function validateContent(Request $request, ?AboutContent $content = null): array
    {
        return $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('about_contents', 'title')->ignore($content?->id),
            ],
            'body' => ['required', 'string'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'color' => ['required', 'string', 'in:black,white,blue,red,green,yellow,pink,purple,brown,gray,silver,gold,multicolor,other'],
        ]);
    }
}
