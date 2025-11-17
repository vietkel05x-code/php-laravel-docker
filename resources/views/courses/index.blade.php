@extends('layouts.app')

@section('title', 'Khóa học')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/courses.css') }}">
@endpush

@section('content')
@php
  $breadcrumbItems = [
    ['label' => 'Trang chủ', 'url' => route('home')],
    ['label' => 'Khóa học']
  ];
@endphp
@include('components.breadcrumb', ['items' => $breadcrumbItems])
<section class="courses-page">
  <h1 class="courses-page__title">Khóa học</h1>

  <div class="courses-grid">
    <!-- Sidebar Filters -->
    <aside class="courses-filters">
      <form method="GET" action="{{ route('courses.index') }}" id="filterForm">
        <input type="hidden" name="search" value="{{ request('search') }}">

        <!-- Category Filter -->
        <div class="courses-filter">
          <h3 class="courses-filter__title">Danh mục</h3>
          <div class="courses-filter__options">
            <label class="courses-filter__option">
              <input type="radio" name="category" value="" {{ !request('category') ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()">
              <span>Tất cả</span>
            </label>
            @foreach($categories as $category)
              <label class="courses-filter__option">
                <input type="radio" name="category" value="{{ $category->id }}" {{ request('category') == $category->id ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()">
                <span>{{ $category->name }}</span>
              </label>
            @endforeach
          </div>
        </div>

        <!-- Level Filter -->
        <div class="courses-filter">
          <h3 class="courses-filter__title">Cấp độ</h3>
          <div class="courses-filter__options">
            <label class="courses-filter__option">
              <input type="radio" name="level" value="" {{ !request('level') ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()">
              <span>Tất cả</span>
            </label>
            <label class="courses-filter__option">
              <input type="radio" name="level" value="beginner" {{ request('level') == 'beginner' ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()">
              <span>Cơ bản</span>
            </label>
            <label class="courses-filter__option">
              <input type="radio" name="level" value="intermediate" {{ request('level') == 'intermediate' ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()">
              <span>Trung bình</span>
            </label>
            <label class="courses-filter__option">
              <input type="radio" name="level" value="advanced" {{ request('level') == 'advanced' ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()">
              <span>Nâng cao</span>
            </label>
          </div>
        </div>

        <!-- Price Filter -->
        <div class="courses-filter">
          <h3 class="courses-filter__title">Giá</h3>
          <div class="courses-filter__options">
            <label class="courses-filter__option">
              <input type="radio" name="price_range" value="" {{ !request('price_range') ? 'checked' : '' }} onchange="updatePriceFilter('')">
              <span>Tất cả</span>
            </label>
            <label class="courses-filter__option">
              <input type="radio" name="price_range" value="free" {{ request('price_range') == 'free' ? 'checked' : '' }} onchange="updatePriceFilter('free')">
              <span>Miễn phí</span>
            </label>
            <label class="courses-filter__option">
              <input type="radio" name="price_range" value="0-500000" {{ request('price_range') == '0-500000' ? 'checked' : '' }} onchange="updatePriceFilter('0-500000')">
              <span>Dưới 500,000đ</span>
            </label>
            <label class="courses-filter__option">
              <input type="radio" name="price_range" value="500000-1000000" {{ request('price_range') == '500000-1000000' ? 'checked' : '' }} onchange="updatePriceFilter('500000-1000000')">
              <span>500,000đ - 1,000,000đ</span>
            </label>
            <label class="courses-filter__option">
              <input type="radio" name="price_range" value="1000000" {{ request('price_range') == '1000000' ? 'checked' : '' }} onchange="updatePriceFilter('1000000')">
              <span>Trên 1,000,000đ</span>
            </label>
          </div>
        </div>

        <!-- Sort -->
        <div class="courses-filter">
          <h3 class="courses-filter__title">Sắp xếp</h3>
          <select name="sort" onchange="document.getElementById('filterForm').submit()" class="courses-filter__select">
            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Đánh giá cao</option>
            <option value="students" {{ request('sort') == 'students' ? 'selected' : '' }}>Nhiều học viên</option>
            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Giá thấp đến cao</option>
            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Giá cao đến thấp</option>
          </select>
        </div>
      </form>
    </aside>

    <!-- Course List -->
    <main class="courses-list">
      @if($courses->count() > 0)
        <div class="courses-grid-view">
          @foreach($courses as $course)
            <div class="course-card">
              <a href="{{ route('courses.show', $course->slug) }}">
                <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}" 
                     class="course-card__image">
              </a>
              
              <div class="course-card__content">
                <h3 class="course-card__title">
                  <a href="{{ route('courses.show', $course->slug) }}" class="course-card__title-link">
                    {{ $course->title }}
                  </a>
                </h3>
                <p class="course-card__instructor">{{ $course->instructor->name ?? 'Instructor' }}</p>
                
                <div class="course-card__rating">
                  <span class="course-card__rating-score">{{ number_format($course->rating, 1) }}</span>
                  <div class="course-card__rating-stars">
                    @for($i = 0; $i < 5; $i++)
                      <i class="fa fa-star{{ $i < floor($course->rating) ? '' : '-o' }}"></i>
                    @endfor
                  </div>
                  <span class="course-card__rating-count">({{ $course->rating_count }})</span>
                </div>

                <div class="course-card__price">
                  @if($course->price == 0)
                    <span class="course-card__price-current" style="color: var(--color-success); font-weight: bold;">
                      Miễn phí
                    </span>
                  @else
                    <span class="course-card__price-current">
                      ₫{{ number_format($course->price, 0, ',', '.') }}
                    </span>
                    @if($course->compare_at_price)
                      <span class="course-card__price-old">
                        ₫{{ number_format($course->compare_at_price, 0, ',', '.') }}
                      </span>
                    @endif
                  @endif
                </div>
              </div>
            </div>
          @endforeach
        </div>

        <div style="margin-top: var(--spacing-xl);">
          {{ $courses->links('vendor.pagination.custom') }}
        </div>
      @else
        <div class="courses-empty">
          <p class="courses-empty__message">Không tìm thấy khóa học nào</p>
          <a href="{{ route('courses.index') }}" class="btn btn--primary">
            Xem tất cả khóa học
          </a>
        </div>
      @endif
    </main>
  </div>
</section>

<script>
function updatePriceFilter(range) {
  const form = document.getElementById('filterForm');
  if (range === '') {
    form.price_min.value = '';
    form.price_max.value = '';
  } else if (range === 'free') {
    form.price_min.value = '0';
    form.price_max.value = '0';
  } else if (range === '0-500000') {
    form.price_min.value = '0';
    form.price_max.value = '500000';
  } else if (range === '500000-1000000') {
    form.price_min.value = '500000';
    form.price_max.value = '1000000';
  } else if (range === '1000000') {
    form.price_min.value = '1000000';
    form.price_max.value = '';
  }
  form.submit();
}

document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('filterForm');
  const priceMin = document.createElement('input');
  priceMin.type = 'hidden';
  priceMin.name = 'price_min';
  priceMin.value = '{{ request("price_min") }}';
  form.appendChild(priceMin);
  
  const priceMax = document.createElement('input');
  priceMax.type = 'hidden';
  priceMax.name = 'price_max';
  priceMax.value = '{{ request("price_max") }}';
  form.appendChild(priceMax);
});
</script>
@endsection
