<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $meta_title ?? 'نسيت كلمة المرور - بوابة المدير' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        <link rel="stylesheet" href="{{ url('resources/css/app.css') }}">

</head>
<body>
    <div class="bg-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="forgot-container">
        <div class="forgot-header">
            <div class="logo">
                <i class="fas fa-key"></i>
            </div>
            <h1 class="forgot-title">نسيت كلمة المرور؟</h1>
            <p class="forgot-subtitle">لا تقلق، سنرسل لك رابط إعادة تعيين كلمة المرور</p>
        </div>

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

        <div class="instructions">
            <h4><i class="fas fa-lightbulb"></i> كيفية إعادة تعيين كلمة المرور</h4>
            <p>أدخل البريد الإلكتروني المرتبط بحسابك وستصلك رسالة تحتوي على رابط لإعادة تعيين كلمة المرور. تأكد من فحص مجلد الرسائل المهملة أيضاً.</p>
        </div>

        <form action="{{ route('admin.send.reset') }}" method="POST" id="forgotForm">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">البريد الإلكتروني</label>
                <div style="position: relative;">
                    <input type="email" name="email" id="email" class="form-input" value="{{ old('email') }}" placeholder="admin@example.com" required>
                    <i class="fas fa-envelope input-icon"></i>
                </div>
                @error('email')
                <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="reset-button" id="resetBtn">
                <span>إرسال رابط إعادة التعيين</span>
                <div class="button-spinner" id="spinner"></div>
            </button>
        </form>

        <div class="back-login">
            <a href="{{ route('admin.login') }}">
                <i class="fas fa-arrow-right"></i>
                العودة إلى تسجيل الدخول
            </a>
        </div>
    </div>

  <script src="{{ url('resources/js/app.js') }}"></script>

</body>
</html>