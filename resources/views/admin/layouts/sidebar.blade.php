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
                <!-- إدارة السيارات -->
                <li class="nav-header mt-3">
                    <i class="fas fa-car text-muted mr-2"></i>
                    {{ __('إدارة السيارات') }}
                </li>

                <!-- إدارة السيارات - Car Makes & Models -->
                <li class="nav-item {{ Str::contains($routeName, ['admin.car_make', 'admin.car_model']) ? 'menu-open' : '' }}"
                    id="cars-menu">
                    <a href="javascript:void(0);"
                        class="nav-link {{ Str::contains($routeName, ['admin.car_make', 'admin.car_model']) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-car-side text-indigo"></i>
                        <p class="font-weight-medium">
                            {{ __('إدارة السيارات') }}
                            <i class="fas fa-angle-left left" id="cars-arrow"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.car_make.list') }}"
                                class="nav-link {{ Str::contains($routeName, 'admin.car_make') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-teal"></i>
                                <p>{{ __('ماركات السيارات') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.car_model.list') }}"
                                class="nav-link {{ Str::contains($routeName, 'admin.car_model') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-teal"></i>
                                <p>{{ __('موديلات السيارات') }}</p>
                            </a>
                        </li>
                    </ul>
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

                <!-- إدارة الخدمات - FIXED STRUCTURE -->
                <li class="nav-item {{ Str::contains($routeName, ['admin.service', 'admin.sub_service', 'admin.product', 'admin.brand', 'admin.color']) ? 'menu-open' : '' }}"
                    id="products-menu">
                    <a href="javascript:void(0);"
                        class="nav-link {{ Str::contains($routeName, ['admin.service', 'admin.sub_service', 'admin.product', 'admin.brand', 'admin.color']) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-box-open text-info"></i>
                        <p class="font-weight-medium">
                            {{ __('إدارة الخدمات') }}
                            <i class="fas fa-angle-left left" id="products-arrow"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.service.list') }}"
                                class="nav-link {{ Str::contains($routeName, 'admin.service') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-cyan"></i>
                                <p>{{ __('الخدمات') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.sub_service.list') }}"
                                class="nav-link {{ Str::contains($routeName, 'admin.sub_service') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-cyan"></i>
                                <p>{{ __('الخدمات الفرعية') }}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.product_by_car.list') }}"
                                class="nav-link {{ Str::contains($routeName, 'admin.product_by_car') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-cyan"></i>
                                <p>{{ __('خدمات حسب السنة   ') }}</p>
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
                    <a href="javascript:void(0);"
                        class="nav-link {{ Str::contains($routeName, ['admin.settings']) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-sliders-h text-danger"></i>
                        <p class="font-weight-medium">
                            {{ __('الإعدادات المتقدمة') }}
                            <i class="fas fa-angle-left left" id="settings-arrow"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.settings.system') }}"
                                class="nav-link {{ $routeName === 'admin.settings.system' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-orange"></i>
                                <p>{{ __('إعدادات النظام') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.settings.homepage') }}"
                                class="nav-link {{ $routeName === 'admin.settings.homepage' ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-orange"></i>
                                <p>{{ __('إعدادات النظام') }}</p>
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
                                <p>{{ __('وسائل التواصل الاجتماعي') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.settings.backup') }}"
                                class="nav-link {{ Str::contains($routeName, 'admin.settings.backup') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-orange"></i>
                                <p>{{ __('إعدادات SEO') }}</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.maintenance.index') }}"
                                class="nav-link {{ Str::contains($routeName, 'admin.maintenance') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon text-orange"></i>
                                <p>{{ __('وضع الصيانة') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- تسجيل الخروج -->
                <li class="nav-header mt-3">
                    <i class="fas fa-sign-out-alt text-muted mr-2"></i>
                    {{ __('الحساب') }}
                </li>

                {{-- <li class="nav-item">
                    <a href="{{ route('admin.profile') }}"
                        class="nav-link {{ Str::contains($routeName, 'admin.profile') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-cog text-info"></i>
                        <p class="font-weight-medium">{{ __('الملف الشخصي') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.change.password') }}"
                        class="nav-link {{ Str::contains($routeName, 'admin.change.password') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-key text-warning"></i>
                        <p class="font-weight-medium">{{ __('تغيير كلمة المرور') }}</p>
                    </a>
                </li> --}}

                <li class="nav-item">
                    <a href="{{ route('admin.logout') }}" class="nav-link"
                        onclick="return confirm('{{ __('هل أنت متأكد من تسجيل الخروج؟') }}')">
                        <i class="nav-icon fas fa-sign-out-alt text-danger"></i>
                        <p class="font-weight-medium">{{ __('تسجيل الخروج') }}</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>



@section('script')
    <script>
        // Initialize menu system when document is ready
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize menu click handlers
            if (typeof initializeMenuClickHandlers === 'function') {
                initializeMenuClickHandlers();
            }

            // Fix menu arrows
            if (typeof fixMenuArrows === 'function') {
                fixMenuArrows();
            }

            console.log('Sidebar menu initialized');
        });
    </script>
@endsection
