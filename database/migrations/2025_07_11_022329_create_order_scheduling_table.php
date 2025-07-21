<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("CREATE TABLE `order_scheduling` (   `scheduling_id` int(11) NOT NULL AUTO_INCREMENT,   `order_id` int(11) NOT NULL,   `scheduled_datetime` datetime NOT NULL,   `is_completed` tinyint(1) DEFAULT 0,   `created_at` timestamp NULL DEFAULT current_timestamp(),   `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),   PRIMARY KEY (`scheduling_id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    }

    public function down(): void
    {
        Schema::dropIfExists('order_scheduling');
    }
};
