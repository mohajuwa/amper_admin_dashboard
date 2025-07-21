<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("CREATE TABLE `order_vendor_quotes` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `order_id` int(11) NOT NULL,   `vendor_id` int(11) NOT NULL,   `price` decimal(10,2) NOT NULL,   `notes` text DEFAULT NULL,   `status` enum('pending','submitted','accepted','rejected','expired') DEFAULT 'pending',   `expires_at` timestamp NULL DEFAULT NULL,   `created_at` timestamp NOT NULL DEFAULT current_timestamp(),   `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");
    }

    public function down(): void
    {
        Schema::dropIfExists('order_vendor_quotes');
    }
};
