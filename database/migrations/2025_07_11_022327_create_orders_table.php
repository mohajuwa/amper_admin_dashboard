<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("CREATE TABLE `orders` (   `order_id` int(11) NOT NULL AUTO_INCREMENT,   `order_number` int(11) NOT NULL,   `user_id` int(11) DEFAULT NULL,   `orders_address` int(11) NOT NULL,   `vendor_id` int(11) DEFAULT NULL,   `vehicle_id` int(11) DEFAULT NULL,   `fault_type_id` int(11) DEFAULT NULL,   `order_status` tinyint(4) DEFAULT 0,   `order_type` tinyint(4) NOT NULL DEFAULT 0,   `orders_coupon_id` int(11) DEFAULT NULL,   `orders_paymentmethod` int(11) DEFAULT 0,   `orders_pricedelivery` int(11) NOT NULL,   `order_date` timestamp NOT NULL DEFAULT current_timestamp(),   `total_amount` decimal(10,2) DEFAULT NULL,   `workshop_amount` decimal(10,2) DEFAULT NULL,   `app_commission` decimal(10,2) DEFAULT NULL,   `payment_status` enum('pending','paid','failed','cancelled') DEFAULT 'pending',   `notes` text DEFAULT NULL,   `is_scheduled` tinyint(1) NOT NULL DEFAULT 0,   PRIMARY KEY (`order_id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;");
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
