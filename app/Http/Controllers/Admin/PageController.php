<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactUsModel;
use App\Models\HomeSettingModel;
use App\Models\NotificationModel;
use App\Models\PageModel;
use App\Models\SMTPModel;
use App\Models\SystemSettingModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{

    public function contactUs()
    {
        $data['header_title'] = 'اتصل بنا';
        $data['getRecord'] = ContactUsModel::getRecord();

        return view('admin.contactus.list', $data);
    }

    public function notification()
    {
        $data['header_title'] = 'الإشعارات';
        $data['getRecord'] = NotificationModel::getRecord();

        return view('admin.notification.list', $data);
    }

// في PageController.php - استبدل الدالة markAsRead

public function markAsRead(Request $request, $id)
{
    try {
        if (!$id) {
            return response()->json([
                'success' => false,
                'message' => 'معرف الإشعار مطلوب'
            ]);
        }

        // استخدام النموذج لتحديث الإشعار
        $result = NotificationModel::where('notification_id', $id)
            ->update(['notification_read' => 1]);
        
        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'تم تحديد الإشعار كمقروء بنجاح'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'فشل في تحديث الإشعار أو الإشعار مقروء بالفعل'
            ]);
        }
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'حدث خطأ: ' . $e->getMessage()
        ]);
    }
}

