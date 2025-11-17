@extends('layouts.admin')

@section('title', 'Quản lý mã giảm giá')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
@endpush

@section('content')
<section class="admin-page">
  <div class="admin-page-header">
    <h1 class="admin-page-header__title">Quản lý mã giảm giá</h1>
    <a href="{{ route('admin.coupons.create') }}" class="admin-page-header__action">
      + Thêm mã giảm giá
    </a>
  </div>

  @if(session('success'))
    <div class="alert alert--success">{{ session('success') }}</div>
  @endif

  @if($coupons->count() > 0)
    <div class="admin-table-wrapper">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Mã</th>
            <th>Loại</th>
            <th class="admin-table__cell--right">Giá trị</th>
            <th class="admin-table__cell--center">Đã dùng</th>
            <th>Thời gian</th>
            <th class="admin-table__cell--center">Trạng thái</th>
            <th class="admin-table__cell--center">Hành động</th>
          </tr>
        </thead>
        <tbody>
          @foreach($coupons as $coupon)
            @php
              $now = now();
              $isActive = (!$coupon->starts_at || $coupon->starts_at <= $now) && 
                         (!$coupon->ends_at || $coupon->ends_at >= $now) &&
                         (!$coupon->max_uses || $coupon->uses < $coupon->max_uses);
            @endphp
            <tr>
              <td class="admin-table__cell--bold admin-table__cell--monospace">{{ $coupon->code }}</td>
              <td>
                <span class="admin-badge" style="background: {{ $coupon->type == 'percent' ? '#17a2b8' : 'var(--color-success)' }}; color: white;">
                  {{ $coupon->type == 'percent' ? 'Phần trăm' : 'Cố định' }}
                </span>
              </td>
              <td class="admin-table__cell--right admin-table__cell--bold">
                @if($coupon->type == 'percent')
                  {{ $coupon->value }}%
                @else
                  ₫{{ number_format($coupon->value, 0, ',', '.') }}
                @endif
              </td>
              <td class="admin-table__cell--center">
                {{ $coupon->uses }}
                @if($coupon->max_uses)
                  / {{ $coupon->max_uses }}
                @else
                  / ∞
                @endif
              </td>
              <td class="admin-table__cell--secondary">
                @if($coupon->starts_at && $coupon->ends_at)
                  {{ $coupon->starts_at->format('d/m/Y') }} - {{ $coupon->ends_at->format('d/m/Y') }}
                @elseif($coupon->starts_at)
                  Từ {{ $coupon->starts_at->format('d/m/Y') }}
                @elseif($coupon->ends_at)
                  Đến {{ $coupon->ends_at->format('d/m/Y') }}
                @else
                  Không giới hạn
                @endif
              </td>
              <td class="admin-table__cell--center">
                <span class="admin-badge" style="background: {{ $isActive ? 'var(--color-success)' : '#6c757d' }}; color: white;">
                  {{ $isActive ? 'Hoạt động' : 'Hết hạn' }}
                </span>
              </td>
              <td class="admin-table__cell--center">
                <div class="admin-actions-container">
                  <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn--outline btn--sm">
                    Sửa
                  </a>
                  <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="admin-actions-container--inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Bạn có chắc muốn xóa mã giảm giá này?')" 
                            class="btn btn--danger btn--sm">
                      Xóa
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="admin-pagination">
      {{ $coupons->links() }}
    </div>
  @else
    <div class="admin-card card">
      <p class="admin-empty-state admin-empty-state--with-margin">
        Chưa có mã giảm giá nào
      </p>
      <div class="admin-flex admin-flex--center">
        <a href="{{ route('admin.coupons.create') }}" class="btn btn--primary">
          Tạo mã giảm giá đầu tiên
        </a>
      </div>
    </div>
  @endif
</section>
@endsection
