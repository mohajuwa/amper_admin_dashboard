@section('style')
@endsection

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    @php
        use App\Models\Admin;
        use App\Models\User;
        use App\Models\OrderModel;
        use App\Models\ContactUsModel;
        use App\Models\NotificationModel;

        $systemSetting = App\Models\SystemSettingModel::getSingle();
        $currentSegment = Request::segment(2);
        $currentAction = Request::segment(3);
        $routeName = Route::currentRouteName();

        $adminCount = Admin::count();
        $customerCount = User::count();
        $pendingOrdersCount = OrderModel::where('order_status', '0')->count();
        $unreadContactsCount = ContactUsModel::where('is_read', 0)->count();
        $unreadNotificationsCount = NotificationModel::where('notification_read', 0)->count();
    @endphp

    <!-- Brand Logo -->
    <div class="brand-link text-center">
        <img src="{{ $systemSetting->getFevIcon() }}" alt="شعار الموقع" class="brand-image img-circle elevation-3"
            style="width: 50px; height: 50px; object-fit: contain; border-radius: 50%;">
        <span class="brand-text font-weight-bold text-white"
            style="font-size: 1.2rem; letter-spacing: 1px;">{{ $systemSetting->website_name }}</span>
    </div>

    <div class="sidebar">
        <!-- Sidebar Search -->
        <div class="form-inline sidebar-search mb-3" style="border-radius: 25px; overflow: hidden;">
            <div class="input-group w-100">
                <input class="form-control form-control-sidebar bg-dark border-0" type="search"
                    placeholder="{{ __('البحث في القائمة...') }}" aria-label="Search"
                    style="border-top-left-radius: 25px; border-bottom-left-radius: 25px; padding-left: 15px;">
                <div class="input-group-append">
                    <button class="btn btn-sidebar bg-primary border-0"
                        style="border-top-right-radius: 25px; border-bottom-right-radius: 25px;">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-flat nav-child-indent">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link {{ Str::contains($routeName, 'admin.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt text-info"></i>
                        <p class="font-weight-medium">{{ __('لوحة التحكم') }}</p>
                    </a>
                </li>

                <!-- Analytics & Reports -->
                {{-- <li class="nav-item">
                    <a href="{{ route('admin.analytics') }}"
                        class="nav-link {{ Str::contains($routeName, 'admin.analytics') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line text-success"></i>
                        <p class="font-weight-medium">{{ __('التحليلات') }}</p>
                    </a>
                </li> --}}

                <!-- إدارة المستخدمين -->
                <li class="nav-header mt-3">
                    <i class="fas fa-users text-muted mr-2"></i>
                    {{ __('إدارة المستخدمين') }}
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.admin.list') }}"
                        class="nav-link {{ Str::contains($routeName, 'admin.admin') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-shield text-warning"></i>
                        <p class="font-weight-medium">
                            {{ __('المديرين') }}
                            @if (isset($adminCount) && $adminCount > 0)
                                <span class="badge badge-info right">{{ $adminCount }}</span>
                            @endif
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.customer.list') }}"
                        class="nav-link {{ Str::contains($routeName, 'admin.customer') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users text-primary"></i>
                        <p class="font-weight-medium">
                            {{ __('العملاء') }}
                            @if (isset($customerCount) && $customerCount > 0)
                                <span class="badge badge-success right">{{ $customerCount }}</span>
                            @endif
                        </p>
                    </a>
                </li>

                <!-- التجارة الإلكترونية -->
                <li class="nav-header mt-3">
                    <i class="fas fa-shopping-cart text-muted mr-2"></i>
                    {{ __('التجارة الإلكترونية') }}
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.order.list') }}"
                        class="nav-link {{ Str::contains($routeName, 'admin.order') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-shopping-bag text-success"></i>
                        <p class="font-weight-medium">
                            {{ __('الطلبات') }}
                            @if (isset($pendingOrdersCount) && $pendingOrdersCount > 0)
                                <span class="badge badge-danger right">{{ $pendingOrdersCount }}</span>
                            @endif
                        </p>
                    </a>
                </li>

                <!-- إدارة المنتجات - FIXED STRUCTURE -->
                <li class="nav-item {{ Str::contains($routeName, ['admin.category', 'admin.sub_category', 'admin.product', 'admin.brand', 'admin.color']) ? 'menu-open' : '' }}"
                    id="products-menu">
                    <a href="#"
                        class="nav-link {{ Str::contains($routeName, ['admin.category', 'admin.sub_category', 'admin.product', 'admin.brand', 'admin.color']) ? 'active' : '' }}"
                        onclick="toggleMenu('products-menu'); return false;">
                        <i class="nav-icon fas fa-box-open text-info"></i>
                        <p class="font-weight-medium">
                            {{ __('إدارة المنتجات') }}
                            <i class="fas fa-angle-left left" id="products-arrow"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.category.list') }}"
                                class="nav-link {{ Str::contains($routeName, 'admin.category') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-cyan"></i>
                                <p>{{ __('الفئات') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.sub_category.list') }}"
                                class="nav-link {{ Str::contains($routeName, 'admin.sub_category') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-cyan"></i>
                                <p>{{ __('الفئات الفرعية') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.product.list') }}"
                                class="nav-link {{ Str::contains($routeName, 'admin.product') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-cyan"></i>
                                <p>{{ __('المنتجات') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.brand.list') }}"
                                class="nav-link {{ Str::contains($routeName, 'admin.brand') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-cyan"></i>
                                <p>{{ __('العلامات التجارية') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.color.list') }}"
                                class="nav-link {{ Str::contains($routeName, 'admin.color') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-cyan"></i>
                                <p>{{ __('الألوان') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- التسويق والعروض -->
                <li class="nav-header mt-3">
                    <i class="fas fa-bullhorn text-muted mr-2"></i>
                    {{ __('التسويق والعروض') }}
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.discount-codes.list') }}"
                        class="nav-link {{ Str::contains($routeName, 'admin.discount-codes') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tags text-warning"></i>
                        <p class="font-weight-medium">{{ __('أكواد الخصم') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.slider.list') }}"
                        class="nav-link {{ Str::contains($routeName, 'admin.slider') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-images text-purple"></i>
                        <p class="font-weight-medium">{{ __('شرائح الصفحة الرئيسية') }}</p>
                    </a>
                </li>

                <!-- إدارة المحتوى -->
                <li class="nav-header mt-3">
                    <i class="fas fa-edit text-muted mr-2"></i>
                    {{ __('إدارة المحتوى') }}
                </li>

                <!-- إدارة المدونة - FIXED STRUCTURE -->
                <li class="nav-item {{ Str::contains($routeName, ['admin.blog_category', 'admin.blog']) ? 'menu-open' : '' }}"
                    id="blog-menu">
                    <a href="#"
                        class="nav-link {{ Str::contains($routeName, ['admin.blog_category', 'admin.blog']) ? 'active' : '' }}"
                        onclick="toggleMenu('blog-menu'); return false;">
                        <i class="nav-icon fas fa-blog text-indigo"></i>
                        <p class="font-weight-medium">
                            {{ __('إدارة المدونة') }}
                            <i class="fas fa-angle-left left" id="blog-arrow"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.blog_category.list') }}"
                                class="nav-link {{ Str::contains($routeName, 'admin.blog_category') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-teal"></i>
                                <p>{{ __('فئات المدونة') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.blog.list') }}"
                                class="nav-link {{ Str::contains($routeName, 'admin.blog') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-teal"></i>
                                <p>{{ __('مقالات المدونة') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.page.list') }}"
                        class="nav-link {{ Str::contains($routeName, 'admin.page') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt text-secondary"></i>
                        <p class="font-weight-medium">{{ __('الصفحات الثابتة') }}</p>
                    </a>
                </li>

                <!-- النظام والإعدادات -->
                <li class="nav-header mt-3">
                    <i class="fas fa-cogs text-muted mr-2"></i>
                    {{ __('النظام والإعدادات') }}
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.shipping_charge.list') }}"
                        class="nav-link {{ Str::contains($routeName, 'admin.shipping_charge') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-truck text-info"></i>
                        <p class="font-weight-medium">{{ __('رسوم الشحن') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.partner.list') }}"
                        class="nav-link {{ Str::contains($routeName, 'admin.partner') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-handshake text-success"></i>
                        <p class="font-weight-medium">{{ __('شعارات الشركاء') }}</p>
                    </a>
                </li>

                <!-- الرسائل والإشعارات -->
                <li class="nav-header mt-3">
                    <i class="fas fa-bell text-muted mr-2"></i>
                    {{ __('الرسائل والإشعارات') }}
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.contact.list') }}"
                        class="nav-link {{ Str::contains($routeName, 'admin.contact') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-envelope text-primary"></i>
                        <p class="font-weight-medium">
                            {{ __('رسائل التواصل') }}
                            @if (isset($unreadContactsCount) && $unreadContactsCount > 0)
                                <span class="badge badge-warning right">{{ $unreadContactsCount }}</span>
                            @endif
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.notifications') }}"
                        class="nav-link {{ Str::contains($routeName, 'admin.notifications') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-bell text-warning"></i>
                        <p class="font-weight-medium">
                            {{ __('الإشعارات') }}
                            @if (isset($unreadNotificationsCount) && $unreadNotificationsCount > 0)
                                <span class="badge badge-danger right">{{ $unreadNotificationsCount }}</span>
                            @endif
                        </p>
                    </a>
                </li>

                <!-- الإعدادات المتقدمة - FIXED STRUCTURE -->
                <li class="nav-item {{ Str::contains($routeName, ['admin.settings']) ? 'menu-open' : '' }}"
                    id="settings-menu">
                    <a href="#"
                        class="nav-link {{ Str::contains($routeName, ['admin.settings']) ? 'active' : '' }}"
                        onclick="toggleMenu('settings-menu'); return false;">
                        <i class="nav-icon fas fa-sliders-h text-danger"></i>
                        <p class="font-weight-medium">
                            {{ __('الإعدادات المتقدمة') }}
                            <i class="fas fa-angle-left left" id="settings-arrow"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.settings.system') }}"
                                class="nav-link {{ Str::contains($routeName, 'admin.settings.system') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-orange"></i>
                                <p>{{ __('إعدادات النظام') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.settings.homepage') }}"
                                class="nav-link {{ Str::contains($routeName, 'admin.settings.homepage') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-orange"></i>
                                <p>{{ __('إعدادات الصفحة الرئيسية') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.settings.email') }}"
                                class="nav-link {{ Str::contains($routeName, 'admin.settings.email') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-orange"></i>
                                <p>{{ __('إعدادات البريد الإلكتروني') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.settings.payment') }}"
                                class="nav-link {{ Str::contains($routeName, 'admin.settings.payment') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-orange"></i>
                                <p>{{ __('إعدادات الدفع') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.settings.cache') }}"
                                class="nav-link {{ Str::contains($routeName, 'admin.settings.cache') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-orange"></i>
                                <p>{{ __('إدارة الذاكرة المؤقتة') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.settings.backup') }}"
                                class="nav-link {{ Str::contains($routeName, 'admin.settings.backup') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-orange"></i>
                                <p>{{ __('النسخ الاحتياطي') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- وضع الصيانة -->
                <li class="nav-item">
                    <a href="{{ route('admin.maintenance.index') }}"
                        class="nav-link {{ Str::contains($routeName, 'admin.maintenance') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tools text-secondary"></i>
                        <p class="font-weight-medium">{{ __('وضع الصيانة') }}</p>
                    </a>
                </li>

                <!-- تسجيل الخروج -->
                <li class="nav-item mt-4 mb-3">
                    <a href="{{ route('admin.logout') }}" class="nav-link bg-danger text-white"
                        onclick="return confirm('{{ __('هل أنت متأكد من تسجيل الخروج؟') }}')">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p class="font-weight-bold">{{ __('تسجيل الخروج') }}</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>

@section('script')
    <script type="text/javascript">
        console.log('Sidebar loaded successfully');
    </script>
@endsection
