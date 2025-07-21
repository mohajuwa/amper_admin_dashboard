<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("CREATE TABLE `sub_services` (   `sub_service_id` int(11) NOT NULL AUTO_INCREMENT,   `service_id` int(11) NOT NULL,   `name` longtext NOT NULL,   `price` double NOT NULL DEFAULT 0,   `status` tinyint(4) NOT NULL DEFAULT 0,   `created_at` datetime NOT NULL DEFAULT current_timestamp(),   `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),   PRIMARY KEY (`sub_service_id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;");
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_services');
    }
};
