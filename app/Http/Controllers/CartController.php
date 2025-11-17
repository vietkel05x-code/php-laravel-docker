<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $courses = [];
        $total = 0;

        foreach ($cart as $courseId => $item) {
            $course = Course::find($courseId);
            if ($course) {
                $courses[] = $course;
                $total += $course->price;
            }
        }

        return view('cart.index', compact('courses', 'total'));
    }

    public function add(Request $request, Course $course)
    {
        $cart = session()->get('cart', []);

        // Check if already enrolled
        if (Auth::check() && $course->isEnrolledBy(Auth::id())) {
            return redirect()->back()->with('error', 'Bạn đã đăng ký khóa học này rồi!');
        }

        // Check if already in cart
        if (isset($cart[$course->id])) {
            return redirect()->back()->with('info', 'Khóa học đã có trong giỏ hàng!');
        }

        $cart[$course->id] = [
            'course_id' => $course->id,
            'added_at' => now(),
        ];

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Đã thêm vào giỏ hàng!');
    }

    public function remove(Course $course)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$course->id])) {
            unset($cart[$course->id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Đã xóa khỏi giỏ hàng!');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Đã xóa toàn bộ giỏ hàng!');
    }

    public function count()
    {
        $cart = session()->get('cart', []);
        return response()->json(['count' => count($cart)]);
    }
}

