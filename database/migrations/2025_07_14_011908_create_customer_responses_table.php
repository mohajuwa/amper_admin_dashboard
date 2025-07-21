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
                    CREATE TABLE `customer_responses` (
          `response_id` INT(11) NOT NULL AUTO_INCREMENT,
          `order_id` INT(11) NOT NULL,
          `offer_id` INT(11) DEFAULT NULL,
          `user_id` INT(11) NOT NULL,
          `response_type` ENUM('accept', 'reject', 'request_modification', 'cancel_order') DEFAULT 'accept',
          `final_agreed_price` DECIMAL(10,2) DEFAULT NULL,
          `customer_notes` TEXT DEFAULT NULL,
          `preferred_schedule` DATETIME DEFAULT NULL,
          `responded_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
          PRIMARY KEY (`response_id`),
          KEY `idx_order_user` (`order_id`, `user_id`),
          CONSTRAINT `customer_responses_order_fk` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
          CONSTRAINT `customer_responses_offer_fk` FOREIGN KEY (`offer_id`) REFERENCES `order_offers` (`offer_id`) ON DELETE SET NULL,
          CONSTRAINT `customer_responses_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
                    ");
                }

                public function down(): void
                {
                    Schema::dropIfExists('customer_responses');
                }
            };