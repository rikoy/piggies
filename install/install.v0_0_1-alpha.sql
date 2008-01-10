-- phpMyAdmin SQL Dump
-- version 2.10.2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Jan 10, 2008 at 05:04 PM
-- Server version: 5.0.41
-- PHP Version: 5.2.3

-- 
-- Database: `piggy`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `piggy_accounts`
-- 

CREATE TABLE `piggy_accounts` (
  `account_id` int(7) unsigned NOT NULL auto_increment,
  `account_name` varchar(50) NOT NULL,
  PRIMARY KEY  (`account_id`)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `piggy_accounts`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `piggy_buckets`
-- 

CREATE TABLE `piggy_buckets` (
  `bucket_id` int(7) unsigned NOT NULL auto_increment,
  `bucket_type` enum('Income','Expense') NOT NULL,
  `bucket_name` varchar(50) NOT NULL,
  PRIMARY KEY  (`bucket_id`)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `piggy_buckets`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `piggy_sysinfo`
-- 

CREATE TABLE `piggy_sysinfo` (
  `version_number` varchar(15) NOT NULL,
  PRIMARY KEY  (`version_number`)
) TYPE=InnoDB;

-- 
-- Dumping data for table `piggy_sysinfo`
-- 

REPLACE INTO `piggy_sysinfo` VALUES ('0.0.1-alpha');

-- --------------------------------------------------------

-- 
-- Table structure for table `piggy_transactions`
-- 

CREATE TABLE `piggy_transactions` (
  `transaction_id` int(11) unsigned NOT NULL auto_increment,
  `transaction_account_id` int(7) unsigned NOT NULL,
  `transaction_type` enum('Withdrawal','Deposit','Transfer','Bucket') NOT NULL,
  `transaction_payee` varchar(50) NOT NULL,
  `transaction_memo` text NOT NULL,
  `transaction_posted_ts` timestamp NOT NULL,
  `transaction_cleared` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`transaction_id`),
  KEY `FK_account_id` (`transaction_account_id`)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `piggy_transactions`
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


-- --------------------------------------------------------

-- 
-- Table structure for table `piggy_users`
-- 

CREATE TABLE `piggy_users` (
  `user_id` int(7) unsigned NOT NULL auto_increment,
  `user_name` varchar(50) NOT NULL,
  `user_password` varchar(32) NOT NULL,
  `user_first_name` varchar(50) NOT NULL,
  `user_last_name` varchar(50) NOT NULL,
  PRIMARY KEY  (`user_id`)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `piggy_users`
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
