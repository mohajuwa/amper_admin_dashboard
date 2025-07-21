<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("CREATE TABLE `fault_types` (   `fault_id` int(11) NOT NULL AUTO_INCREMENT,   `name` longtext NOT NULL,   `service_id` int(11) NOT NULL,   `status` tinyint(4) NOT NULL DEFAULT 0,   PRIMARY KEY (`fault_id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;");
    }

    public function down(): void
    {
        Schema::dropIfExists('fault_types');
    }
};
