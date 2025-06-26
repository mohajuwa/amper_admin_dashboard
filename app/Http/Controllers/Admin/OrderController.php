<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderStatusMail;
use App\Models\NotificationModel;
use App\Models\OrderEnhViewModel;
use App\Models\OrderModel;
use App\Models\OrdersViewModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function list()
    {
        $data['header_title'] = 'قائمة الطلبات';
        $data['getRecord'] = OrderModel::getRecord();
        
        return view('admin.order.list', $data);
    }

    public function orderDetail($orderId, Request $request)
    {
        if (!empty($request->noti_id)) {
            NotificationModel::updateReadNoti($request->noti_id);
        }

        $data['header_title'] = 'تفاصيل الطلب';
        $record = OrderEnhViewModel::getSingle($orderId);

        if (!$record) {
            return redirect()->back()->with('error', 'الطلب غير موجود');
        }

        // Add debugging information if in debug mode
        if (config('app.debug')) {
            \Log::info('Order Debug Info', [
                'order_id' => $orderId,
                'raw_json_fields' => $record->getRawJsonFields(),
                'debug_json_fields' => $record->debugJsonFields()
            ]);
        }

        // No need for manual JSON decoding - the model accessors handle this
        $data['getRecord'] = $record;

        return view('admin.order.detail', $data);
    }

    public function debugOrder($orderId)
    {
        // Only allow in debug mode
        if (!config('app.debug')) {
            abort(404);
        }

        $record = OrderEnhViewModel::getSingle($orderId);
        
        if (!$record) {
            return response()->json(['error' => 'الطلب غير موجود'], 404);
        }

        // Get raw database query result
        $rawQuery = DB::table('ordersview')->where('order_id', $orderId)->first();
        
        $diagnostics = [
            'order_id' => $orderId,
            'model_table' => $record->getTable(),
            'model_attributes_count' => count($record->getAttributes()),
            'raw_db_query' => $rawQuery,
            'model_raw_fields' => $record->getRawJsonFields(),
            'model_processed_fields' => [
                'vendor_name' => $record->vendor_name,
                'make_name' => $record->make_name,
                'model_name' => $record->model_name,
                'service_names' => $record->service_names,
                'sub_service_names' => $record->sub_service_names,
            ],
            'debug_info' => $record->debugJsonFields(),
            'connection_info' => [
                'database' => DB::connection()->getDatabaseName(),
                'driver' => DB::connection()->getDriverName(),
            ]
        ];

        // Check if view exists
        $viewExists = DB::selectOne("SHOW TABLES LIKE 'ordersview'");
        $diagnostics['view_exists'] = $viewExists !== null;

        // Check specific field values directly from DB
        if ($viewExists) {
            $fieldCheck = DB::selectOne("
                SELECT 
                    vendor_name,
                    make_name,
                    model_name,
                    service_names,
                    sub_service_names,
                    CHAR_LENGTH(vendor_name) as vendor_name_length,
                    CHAR_LENGTH(service_names) as service_names_length
                FROM ordersview 
                WHERE order_id = ?
            ", [$orderId]);
            
            $diagnostics['direct_db_check'] = $fieldCheck;
        }

        return response()->json($diagnostics, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|integer|in:0,1,2,4,5',  // Updated status codes
        ]);

        $getOrder = OrdersViewModel::getSingle($id);
        
        if (!$getOrder) {
            return response()->json(['error' => 'الطلب غير موجود'], 404);
        }

        $getOrder->order_status = $request->status;
        $getOrder->save();

        // Send email notification if email exists
        if ($getOrder->email) {
            Mail::to($getOrder->email)->send(new OrderStatusMail($getOrder));
        }

        // Create notification for user
        $user_id = $getOrder->user_id;
        $url = url('user/orders');
        $message = "تم تحديث حالة طلبك #" . $getOrder->order_number;

        NotificationModel::insertRecord($user_id, $url, $message);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الحالة بنجاح'
        ]);
    }

    // Legacy method for backward compatibility
    public function orderStatus(Request $request)
    {
        return $this->updateStatus($request, $request->order_id);
    }

    public function generateInvoice($id)
    {
        $order = OrderEnhViewModel::getSingle($id);
        
        if (!$order) {
            return redirect()->back()->with('error', 'الطلب غير موجود');
        }

        // Generate invoice logic here
        // Return PDF or view for invoice
        return view('admin.order.invoice', compact('order'));
    }

    public function export(Request $request)
    {
        // Export orders to Excel/CSV
        // Implementation depends on your export library
    }

    public function statistics()
    {
        $stats = [
            'total_orders' => OrderModel::count(),
            'pending_orders' => OrderModel::where('order_status', 0)->count(),
            'completed_orders' => OrderModel::where('order_status', 4)->count(),
            'cancelled_orders' => OrderModel::where('order_status', 5)->count(),
            'total_revenue' => OrderModel::where('order_status', 4)->sum('total_amount'),
        ];

        return view('admin.order.statistics', compact('stats'));
    }
}