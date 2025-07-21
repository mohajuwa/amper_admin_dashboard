<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("CREATE TABLE `vendors` (   `vendor_id` int(11) NOT NULL AUTO_INCREMENT,   `owner_name` longtext NOT NULL,   `vendor_name` longtext NOT NULL,   `vendor_type` enum('workshop','tow_truck') NOT NULL,   `phone` varchar(50) DEFAULT NULL,   `password` varchar(500) NOT NULL,   `address` varchar(255) DEFAULT NULL,   `description` text DEFAULT NULL,   `status` enum('active','inactive','suspended') DEFAULT 'inactive',   `registered_at` datetime NOT NULL DEFAULT current_timestamp(),   PRIMARY KEY (`vendor_id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");
    }

    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
