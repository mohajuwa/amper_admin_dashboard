<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\SubService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubServiceController extends Controller
{
    private const STATUS_ACTIVE = 0;
    private const STATUS_INACTIVE = 1;
    private const STATUS_DELETED = 2;

    public function list()
    {
        return view('admin.sub_service.list', [
            'header_title' => 'الخدمات الفرعية',
            'getRecord' => SubService::getRecord()
        ]);
    }

    public function add()
    {
        return view('admin.sub_service.add', [
            'header_title' => 'إضافة خدمة فرعية',
            'getServices' => Service::getAllServices()
        ]);
    }

    public function edit($id)
    {
        $subService = SubService::getSingle($id);

        if (!$subService) {
            return redirect()->route('admin.sub_service.list')
                ->with('error', 'الخدمة الفرعية غير موجودة');
        }

        return view('admin.sub_service.edit', [
            'header_title' => 'تعديل الخدمة الفرعية',
            'getRecord' => $subService,
            'getServices' => Service::getRecord()
        ]);
    }

    public function insert(Request $request)
    {
        $validatedData = $this->validateSubServiceData($request);

        try {
            $subService = new SubService();
            $this->fillSubServiceData($subService, $validatedData, $request);
            $subService->save();

            return $this->respondSuccess($request, 'تم إنشاء الخدمة الفرعية بنجاح', $subService);
        } catch (\Exception $e) {
            return $this->handleError($request, $e, 'حدث خطأ أثناء إنشاء الخدمة الفرعية');
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $this->validateSubServiceData($request);

        try {
            $subService = SubService::getSingle($id);

            if (!$subService) {
                return $this->respondNotFound($request, 'الخدمة الفرعية غير موجودة');
            }

            $this->fillSubServiceData($subService, $validatedData, $request);
            $subService->save();

            return $this->respondSuccess($request, 'تم تحديث الخدمة الفرعية بنجاح', $subService);
        } catch (\Exception $e) {
            return $this->handleError($request, $e, 'حدث خطأ أثناء تحديث الخدمة الفرعية');
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $subService = SubService::getSingle($id);

            if (!$subService) {
                return response()->json([
                    'success' => false,
                    'message' => 'الخدمة الفرعية غير موجودة'
                ], 404);
            }

            $subService->update(['status' => self::STATUS_DELETED]);

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الخدمة الفرعية بنجاح',
                'redirect' => route('admin.sub_service.list')
            ]);
        } catch (\Exception $e) {
            Log::error('SubService deletion error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف الخدمة الفرعية'
            ], 500);
        }
    }

    public function restore(Request $request, $id)
    {
        try {
            $subService = SubService::getSingle($id);

            if (!$subService) {
                return $this->respondNotFound($request, 'الخدمة الفرعية غير موجودة');
            }

            $subService->update(['status' => self::STATUS_ACTIVE]);

            return $this->respondSuccess($request, 'تم استعادة الخدمة الفرعية بنجاح', $subService);
        } catch (\Exception $e) {
            return $this->handleError($request, $e, 'حدث خطأ أثناء استعادة الخدمة الفرعية');
        }
    }

    public function changeStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:0,1,2']);

        try {
            $subService = SubService::getSingle($id);

            if (!$subService) {
                return response()->json([
                    'success' => false,
                    'message' => 'الخدمة الفرعية غير موجودة'
                ], 404);
            }

            $subService->update(['status' => (int) $request->status]);

            $statusText = [
                self::STATUS_ACTIVE => 'نشط',
                self::STATUS_INACTIVE => 'غير نشط',
                self::STATUS_DELETED => 'محذوف'
            ];

            return response()->json([
                'success' => true,
                'message' => 'تم تغيير حالة الخدمة الفرعية إلى: ' . $statusText[$request->status],
                'data' => [
                    'sub_service_id' => $subService->sub_service_id,
                    'status' => $subService->status,
                    'status_text' => $statusText[$request->status]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('SubService status change error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تغيير حالة الخدمة الفرعية'
            ], 500);
        }
    }

    public function getStats()
    {
        try {
            $stats = [
                'total' => SubService::count(),
                'active' => SubService::where('status', self::STATUS_ACTIVE)->count(),
                'inactive' => SubService::where('status', self::STATUS_INACTIVE)->count(),
                'deleted' => SubService::where('status', self::STATUS_DELETED)->count(),
            ];

            return response()->json(['success' => true, 'data' => $stats]);
        } catch (\Exception $e) {
            Log::error('SubService stats error: ' . $e->getMessage());
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
            'ids.*' => 'required|integer|exists:sub_services,sub_service_id'
        ]);

        try {
            $statusMap = [
                'activate' => self::STATUS_ACTIVE,
                'deactivate' => self::STATUS_INACTIVE,
                'delete' => self::STATUS_DELETED,
                'restore' => self::STATUS_ACTIVE
            ];

            $count = SubService::whereIn('sub_service_id', $request->ids)
                ->update(['status' => $statusMap[$request->action]]);

            $messages = [
                'activate' => "تم تفعيل {$count} خدمة فرعية بنجاح",
                'deactivate' => "تم إلغاء تفعيل {$count} خدمة فرعية بنجاح",
                'delete' => "تم حذف {$count} خدمة فرعية بنجاح",
                'restore' => "تم استعادة {$count} خدمة فرعية بنجاح"
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

    public function getSubServices(Request $request)
    {
        $serviceId = $request->get('service_id');

        \Log::info('getSubServices called with service_id: ' . $serviceId);

        if (!$serviceId) {
            return response()->json(['sub_services' => []]);
        }

        try {
            // Use raw SQL to get exact data without any Laravel processing
            $subServices = \DB::select("
            SELECT sub_service_id, name 
            FROM sub_services 
            WHERE service_id = ? AND status = 0
        ", [$serviceId]);

            \Log::info('Found sub-services count: ' . count($subServices));

            $processedSubServices = [];

            foreach ($subServices as $subService) {
                \Log::info('Raw name: ' . $subService->name);

                // Try to decode the JSON
                $nameData = json_decode($subService->name, true);

                if (json_last_error() === JSON_ERROR_NONE && is_array($nameData)) {
                    \Log::info('Successfully decoded: ' . json_encode($nameData));
                    $processedSubServices[] = [
                        'sub_service_id' => $subService->sub_service_id,
                        'name' => $nameData
                    ];
                } else {
                    \Log::info('JSON decode failed: ' . json_last_error_msg());
                    $processedSubServices[] = [
                        'sub_service_id' => $subService->sub_service_id,
                        'name' => $subService->name
                    ];
                }
            }

            \Log::info('Final result: ' . json_encode($processedSubServices));

            return response()->json([
                'success' => true,
                'sub_services' => $processedSubServices
            ]);

        } catch (\Exception $e) {
            \Log::error('Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch sub-services'
            ], 500);
        }
    }

    private function validateSubServiceData(Request $request)
    {
        return $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'service_id' => 'required|exists:services,service_id',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:0,1,2'
        ], [
            'name_ar.required' => 'اسم الخدمة الفرعية باللغة العربية مطلوب',
            'name_en.required' => 'اسم الخدمة الفرعية باللغة الإنجليزية مطلوب',
            'service_id.required' => 'الخدمة الرئيسية مطلوبة',
            'service_id.exists' => 'الخدمة الرئيسية غير موجودة',
            'price.required' => 'السعر مطلوب',
            'price.numeric' => 'السعر يجب أن يكون رقم',
            'price.min' => 'السعر يجب أن يكون أكبر من أو يساوي صفر',
            'status.required' => 'حالة الخدمة الفرعية مطلوبة',
        ]);
    }

    private function fillSubServiceData($subService, $validatedData, $request)
    {
        // Store name as array instead of JSON
        $subService->name = [
            'en' => trim($validatedData['name_en']),
            'ar' => trim($validatedData['name_ar'])

        ];

        $subService->service_id = (int) $validatedData['service_id'];
        $subService->price = (float) $validatedData['price'];
        $subService->status = (int) $validatedData['status'];
    }

    private function respondSuccess($request, $message, $subService)
    {
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect' => route('admin.sub_service.list'),
                'data' => [
                    'sub_service_id' => $subService->sub_service_id,
                    'name' => $subService->name,
                    'service_id' => $subService->service_id,
                    'price' => $subService->price,
                    'status' => $subService->status
                ]
            ]);
        }

        return redirect()->route('admin.sub_service.list')->with('success', $message);
    }

    private function respondNotFound($request, $message)
    {
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $message
            ], 404);
        }

        return redirect()->route('admin.sub_service.list')->with('error', $message);
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