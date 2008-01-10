-- phpMyAdmin SQL Dump
-- version 2.10.2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Jan 10, 2008 at 03:36 PM
-- Server version: 5.0.41
-- PHP Version: 5.2.3

-- 
-- Database: `piggy`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `piggy_transaction_details`
-- 

CREATE TABLE `piggy_transaction_details` (
  `details_id` int(11) unsigned NOT NULL auto_increment,
  `details_transaction_id` int(11) unsigned NOT NULL,
  `details_account_id` int(7) unsigned NOT NULL,
  `details_bucket_id` int(7) unsigned NOT NULL,
  `details_amount` double(8,2) NOT NULL,
  `details_display` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`details_id`),
  KEY `FK_transaction_id` (`details_transaction_id`),
  KEY `FK_account_id` (`details_account_id`),
  KEY `FK_bucket_id` (`details_bucket_id`)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `piggy_transaction_details`
-- 


-- 
-- Constraints for dumped tables
-- 

-- 
-- Constraints for table `piggy_transaction_details`
-- 
ALTER TABLE `piggy_transaction_details`
  ADD CONSTRAINT `piggy_transaction_details_ibfk_3` FOREIGN KEY (`details_bucket_id`) REFERENCES `piggy_buckets` (`bucket_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `piggy_transaction_details_ibfk_1` FOREIGN KEY (`details_transaction_id`) REFERENCES `piggy_transactions` (`transaction_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `piggy_transaction_details_ibfk_2` FOREIGN KEY (`details_account_id`) REFERENCES `piggy_accounts` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE;
