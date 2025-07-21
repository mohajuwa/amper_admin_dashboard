-- --------------------------------------------------------
-- Car Service App - Full Database Schema
-- Part 1: Table Structures
-- --------------------------------------------------------

-- Drop tables in reverse order of dependency to avoid foreign key errors
DROP TABLE IF EXISTS `password_reset_tokens`, `payments`, `attachments`, `order_vendor_quotes`, `order_scheduling`, `order_details`, `orders`, `fault_types`, `sub_services`, `services`, `vehicles`, `car_models`, `car_makes`, `addresses`, `vendors`, `users`, `admins`;

CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('super_admin','admin','moderator','editor','viewer') DEFAULT 'super_admin',
  `status` enum('active','inactive') DEFAULT 'active',
  `is_delete` int(11) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `verfiycode` int(4) NOT NULL DEFAULT 0,
  `status` enum('active','inactive','banned') DEFAULT 'active',
  `is_delete` int(11) NOT NULL DEFAULT 0,
  `approve` tinyint(4) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `vendors` (
  `vendor_id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_name` longtext NOT NULL,
  `vendor_name` longtext NOT NULL,
  `vendor_type` enum('workshop','tow_truck') NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `password` varchar(500) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive','suspended') DEFAULT 'inactive',
  `registered_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`vendor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `addresses` (
  `address_id` int(11) NOT NULL AUTO_INCREMENT,
  `address_user_id` int(11) NOT NULL,
  `address_name` varchar(50) DEFAULT NULL,
  `address_street` varchar(255) NOT NULL,
  `address_city` varchar(255) NOT NULL,
  `address_latitude` double DEFAULT NULL,
  `address_longitude` double DEFAULT NULL,
  `address_status` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`address_id`),
  KEY `fk_address_user` (`address_user_id`),
  CONSTRAINT `fk_address_user` FOREIGN KEY (`address_user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `car_makes` (
  `make_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `popularity` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(4) DEFAULT 1,
  PRIMARY KEY (`make_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `car_models` (
  `model_id` int(11) NOT NULL AUTO_INCREMENT,
  `make_id` int(11) DEFAULT NULL,
  `name` longtext NOT NULL,
  `status` tinyint(4) DEFAULT 1,
  PRIMARY KEY (`model_id`),
  KEY `fk_model_make` (`make_id`),
  CONSTRAINT `fk_model_make` FOREIGN KEY (`make_id`) REFERENCES `car_makes` (`make_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `vehicles` (
  `vehicle_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `car_make_id` int(11) NOT NULL,
  `car_model_id` int(11) NOT NULL,
  `year` int(11) DEFAULT NULL,
  `license_plate_number` longtext DEFAULT NULL,
  `status` tinyint(4) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`vehicle_id`),
  KEY `fk_vehicle_user` (`user_id`),
  KEY `fk_vehicle_make` (`car_make_id`),
  KEY `fk_vehicle_model` (`car_model_id`),
  CONSTRAINT `fk_vehicle_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_vehicle_make` FOREIGN KEY (`car_make_id`) REFERENCES `car_makes` (`make_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_vehicle_model` FOREIGN KEY (`car_model_id`) REFERENCES `car_models` (`model_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `services` (
  `service_id` int(11) NOT NULL AUTO_INCREMENT,
  `service_name` longtext DEFAULT NULL,
  `service_img` varchar(500) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `sub_services` (
  `sub_service_id` int(11) NOT NULL AUTO_INCREMENT,
  `service_id` int(11) NOT NULL,
  `name` longtext NOT NULL,
  `price` double NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`sub_service_id`),
  KEY `fk_subservice_service` (`service_id`),
  CONSTRAINT `fk_subservice_service` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `fault_types` (
  `fault_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` longtext NOT NULL,
  `service_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`fault_id`),
  KEY `fk_fault_service` (`service_id`),
  CONSTRAINT `fk_fault_service` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_number` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `orders_address` int(11) NOT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `fault_type_id` int(11) DEFAULT NULL,
  `order_status` tinyint(4) DEFAULT 0,
  `order_type` tinyint(4) NOT NULL DEFAULT 0,
  `orders_coupon_id` int(11) DEFAULT NULL,
  `orders_paymentmethod` int(11) DEFAULT 0,
  `orders_pricedelivery` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) DEFAULT NULL,
  `workshop_amount` decimal(10,2) DEFAULT NULL,
  `app_commission` decimal(10,2) DEFAULT NULL,
  `payment_status` enum('pending','paid','failed','cancelled') DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `is_scheduled` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`order_id`),
  KEY `fk_order_user` (`user_id`),
  KEY `fk_order_vendor` (`vendor_id`),
  KEY `fk_order_vehicle` (`vehicle_id`),
  CONSTRAINT `fk_order_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL,
  CONSTRAINT `fk_order_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`vendor_id`) ON DELETE SET NULL,
  CONSTRAINT `fk_order_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`vehicle_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `order_details` (
  `detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `sub_service_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) DEFAULT 0.00,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`detail_id`),
  KEY `fk_detail_order` (`order_id`),
  KEY `fk_detail_subservice` (`sub_service_id`),
  CONSTRAINT `fk_detail_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_detail_subservice` FOREIGN KEY (`sub_service_id`) REFERENCES `sub_services` (`sub_service_id`) ON DELETE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `order_scheduling` (
  `scheduling_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `scheduled_datetime` datetime NOT NULL,
  `is_completed` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`scheduling_id`),
  KEY `fk_schedule_order` (`order_id`),
  CONSTRAINT `fk_schedule_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `order_vendor_quotes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('pending','submitted','accepted','rejected','expired') DEFAULT 'pending',
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_quote_order` (`order_id`),
  KEY `fk_quote_vendor` (`vendor_id`),
  CONSTRAINT `fk_quote_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_quote_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`vendor_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `attachment_link` varchar(255) NOT NULL,
  `status` tinyint(4) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `fk_attach_order` (`order_id`),
  CONSTRAINT `fk_attach_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_status` enum('pending','completed','failed') DEFAULT 'pending',
  PRIMARY KEY (`payment_id`),
  KEY `fk_payment_order` (`order_id`),
  CONSTRAINT `fk_payment_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------
-- Part 2: View Structures
-- --------------------------------------------------------

DROP VIEW IF EXISTS `enhanced_orders_view`, `ordersview`, `order_calculations_view`, `order_details_view`, `order_summary_view`, `vehicle_essential_info`;

CREATE VIEW `enhanced_orders_view` AS select `o`.`order_id` AS `order_id`,`o`.`order_number` AS `order_number`,`o`.`user_id` AS `user_id`,`u`.`full_name` AS `user_name`,`o`.`orders_address` AS `orders_address`,`o`.`vendor_id` AS `vendor_id`,`v`.`vendor_name` AS `vendor_name`,`o`.`vehicle_id` AS `vehicle_id`,`vm`.`name` AS `make_name`,`vmod`.`name` AS `model_name`,`veh`.`year` AS `year`,`veh`.`license_plate_number` AS `license_plate_number`,group_concat(`od`.`sub_service_id` separator ',') AS `sub_service_ids`,group_concat(`ss`.`name` separator ',') AS `sub_service_names`,sum(`od`.`total_price`) AS `services_total_price`,group_concat(distinct `s`.`service_id` separator ',') AS `service_ids`,group_concat(distinct `s`.`service_name` separator ',') AS `service_names`,`fau`.`name` AS `fault_type_name`,`o`.`order_status` AS `order_status`,`o`.`order_type` AS `order_type`,`o`.`orders_coupon_id` AS `orders_coupon_id`,`o`.`orders_paymentmethod` AS `orders_paymentmethod`,`o`.`orders_pricedelivery` AS `orders_pricedelivery`,`o`.`order_date` AS `order_date`,`o`.`total_amount` AS `total_amount`,`o`.`workshop_amount` AS `workshop_amount`,`o`.`app_commission` AS `app_commission`,`o`.`payment_status` AS `payment_status`,`o`.`notes` AS `notes`,`a`.`address_name` AS `address_name`,`a`.`address_street` AS `address_street`,`a`.`address_city` AS `address_city`,`a`.`address_latitude` AS `address_latitude`,`a`.`address_longitude` AS `address_longitude` from ((((((((((`orders` `o` left join `users` `u` on(`o`.`user_id` = `u`.`user_id`)) left join `vendors` `v` on(`o`.`vendor_id` = `v`.`vendor_id`)) left join `vehicles` `veh` on(`o`.`vehicle_id` = `veh`.`vehicle_id`)) left join `car_makes` `vm` on(`veh`.`car_make_id` = `vm`.`make_id`)) left join `car_models` `vmod` on(`veh`.`car_model_id` = `vmod`.`model_id`)) left join `addresses` `a` on(`o`.`orders_address` = `a`.`address_id`)) left join `order_details` `od` on(`o`.`order_id` = `od`.`order_id`)) left join `sub_services` `ss` on(`od`.`sub_service_id` = `ss`.`sub_service_id`)) left join `services` `s` on(`ss`.`service_id` = `s`.`service_id`)) left join `fault_types` `fau` on(`o`.`fault_type_id` = `fau`.`fault_id`)) group by `o`.`order_id`;
CREATE VIEW `ordersview` AS select `o`.`order_id` AS `order_id`,`o`.`order_number` AS `order_number`,`o`.`user_id` AS `user_id`,`o`.`orders_address` AS `orders_address_fk`,`o`.`vendor_id` AS `vendor_id`,`o`.`vehicle_id` AS `vehicle_id`,`o`.`fault_type_id` AS `fault_type_id`,`o`.`order_status` AS `order_status`,`o`.`order_type` AS `order_type`,`o`.`orders_coupon_id` AS `orders_coupon_id`,`o`.`orders_paymentmethod` AS `orders_paymentmethod`,`o`.`orders_pricedelivery` AS `orders_pricedelivery`,`o`.`order_date` AS `order_date`,`o`.`total_amount` AS `total_amount`,`o`.`workshop_amount` AS `workshop_amount`,`o`.`app_commission` AS `app_commission`,`o`.`payment_status` AS `payment_status`,`o`.`notes` AS `notes`,`o`.`is_scheduled` AS `is_scheduled`,max(`os`.`scheduled_datetime`) AS `scheduled_datetime`,`a`.`address_id` AS `address_id`,`a`.`address_user_id` AS `address_user_id`,`a`.`address_name` AS `address_name`,`a`.`address_street` AS `address_street`,`a`.`address_city` AS `address_city`,`a`.`address_latitude` AS `address_latitude`,`a`.`address_longitude` AS `address_longitude`,`a`.`address_status` AS `address_status` from ((`orders` `o` left join `addresses` `a` on(`o`.`orders_address` = `a`.`address_id`)) left join `order_scheduling` `os` on(`o`.`order_id` = `os`.`order_id`)) group by `o`.`order_id`;
CREATE VIEW `order_calculations_view` AS select `od`.`order_id` AS `order_id`,sum(`od`.`quantity` * `od`.`unit_price`) AS `subtotal`,sum(`od`.`discount`) AS `total_discounts`,sum(`od`.`total_price`) AS `total_services_amount`,`o`.`orders_pricedelivery` AS `delivery_price`,`o`.`total_amount` AS `total_amount`,`o`.`workshop_amount` AS `workshop_amount`,`o`.`app_commission` AS `app_commission` from (`order_details` `od` join `orders` `o` on(`od`.`order_id` = `o`.`order_id`)) group by `od`.`order_id`;
CREATE VIEW `order_details_view` AS select `od`.`detail_id` AS `detail_id`,`od`.`order_id` AS `order_id`,`o`.`is_scheduled` AS `order_is_scheduled`,`os_main`.`scheduled_datetime` AS `order_scheduled_datetime`,`od`.`sub_service_id` AS `sub_service_id`,`ss`.`service_id` AS `service_id`,json_unquote(json_extract(`ss`.`name`,'$')) AS `sub_service_name`,json_unquote(json_extract(`s`.`service_name`,'$')) AS `service_name`,`od`.`quantity` AS `quantity`,`od`.`unit_price` AS `unit_price`,`od`.`discount` AS `discount`,`od`.`total_price` AS `total_price`,`od`.`created_at` AS `created_at`,`od`.`updated_at` AS `updated_at` from (((`order_details` `od` join `sub_services` `ss` on(`od`.`sub_service_id` = `ss`.`sub_service_id`)) join `services` `s` on(`ss`.`service_id` = `s`.`service_id`)) left join `orders` `o` on(`od`.`order_id` = `o`.`order_id`)) left join (select `order_scheduling`.`order_id` AS `order_id`,max(`order_scheduling`.`scheduled_datetime`) AS `scheduled_datetime` from `order_scheduling` group by `order_scheduling`.`order_id`) `os_main` on(`o`.`order_id` = `os_main`.`order_id`);
CREATE VIEW `order_summary_view` AS select `o`.`order_id` AS `order_id`,`o`.`order_number` AS `order_number`,`o`.`user_id` AS `user_id`,`u`.`full_name` AS `user_name`,`o`.`vendor_id` AS `vendor_id`,json_unquote(json_extract(`v`.`vendor_name`,'$')) AS `vendor_name`,`o`.`vehicle_id` AS `vehicle_id`,json_unquote(json_extract(`vm`.`name`,'$')) AS `make_name`,json_unquote(json_extract(`vmod`.`name`,'$')) AS `model_name`,`o`.`order_status` AS `order_status`,`o`.`payment_status` AS `payment_status`,`o`.`order_date` AS `order_date`,`o`.`total_amount` AS `total_amount`,count(`od`.`detail_id`) AS `service_count`,sum(`od`.`total_price`) AS `services_total` from (((((`orders` `o` left join `users` `u` on(`o`.`user_id` = `u`.`user_id`)) left join `vendors` `v` on(`o`.`vendor_id` = `v`.`vendor_id`)) left join `vehicles` `veh` on(`o`.`vehicle_id` = `veh`.`vehicle_id`)) left join `car_makes` `vm` on(`veh`.`car_make_id` = `vm`.`make_id`)) left join `car_models` `vmod` on(`veh`.`car_model_id` = `vmod`.`model_id`)) left join `order_details` `od` on(`o`.`order_id` = `od`.`order_id`)) group by `o`.`order_id`;
CREATE VIEW `vehicle_essential_info` AS select `v`.`vehicle_id` AS `vehicle_id`,`u`.`user_id` AS `user_id`,`cm`.`make_id` AS `make_id`,`cmod`.`model_id` AS `model_id`,`cm`.`name` AS `make_name`,`cm`.`logo` AS `make_logo`,`cmod`.`name` AS `model_name`,`v`.`year` AS `year`,`v`.`license_plate_number` AS `license_plate_number`,`v`.`status` AS `status` from (((`vehicles` `v` left join `users` `u` on(`v`.`user_id` = `u`.`user_id`)) left join `car_makes` `cm` on(`v`.`car_make_id` = `cm`.`make_id`)) left join `car_models` `cmod` on(`v`.`car_model_id` = `cmod`.`model_id`));

