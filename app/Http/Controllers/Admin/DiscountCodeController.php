<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiscountCodeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DiscountCodeController extends Controller
{
    private const STATUS_ACTIVE = 0;
    private const STATUS_INACTIVE = 1;
    private const STATUS_DELETED = 2;

    public function list()
    {
        return view('admin.discount-codes.list', [
            'header_title' => 'قائمة أكواد الخصم',
            'getRecord' => DiscountCodeModel::getRecord()
        ]);
    }

    public function add()
    {
        return view('admin.discount-codes.add', [
            'header_title' => 'إضافة كود خصم جديد'
        ]);
    }

    public function edit($id)
    {
        $discount_code = DiscountCodeModel::getSingle($id);

        if (!$discount_code) {
            return redirect()->route('admin.discount-codes.list')
                ->with('error', 'كود الخصم غير موجود');
        }

        return view('admin.discount-codes.edit', [
            'header_title' => 'تعديل كود الخصم',
            'getRecord' => $discount_code
        ]);
    }

    public function insert(Request $request)
    {
        $validatedData = $this->validateDiscountCodeData($request);

        try {
            $discount_code = new DiscountCodeModel();
            $this->fillDiscountCodeData($discount_code, $validatedData);
            $discount_code->save();

            return $this->respondSuccess($request, 'تم إنشاء كود الخصم بنجاح', $discount_code);
        } catch (\Exception $e) {
            return $this->handleError($request, $e, 'حدث خطأ أثناء إنشاء كود الخصم');
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $this->validateDiscountCodeData($request);

        try {
            $discount_code = DiscountCodeModel::getSingle($id);

            if (!$discount_code) {
                return $this->respondNotFound($request, 'كود الخصم غير موجود');
            }

            $this->fillDiscountCodeData($discount_code, $validatedData);
            $discount_code->save();

            return $this->respondSuccess($request, 'تم تحديث كود الخصم بنجاح', $discount_code);
        } catch (\Exception $e) {
            return $this->handleError($request, $e, 'حدث خطأ أثناء تحديث كود الخصم');
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $discount_code = DiscountCodeModel::getSingle($id);

            if (!$discount_code) {
                return response()->json([
                    'success' => false,
                    'message' => 'كود الخصم غير موجود'
                ], 404);
            }

            $discount_code->update(['coupon_status' => self::STATUS_DELETED]);

            return response()->json([
                'success' => true,
                'message' => 'تم حذف كود الخصم بنجاح',
                'redirect' => route('admin.discount-codes.list')
            ]);
        } catch (\Exception $e) {
            Log::error('DiscountCodeModel deletion error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف كود الخصم'
            ], 500);
        }
    }
    public function restore(Request $request, $id)
    {
        try {
            $discount_code = DiscountCodeModel::getSingle($id);

            if (!$discount_code) {
                return response()->json([
                    'success' => false,
                    'message' => 'كود الخصم غير موجود'
                ], 404);
            }

            $discount_code->update(['coupon_status' => self::STATUS_ACTIVE]);

            return response()->json([
                'success' => true,
                'message' => 'تم استعادة كود الخصم بنجاح',
                'redirect' => route('admin.discount-codes.list')
            ]);
        } catch (\Exception $e) {
            Log::error('DiscountCodeModel deletion error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء استعادة كود الخصم'
            ], 500);
        }
    }    private function validateDiscountCodeData(Request $request)
    {
        // Convert datetime-local format to MySQL datetime format
        if ($request->has('coupon_expiredate') && $request->coupon_expiredate) {
            try {
                // Handle datetime-local format (Y-m-d\TH:i)
                if (strpos($request->coupon_expiredate, 'T') !== false) {
                    $date = Carbon::createFromFormat('Y-m-d\TH:i', $request->coupon_expiredate);
                    $request->merge(['coupon_expiredate' => $date->format('Y-m-d H:i:s')]);
                }
            } catch (\Exception $e) {
                // If parsing fails, let validation handle it
            }
        }

        return $request->validate([
            'coupon_name' => 'required|string|max:255',
            'coupon_count' => 'required|integer|min:1',
            'coupon_discount' => 'required|numeric|min:0|max:100',
            'coupon_expiredate' => 'required|date|after_or_equal:today',
            'status' => 'required|in:0,1,2',
        ], [
            'coupon_name.required' => 'اسم كود الخصم مطلوب',
            'coupon_name.max' => 'اسم كود الخصم يجب أن يكون أقل من 255 حرف',
            'coupon_count.required' => 'عدد مرات الاستخدام مطلوب',
            'coupon_count.integer' => 'عدد مرات الاستخدام يجب أن يكون رقم صحيح',
            'coupon_count.min' => 'عدد مرات الاستخدام يجب أن يكون أكبر من 0',
            'coupon_discount.required' => 'نسبة الخصم مطلوبة',
            'coupon_discount.numeric' => 'نسبة الخصم يجب أن تكون رقم',
            'coupon_discount.min' => 'نسبة الخصم يجب أن تكون أكبر من أو تساوي 0',
            'coupon_discount.max' => 'نسبة الخصم يجب أن تكون أقل من أو تساوي 100',
            'coupon_expiredate.required' => 'تاريخ انتهاء الصلاحية مطلوب',
            'coupon_expiredate.date' => 'تاريخ انتهاء الصلاحية يجب أن يكون تاريخ صحيح',
            'coupon_expiredate.after_or_equal' => 'تاريخ انتهاء الصلاحية يجب أن يكون اليوم أو بعد اليوم',
            'status.required' => 'حالة كود الخصم مطلوبة',
            'status.in' => 'حالة كود الخصم غير صحيحة',
        ]);
    }

    private function fillDiscountCodeData($discount_code, $validatedData)
    {
        $discount_code->coupon_name = trim($validatedData['coupon_name']);
        $discount_code->coupon_count = (int) $validatedData['coupon_count'];
        $discount_code->coupon_discount = (float) $validatedData['coupon_discount'];
        $discount_code->coupon_expiredate = $validatedData['coupon_expiredate'];
        $discount_code->coupon_status = (int) $validatedData['status']; // Changed from coupon_status to status
    }

    private function respondSuccess($request, $message, $discount_code)
    {
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect' => route('admin.discount-codes.list'),
                'data' => [
                    'coupon_id' => $discount_code->coupon_id,
                    'coupon_name' => $discount_code->coupon_name,
                    'coupon_count' => $discount_code->coupon_count,
                    'coupon_discount' => $discount_code->coupon_discount,
                    'coupon_expiredate' => $discount_code->coupon_expiredate,
                    'status' => $discount_code->coupon_status
                ]
            ]);
        }

        return redirect()->route('admin.discount-codes.list')->with('success', $message);
    }

    private function respondNotFound($request, $message)
    {
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $message
            ], 404);
        }

        return redirect()->route('admin.discount-codes.list')->with('error', $message);
    }

    private function handleError($request, $exception, $message)
    {
        Log::error($message . ': ' . $exception->getMessage());

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $message . ' - ' . $exception->getMessage() // Add this for debugging
            ], 500);
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $message);
    }
}