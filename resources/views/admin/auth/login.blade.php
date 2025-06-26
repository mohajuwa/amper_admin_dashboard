<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بوابة المدير - تسجيل الدخول</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
     <link rel="stylesheet" href="{{ url('resources/css/app.css') }}">

</head>
<body>
    <div class="bg-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="login-container">
        <div class="login-header">
            <div class="logo">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h1 class="login-title">بوابة المدير</h1>
            <p class="login-subtitle">مرحباً بك في لوحة التحكم الإدارية</p>
        </div>

        <form action="{{ route('admin.authenticate') }}" method="POST" id="loginForm">
            @csrf
            
            <!-- Success Messages -->
            @if(session('success'))
            <div class="success-message">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            <!-- Info Messages -->
            @if(session('info'))
            <div class="info-message">
                <i class="fas fa-info-circle"></i>
                <span>{{ session('info') }}</span>
            </div>
            @endif

            <!-- Error Messages -->
            @if(session('error'))
            <div class="error-message">
                <i class="fas fa-exclamation-triangle"></i>
                <span>{{ session('error') }}</span>
            </div>
            @endif

            @if($errors->any())
            <div class="error-message">
                <i class="fas fa-exclamation-triangle"></i>
                <div>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="form-group">
                <label for="email" class="form-label">البريد الإلكتروني</label>
                <div class="input-container">
                    <input type="email" name="email" id="email" class="form-input" value="{{ old('email') }}" placeholder="admin@example.com" required style="padding-left: 1.2rem;">
                    <i class="fas fa-envelope input-icon"></i>
                </div>
                @error('email')
                <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">كلمة المرور</label>
                <div class="input-container">
                    <input type="password" name="password" id="password" class="form-input" placeholder="••••••••" required>
                    <i class="fas fa-lock input-icon"></i>
                    <i class="fas fa-eye password-toggle" id="togglePassword" title="إظهار/إخفاء كلمة المرور"></i>
                </div>
                @error('password')
                <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="remember-forgot">
                <div class="checkbox-group">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember">تذكرني</label>
                </div>
                <a href="{{ route('admin.forgot.password') }}" class="forgot-link">نسيت كلمة المرور؟</a>
            </div>

            <button type="submit" class="login-button" id="loginBtn">
                <span>تسجيل الدخول</span>
                <div class="button-spinner" id="spinner"></div>
            </button>
        </form>

        <div class="security-info">
            <h4><i class="fas fa-info-circle"></i> معلومات الأمان</h4>
            <ul>
                <li>استخدم كلمة مرور قوية تحتوي على أرقام ورموز</li>
                <li>لا تشارك بيانات تسجيل الدخول مع أي شخص</li>
                <li>تأكد من تسجيل الخروج بعد انتهاء الجلسة</li>
                <li>تغيير كلمة المرور بانتظام لضمان الأمان</li>
            </ul>
        </div>
    </div>

  <script src="{{ url('resources/js/app.js') }}"></script>

</body>
</html>