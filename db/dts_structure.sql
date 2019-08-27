-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 
-- Generation Time: Aug 27, 2019 at 02:53 PM
-- Server version: 5.5.63-MariaDB
-- PHP Version: 7.2.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ebdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `carrier`
--

CREATE TABLE `carrier` (
  `carrier_id` int(11) NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `phys_address` varchar(100) DEFAULT NULL,
  `phys_city` varchar(100) DEFAULT NULL,
  `phys_state` char(2) DEFAULT NULL,
  `phys_zip` varchar(10) DEFAULT NULL,
  `contact_name` varchar(30) DEFAULT NULL,
  `acct_rec_address` varchar(100) DEFAULT NULL,
  `acct_rec_city` varchar(100) DEFAULT NULL,
  `acct_rec_state` char(2) DEFAULT NULL,
  `acct_rec_zip` varchar(10) DEFAULT NULL,
  `main_phone_number` varchar(30) DEFAULT NULL,
  `fax` varchar(30) DEFAULT NULL,
  `carrier_rep` int(11) DEFAULT NULL,
  `insurance_on_file` binary(1) DEFAULT NULL,
  `do_not_load` binary(1) DEFAULT '0',
  `insurance_expires` date DEFAULT NULL,
  `packet_on_file` binary(1) DEFAULT NULL,
  `certification_holder` binary(1) DEFAULT NULL,
  `limited_liability` decimal(11,2) DEFAULT NULL,
  `cargo_limit` decimal(11,2) DEFAULT NULL,
  `mc_number` varchar(255) DEFAULT NULL,
  `icc_number` varchar(255) DEFAULT NULL,
  `carrier_notes` mediumtext,
  `last_changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `SCAC` varchar(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `carrier_notes`
--

CREATE TABLE `carrier_notes` (
  `note_id` int(11) NOT NULL,
  `carrier_id` int(11) NOT NULL DEFAULT '0',
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `notes` mediumtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `contact_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL DEFAULT '',
  `body` mediumtext NOT NULL,
  `sender` varchar(255) NOT NULL DEFAULT '',
  `remote_address` varchar(255) NOT NULL DEFAULT '',
  `date_sent` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `contact_lists`
--

CREATE TABLE `contact_lists` (
  `contact_list_id` int(11) NOT NULL,
  `contact_list_name` varchar(50) NOT NULL DEFAULT '',
  `active` binary(1) NOT NULL DEFAULT ' '
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` char(2) DEFAULT NULL,
  `zip` varchar(10) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `fax` varchar(30) DEFAULT NULL,
  `contact_name` varchar(30) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `billing_attention` varchar(30) DEFAULT NULL,
  `billing_address` varchar(100) DEFAULT NULL,
  `billing_city` varchar(100) DEFAULT NULL,
  `billing_state` char(2) DEFAULT NULL,
  `billing_zip` varchar(10) DEFAULT NULL,
  `billing_phone` varchar(30) DEFAULT NULL,
  `billing_fax` varchar(30) DEFAULT NULL,
  `billing_contact_name` varchar(30) DEFAULT NULL,
  `account_status` varchar(30) DEFAULT 'Not Active',
  `acct_owner` int(11) DEFAULT NULL,
  `date_changed` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customer_notes`
--

CREATE TABLE `customer_notes` (
  `note_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL DEFAULT '0',
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `notes` mediumtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cust_owner_notes`
--

CREATE TABLE `cust_owner_notes` (
  `cust_note_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `note` mediumtext NOT NULL,
  `note_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `errors`
--

CREATE TABLE `errors` (
  `error_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `error_string` mediumtext NOT NULL,
  `server_values` mediumtext NOT NULL,
  `function` varchar(50) NOT NULL DEFAULT '',
  `request_values` mediumtext NOT NULL,
  `error_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `group_id` int(11) NOT NULL,
  `group_name` varchar(30) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `load`
--

CREATE TABLE `load` (
  `load_id` int(11) NOT NULL,
  `activity_date` date DEFAULT NULL,
  `rating` varchar(30) DEFAULT NULL,
  `cancelled` binary(1) DEFAULT NULL,
  `problem` mediumtext,
  `solution` mediumtext,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `order_date` datetime DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `cust_line_haul` decimal(11,2) DEFAULT '0.00',
  `cust_line_haul_amount` decimal(11,2) DEFAULT '0.00',
  `carrier_line_haul` decimal(11,2) DEFAULT '0.00',
  `carrier_line_haul_amount` decimal(11,2) DEFAULT '0.00',
  `cust_detention` decimal(11,2) DEFAULT '0.00',
  `cust_detention_amount` decimal(11,2) DEFAULT '0.00',
  `carrier_detention` decimal(11,2) DEFAULT '0.00',
  `carrier_detention_amount` decimal(11,2) DEFAULT '0.00',
  `cust_tonu` decimal(11,2) DEFAULT '0.00',
  `cust_tonu_amount` decimal(11,2) DEFAULT '0.00',
  `carrier_tonu` decimal(11,2) DEFAULT '0.00',
  `carrier_tonu_amount` decimal(11,2) DEFAULT '0.00',
  `cust_unload_load` decimal(11,2) DEFAULT '0.00',
  `cust_unload_load_amount` decimal(11,2) DEFAULT '0.00',
  `carrier_unload_load` decimal(11,2) DEFAULT '0.00',
  `carrier_unload_load_amount` decimal(11,2) DEFAULT '0.00',
  `cust_fuel` decimal(11,2) DEFAULT '0.00',
  `cust_fuel_amount` decimal(11,2) DEFAULT '0.00',
  `carrier_fuel` decimal(11,2) DEFAULT '0.00',
  `carrier_fuel_amount` decimal(11,2) DEFAULT '0.00',
  `cust_other` decimal(11,2) DEFAULT '0.00',
  `cust_other_amount` decimal(11,2) DEFAULT '0.00',
  `carrier_other` decimal(11,2) DEFAULT '0.00',
  `carrier_other_amount` decimal(11,2) DEFAULT '0.00',
  `trailer_type` varchar(30) DEFAULT NULL,
  `load_type` varchar(50) DEFAULT NULL,
  `pallets` int(11) DEFAULT NULL,
  `length` int(11) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `weight` int(11) DEFAULT NULL,
  `class` varchar(30) DEFAULT NULL,
  `commodity` varchar(100) DEFAULT NULL,
  `carrier_id` int(11) DEFAULT NULL,
  `ltl_carrier` varchar(50) DEFAULT NULL,
  `pro_number` varchar(50) DEFAULT NULL,
  `ltl_number` varchar(30) DEFAULT NULL,
  `order_by` int(11) DEFAULT NULL,
  `wc_active` binary(1) NOT NULL DEFAULT '0',
  `wc_percent` int(11) DEFAULT '35',
  `zone` varchar(100) NOT NULL,
  `dls_percent` int(11) NOT NULL DEFAULT '20',
  `dls_active` binary(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `load_carrier`
--

CREATE TABLE `load_carrier` (
  `carrier_id` int(11) NOT NULL DEFAULT '0',
  `load_id` int(11) NOT NULL DEFAULT '0',
  `driver_name` varchar(30) DEFAULT NULL,
  `tractor_number` varchar(30) DEFAULT NULL,
  `trailer_number` varchar(30) DEFAULT NULL,
  `cell_number` varchar(30) DEFAULT NULL,
  `equipment_type` varchar(30) DEFAULT NULL,
  `booked_with` int(11) DEFAULT NULL,
  `notes` mediumtext,
  `booked_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Stand-in structure for view `load_report_totals`
-- (See below for the actual view)
--
CREATE TABLE `load_report_totals` (
`load_id` int(11)
,`load_type` varchar(50)
,`activity_date` date
,`customer_id` int(11)
,`wc_percent` int(11)
,`wc_active` binary(1)
,`dls_percent` int(11)
,`dls_active` binary(1)
,`cust_rate` decimal(27,4)
,`carrier_rate` decimal(27,4)
,`profit` decimal(43,6)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `load_report_totals_old`
-- (See below for the actual view)
--
CREATE TABLE `load_report_totals_old` (
`load_id` int(11)
,`load_type` varchar(50)
,`activity_date` date
,`customer_id` int(11)
,`wc_percent` int(11)
,`wc_active` binary(1)
,`dls_percent` int(11)
,`dls_active` binary(1)
,`cust_rate` decimal(27,4)
,`carrier_rate` decimal(27,4)
,`profit` decimal(28,4)
);

-- --------------------------------------------------------

--
-- Table structure for table `load_warehouse`
--

CREATE TABLE `load_warehouse` (
  `load_id` int(11) NOT NULL DEFAULT '0',
  `pick_dest_num` varchar(50) DEFAULT NULL,
  `open_time` time NOT NULL DEFAULT '08:00:00',
  `close_time` time NOT NULL DEFAULT '04:00:00',
  `warehouse_id` int(11) NOT NULL DEFAULT '0',
  `activity_date` date DEFAULT NULL,
  `activity_time` time DEFAULT NULL,
  `type` varchar(30) NOT NULL DEFAULT '',
  `scheduled_with` int(11) NOT NULL DEFAULT '0',
  `creation_date` datetime DEFAULT NULL,
  `complete` binary(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `region_lists`
--

CREATE TABLE `region_lists` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `active` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `request_id` int(11) NOT NULL,
  `type` varchar(3) NOT NULL,
  `origin_city` varchar(100) NOT NULL,
  `origin_zip` varchar(10) NOT NULL,
  `dest_city` varchar(100) NOT NULL,
  `dest_zip` varchar(10) NOT NULL,
  `class` varchar(30) NOT NULL,
  `commodity` varchar(100) NOT NULL,
  `weight` int(11) NOT NULL,
  `pallets` int(11) NOT NULL,
  `dimensions` varchar(30) NOT NULL,
  `pick_up_date` date NOT NULL,
  `special_services` mediumtext NOT NULL,
  `request_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cookie` varchar(32) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `hash` varchar(32) NOT NULL,
  `user_id` int(11) NOT NULL,
  `hash_expires` datetime NOT NULL,
  `last_login` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `first_name` varchar(100) NOT NULL DEFAULT '',
  `last_name` varchar(100) NOT NULL DEFAULT '',
  `hash` varchar(100) NOT NULL DEFAULT '',
  `hash_expires` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` tinyint(1) DEFAULT '0',
  `available` binary(1) NOT NULL DEFAULT '0',
  `hash_password` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_contact_list`
--

CREATE TABLE `user_contact_list` (
  `user_id` int(11) NOT NULL DEFAULT '0',
  `contact_list_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_group`
--

CREATE TABLE `user_group` (
  `user_id` int(11) NOT NULL DEFAULT '0',
  `group_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_region_list`
--

CREATE TABLE `user_region_list` (
  `user_id` int(11) NOT NULL DEFAULT '0',
  `region_list_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `warehouse`
--

CREATE TABLE `warehouse` (
  `warehouse_id` int(11) NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `zip` varchar(10) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `fax` varchar(30) DEFAULT NULL,
  `notes` mediumtext,
  `directions` mediumtext,
  `contact_name` varchar(100) DEFAULT NULL,
  `sun_open_time` time NOT NULL DEFAULT '08:00:00',
  `sun_close_time` time NOT NULL DEFAULT '16:00:00',
  `mon_open_time` time NOT NULL DEFAULT '08:00:00',
  `mon_close_time` time NOT NULL DEFAULT '16:00:00',
  `tues_open_time` time NOT NULL DEFAULT '08:00:00',
  `tues_close_time` time NOT NULL DEFAULT '16:00:00',
  `wed_open_time` time NOT NULL DEFAULT '08:00:00',
  `wed_close_time` time NOT NULL DEFAULT '16:00:00',
  `thurs_open_time` time NOT NULL DEFAULT '08:00:00',
  `thurs_close_time` time NOT NULL DEFAULT '16:00:00',
  `fri_open_time` time NOT NULL DEFAULT '08:00:00',
  `fri_close_time` time NOT NULL DEFAULT '16:00:00',
  `sat_open_time` time NOT NULL DEFAULT '08:00:00',
  `sat_close_time` time NOT NULL DEFAULT '16:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure for view `load_report_totals`
--

CREATE VIEW `load_report_totals`  AS  select `l`.`load_id` AS `load_id`,`l`.`load_type` AS `load_type`,`l`.`activity_date` AS `activity_date`,`l`.`customer_id` AS `customer_id`,`l`.`wc_percent` AS `wc_percent`,`l`.`wc_active` AS `wc_active`,`l`.`dls_percent` AS `dls_percent`,`l`.`dls_active` AS `dls_active`,`l`.`cust_rate` AS `cust_rate`,`l`.`carrier_rate` AS `carrier_rate`,if(`l`.`dls_active`,if(`l`.`wc_active`,(`l`.`profit` - (`l`.`profit` * ((`l`.`dls_percent` + `l`.`wc_percent`) * 0.01))),(`l`.`profit` - (`l`.`profit` * (`l`.`dls_percent` * 0.01)))),`l`.`profit`) AS `profit` from `load_report_totals_old` `l` ;

-- --------------------------------------------------------

--
-- Structure for view `load_report_totals_old`
--;

CREATE VIEW `load_report_totals_old`  AS  select `load`.`load_id` AS `load_id`,`load`.`load_type` AS `load_type`,`load`.`activity_date` AS `activity_date`,`load`.`customer_id` AS `customer_id`,`load`.`wc_percent` AS `wc_percent`,`load`.`wc_active` AS `wc_active`,`load`.`dls_percent` AS `dls_percent`,`load`.`dls_active` AS `dls_active`,((((((`load`.`cust_line_haul` * `load`.`cust_line_haul_amount`) + (`load`.`cust_detention` * `load`.`cust_detention_amount`)) + (`load`.`cust_tonu` * `load`.`cust_tonu_amount`)) + (`load`.`cust_unload_load` * `load`.`cust_unload_load_amount`)) + (`load`.`cust_fuel` * `load`.`cust_fuel_amount`)) + (`load`.`cust_other` * `load`.`cust_other_amount`)) AS `cust_rate`,((((((`load`.`carrier_line_haul` * `load`.`carrier_line_haul_amount`) + (`load`.`carrier_detention` * `load`.`carrier_detention_amount`)) + (`load`.`carrier_tonu` * `load`.`carrier_tonu_amount`)) + (`load`.`carrier_unload_load` * `load`.`carrier_unload_load_amount`)) + (`load`.`carrier_fuel` * `load`.`carrier_fuel_amount`)) + (`load`.`carrier_other` * `load`.`carrier_other_amount`)) AS `carrier_rate`,(((((((`load`.`cust_line_haul` * `load`.`cust_line_haul_amount`) + (`load`.`cust_detention` * `load`.`cust_detention_amount`)) + (`load`.`cust_tonu` * `load`.`cust_tonu_amount`)) + (`load`.`cust_unload_load` * `load`.`cust_unload_load_amount`)) + (`load`.`cust_fuel` * `load`.`cust_fuel_amount`)) + (`load`.`cust_other` * `load`.`cust_other_amount`)) - ((((((`load`.`carrier_line_haul` * `load`.`carrier_line_haul_amount`) + (`load`.`carrier_detention` * `load`.`carrier_detention_amount`)) + (`load`.`carrier_tonu` * `load`.`carrier_tonu_amount`)) + (`load`.`carrier_unload_load` * `load`.`carrier_unload_load_amount`)) + (`load`.`carrier_fuel` * `load`.`carrier_fuel_amount`)) + (`load`.`carrier_other` * `load`.`carrier_other_amount`))) AS `profit` from `load` where (isnull(`load`.`cancelled`) or (`load`.`cancelled` = 0)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carrier`
--
ALTER TABLE `carrier`
  ADD PRIMARY KEY (`carrier_id`),
  ADD KEY `carrier_rep` (`carrier_rep`);

--
-- Indexes for table `carrier_notes`
--
ALTER TABLE `carrier_notes`
  ADD PRIMARY KEY (`note_id`),
  ADD KEY `customer_id` (`carrier_id`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`contact_id`);

--
-- Indexes for table `contact_lists`
--
ALTER TABLE `contact_lists`
  ADD PRIMARY KEY (`contact_list_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`),
  ADD KEY `acct_owner` (`acct_owner`);

--
-- Indexes for table `customer_notes`
--
ALTER TABLE `customer_notes`
  ADD PRIMARY KEY (`note_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `cust_owner_notes`
--
ALTER TABLE `cust_owner_notes`
  ADD PRIMARY KEY (`cust_note_id`);

--
-- Indexes for table `errors`
--
ALTER TABLE `errors`
  ADD PRIMARY KEY (`error_id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`group_id`);

--
-- Indexes for table `load`
--
ALTER TABLE `load`
  ADD PRIMARY KEY (`load_id`),
  ADD KEY `carrier_id` (`carrier_id`),
  ADD KEY `order_by` (`order_by`),
  ADD KEY `customer_id_2` (`customer_id`);

--
-- Indexes for table `load_carrier`
--
ALTER TABLE `load_carrier`
  ADD PRIMARY KEY (`carrier_id`,`load_id`),
  ADD KEY `booked_with` (`booked_with`),
  ADD KEY `load_id` (`load_id`);

--
-- Indexes for table `load_warehouse`
--
ALTER TABLE `load_warehouse`
  ADD PRIMARY KEY (`load_id`,`warehouse_id`),
  ADD KEY `scheduled_with` (`scheduled_with`),
  ADD KEY `warehouse_id` (`warehouse_id`);

--
-- Indexes for table `region_lists`
--
ALTER TABLE `region_lists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`request_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`hash`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `hash` (`hash`),
  ADD KEY `active` (`active`);

--
-- Indexes for table `user_contact_list`
--
ALTER TABLE `user_contact_list`
  ADD PRIMARY KEY (`user_id`,`contact_list_id`);

--
-- Indexes for table `user_group`
--
ALTER TABLE `user_group`
  ADD PRIMARY KEY (`user_id`,`group_id`);

--
-- Indexes for table `user_region_list`
--
ALTER TABLE `user_region_list`
  ADD PRIMARY KEY (`user_id`,`region_list_id`);

--
-- Indexes for table `warehouse`
--
ALTER TABLE `warehouse`
  ADD PRIMARY KEY (`warehouse_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carrier`
--
ALTER TABLE `carrier`
  MODIFY `carrier_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `carrier_notes`
--
ALTER TABLE `carrier_notes`
  MODIFY `note_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_lists`
--
ALTER TABLE `contact_lists`
  MODIFY `contact_list_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_notes`
--
ALTER TABLE `customer_notes`
  MODIFY `note_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cust_owner_notes`
--
ALTER TABLE `cust_owner_notes`
  MODIFY `cust_note_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `errors`
--
ALTER TABLE `errors`
  MODIFY `error_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `load`
--
ALTER TABLE `load`
  MODIFY `load_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `region_lists`
--
ALTER TABLE `region_lists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `warehouse`
--
ALTER TABLE `warehouse`
  MODIFY `warehouse_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
