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
                    CREATE TABLE `order_activity_log` (
          `activity_id` INT(11) NOT NULL AUTO_INCREMENT,
          `order_id` INT(11) NOT NULL,
          `actor_type` ENUM('admin', 'vendor', 'customer', 'system') NOT NULL,
          `actor_id` INT(11) NOT NULL,
          `activity_type` ENUM(
            'offer_sent', 'offer_accepted', 'offer_rejected', 'counter_offer_made',
            'price_negotiated', 'vendor_assigned', 'order_confirmed', 'order_cancelled',
            'payment_processed', 'service_started', 'service_completed'
          ) NOT NULL,
          `activity_description` TEXT NOT NULL,
          `related_offer_id` INT(11) DEFAULT NULL,
          `related_response_id` INT(11) DEFAULT NULL,
          `previous_status` VARCHAR(50) DEFAULT NULL,
          `new_status` VARCHAR(50) DEFAULT NULL,
          `metadata` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
          `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
          PRIMARY KEY (`activity_id`),
          KEY `idx_order_activity` (`order_id`, `activity_type`, `created_at`),
          CONSTRAINT `order_activity_log_order_fk` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
                    ");
                }

                public function down(): void
                {
                    Schema::dropIfExists('order_activity_log');
                }
            };