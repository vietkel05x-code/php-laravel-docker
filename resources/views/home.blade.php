@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')
@push('styles')
<style>
    .courses-row{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:16px}
    .course-tile{display:block;background:#fff;border:1px solid var(--color-border,#e5e7eb);border-radius:10px;overflow:hidden;transition:transform .2s,box-shadow .2s;color:inherit;text-decoration:none}
    .course-tile:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(0,0,0,.06)}
    .course-tile__thumb{aspect-ratio:16/9;background:#f0f0f0}
    .course-tile__thumb img{width:100%;height:100%;object-fit:cover;display:block}
    .course-tile__body{padding:12px 14px}
    .course-tile__title{font-weight:600;line-height:1.3;height:2.6em;overflow:hidden;margin-bottom:6px}
    .course-tile__meta{font-size:13px;color:#6b7280;display:flex;gap:6px;align-items:center;margin-bottom:8px}
    .course-tile__price{display:flex;gap:8px;align-items:baseline}
    .course-tile__price .current{font-weight:700}
    .course-tile__price .old{text-decoration:line-through;color:#9ca3af;font-size:13px}
    .course-tile__price .free{color:var(--color-success,#10b981);font-weight:700}
</style>
@endpush
<!-- HERO SECTION -->
<section class="hero">
    <div class="hero-content">
        <h2>Bắt đầu hành trình học tập với chi phí<span> tiết kiệm!</span></h2>
        <p>Nếu bạn mới bắt đầu với E-Learning, tin vui cho bạn đây! Trong thời gian có hạn, các khóa học chỉ từ <strong>279,000đ</strong> dành cho học viên mới. Đăng ký ngay hôm nay!</p>
        <div class="hero-cta">
            <a href="{{ route('courses.index') }}" class="cta-primary">Khám phá khóa học</a>
            <a href="{{ route('register') }}" class="cta-secondary">Đăng ký miễn phí</a>
        </div>
    </div>
    <div class="hero-stats">
        <div class="stat-item">
            <div class="stat-number" data-count="{{ \App\Models\Course::where('status', 'published')->count() }}">0</div>
            <div class="stat-label">Khóa học</div>
        </div>
        <div class="stat-item">
            <div class="stat-number" data-count="{{ \App\Models\User::where('role', 'student')->count() }}">0</div>
            <div class="stat-label">Học viên</div>
        </div>
        <div class="stat-item">
            <div class="stat-number" data-count="{{ \App\Models\Enrollment::where('status', 'active')->count() }}">0</div>
            <div class="stat-label">Lượt đăng ký</div>
        </div>
    </div>
</section>

<!-- CATEGORY SECTION -->
<section class="category-section">
  <div class="category-left">
    <h2>Trang bị các kỹ năng quan trọng cho công việc và cuộc sống.</h2>
    <p>E-Learning giúp bạn rèn luyện nhanh các kỹ năng được nhà tuyển dụng tìm kiếm và nâng tầm sự nghiệp trong bối cảnh thị trường lao động luôn biến động.</p>
  </div>

  <div class="category-right">
    <div class="category-carousel-wrapper">
        <div class="category-carousel">
            @foreach ($categories as $cat)
                <div class="category-card">
                    <img src="{{ asset('img/categories/' . ($cat->thumbnail->path ?? 'default.jpg')) }}" alt="{{ $cat->name }}">
                    <div class="category-info">
                        <h4>{{ $cat->name }}</h4>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="carousel-dots">
        <span class="dot active" data-index="0"></span>
        <span class="dot" data-index="1"></span>
        <span class="dot" data-index="2"></span>
    </div>
  </div>
</section>


<!-- ================= COURSES BY CATEGORY (TABS) ================= -->
<div class="page-wrapper">

    <section class="tab-section">
        <h2>Kỹ năng giúp bạn bứt phá trong sự nghiệp và cuộc sống.</h2>
        <p>Dù là kỹ năng thiết yếu hay kiến thức kỹ thuật chuyên sâu, E-Learning luôn hỗ trợ bạn phát triển sự nghiệp bền vững.</p>

        <div class="tab-header">
            @foreach ($topCategories as $index => $category)
                <div class="tab-link {{ $index === 0 ? 'active' : '' }}" data-id="{{ $category->id }}">
                    {{ $category->name }}
                </div>
            @endforeach
        </div>

        <div class="tab-content">
            @foreach ($topCategories as $index => $category)
                <div class="tab-panel {{ $index === 0 ? 'active' : '' }}" id="tab-{{ $category->id }}">
                    <div class="course-slider">
                        <button class="arrow prev">&#10094;</button>
                        <div class="course-grid">
                            @foreach ($coursesByCategory[$category->id] as $course)
                            <div class="course-card">
                                <img src="{{ asset('img/courses/' . ($course->thumbnail_path ?? 'default.jpg')) }}" alt="{{ $course->title }}">
                                <h4>{{ $course->title }}</h4>
                                <p class="instructor">{{ $course->instructor->name ?? 'Instructor' }}</p>
                                <div class="price">
                                    <span class="new">₫{{ number_format($course->price, 0, ',', '.') }}</span>
                                    @if($course->compare_at_price)
                                    <span class="old">₫{{ number_format($course->compare_at_price, 0, ',', '.') }}</span>
                                    @endif
                                </div>

                                <!-- Popup -->
                                <div class="course-popup">
                                    <h4>{{ $course->title }}</h4>
                                    <span class="badge">Bestseller</span>
                                    <p class="meta">
                                        {{ $course->formatted_total_duration }} · {{ ucfirst($course->level) }} · {{ ucfirst($course->language) }}
                                    </p>
                                    <p class="desc">{{ $course->short_description }}</p>
                                    <ul>
                                        <li>Understand key concepts and build solid foundations</li>
                                        <li>Hands-on lessons and real projects</li>
                                        <li>Updated to latest version in 2025</li>
                                    </ul>
                                    <button class="add-cart-btn">Add to cart</button>
                                </div>
                            </div>
                            @endforeach
                        </div> <!-- course-grid -->
                        <button class="arrow next">&#10095;</button>
                    </div> <!-- course-slider -->

                </div>
            @endforeach
        </div>
    </section>
</div>

<!-- STATS SECTION -->
<section class="stats-section">
    <div class="page-wrapper">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3>Học mọi lúc mọi nơi</h3>
                <p>Truy cập khóa học từ bất kỳ thiết bị nào, học theo tốc độ của riêng bạn</p>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-certificate"></i>
                </div>
                <h3>Chứng chỉ hoàn thành</h3>
                <p>Nhận chứng chỉ sau khi hoàn thành khóa học để nâng cao sự nghiệp</p>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Cộng đồng học tập</h3>
                <p>Kết nối với hàng nghìn học viên và giảng viên trên toàn thế giới</p>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <h3>Hỗ trợ 24/7</h3>
                <p>Đội ngũ hỗ trợ luôn sẵn sàng giúp đỡ bạn trong suốt quá trình học tập</p>
            </div>
        </div>
    </div>
</section>

<!-- TRENDING COURSES (DB only) -->
@if(isset($trendingCourses) && $trendingCourses->count())
<section class="trending-section">
    <div class="page-wrapper">
        <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:16px;gap:12px;flex-wrap:wrap">
            <h2 style="margin:0;font-size:28px;font-weight:700">Khóa học thịnh hành</h2>
            <a href="{{ route('courses.index', ['sort' => 'students']) }}" class="cta-secondary">Xem tất cả</a>
        </div>
        <div class="courses-row">
            @foreach($trendingCourses as $course)
            <a class="course-tile" href="{{ route('courses.show', $course->slug) }}">
                <div class="course-tile__thumb">
                    <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}">
                </div>
                <div class="course-tile__body">
                    <div class="course-tile__title">{{ $course->title }}</div>
                    <div class="course-tile__meta">
                        <span>{{ number_format($course->rating, 1) }} ★ ({{ $course->rating_count }})</span>
                        <span>·</span>
                        <span>{{ $course->enrolled_students }} học viên</span>
                    </div>
                    <div class="course-tile__price">
                        @if($course->price == 0)
                            <span class="free">Miễn phí</span>
                        @else
                            <span class="current">₫{{ number_format($course->price, 0, ',', '.') }}</span>
                            @if($course->compare_at_price)
                                <span class="old">₫{{ number_format($course->compare_at_price, 0, ',', '.') }}</span>
                            @endif
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- NEWEST COURSES (DB only) -->
@if(isset($newestCourses) && $newestCourses->count())
<section class="newest-section">
    <div class="page-wrapper">
        <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:16px;gap:12px;flex-wrap:wrap">
            <h2 style="margin:0;font-size:28px;font-weight:700">Khóa học mới nhất</h2>
            <a href="{{ route('courses.index', ['sort' => 'latest']) }}" class="cta-secondary">Xem tất cả</a>
        </div>
        <div class="courses-row">
            @foreach($newestCourses as $course)
            <a class="course-tile" href="{{ route('courses.show', $course->slug) }}">
                <div class="course-tile__thumb">
                    <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}">
                </div>
                <div class="course-tile__body">
                    <div class="course-tile__title">{{ $course->title }}</div>
                    <div class="course-tile__meta">
                        <span>{{ number_format($course->rating, 1) }} ★ ({{ $course->rating_count }})</span>
                        <span>·</span>
                        <span>{{ $course->formatted_total_duration }}</span>
                    </div>
                    <div class="course-tile__price">
                        @if($course->price == 0)
                            <span class="free">Miễn phí</span>
                        @else
                            <span class="current">₫{{ number_format($course->price, 0, ',', '.') }}</span>
                            @if($course->compare_at_price)
                                <span class="old">₫{{ number_format($course->compare_at_price, 0, ',', '.') }}</span>
                            @endif
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- REVIEWS FROM DB (no fake) -->
@if(isset($latestReviews) && $latestReviews->count())
<section class="testimonials-section">
    <div class="page-wrapper">
        <h2 style="text-align:center;margin-bottom:48px;font-size:32px;font-weight:700">Nhận xét mới nhất</h2>
        <div class="testimonials-grid">
            @foreach($latestReviews as $review)
            <div class="testimonial-card">
                <div class="testimonial-rating">
                    @for($i=1;$i<=5;$i++)
                        <i class="fas fa-star{{ $i <= (int)$review->rating ? '' : '-o' }}"></i>
                    @endfor
                </div>
                <p class="testimonial-text">{{ \Illuminate\Support\Str::limit($review->content, 180) }}</p>
                <div class="testimonial-author">
                    <div class="author-avatar">
                        {{ strtoupper(substr($review->user->name ?? 'U',0,1)) }}
                    </div>
                    <div class="author-info">
                        <div class="author-name">{{ $review->user->name ?? 'Người dùng' }}</div>
                        <div class="author-role">Khoá: {{ $review->course->title ?? '' }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
