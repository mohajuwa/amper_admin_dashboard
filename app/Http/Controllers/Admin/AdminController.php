<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    private const STATUS_ACTIVE = 'active';
    private const STATUS_INACTIVE = 'inactive';
    private const STATUS_DELETED = 1;
    
    private const ROLE_SUPER_ADMIN = 'super_admin';
    private const ROLE_ADMIN = 'admin';
    private const ROLE_MODERATOR = 'moderator';
    private const ROLE_EDITOR = 'editor';
    private const ROLE_VIEWER = 'viewer';

    public function list()
    {
        return view('admin.admin.list', [
            'header_title' => 'قائمة المسؤولين',
            'getRecord' => Admin::getAdmin()
        ]);
    }

    public function add()
    {
        return view('admin.admin.add', [
            'header_title' => 'إضافة مسؤول جديد'
        ]);
    }

    public function edit($id)
    {
        $admin = Admin::getSingle($id);

        if (!$admin) {
            return redirect()->route('admin.admin.list')
                ->with('error', 'المسؤول غير موجود');
        }

        return view('admin.admin.edit', [
            'header_title' => 'تعديل المسؤول',
            'getRecord' => $admin
        ]);
    }

    public function insert(Request $request)
    {
        $validatedData = $this->validateAdminData($request);

        try {
            $admin = new Admin();
            $this->fillAdminData($admin, $validatedData, $request);
            $admin->is_delete = 0;
            $admin->save();

            return $this->respondSuccess($request, 'تم إنشاء المسؤول بنجاح', $admin);
        } catch (\Exception $e) {
            return $this->handleError($request, $e, 'حدث خطأ أثناء إنشاء المسؤول');
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $this->validateAdminData($request, $id);

        try {
            $admin = Admin::getSingle($id);

            if (!$admin) {
                return $this->respondNotFound($request, 'المسؤول غير موجود');
            }

            $this->fillAdminData($admin, $validatedData, $request);
            $admin->save();

            return $this->respondSuccess($request, 'تم تحديث المسؤول بنجاح', $admin);
        } catch (\Exception $e) {
            return $this->handleError($request, $e, 'حدث خطأ أثناء تحديث المسؤول');
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $admin = Admin::getSingle($id);

            if (!$admin) {
                return response()->json([
                    'success' => false,
                    'message' => 'المسؤول غير موجود'
                ], 404);
            }

            $admin->update(['is_delete' => self::STATUS_DELETED]);

            return response()->json([
                'success' => true,
                'message' => 'تم حذف المسؤول بنجاح',
                'redirect' => route('admin.admin.list')
            ]);
        } catch (\Exception $e) {
            Log::error('Admin deletion error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف المسؤول'
            ], 500);
        }
    }

    public function restore(Request $request, $id)
    {
        try {
            $admin = Admin::getSingle($id);

            if (!$admin) {
                return $this->respondNotFound($request, 'المسؤول غير موجود');
            }

            $admin->update(['is_delete' => 0]);

            return $this->respondSuccess($request, 'تم استعادة المسؤول بنجاح', $admin);
        } catch (\Exception $e) {
            return $this->handleError($request, $e, 'حدث خطأ أثناء استعادة المسؤول');
        }
    }

    public function changeStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:active,inactive']);

        try {
            $admin = Admin::getSingle($id);

            if (!$admin) {
                return response()->json([
                    'success' => false,
                    'message' => 'المسؤول غير موجود'
                ], 404);
            }

            $admin->update(['status' => $request->status]);

            $statusText = [
                self::STATUS_ACTIVE => 'نشط',
                self::STATUS_INACTIVE => 'غير نشط'
            ];

            return response()->json([
                'success' => true,
                'message' => 'تم تغيير حالة المسؤول إلى: ' . $statusText[$request->status],
                'data' => [
                    'admin_id' => $admin->id,
                    'status' => $admin->status,
                    'status_text' => $statusText[$request->status]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Admin status change error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تغيير حالة المسؤول'
            ], 500);
        }
    }

    public function changeRole(Request $request, $id)
    {
        $request->validate(['role' => 'required|in:super_admin,admin,moderator,editor,viewer']);

        try {
            $admin = Admin::getSingle($id);

            if (!$admin) {
                return response()->json([
                    'success' => false,
                    'message' => 'المسؤول غير موجود'
                ], 404);
            }

            $admin->update(['role' => $request->role]);

            $roleText = [
                self::ROLE_SUPER_ADMIN => 'مسؤول رئيسي',
                self::ROLE_ADMIN => 'مسؤول',
                self::ROLE_MODERATOR => 'مشرف',
                self::ROLE_EDITOR => 'محرر',
                self::ROLE_VIEWER => 'مشاهد'
            ];

            return response()->json([
                'success' => true,
                'message' => 'تم تغيير دور المسؤول إلى: ' . $roleText[$request->role],
                'data' => [
                    'admin_id' => $admin->id,
                    'role' => $admin->role,
                    'role_text' => $roleText[$request->role]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Admin role change error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تغيير دور المسؤول'
            ], 500);
        }
    }

    public function getStats()
    {
        try {
            $stats = [
                'total' => Admin::where('is_delete', 0)->count(),
                'active' => Admin::where('status', self::STATUS_ACTIVE)->where('is_delete', 0)->count(),
                'inactive' => Admin::where('status', self::STATUS_INACTIVE)->where('is_delete', 0)->count(),
                'super_admin' => Admin::where('role', self::ROLE_SUPER_ADMIN)->where('is_delete', 0)->count(),
                'admin' => Admin::where('role', self::ROLE_ADMIN)->where('is_delete', 0)->count(),
                'moderator' => Admin::where('role', self::ROLE_MODERATOR)->where('is_delete', 0)->count(),
                'editor' => Admin::where('role', self::ROLE_EDITOR)->where('is_delete', 0)->count(),
                'viewer' => Admin::where('role', self::ROLE_VIEWER)->where('is_delete', 0)->count(),
            ];

            return response()->json(['success' => true, 'data' => $stats]);
        } catch (\Exception $e) {
            Log::error('Admin stats error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب الإحصائيات'
            ], 500);
        }
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete,restore,change_role',
            'ids' => 'required|array|min:1',
            'ids.*' => 'required|integer|exists:admins,id',
            'role' => 'required_if:action,change_role|in:super_admin,admin,moderator,editor,viewer'
        ]);

        try {
            $count = 0;
            $message = '';

            switch ($request->action) {
                case 'activate':
                    $count = Admin::whereIn('id', $request->ids)->update(['status' => self::STATUS_ACTIVE]);
                    $message = "تم تفعيل {$count} مسؤول بنجاح";
                    break;
                case 'deactivate':
                    $count = Admin::whereIn('id', $request->ids)->update(['status' => self::STATUS_INACTIVE]);
                    $message = "تم إلغاء تفعيل {$count} مسؤول بنجاح";
                    break;
                case 'delete':
                    $count = Admin::whereIn('id', $request->ids)->update(['is_delete' => self::STATUS_DELETED]);
                    $message = "تم حذف {$count} مسؤول بنجاح";
                    break;
                case 'restore':
                    $count = Admin::whereIn('id', $request->ids)->update(['is_delete' => 0]);
                    $message = "تم استعادة {$count} مسؤول بنجاح";
                    break;
                case 'change_role':
                    $count = Admin::whereIn('id', $request->ids)->update(['role' => $request->role]);
                    $roleText = [
                        self::ROLE_SUPER_ADMIN => 'مسؤول رئيسي',
                        self::ROLE_ADMIN => 'مسؤول',
                        self::ROLE_MODERATOR => 'مشرف',
                        self::ROLE_EDITOR => 'محرر',
                        self::ROLE_VIEWER => 'مشاهد'
                    ];
                    $message = "تم تغيير دور {$count} مسؤول إلى: " . $roleText[$request->role];
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            Log::error('Bulk action error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تنفيذ العملية'
            ], 500);
        }
    }

    private function validateAdminData(Request $request, $id = null)
    {
        $emailRule = 'required|email|unique:admins,email';
        if ($id) {
            $emailRule .= ',' . $id;
        }

        $passwordRule = $id ? 'nullable|string|min:6' : 'required|string|min:6';

        return $request->validate([
            'name' => 'required|string|max:255',
            'email' => $emailRule,
            'password' => $passwordRule,
            'status' => 'required|in:active,inactive',
            'role' => 'required|in:super_admin,admin,moderator,editor,viewer',
        ], [
            'name.required' => 'اسم المسؤول مطلوب',
            'name.max' => 'اسم المسؤول يجب أن يكون أقصر من 255 حرف',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون على الأقل 6 أحرف',
            'status.required' => 'حالة المسؤول مطلوبة',
            'status.in' => 'حالة المسؤول يجب أن تكون: نشط أو غير نشط',
            'role.required' => 'دور المسؤول مطلوب',
            'role.in' => 'دور المسؤول يجب أن يكون من الأدوار المحددة',
        ]);
    }

    private function fillAdminData($admin, $validatedData, $request)
    {
        $admin->full_name = trim($validatedData['name']);
        $admin->email = trim($validatedData['email']);
        $admin->status = $validatedData['status'];
        $admin->role = $validatedData['role'];

        // Only update password if provided
        if (!empty($validatedData['password'])) {
            $admin->password = Hash::make($validatedData['password']);
        }
    }

    private function respondSuccess($request, $message, $admin)
    {
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect' => route('admin.admin.list'),
                'data' => [
                    'admin_id' => $admin->id,
                    'full_name' => $admin->full_name,
                    'email' => $admin->email,
                    'status' => $admin->status,
                    'role' => $admin->role
                ]
            ]);
        }

        return redirect()->route('admin.admin.list')->with('success', $message);
    }

    private function respondNotFound($request, $message)
    {
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $message
            ], 404);
        }

        return redirect()->route('admin.admin.list')->with('error', $message);
    }

    private function handleError($request, $exception, $message)
    {
        Log::error($message . ': ' . $exception->getMessage());

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $message . ': ' . $exception->getMessage()
            ], 500);
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $message);
    }
}