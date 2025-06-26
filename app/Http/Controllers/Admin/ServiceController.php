<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    private const UPLOAD_PATH = '/home/u798429560/domains/modwir.com/public_html/haytham_store/upload/categories/';
    private const STATUS_ACTIVE = 0;
    private const STATUS_INACTIVE = 1;
    private const STATUS_DELETED = 2;

    public function list()
    {
        return view('admin.service.list', [
            'header_title' => 'قائمة الخدمات',
            'getRecord' => Service::getRecord()
        ]);
    }

    public function add()
    {
        return view('admin.service.add', [
            'header_title' => 'إضافة خدمة جديدة'
        ]);
    }

    public function edit($id)
    {
        $service = Service::getSingle($id);

        if (!$service) {
            return redirect()->route('admin.service.list')
                ->with('error', 'الخدمة غير موجودة');
        }

        return view('admin.service.edit', [
            'header_title' => 'تعديل الخدمة',
            'getRecord' => $service
        ]);
    }

    public function insert(Request $request)
    {
        $validatedData = $this->validateServiceData($request);

        try {
            $service = new Service();
            $this->fillServiceData($service, $validatedData, $request);
            $service->save();

            return $this->respondSuccess($request, 'تم إنشاء الخدمة بنجاح', $service);
        } catch (\Exception $e) {
            return $this->handleError($request, $e, 'حدث خطأ أثناء إنشاء الخدمة');
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $this->validateServiceData($request);

        try {
            $service = Service::getSingle($id);

            if (!$service) {
                return $this->respondNotFound($request, 'الخدمة غير موجودة');
            }

            $this->fillServiceData($service, $validatedData, $request);
            $service->save();

            return $this->respondSuccess($request, 'تم تحديث الخدمة بنجاح', $service);
        } catch (\Exception $e) {
            return $this->handleError($request, $e, 'حدث خطأ أثناء تحديث الخدمة');
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $service = Service::getSingle($id);

            if (!$service) {
                return response()->json([
                    'success' => false,
                    'message' => 'الخدمة غير موجودة'
                ], 404);
            }

            $service->update(['status' => self::STATUS_DELETED]);

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الخدمة بنجاح',
                'redirect' => route('admin.service.list')
            ]);
        } catch (\Exception $e) {
            Log::error('Service deletion error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف الخدمة'
            ], 500);
        }
    }

    public function restore(Request $request, $id)
    {
        try {
            $service = Service::getSingle($id);

            if (!$service) {
                return $this->respondNotFound($request, 'الخدمة غير موجودة');
            }

            $service->update(['status' => self::STATUS_ACTIVE]);

            return $this->respondSuccess($request, 'تم استعادة الخدمة بنجاح', $service);
        } catch (\Exception $e) {
            return $this->handleError($request, $e, 'حدث خطأ أثناء استعادة الخدمة');
        }
    }

    public function changeStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:0,1,2']);

        try {
            $service = Service::getSingle($id);

            if (!$service) {
                return response()->json([
                    'success' => false,
                    'message' => 'الخدمة غير موجودة'
                ], 404);
            }

            $service->update(['status' => (int) $request->status]);

            $statusText = [
                self::STATUS_ACTIVE => 'نشط',
                self::STATUS_INACTIVE => 'غير نشط',
                self::STATUS_DELETED => 'محذوف'
            ];

            return response()->json([
                'success' => true,
                'message' => 'تم تغيير حالة الخدمة إلى: ' . $statusText[$request->status],
                'data' => [
                    'service_id' => $service->service_id,
                    'status' => $service->status,
                    'status_text' => $statusText[$request->status]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Service status change error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تغيير حالة الخدمة'
            ], 500);
        }
    }

    public function getStats()
    {
        try {
            $stats = [
                'total' => Service::count(),
                'active' => Service::where('status', self::STATUS_ACTIVE)->count(),
                'inactive' => Service::where('status', self::STATUS_INACTIVE)->count(),
                'deleted' => Service::where('status', self::STATUS_DELETED)->count(),
            ];

            return response()->json(['success' => true, 'data' => $stats]);
        } catch (\Exception $e) {
            Log::error('Service stats error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب الإحصائيات'
            ], 500);
        }
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete,restore',
            'ids' => 'required|array|min:1',
            'ids.*' => 'required|integer|exists:services,service_id'
        ]);

        try {
            $statusMap = [
                'activate' => self::STATUS_ACTIVE,
                'deactivate' => self::STATUS_INACTIVE,
                'delete' => self::STATUS_DELETED,
                'restore' => self::STATUS_ACTIVE
            ];

            $count = Service::whereIn('service_id', $request->ids)
                ->update(['status' => $statusMap[$request->action]]);

            $messages = [
                'activate' => "تم تفعيل {$count} خدمة بنجاح",
                'deactivate' => "تم إلغاء تفعيل {$count} خدمة بنجاح",
                'delete' => "تم حذف {$count} خدمة بنجاح",
                'restore' => "تم استعادة {$count} خدمة بنجاح"
            ];

            return response()->json([
                'success' => true,
                'message' => $messages[$request->action]
            ]);
        } catch (\Exception $e) {
            Log::error('Bulk action error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تنفيذ العملية'
            ], 500);
        }
    }

    private function validateServiceData(Request $request)
    {
        return $request->validate([
            'service_name_ar' => 'required|string|max:255',
            'service_name_en' => 'required|string|max:255',
            'status' => 'required|in:0,1,2',
            'service_image' => 'nullable|file|mimes:svg|max:2048',
        ], [
            'service_name_ar.required' => 'اسم الخدمة باللغة العربية مطلوب',
            'service_name_en.required' => 'اسم الخدمة باللغة الإنجليزية مطلوب',
            'status.required' => 'حالة الخدمة مطلوبة',
            'service_image.mimes' => 'نوع الصورة يجب أن يكون: SVG فقط',
            'service_image.max' => 'حجم الصورة يجب أن يكون أقل من 2 ميجابايت',
        ]);
    }

    private function fillServiceData($service, $validatedData, $request)
    {
        // Store service name as array instead of JSON
        $service->service_name = [
            'en' => trim($validatedData['service_name_en']),
            'ar' => trim($validatedData['service_name_ar'])

        ];

        $service->status = (int) $validatedData['status'];

        // Handle SVG image upload
        if ($request->hasFile('service_image') && $request->file('service_image')->isValid()) {
            // Delete old image if updating
            if (isset($service->service_img) && $service->service_img) {
                $this->deleteImage($service->service_img);
            }

            $service->service_img = $this->uploadImage($request->file('service_image'));
        }
    }

    private function uploadImage($file)
    {
        $fileName = time() . '_' . Str::random(10) . '.svg';

        if (!file_exists(self::UPLOAD_PATH)) {
            mkdir(self::UPLOAD_PATH, 0755, true);
        }

        $file->move(self::UPLOAD_PATH, $fileName);
        return $fileName;
    }

    private function deleteImage($fileName)
    {
        if ($fileName) {
            $imagePath = self::UPLOAD_PATH . $fileName;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
    }

    private function respondSuccess($request, $message, $service)
    {
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect' => route('admin.service.list'),
                'data' => [
                    'service_id' => $service->service_id,
                    'service_name' => $service->service_name,
                    'status' => $service->status
                ]
            ]);
        }

        return redirect()->route('admin.service.list')->with('success', $message);
    }

    private function respondNotFound($request, $message)
    {
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $message
            ], 404);
        }

        return redirect()->route('admin.service.list')->with('error', $message);
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