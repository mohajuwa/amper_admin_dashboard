<?php

namespace App\Http\Controllers;

use App\Mail\ForgotPasswordMail;
use App\Mail\RegisterMail;
use App\Models\Admin;
use App\Models\NotificationModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * عرض صفحة تسجيل الدخول للمدير
     */
    public function login_admin()
    {
        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->role == 'super_admin') {
            return redirect('admin/dashboard');
        }

        return view('admin.auth.login', [
            'meta_title' => 'تسجيل الدخول - بوابة المدير'
        ]);
    }

    /**
     * معالجة تسجيل دخول المدير
     */
    public function auth_login_admin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'يرجى إدخال بريد إلكتروني صحيح',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون 6 أحرف على الأقل',
        ]);

        $remember = $request->filled('remember');

        if (Auth::guard('admin')->attempt([
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'super_admin',
            'status' => 'active'
        ], $remember)) {
            return redirect('admin/dashboard')->with('success', 'تم تسجيل الدخول بنجاح');
        } else {
            return redirect()->back()->with('error', "البريد الإلكتروني أو كلمة المرور غير صحيحة")->withInput($request->except('password'));
        }
    }

    /**
     * تسجيل خروج المدير
     */
    public function logout_admin()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login')->with('info', "تم تسجيل الخروج بنجاح");
    }

    /**
     * تسجيل مدير جديد
     */
    public function auth_register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ], [
            'name.required' => 'الاسم الكامل مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'يرجى إدخال بريد إلكتروني صحيح',
            'email.unique' => 'هذا البريد الإلكتروني مسجل مسبقاً',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
            'password.min' => 'كلمة المرور يجب أن تحتوي على 8 أحرف على الأقل مع أرقام وأحرف كبيرة وصغيرة',
        ]);

        $checkEmail = Admin::where('email', $request->email)->first();
        if (empty($checkEmail)) {
            $save = new Admin;
            $save->full_name = trim($request->name);
            $save->email = trim($request->email);
            $save->password = Hash::make($request->password);
            $save->role = 'super_admin';
            $save->status = 'active';
            $save->remember_token = Str::random(60);
            $save->save();

            // إرسال بريد التحقق
            try {
                Mail::to($save->email)->send(new RegisterMail($save));
            } catch (\Exception $e) {
                \Log::error('خطأ في إرسال البريد: ' . $e->getMessage());
            }

            // إضافة إشعار
            $user_id = $save->id;
            $url = url('admin/customers/');
            $message = "تم تسجيل مدير جديد: " . $request->name;

            NotificationModel::insertRecord($user_id, $url, $message);

            return response()->json([
                'status' => true,
                'message' => "تم إنشاء الحساب بنجاح. يرجى التحقق من بريدك الإلكتروني.",
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "هذا البريد الإلكتروني مسجل مسبقاً. يرجى اختيار بريد آخر.",
            ]);
        }
    }


    /**
     * عرض صفحة نسيان كلمة المرور للمدير
     */
    public function admin_forgot_password()
    {
        return view('admin.auth.forgot', [
            'meta_title' => 'نسيت كلمة المرور - بوابة المدير'
        ]);
    }

    /**
     * إرسال رابط  كلمة المرور
     */
    public function auth_forgot_password(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'يرجى إدخال بريد إلكتروني صحيح',
        ]);

        $admin = Admin::where('email', $request->email)->first();
        if ($admin) {
            $admin->remember_token = Str::random(60);
            $admin->save();

            try {
                Mail::to($admin->email)->send(new ForgotPasswordMail($admin));
                return redirect()->back()->with('success', 'تم إرسال رابط  كلمة المرور إلى بريدك الإلكتروني.');
            } catch (\Exception $e) {
                \Log::error('خطأ في إرسال البريد: ' . $e->getMessage());
                return redirect()->back()->with('error', 'حدث خطأ في إرسال البريد. يرجى المحاولة مرة أخرى.');
            }
        } else {
            return redirect()->back()->with('error', 'البريد الإلكتروني غير موجود في النظام.');
        }
    }

    /**
     * إرسال رابط  كلمة المرور للمدير
     */
    public function admin_send_reset(Request $request)
    {
        return $this->auth_forgot_password($request);
    }

    /**
     * عرض صفحة  كلمة المرور
     */
    public function reset($token)
    {
        $admin = Admin::where('remember_token', $token)->first();
        if ($admin) {
            return view('admin.auth.reset', [
                'user' => $admin,
                'token' => $token,
                'meta_title' => ' كلمة المرور - بوابة المدير'
            ]);
        } else {
            return redirect()->route('admin.login')->with('error', 'رابط إعادة التعيين غير صحيح أو منتهي الصلاحية.');
        }
    }

    /**
     * عرض صفحة  كلمة المرور للمدير
     */
    public function admin_reset($token)
    {
        return $this->reset($token);
    }

    /**
     * معالجة  كلمة المرور
     */
    public function auth_reset($token, Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ], [
            'password.required' => 'كلمة المرور مطلوبة',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
            'password.min' => 'كلمة المرور يجب أن تحتوي على 8 أحرف على الأقل مع أرقام وأحرف كبيرة وصغيرة',
        ]);

        $admin = Admin::where('remember_token', $token)->first();
        if ($admin) {
            $admin->password = Hash::make($request->password);
            $admin->remember_token = Str::random(60);
            $admin->email_verified_at = now();
            $admin->save();

            return redirect()->route('admin.login')->with('success', 'تم  كلمة المرور بنجاح.');
        } else {
            return redirect()->route('admin.login')->with('error', 'رابط إعادة التعيين غير صحيح.');
        }
    }

    /**
     * معالجة إعادة تعيين كلمة المرور للمدير
     */
    public function admin_auth_reset($token, Request $request)
    {
        return $this->auth_reset($token, $request);
    }

    /**
     * تفعيل البريد الإلكتروني
     */
    public function activate_email($id)
    {
        $id = base64_decode($id);
        $admin = Admin::find($id); // استخدام find بدلاً من getSingle
        
        if ($admin) {
            $admin->email_verified_at = now();
            $admin->save();

            return redirect()->route('admin.login')->with('success', 'تم تفعيل البريد الإلكتروني بنجاح.');
        } else {
            return redirect()->route('admin.login')->with('error', 'رابط التفعيل غير صحيح.');
        }
    }
}