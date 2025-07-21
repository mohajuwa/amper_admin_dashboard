<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("CREATE TABLE `car_models` (   `model_id` int(11) NOT NULL AUTO_INCREMENT,   `make_id` int(11) DEFAULT NULL,   `name` longtext NOT NULL,   `status` tinyint(4) DEFAULT 1,   PRIMARY KEY (`model_id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;");
    }

    public function down(): void
    {
        Schema::dropIfExists('car_models');
    }
};
