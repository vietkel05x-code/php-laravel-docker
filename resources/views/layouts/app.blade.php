<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'E-Learning')</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @stack('styles')

</head>
<body>
    <header class="navbar" id="mainHeader">
        <div class="navbar-left">
            <h1 class="logo"><a href="{{ route('home') }}"><span class="logo-e">E</span><span class="highlight">LEARNING</span></a></h1>
            <nav>
                <a href="{{ route('courses.index') }}">Khám phá</a>
                @auth
                    <a href="{{ route('student.courses') }}">Khóa học của tôi</a>
                @endauth
            </nav>
        </div>

        <div class="navbar-center">
            <form action="{{ route('courses.index') }}" method="GET" class="search-form">
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" name="search" placeholder="Bạn muốn học gì hôm nay?" 
                           value="{{ request('search') }}" class="search-input">
                    <button type="submit" class="search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>

        <div class="navbar-right">
            {{-- Cart icon temporarily hidden --}}
            {{-- <a href="{{ route('cart.index') }}" class="header-icon-btn cart-icon" title="Giỏ hàng">
                <i class="fas fa-shopping-cart"></i>
                @php
                    $cartCount = count(session()->get('cart', []));
                @endphp
                @if($cartCount > 0)
                    <span class="icon-badge">{{ $cartCount }}</span>
                @endif
            </a> --}}
            
            @auth
                <a href="{{ route('notifications.index') }}" class="header-icon-btn notification-icon" title="Thông báo">
                    <i class="fas fa-bell"></i>
                    @php
                        $unreadCount = Auth::user()->unreadNotificationsCount();
                    @endphp
                    @if($unreadCount > 0)
                        <span class="icon-badge notification-badge">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                    @endif
                </a>
                
                <div class="user-menu">
                    <div class="avatar" id="avatarBtn">
                        @if(Auth::user()->avatar)
                            <img src="{{ Auth::user()->avatar_url }}" alt="Avatar" style="width:100%;height:100%;object-fit:cover;border-radius:50%">
                        @else
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="dropdown-menu" id="userDropdown">
                        <div class="user-info">
                            <div class="user-name">{{ Auth::user()->name }}</div>
                            <div class="user-email">{{ Auth::user()->email }}</div>
                        </div>
                        <hr>
                        <a href="{{ route('profile.show') }}"><i class="fas fa-user"></i> Thông tin cá nhân</a>
                        <a href="{{ route('student.courses') }}"><i class="fas fa-book"></i> Khóa học của tôi</a>
                        <a href="{{ route('orders.index') }}"><i class="fas fa-receipt"></i> Đơn hàng</a>
                        <a href="{{ route('notifications.index') }}">
                            <i class="fas fa-bell"></i> Thông báo
                            @if($unreadCount > 0)
                                <span class="menu-badge">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                            @endif
                        </a>
                        <hr>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Đăng xuất</button>
                        </form>
                        <hr>
                        <div class="plus-section">
                            <strong>E-Learning Plus</strong><br>
                            <small>Truy cập 10,000+ khóa học</small>
                        </div>
                    </div>
                </div>
            @else
                <a href="/login" class="login-link">Đăng nhập</a>
                <a href="/register" class="register-btn">Tham gia miễn phí</a>
            @endauth
        </div>
    </header>


    <main>
        @yield('content')
    </main>

    <footer class="main-footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Về E-Learning</h3>
                <p>Nền tảng học trực tuyến hàng đầu với hàng nghìn khóa học chất lượng cao từ các giảng viên chuyên nghiệp.</p>
                <div class="social-links">
                    <a href="#" title="Facebook"><i class="fab fa-facebook"></i></a>
                    <a href="#" title="YouTube"><i class="fab fa-youtube"></i></a>
                    <a href="#" title="LinkedIn"><i class="fab fa-linkedin"></i></a>
                    <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
            
            <div class="footer-section">
                <h3>Khóa học</h3>
                <ul>
                    <li><a href="{{ route('courses.index') }}?level=beginner">Khóa học cơ bản</a></li>
                    <li><a href="{{ route('courses.index') }}?level=intermediate">Khóa học trung bình</a></li>
                    <li><a href="{{ route('courses.index') }}?level=advanced">Khóa học nâng cao</a></li>
                    <li><a href="{{ route('courses.index') }}">Tất cả khóa học</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>Hỗ trợ</h3>
                <ul>
                    <li><a href="#">Trung tâm trợ giúp</a></li>
                    <li><a href="#">Câu hỏi thường gặp</a></li>
                    <li><a href="#">Liên hệ</a></li>
                    <li><a href="#">Chính sách bảo mật</a></li>
                    <li><a href="#">Điều khoản sử dụng</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>Thông tin</h3>
                <ul>
                    <li><a href="#">Về chúng tôi</a></li>
                    <li><a href="#">Trở thành giảng viên</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Tuyển dụng</a></li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; 2025 E-Learning. All rights reserved.</p>
            <div class="footer-links">
                <a href="#">Chính sách</a>
                <a href="#">Điều khoản</a>
                <a href="#">Bản đồ trang</a>
            </div>
        </div>
    </footer>

<script src="{{ asset('js/main.js') }}"></script>

</body>
</html>
