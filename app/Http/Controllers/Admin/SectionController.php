<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{

    public function store(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'position' => 'nullable|integer',
        ]);

        // Get max position if not provided
        if (!isset($validated['position'])) {
            $maxPosition = $course->sections()->max('position') ?? 0;
            $validated['position'] = $maxPosition + 1;
        }

        $course->sections()->create($validated);

        return back()->with('success', 'Đã thêm phần mới!');
    }

    public function update(Request $request, Section $section)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'position' => 'nullable|integer',
        ]);

        $section->update($validated);

        return back()->with('success', 'Đã cập nhật phần!');
    }

    public function destroy(Section $section)
    {
        $section->delete();
        return back()->with('success', 'Đã xóa phần!');
    }
}

