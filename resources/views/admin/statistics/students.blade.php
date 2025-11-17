@extends('layouts.admin')

@section('title', 'Thống kê người học')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
@endpush

@section('content')
<section class="admin-statistics-page">
  <h1 class="admin-statistics-page__title">Thống kê người học</h1>

  <!-- Summary Cards -->
  <div class="admin-summary-cards">
    <div class="admin-summary-card">
      <p class="admin-summary-card__label">Tổng học viên</p>
      <p class="admin-summary-card__value admin-summary-card__value--primary">{{ $totalStudents }}</p>
      <p class="admin-summary-card__meta">{{ $activeStudents }} đang hoạt động</p>
    </div>
    <div class="admin-summary-card">
      <p class="admin-summary-card__label">Học viên mới (30 ngày)</p>
      <p class="admin-summary-card__value admin-summary-card__value--success">{{ $newStudents }}</p>
    </div>
    <div class="admin-summary-card">
      <p class="admin-summary-card__label">Tỉ lệ ghi danh</p>
      <p class="admin-summary-card__value admin-summary-card__value--info">{{ $enrollmentRate }}%</p>
      <p class="admin-summary-card__meta">{{ $studentsWithEnrollments }} / {{ $totalStudents }} học viên</p>
    </div>
    <div class="admin-summary-card">
      <p class="admin-summary-card__label">Học viên có tiến độ</p>
      <p class="admin-summary-card__value admin-summary-card__value--warning">{{ $studentsWithProgress }}</p>
    </div>
  </div>

  <!-- Top Students by Enrollment -->
  <div class="admin-card">
    <h2 class="admin-card__title admin-mb--lg">Học viên có nhiều khóa học nhất</h2>
    @if($studentsByEnrollment->count() > 0)
      <div style="overflow-x: auto;">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Học viên</th>
              <th>Email</th>
              <th class="admin-table__cell--center">Số khóa học</th>
              <th>Ngày tham gia</th>
            </tr>
          </thead>
          <tbody>
            @foreach($studentsByEnrollment as $student)
              <tr>
                <td class="admin-table__cell--bold">{{ $student->name }}</td>
                <td>{{ $student->email }}</td>
                <td class="admin-table__cell--center admin-table__cell--primary admin-table__cell--bold">{{ $student->enrollments_count }}</td>
                <td class="admin-table__cell--secondary">{{ $student->created_at->format('d/m/Y') }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <p class="admin-card__empty">Chưa có dữ liệu</p>
    @endif
  </div>

  <!-- Completion Statistics -->
  @if(count($completionData) > 0)
    <div class="admin-card">
      <h2 class="admin-card__title admin-mb--lg">Top học viên theo tỉ lệ hoàn thành</h2>
      <div style="overflow-x: auto;">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Học viên</th>
              <th>Email</th>
              <th class="admin-table__cell--center">Số khóa học</th>
              <th class="admin-table__cell--center">Bài học đã hoàn thành</th>
              <th class="admin-table__cell--center">Tổng bài học</th>
              <th class="admin-table__cell--center">Tỉ lệ hoàn thành</th>
            </tr>
          </thead>
          <tbody>
            @foreach($completionData as $data)
              <tr>
                <td class="admin-table__cell--bold">{{ $data['student']->name }}</td>
                <td>{{ $data['student']->email }}</td>
                <td class="admin-table__cell--center">{{ $data['enrollments'] }}</td>
                <td class="admin-table__cell--center admin-table__cell--bold" style="color: var(--color-success);">{{ $data['completed_lessons'] }}</td>
                <td class="admin-table__cell--center">{{ $data['total_lessons'] }}</td>
                <td class="admin-table__cell--center">
                  <div class="admin-flex admin-flex--center-items admin-flex--center admin-flex--gap-sm">
                    <div style="flex: 1; max-width: 200px; height: 8px; background: #e9ecef; border-radius: var(--radius-sm); overflow: hidden;">
                      <div style="height: 100%; background: var(--color-success); width: {{ $data['completion_rate'] }}%;"></div>
                    </div>
                    <span class="admin-table__cell--bold" style="min-width: 50px;">{{ $data['completion_rate'] }}%</span>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @endif
</section>
@endsection
