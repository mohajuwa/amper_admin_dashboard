<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("CREATE TABLE `car_makes` (   `make_id` int(11) NOT NULL AUTO_INCREMENT,   `name` longtext NOT NULL,   `logo` varchar(255) DEFAULT NULL,   `popularity` int(11) NOT NULL DEFAULT 0,   `status` tinyint(4) DEFAULT 1,   PRIMARY KEY (`make_id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;");
    }

    public function down(): void
    {
        Schema::dropIfExists('car_makes');
    }
};
