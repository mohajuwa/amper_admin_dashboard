<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button" title="تبديل الشريط الجانبي">
                <i class="fas fa-bars"></i>
            </a>
        </li>
  
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        @php
            use App\Models\NotificationModel;
            $unreadNotifications = NotificationModel::getUnreadNotifications();
        @endphp

        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" title="الإشعارات">
                <i class="far fa-bell"></i>
                @if($unreadNotifications->count() > 0)
                    <span class="badge navbar-badge">{{ $unreadNotifications->count() }}</span>
                @endif
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-left">
                <span class="dropdown-item dropdown-header">
                    {{ $unreadNotifications->count() }} إشعار{{ $unreadNotifications->count() != 1 ? 'ات' : '' }}
                </span>

                @if($unreadNotifications->count() > 0)
                    @foreach($unreadNotifications->take(5) as $notification)
                        <div class="dropdown-divider"></div>
                        <a href="{{ url('admin/orders/' . $notification->notification_id) }}"
                            class="dropdown-item notification-item">
                            <i class="fas fa-shopping-cart"></i>
                            <div class="media-body">
                                <h3 class="dropdown-item-title">
                                    {{ Str::limit($notification->notification_body['ar'] ?? $notification->notification_body['en'] ?? 'إشعار جديد', 50) }}
                                </h3>
                                <p class="text-sm text-muted">
                                    <i class="far fa-clock"></i>
                                    {{ $notification->notification_datetime->diffForHumans() }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                @else
                    <div class="dropdown-divider"></div>
                    <div class="dropdown-item empty-notifications">
                        <i class="fas fa-bell-slash"></i>
                        <p>لا توجد إشعارات جديدة</p>
                    </div>
                @endif

                <div class="dropdown-divider"></div>
                <a href="{{ url('admin/notification') }}" class="dropdown-item dropdown-footer">
                    عرض جميع الإشعارات
                </a>
            </div>
        </li>

        <!-- User Dropdown -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" title="قائمة المستخدم">
                <i class="fas fa-user-circle"></i>
                <span class="d-none d-md-inline user-name">{{ Auth::guard('admin')->user()->full_name }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="#" class="dropdown-item">
                    <i class="fas fa-user"></i>
                    الملف الشخصي
                </a>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-cog"></i>
                    الإعدادات
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ url('admin/logout') }}" class="dropdown-item text-danger"
                    onclick="return confirm('هل أنت متأكد من تسجيل الخروج؟')">
                    <i class="fas fa-sign-out-alt"></i>
                    تسجيل الخروج
                </a>
            </div>
        </li>

        <!-- Fullscreen -->
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button" title="ملء الشاشة">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>


@include('admin.layouts.sidebar')