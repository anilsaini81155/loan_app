
#database name
CREATE DATABASE `my_proj`;


#token_table
CREATE TABLE `token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `mobile_no` varchar(15) NOT NULL,
   `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
   PRIMARY KEY (`id`)
)ENGINE=InnoDB  DEFAULT CHARSET=latin1;


#user_table
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `mobile_no` varchar(15) NOT NULL,
  `is_deleted` enum('True','False') NOT NULL DEFAULT 'False',
   `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
   PRIMARY KEY (`id`)
)ENGINE=InnoDB  DEFAULT CHARSET=latin1;


#loan_table
CREATE TABLE `loan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loan_amount` float(12,4) DEFAULT NULL,
  `loan_tenure` int(10) DEFAULT NULL,
  `loan_status` enum('Running','Closed') NOT NULL DEFAULT 'Running',
   `emi_amount` float(12,4) DEFAULT NULL,
   `user_id` int(11) NOT NULL,
   `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
   PRIMARY KEY (`id`),
   KEY `user_id` (`user_id`),
   CONSTRAINT `user_2` FOREIGN KEY (`user_id`) REFERENCES `los_application` (`id`) ON DELETE CASCADE
)ENGINE=InnoDB  DEFAULT CHARSET=latin1;

#emi_table
CREATE TABLE `emi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emi_amount` float(12,4) DEFAULT NULL,
  `emi_date` date NOT NULL,
  `emi_status` enum('Paid','UnPaid') NOT NULL DEFAULT 'UnPaid',
   `loan_id` int(11) NOT NULL,
   `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
   PRIMARY KEY (`id`),
   KEY `loan_id` (`loan_id`),
   CONSTRAINT `loan_id_2` FOREIGN KEY (`loan_id`) REFERENCES `loan` (`id`) ON DELETE CASCADE
)ENGINE=InnoDB  DEFAULT CHARSET=latin1;
