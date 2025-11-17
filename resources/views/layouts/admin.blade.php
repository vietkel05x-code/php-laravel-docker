<!DOCTYPE html>
<html lang="vi" class="admin-html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - E-Learning</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
    @stack('styles')
</head>
<body class="admin-body">
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <div class="logo-icon">E</div>
                    <span>Admin Panel</span>
                </div>
            </div>
            <nav class="sidebar-menu">
                <div class="menu-section">
                    <div class="menu-section-title">Chính</div>
                    <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>Bảng điều khiển</span>
                    </a>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">Quản lý nội dung</div>
                    <a href="{{ route('admin.courses.index') }}" class="menu-item {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
                        <i class="fas fa-book"></i>
                        <span>Khóa học</span>
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="menu-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <i class="fas fa-folder"></i>
                        <span>Danh mục</span>
                    </a>
                    <a href="{{ route('admin.reviews.index') }}" class="menu-item {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                        <i class="fas fa-star"></i>
                        <span>Đánh giá</span>
                    </a>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">Quản lý người dùng</div>
                    <a href="{{ route('admin.users.index') }}" class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>Người dùng</span>
                    </a>
                    <a href="{{ route('admin.notifications.index') }}" class="menu-item {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                        <i class="fas fa-bell"></i>
                        <span>Thông báo</span>
                        @php
                            $adminUser = \App\Helpers\AdminHelper::user();
                            $unreadCount = $adminUser ? $adminUser->unreadNotificationsCount() : 0;
                        @endphp
                        @if($unreadCount > 0)
                            <span class="menu-badge">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                        @endif
                    </a>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">Bán hàng</div>
                    <a href="{{ route('admin.coupons.index') }}" class="menu-item {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                        <i class="fas fa-tag"></i>
                        <span>Mã giảm giá</span>
                    </a>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">Thống kê</div>
                    <a href="{{ route('admin.statistics.revenue') }}" class="menu-item {{ request()->routeIs('admin.statistics.revenue') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        <span>Doanh thu</span>
                    </a>
                    <a href="{{ route('admin.statistics.courses') }}" class="menu-item {{ request()->routeIs('admin.statistics.courses') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i>
                        <span>Khóa học</span>
                    </a>
                    <a href="{{ route('admin.statistics.students') }}" class="menu-item {{ request()->routeIs('admin.statistics.students') ? 'active' : '' }}">
                        <i class="fas fa-chart-pie"></i>
                        <span>Người học</span>
                    </a>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">Khác</div>
                    <a href="{{ route('home') }}" class="menu-item">
                        <i class="fas fa-globe"></i>
                        <span>Về trang chủ</span>
                    </a>
                    <form action="{{ route('admin.logout') }}" method="POST" class="admin-logout-form">
                        @csrf
                        <button type="submit" class="menu-item">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Đăng xuất</span>
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <!-- Header -->
            <header class="admin-header">
                <div class="header-left">
                    <button class="menu-toggle" id="menuToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="page-title">@yield('page-title', 'Bảng điều khiển')</h1>
                </div>
                <div class="header-right">
                    <div class="header-search">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Tìm kiếm...">
                    </div>
                    <div class="header-actions">
                        <a href="{{ route('notifications.index') }}" class="header-icon">
                            <i class="fas fa-bell"></i>
                            @php
                                $adminUser = \App\Helpers\AdminHelper::user();
                                $unreadCount = $adminUser ? $adminUser->unreadNotificationsCount() : 0;
                            @endphp
                            @if($unreadCount > 0)
                                <span class="badge">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.profile.show') }}" class="user-profile">
                            @php
                                $adminUser = \App\Helpers\AdminHelper::user();
                            @endphp
                            <div class="user-avatar">
                                {{ $adminUser ? strtoupper(substr($adminUser->name, 0, 1)) : 'A' }}
                            </div>
                            <div class="user-info">
                                <div class="user-name">{{ $adminUser ? $adminUser->name : 'Admin' }}</div>
                                <div class="user-role">Administrator</div>
                            </div>
                        </a>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <div class="admin-content">
                @if(session('success'))
                    <div class="admin-session-message admin-session-message--success">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="admin-session-message admin-session-message--error">
                        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="admin-session-message admin-session-message--warning">
                        <strong><i class="fas fa-exclamation-triangle"></i> Có lỗi xảy ra:</strong>
                        <ul class="admin-session-message__list">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        // Mobile menu toggle
        document.getElementById('menuToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('open');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const menuToggle = document.getElementById('menuToggle');
            
            if (window.innerWidth <= 768 && 
                sidebar.classList.contains('open') && 
                !sidebar.contains(e.target) && 
                !menuToggle.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
