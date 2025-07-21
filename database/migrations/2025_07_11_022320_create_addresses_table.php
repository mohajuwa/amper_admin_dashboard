<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("CREATE TABLE `addresses` (   `address_id` int(11) NOT NULL AUTO_INCREMENT,   `address_user_id` int(11) NOT NULL,   `address_name` varchar(50) DEFAULT NULL,   `address_street` varchar(255) NOT NULL,   `address_city` varchar(255) NOT NULL,   `address_latitude` double DEFAULT NULL,   `address_longitude` double DEFAULT NULL,   `address_status` tinyint(4) NOT NULL DEFAULT 0,   PRIMARY KEY (`address_id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;");
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
