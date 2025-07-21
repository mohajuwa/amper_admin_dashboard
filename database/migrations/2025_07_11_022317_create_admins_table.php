<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("CREATE TABLE `admins` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `full_name` varchar(255) NOT NULL,   `email` varchar(255) NOT NULL,   `password` varchar(255) NOT NULL,   `role` enum('super_admin','admin','moderator','editor','viewer') DEFAULT 'super_admin',   `status` enum('active','inactive') DEFAULT 'active',   `is_delete` int(11) NOT NULL DEFAULT 0,   `remember_token` varchar(100) NOT NULL,   `created_at` timestamp NOT NULL DEFAULT current_timestamp(),   `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),   PRIMARY KEY (`id`),   UNIQUE KEY `email` (`email`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
