<?php
// app/Http/Controllers/Admin/CourseController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
// use Mews\Purifier\Facades\Purifier; // nếu bạn cài purifier

class CourseController extends Controller
{
    public function create()
    {
        $categories = \App\Models\Category::orderBy('name')->get();
        return view('admin.courses.form', ['course' => new Course(), 'categories' => $categories]);
    }

    public function index(Request $request)
    {
        $query = \App\Models\Course::with('category');
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        
        $courses = $query->latest()->paginate(10)->withQueryString();
        $categories = \App\Models\Category::orderBy('name')->get();
        
        return view('admin.courses.index', compact('courses', 'categories'));
    }
 

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => 'required|string|max:255|unique:courses,slug',
            'category_id'      => 'nullable|exists:categories,id',
            'price'            => 'nullable|numeric',
            'compare_at_price' => 'nullable|numeric',
            // Upload thumbnail via file input
            'thumbnail_file'   => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:4096',
            'status'           => 'required|string|in:draft,published,hidden,archived',
            'level'            => 'nullable|string',
            'language'         => 'nullable|string',
            'short_description' => 'nullable|string',
            'description'      => 'nullable|string',
            'description_html' => 'nullable|string',
        ]);

        // Nếu dùng purifier:
        // $data['description_html'] = Purifier::clean($data['description_html'] ?? '');

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail_file')) {
            $file = $request->file('thumbnail_file');
            $nameBase = Str::slug($data['title'] ?? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
            $filename = $nameBase . '-' . time() . '.' . $file->getClientOriginalExtension();
            $destination = public_path('img/courses');
            if (! File::exists($destination)) {
                File::makeDirectory($destination, 0755, true);
            }
            $file->move($destination, $filename);
            $data['thumbnail_path'] = $filename;
            unset($data['thumbnail_file']);
        }

        $course = Course::create($data);
        return redirect()->route('admin.courses.edit', $course)->with('ok', 'Đã tạo khóa học');
    }

    public function edit(Course $course)
    {
        $course->load([
            'sections' => function($query) {
                $query->orderBy('position');
            },
            'sections.lessons' => function($query) {
                $query->orderBy('position');
            }
        ]);
        $categories = \App\Models\Category::orderBy('name')->get();
        return view('admin.courses.form', compact('course', 'categories'));
    }

    public function update(Request $request, Course $course)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => 'required|string|max:255|unique:courses,slug,' . $course->id,
            'category_id'      => 'nullable|exists:categories,id',
            'price'            => 'nullable|numeric',
            'compare_at_price' => 'nullable|numeric',
            // Upload thumbnail via file input
            'thumbnail_file'   => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:4096',
            'status'           => 'required|string|in:draft,published,hidden,archived',
            'level'            => 'nullable|string',
            'language'         => 'nullable|string',
            'short_description' => 'nullable|string',
            'description'      => 'nullable|string',
            'description_html' => 'nullable|string',
        ]);

        // $data['description_html'] = Purifier::clean($data['description_html'] ?? '');

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail_file')) {
            $file = $request->file('thumbnail_file');
            $nameBase = Str::slug($data['title'] ?? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
            $filename = $nameBase . '-' . time() . '.' . $file->getClientOriginalExtension();
            $destination = public_path('img/courses');
            if (! File::exists($destination)) {
                File::makeDirectory($destination, 0755, true);
            }
            $file->move($destination, $filename);

            // Delete old thumbnail if exists
            if (! empty($course->thumbnail_path)) {
                $old = public_path('img/courses/' . $course->thumbnail_path);
                if (File::exists($old)) {
                    @File::delete($old);
                }
            }

            $data['thumbnail_path'] = $filename;
            unset($data['thumbnail_file']);
        }

        $course->update($data);
        return back()->with('ok', 'Đã lưu thay đổi');
    }
}
