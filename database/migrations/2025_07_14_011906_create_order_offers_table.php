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
                    CREATE TABLE `order_offers` (
          `offer_id` INT(11) NOT NULL AUTO_INCREMENT,
          `order_id` INT(11) NOT NULL,
          `vendor_id` INT(11) NOT NULL,
          `admin_id` INT(11) NOT NULL,
          `offer_type` ENUM('service_quote', 'negotiation', 'counter_offer') DEFAULT 'service_quote',
          `offered_price` DECIMAL(10,2) NOT NULL,
          `service_details` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`service_details`)),
          `offer_notes` TEXT DEFAULT NULL,
          `expires_at` DATETIME DEFAULT NULL,
          `status` ENUM('pending', 'accepted', 'rejected', 'expired', 'cancelled') DEFAULT 'pending',
          `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
          `updated_at` TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
          PRIMARY KEY (`offer_id`),
          KEY `idx_order_vendor` (`order_id`, `vendor_id`),
          KEY `idx_status_created` (`status`, `created_at`),
          CONSTRAINT `order_offers_order_fk` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
          CONSTRAINT `order_offers_vendor_fk` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`vendor_id`) ON DELETE CASCADE,
          CONSTRAINT `order_offers_admin_fk` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
                    ");
                }

                public function down(): void
                {
                    Schema::dropIfExists('order_offers');
                }
            };