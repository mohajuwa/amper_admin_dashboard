<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\NotificationModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    private const STATUS_ACTIVE = 'active';
    private const STATUS_INACTIVE = 'inactive';
    private const STATUS_BANNED = 'banned';
    private const STATUS_DELETED = 1;
    private const APPROVE_YES = 1;
    private const APPROVE_NO = 0;

    public function customerList(Request $request)
    {
        // Handle notification read status
        if (!empty($request->noti_id)) {
            NotificationModel::updateReadNoti($request->noti_id);
        }

        return view('admin.custom.list', [
            'header_title' => 'العملاء',
            'getRecord' => User::getCustomer()
        ]);
    }

    public function customerAdd()
    {
        return view('admin.custom.add', [
            'header_title' => 'إضافة عميل'
        ]);
    }

    public function customerEdit($id)
    {
        $customer = User::getSingle($id);

        if (!$customer) {
            return redirect('admin/customers')
                ->with('error', 'العميل غير موجود');
        }

        return view('admin.custom.edit', [
            'header_title' => 'تعديل العميل',
            'getRecord' => $customer
        ]);
    }

    public function customerInsert(Request $request)
    {
        $validatedData = $this->validateCustomerData($request);

        try {
            $customer = new User();
            $this->fillCustomerData($customer, $validatedData);
            $customer->save();

            return $this->respondSuccess($request, 'تم إنشاء العميل بنجاح', $customer);
        } catch (\Exception $e) {
            return $this->handleError($request, $e, 'حدث خطأ أثناء إنشاء العميل');
        }
    }

    public function customerUpdate(Request $request, $id)
    {
        $validatedData = $this->validateCustomerData($request, $id);

        try {
            $customer = User::getSingle($id);

            if (!$customer) {
                return $this->respondNotFound($request, 'العميل غير موجود');
            }

            $this->fillCustomerData($customer, $validatedData);
            $customer->save();

            return $this->respondSuccess($request, 'تم تحديث بيانات العميل بنجاح', $customer);
        } catch (\Exception $e) {
            return $this->handleError($request, $e, 'حدث خطأ أثناء تحديث بيانات العميل');
        }
    }

    public function customerDelete(Request $request, $id)
    {
        try {
            $customer = User::getSingle($id);

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'العميل غير موجود'
                ], 404);
            }

            $customer->update(['is_delete' => self::STATUS_DELETED]);

            return response()->json([
                'success' => true,
                'message' => 'تم حذف العميل بنجاح'
            ]);
        } catch (\Exception $e) {
            Log::error('Customer deletion error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف العميل'
            ], 500);
        }
    }

    public function changeStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:active,inactive,banned']);

        try {
            $customer = User::getSingle($id);

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'العميل غير موجود'
                ], 404);
            }

            $customer->update(['status' => $request->status]);

            $statusText = [
                self::STATUS_ACTIVE => 'نشط',
                self::STATUS_INACTIVE => 'غير نشط',
                self::STATUS_BANNED => 'محظور'
            ];

            return response()->json([
                'success' => true,
                'message' => 'تم تغيير حالة العميل إلى: ' . $statusText[$request->status],
                'data' => [
                    'customer_id' => $customer->id,
                    'status' => $customer->status,
                    'status_text' => $statusText[$request->status]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Customer status change error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تغيير حالة العميل'
            ], 500);
        }
    }

    public function changeApproval(Request $request, $id)
    {
        $request->validate(['approve' => 'required|in:0,1']);

        try {
            $customer = User::getSingle($id);

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'العميل غير موجود'
                ], 404);
            }

            $customer->update(['approve' => (int) $request->approve]);

            $approvalText = [
                self::APPROVE_YES => 'موافق عليه',
                self::APPROVE_NO => 'غير موافق عليه'
            ];

            return response()->json([
                'success' => true,
                'message' => 'تم تغيير حالة الموافقة إلى: ' . $approvalText[$request->approve],
                'data' => [
                    'customer_id' => $customer->id,
                    'approve' => $customer->approve,
                    'approve_text' => $approvalText[$request->approve]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Customer approval change error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تغيير حالة الموافقة'
            ], 500);
        }
    }
    private function validateCustomerData(Request $request, $userId = null)
    {
        $phoneRule = 'required|string|max:20';

        if (is_null($userId)) {
            // حالة الإضافة
            $phoneRule .= '|unique:users,phone';
        } else {
            // حالة التعديل، تجاهل رقم العميل الحالي
            $phoneRule .= '|unique:users,phone,' . $userId . ',user_id';
        }

        return $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => $phoneRule,
            'verfiycode' => 'required|digits:4',
            'status' => 'required|in:active,inactive,banned',
            'approve' => 'required|in:0,1',
        ], [
            'full_name.required' => 'الاسم الكامل مطلوب',
            'full_name.max' => 'الاسم الكامل يجب أن يكون أقصر من 255 حرف',
            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.unique' => 'رقم الهاتف موجود من قبل',
            'phone.max' => 'رقم الهاتف يجب أن يكون أقصر من 20 رقم',
            'verfiycode.required' => 'كود التحقق مطلوب',
            'verfiycode.digits' => 'رمز التحقق يجب أن يكون 4 أرقام',
            'status.required' => 'حالة العميل مطلوبة',
            'status.in' => 'حالة العميل يجب أن تكون: نشط، غير نشط، أو محظور',
            'approve.required' => 'حالة الموافقة مطلوبة',
            'approve.in' => 'حالة الموافقة يجب أن تكون: موافق أو غير موافق',
        ]);
    }



    private function fillCustomerData($customer, $validatedData)
    {
        $customer->full_name = trim($validatedData['full_name']);
        $customer->phone = $validatedData['phone'];
        $customer->verfiycode = $validatedData['verfiycode'];
        $customer->status = $validatedData['status'];
        $customer->approve = (int) $validatedData['approve'];
    }

    private function respondSuccess($request, $message, $customer)
    {
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect' => route('admin.customer.list'),
                'data' => [
                    'customer_id' => $customer->id,
                    'full_name' => $customer->full_name,
                    'status' => $customer->status,
                    'approve' => $customer->approve
                ]
            ]);
        }

        return redirect('admin/customers')->with('success', $message);
    }

    private function respondNotFound($request, $message)
    {
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $message
            ], 404);
        }

        return redirect('admin/customers')->with('error', $message);
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