// إضافة دالة لتحديد جميع الإشعارات كمقروءة
public function markAllAsRead(Request $request)
{
    try {
        $result = NotificationModel::where('notification_read', 0)
            ->update(['notification_read' => 1]);
        
        return response()->json([
            'success' => true,
            'message' => 'تم تحديد جميع الإشعارات كمقروءة بنجاح',
            'updated_count' => $result
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'حدث خطأ: ' . $e->getMessage()
        ]);
    }
}

    public function deleteNotification($id)
    {
        try {
            NotificationModel::where('notification_id', '=', $id)->delete();
            return redirect()->back()->with('success', 'تم حذف الإشعار بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف الإشعار');
        }
    }

    public function contactUsDelete($id)
    {
        ContactUsModel::where('id', '=', $id)->delete();
        return redirect()->back()->with('success', 'تم حذف السجل بنجاح');
    }

    public function list()
    {
        $data['header_title'] = 'الصفحات';
        $data['getRecord'] = PageModel::getRecord();

        return view('admin.page.list', $data);
    }

    public function edit($id)
    {
        $data['getRecord'] = PageModel::getSingle($id);
        $data['header_title'] = 'تحرير الصفحة';
        return view('admin.page.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $page = PageModel::getSingle($id);

        $page->name = trim($request->name);
        $page->slug = trim($request->slug);
        $page->title = trim($request->title);
        $page->description = trim($request->description);
        $page->meta_title = trim($request->meta_title);
        $page->meta_description = trim($request->meta_description);
        $page->meta_keywords = trim($request->meta_keywords);

        $page->save();
        if (!empty($request->file('image'))) {

            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $randomStr = $page->id . Str::random(20);
            $fileName = strtolower($randomStr) . '.' . $ext;
            $file->move('upload/page/', $fileName);
            $page->image_name = trim($fileName);

            $page->save();
        }

        return redirect()->back()->with('success', "تم تحديث الصفحة بنجاح");
    }

    public function systemSettings()
    {
        $data['header_title'] = 'إعدادات النظام';
        $data['getRecord'] = SystemSettingModel::getSingle();

        return view('admin.setting.system_settings', $data);
    }

    public function updateSystemSettings(Request $request)
    {
        $systemSetting = SystemSettingModel::getSingle();
        $systemSetting->website_name = trim($request->website_name);
        $systemSetting->address = trim($request->address);
        $systemSetting->footer_description = trim($request->footer_description);
        $systemSetting->phone = trim($request->phone);
        $systemSetting->phone_two = trim($request->phone_two);
        $systemSetting->submit_email = trim($request->submit_email);
        $systemSetting->email = trim($request->email);
        $systemSetting->email_two = trim($request->email_two);
        $systemSetting->working_hours = trim($request->working_hours);
        $systemSetting->facebook_link = trim($request->facebook_link);
        $systemSetting->twitter_link = trim($request->twitter_link);
        $systemSetting->instagram_link = trim($request->instagram_link);
        $systemSetting->youtube_link = trim($request->youtube_link);
        $systemSetting->paintrest_link = trim($request->paintrest_link);

        $systemSetting->save();

        if (!empty($request->file('logo'))) {
            $file = $request->file('logo');
            $ext = $file->getClientOriginalExtension();
            $randomStr = $systemSetting->id . Str::random(20);
            $fileName = strtolower($randomStr) . '.' . $ext;
            $file->move('upload/setting/', $fileName);
            $systemSetting->logo = trim($fileName);
            $systemSetting->save();
        }

        if (!empty($request->file('fevicon'))) {
            $file = $request->file('fevicon');
            $ext = $file->getClientOriginalExtension();
            $randomStr = $systemSetting->id . Str::random(20);
            $fileName = strtolower($randomStr) . '.' . $ext;
            $file->move('upload/setting/', $fileName);
            $systemSetting->fevicon = trim($fileName);
            $systemSetting->save();
        }

        if (!empty($request->file('footer_payment_icon'))) {
            $file = $request->file('footer_payment_icon');
            $ext = $file->getClientOriginalExtension();
            $randomStr = $systemSetting->id . Str::random(20);
            $fileName = strtolower($randomStr) . '.' . $ext;
            $file->move('upload/setting/', $fileName);
            $systemSetting->footer_payment_icon = trim($fileName);
            $systemSetting->save();
        }

        return redirect()->back()->with('success', "تم تحديث الإعدادات بنجاح");
    }

    public function homeSettings()
    {
        $data['header_title'] = 'إعدادات الصفحة الرئيسية';
        $data['getRecord'] = HomeSettingModel::getSingle();

        return view('admin.setting.home_settings', $data);
    }

    public function updateHomeSettings(Request $request)
    {
        $homeSetting = HomeSettingModel::getSingle();

        $homeSetting->trendy_product_title = trim($request->trendy_product_title);
        $homeSetting->shop_category_title = trim($request->shop_category_title);
        $homeSetting->recent_arrival_title = trim($request->recent_arrival_title);
        $homeSetting->blog_title = trim($request->blog_title);
        $homeSetting->payment_delivery_title = trim($request->payment_delivery_title);
        $homeSetting->payment_delivery_description = trim($request->payment_delivery_description);
        $homeSetting->refund_title = trim($request->refund_title);
        $homeSetting->refund_description = trim($request->refund_description);
        $homeSetting->support_title = trim($request->support_title);
        $homeSetting->support_description = trim($request->support_description);
        $homeSetting->signup_title = trim($request->signup_title);
        $homeSetting->signup_description = trim($request->signup_description);
        $homeSetting->save();

        if (!empty($request->file('payment_delivery_image'))) {
            $file = $request->file('payment_delivery_image');
            $ext = $file->getClientOriginalExtension();
            $randomStr = $homeSetting->id . Str::random(20);
            $fileName = strtolower($randomStr) . '.' . $ext;
            $file->move('upload/setting/', $fileName);
            $homeSetting->payment_delivery_image = trim($fileName);
            $homeSetting->save();
        }

        if (!empty($request->file('refund_image'))) {
            $file = $request->file('refund_image');
            $ext = $file->getClientOriginalExtension();
            $randomStr = $homeSetting->id . Str::random(20);
            $fileName = strtolower($randomStr) . '.' . $ext;
            $file->move('upload/setting/', $fileName);
            $homeSetting->refund_image = trim($fileName);
            $homeSetting->save();
        }

        if (!empty($request->file('support_image'))) {
            $file = $request->file('support_image');
            $ext = $file->getClientOriginalExtension();
            $randomStr = $homeSetting->id . Str::random(20);
            $fileName = strtolower($randomStr) . '.' . $ext;
            $file->move('upload/setting/', $fileName);
            $homeSetting->support_image = trim($fileName);
            $homeSetting->save();
        }

        if (!empty($request->file('signup_image'))) {
            $file = $request->file('signup_image');
            $ext = $file->getClientOriginalExtension();
            $randomStr = $homeSetting->id . Str::random(20);
            $fileName = strtolower($randomStr) . '.' . $ext;
            $file->move('upload/setting/', $fileName);
            $homeSetting->signup_image = trim($fileName);
            $homeSetting->save();
        }

        return redirect()->back()->with('success', "تم تحديث إعدادات الصفحة الرئيسية بنجاح");
    }

    public function smtpSettings()
    {
        $data['header_title'] = 'إعدادات SMTP';
        $data['getRecord'] = SMTPModel::getSingle();

        return view('admin.setting.smtp_settings', $data);
    }

    public function updateSmtpSettings(Request $request)
    {
        $SMTPSetting = SMTPModel::getSingle();
        $SMTPSetting->name = trim($request->name);
        $SMTPSetting->mail_mailer = trim($request->mail_mailer);
        $SMTPSetting->mail_host = trim($request->mail_host);
        $SMTPSetting->mail_port = trim($request->mail_port);
        $SMTPSetting->mail_username = trim($request->mail_username);
        $SMTPSetting->mail_password = trim($request->mail_password);
        $SMTPSetting->maill_enqryption = trim($request->maill_enqryption);
        $SMTPSetting->mail_from_address = trim($request->mail_from_address);

        $SMTPSetting->save();

        return redirect()->back()->with('success', "تم تحديث إعدادات SMTP بنجاح");
    }
}