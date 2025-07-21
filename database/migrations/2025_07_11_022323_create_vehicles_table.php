<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("CREATE TABLE `vehicles` (   `vehicle_id` int(11) NOT NULL AUTO_INCREMENT,   `user_id` int(11) NOT NULL,   `car_make_id` int(11) NOT NULL,   `car_model_id` int(11) NOT NULL,   `year` int(11) DEFAULT NULL,   `license_plate_number` longtext DEFAULT NULL,   `status` tinyint(4) DEFAULT 0,   `created_at` timestamp NOT NULL DEFAULT current_timestamp(),   PRIMARY KEY (`vehicle_id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;");
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
