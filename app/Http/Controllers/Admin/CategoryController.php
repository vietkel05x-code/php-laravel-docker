<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::with('media')->orderBy('name')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')->get();
        return view('admin.categories.form', ['category' => new Category(), 'parentCategories' => $parentCategories]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'slug' => 'nullable|string|max:160|unique:categories,slug',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $category = Category::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Đã tạo danh mục!');
    }

    public function edit(Category $category)
    {
        $parentCategories = Category::whereNull('parent_id')->where('id', '!=', $category->id)->get();
        return view('admin.categories.form', compact('category', 'parentCategories'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'slug' => 'nullable|string|max:160|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Prevent setting itself as parent
        if ($validated['parent_id'] == $category->id) {
            return back()->withErrors(['parent_id' => 'Không thể chọn chính nó làm danh mục cha']);
        }

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Đã cập nhật danh mục!');
    }

    public function destroy(Category $category)
    {
        // Check if has courses
        if ($category->courses()->count() > 0) {
            return back()->with('error', 'Không thể xóa danh mục có khóa học!');
        }

        // Check if has children
        if ($category->children()->count() > 0) {
            return back()->with('error', 'Không thể xóa danh mục có danh mục con!');
        }

        $category->delete();

        return back()->with('success', 'Đã xóa danh mục!');
    }
}

