<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VendorModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Traits\GenericListController;

class VendorController extends Controller
{
    use GenericListController;
    private const STATUS_ACTIVE = 1;
    private const STATUS_INACTIVE = 0;
    private const STATUS_DELETED = 2;

    /**
     * Display a listing of vendors.
     */
    public function list()
    {
        return view('admin.vendor.list', [
            'header_title' => 'قائمة الموردين',
            'getRecord' => VendorModel::getRecord()
        ]);
    }

    /**
     * Show the form for creating a new vendor.
     */
    public function add()
    {
        return view('admin.vendor.add', [
            'header_title' => 'إضافة مورد جديد',
        ]);
    }

    /**
     * Show the form for editing the specified vendor.
     */
    public function edit($id)
    {
        $vendor = VendorModel::getSingle($id);

        if (!$vendor) {
            return redirect()->route('admin.vendor.list')
                ->with('error', 'المورد غير موجود');
        }

        return view('admin.vendor.edit', [
            'header_title' => 'تعديل المورد',
            'getRecord' => $vendor
        ]);
    }

    /**
     * Store a newly created vendor in storage.
     */
    public function insert(Request $request)
    {
        $validatedData = $this->validateVendorData($request);

        try {
            $vendor = new VendorModel();
            $this->fillVendorData($vendor, $validatedData, $request);
            $vendor->save();

            return $this->respondSuccess($request, 'تم إنشاء المورد بنجاح', $vendor);
        } catch (\Exception $e) {
            return $this->handleError($request, $e, 'حدث خطأ أثناء إنشاء المورد');
        }
    }

    /**
     * Update the specified vendor in storage.
     */
    public function update(Request $request, $id)
    {
        // Use the vendor ID in the unique rule for the phone number
        $validatedData = $this->validateVendorData($request, $id);

        try {
            $vendor = VendorModel::getSingle($id);

            if (!$vendor) {
                return $this->respondNotFound($request, 'المورد غير موجود');
            }

            $this->fillVendorData($vendor, $validatedData, $request);
            $vendor->save();

            return $this->respondSuccess($request, 'تم تحديث المورد بنجاح', $vendor);
        } catch (\Exception $e) {
            return $this->handleError($request, $e, 'حدث خطأ أثناء تحديث المورد');
        }
    }

    /**
     * Soft delete the specified vendor.
     */
    public function delete(Request $request, $id)
    {
        try {
            $vendor = VendorModel::getSingle($id);

            if (!$vendor) {
                return response()->json([
                    'success' => false,
                    'message' => 'المورد غير موجود'
                ], 404);
            }

            $vendor->update(['status' => self::STATUS_DELETED]);

            return response()->json([
                'success' => true,
                'message' => 'تم حذف المورد بنجاح',
                'redirect' => route('admin.vendor.list')
            ]);
        } catch (\Exception $e) {
            Log::error('Vendor deletion error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف المورد'
            ], 500);
        }
    }

    /**
     * Restore a soft-deleted vendor.
     */
    public function restore(Request $request, $id)
    {
        try {
            // find() can find soft-deleted models if the model uses the SoftDeletes trait.
            // If not, you might need a different approach to find the model.
            $vendor = VendorModel::find($id);

            if (!$vendor) {
                return response()->json([
                    'success' => false,
                    'message' => 'العنصر غير موجود'
                ], 404);
            }

            $vendor->update(['status' => self::STATUS_ACTIVE]);

            return response()->json([
                'success' => true,
                'message' => 'تم استعادة العنصر بنجاح',
                'redirect' => route('admin.vendor.list')
            ]);
        } catch (\Exception $e) {
            Log::error('Vendor restoration error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء استعادة العنصر'
            ], 500);
        }
    }

    /**
     * Validate vendor data from the request.
     */
    private function validateVendorData(Request $request, $id = null)
    {
        $rules = [
            'owner_name_ar' => 'required|string|max:255',
            'owner_name_en' => 'required|string|max:255',
            'vendor_name_ar' => 'required|string|max:255',
            'vendor_name_en' => 'required|string|max:255',

            // CORRECTED RULE: Now accepts the values from your form.
            'vendor_type' => 'required|in:workshop,tow_truck',

            'phone' => 'required|string|max:20|unique:vendors,phone,' . $id . ',vendor_id',
            'address' => 'nullable|string|max:500',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',

            // CORRECTED RULE: Now allows the 'deleted' status to be set from the form.
            'status' => 'required|in:0,1,2',

            'password' => $id ? 'nullable|string|min:6|confirmed' : 'required|string|min:6|confirmed',
        ];

        return $request->validate($rules, [
            'owner_name_ar.required' => 'اسم المالك (عربي) مطلوب.',
            'owner_name_en.required' => 'اسم المالك (إنجليزي) مطلوب.',
            'vendor_name_ar.required' => 'اسم المورد (عربي) مطلوب.',
            'vendor_name_en.required' => 'اسم المورد (إنجليزي) مطلوب.',
            'vendor_type.required' => 'نوع المورد مطلوب.',
            'vendor_type.in' => 'النوع المختار غير صالح.', // Added a more specific message
            'phone.required' => 'رقم الهاتف مطلوب.',
            'phone.unique' => 'رقم الهاتف هذا مستخدم بالفعل.',
            'status.required' => 'الحالة مطلوبة.',
            'password.required' => 'كلمة المرور مطلوبة.',
            'password.min' => 'يجب أن تكون كلمة المرور 6 أحرف على الأقل.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
        ]);
    }
    /**
     * Fill the VendorModel with validated data.
     */
    private function fillVendorData(VendorModel $vendor, array $validatedData, Request $request)
    {
        // Correctly structure the JSON data for the model
        $vendor->owner_name = [
            'ar' => trim($validatedData['owner_name_ar']),
            'en' => trim($validatedData['owner_name_en']),
        ];

        $vendor->vendor_name = [
            'ar' => trim($validatedData['vendor_name_ar']),
            'en' => trim($validatedData['vendor_name_en']),
        ];

        $vendor->description = [
            'ar' => trim($validatedData['description_ar'] ?? ''),
            'en' => trim($validatedData['description_en'] ?? ''),
        ];

        $vendor->vendor_type = $validatedData['vendor_type'];
        $vendor->phone = $validatedData['phone'];
        $vendor->address = $validatedData['address'];
        $vendor->status = (int) $validatedData['status'];

        // Only update the password if a new one is provided
        if (!empty($validatedData['password'])) {
            $vendor->password = Hash::make($validatedData['password']);
        }

        // Set registration date only on creation
        if (!$vendor->exists) {
            $vendor->registered_at = now();
        }
    }

    /**
     * Respond with a success message and data.
     */
    private function respondSuccess(Request $request, $message, VendorModel $vendor)
    {
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect' => route('admin.vendor.list'),
                'data' => $vendor->fresh() // Return the updated model data
            ]);
        }

        return redirect()->route('admin.vendor.list')->with('success', $message);
    }

    /**
     * Respond with a 'not found' error.
     */
    private function respondNotFound(Request $request, $message)
    {
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $message
            ], 404);
        }

        return redirect()->route('admin.vendor.list')->with('error', $message);
    }

    /**
     * Handle exceptions and return an error response.
     */
    private function handleError(Request $request, \Exception $exception, $message)
    {
        // Log the detailed error for debugging
        Log::error($message . ': ' . $exception->getMessage());

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                // Provide a generic error message to the user
                'message' => 'حدث خطأ غير متوقع. يرجى المحاولة مرة أخرى.'
            ], 500);
        }

        return redirect()->back()
            ->withInput()
            ->with('error', $message);
    }
}