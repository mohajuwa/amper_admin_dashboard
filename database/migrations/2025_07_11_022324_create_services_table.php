<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("CREATE TABLE `services` (   `service_id` int(11) NOT NULL AUTO_INCREMENT,   `service_name` longtext DEFAULT NULL,   `service_img` varchar(500) DEFAULT NULL,   `status` tinyint(4) NOT NULL DEFAULT 0,   `created_at` datetime NOT NULL DEFAULT current_timestamp(),   `updated_at` datetime NOT NULL DEFAULT current_timestamp(),   PRIMARY KEY (`service_id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;");
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
