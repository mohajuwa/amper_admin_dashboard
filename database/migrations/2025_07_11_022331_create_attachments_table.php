<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("CREATE TABLE `attachments` (   `id` int(11) NOT NULL AUTO_INCREMENT,   `order_id` int(11) NOT NULL,   `attachment_link` varchar(255) NOT NULL,   `status` tinyint(4) DEFAULT 0,   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;");
    }

    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
