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
                    CREATE TABLE `vendor_responses` (
          `response_id` INT(11) NOT NULL AUTO_INCREMENT,
          `offer_id` INT(11) NOT NULL,
          `vendor_id` INT(11) NOT NULL,
          `response_type` ENUM('accept', 'reject', 'counter_offer', 'request_modification') DEFAULT 'accept',
          `response_price` DECIMAL(10,2) DEFAULT NULL,
          `response_notes` TEXT DEFAULT NULL,
          `rejection_reason` VARCHAR(255) DEFAULT NULL,
          `attachments` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachments`)),
          `responded_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
          PRIMARY KEY (`response_id`),
          KEY `idx_offer_vendor` (`offer_id`, `vendor_id`),
          CONSTRAINT `vendor_responses_offer_fk` FOREIGN KEY (`offer_id`) REFERENCES `order_offers` (`offer_id`) ON DELETE CASCADE,
          CONSTRAINT `vendor_responses_vendor_fk` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`vendor_id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
                    ");
                }

                public function down(): void
                {
                    Schema::dropIfExists('vendor_responses');
                }
            };