<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("CREATE TABLE `users` (   `user_id` int(11) NOT NULL AUTO_INCREMENT,   `full_name` varchar(255) NOT NULL,   `phone` varchar(50) DEFAULT NULL,   `verfiycode` int(4) NOT NULL DEFAULT 0,   `status` enum('active','inactive','banned') DEFAULT 'active',   `is_delete` int(11) NOT NULL DEFAULT 0,   `approve` tinyint(4) DEFAULT 1,   `created_at` timestamp NOT NULL DEFAULT current_timestamp(),   `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),   PRIMARY KEY (`user_id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
