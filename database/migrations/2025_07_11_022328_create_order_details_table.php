<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("CREATE TABLE `order_details` (   `detail_id` int(11) NOT NULL AUTO_INCREMENT,   `order_id` int(11) NOT NULL,   `sub_service_id` int(11) NOT NULL,   `quantity` int(11) NOT NULL DEFAULT 1,   `unit_price` decimal(10,2) NOT NULL,   `discount` decimal(10,2) DEFAULT 0.00,   `total_price` decimal(10,2) NOT NULL,   `created_at` timestamp NOT NULL DEFAULT current_timestamp(),   `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),   PRIMARY KEY (`detail_id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;");
    }

    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
