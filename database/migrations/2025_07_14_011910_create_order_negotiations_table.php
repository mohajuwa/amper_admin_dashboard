<?php

            use Illuminate\Database\Migrations\Migration;
            use Illuminate\Database\Schema\Blueprint;
            use Illuminate\Support\Facades\Schema;
            use Illuminate\Support\Facades\DB;

            return new class extends Migration
            {
                public function up(): void
                {
                    DB::unprepared("
                    CREATE TABLE `order_negotiations` (
          `negotiation_id` INT(11) NOT NULL AUTO_INCREMENT,
          `order_id` INT(11) NOT NULL,
          `vendor_id` INT(11) NOT NULL,
          `negotiation_round` INT(11) NOT NULL DEFAULT 1,
          `initiator_type` ENUM('admin', 'vendor') NOT NULL,
          `initiator_id` INT(11) NOT NULL,
          `proposed_price` DECIMAL(10,2) NOT NULL,
          `negotiation_notes` TEXT DEFAULT NULL,
          `status` ENUM('active', 'accepted', 'rejected', 'superseded') DEFAULT 'active',
          `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
          PRIMARY KEY (`negotiation_id`),
          KEY `idx_order_vendor_round` (`order_id`, `vendor_id`, `negotiation_round`),
          CONSTRAINT `order_negotiations_order_fk` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
          CONSTRAINT `order_negotiations_vendor_fk` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`vendor_id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
                    ");
                }

                public function down(): void
                {
                    Schema::dropIfExists('order_negotiations');
                }
            };