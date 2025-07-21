<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("CREATE TABLE `password_reset_tokens` (   `email` varchar(255) NOT NULL,   `token` varchar(255) NOT NULL,   `created_at` timestamp NULL DEFAULT NULL,   PRIMARY KEY (`email`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    }

    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
    }
};
