-- Extended Church Management System Database Schema
-- This extends the existing churchdb with multi-branch support and additional features

-- Adding branch management tables
CREATE TABLE `branches` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `branch_name` VARCHAR(255) NOT NULL,
  `branch_code` VARCHAR(50) NOT NULL UNIQUE,
  `location` VARCHAR(255) NOT NULL,
  `address` TEXT,
  `phone` VARCHAR(20),
  `email` VARCHAR(100),
  `pastor_name` VARCHAR(255),
  `established_date` DATE,
  `is_headquarters` TINYINT(1) DEFAULT 0,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);

-- Adding worship team management
CREATE TABLE `worship_teams` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `team_name` VARCHAR(255) NOT NULL,
  `branch_id` INT(11) NOT NULL,
  `description` TEXT,
  `team_leader` INT(11),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`) ON DELETE CASCADE
);

-- Extending member management for branches
ALTER TABLE `tblchristian` 
ADD COLUMN `branch_id` INT(11) DEFAULT NULL AFTER `ID`,
ADD FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`) ON DELETE SET NULL;

-- Adding equipment and property management
CREATE TABLE `church_equipment` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `item_name` VARCHAR(255) NOT NULL,
  `item_code` VARCHAR(50) UNIQUE,
  `category` VARCHAR(100),
  `description` TEXT,
  `purchase_date` DATE,
  `purchase_cost` DECIMAL(10,2),
  `current_value` DECIMAL(10,2),
  `branch_id` INT(11),
  `status` ENUM('working', 'broken', 'needs_repair', 'disposed') DEFAULT 'working',
  `assigned_to` INT(11), -- Reference to member ID if assigned to someone
  `location` VARCHAR(255),
  `maintenance_schedule` TEXT,
  `last_maintenance_date` DATE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`) ON DELETE SET NULL
);

-- Adding event management
CREATE TABLE `church_events` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `event_name` VARCHAR(255) NOT NULL,
  `event_description` TEXT,
  `branch_id` INT(11),
  `event_type` VARCHAR(100),
  `start_date` DATETIME NOT NULL,
  `end_date` DATETIME,
  `location` VARCHAR(255),
  `organizer` INT(11), -- Reference to admin ID
  `max_attendees` INT(11),
  `registration_required` TINYINT(1) DEFAULT 0,
  `status` ENUM('planned', 'ongoing', 'completed', 'cancelled') DEFAULT 'planned',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`) ON DELETE SET NULL
);

-- Event registrations
CREATE TABLE `event_registrations` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `event_id` INT(11) NOT NULL,
  `member_id` INT(11),
  `guest_name` VARCHAR(255),
  `guest_email` VARCHAR(100),
  `guest_phone` VARCHAR(20),
  `registration_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `attendance_status` ENUM('registered', 'attended', 'absent') DEFAULT 'registered',
  `notes` TEXT,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`event_id`) REFERENCES `church_events`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`member_id`) REFERENCES `tblchristian`(`ID`) ON DELETE SET NULL
);

-- Adding donation tracking
CREATE TABLE `donations` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `donor_name` VARCHAR(255) NOT NULL,
  `donor_email` VARCHAR(100),
  `donor_phone` VARCHAR(20),
  `donor_member_id` INT(11), -- If donor is a church member
  `branch_id` INT(11),
  `amount` DECIMAL(10,2) NOT NULL,
  `currency` VARCHAR(10) DEFAULT 'USD',
  `donation_type` VARCHAR(100), -- tithe, offering, donation, etc.
  `payment_method` VARCHAR(50), -- cash, check, credit card, bank transfer
  `donation_date` DATE NOT NULL,
  `receipt_number` VARCHAR(50) UNIQUE,
  `notes` TEXT,
  `created_by` INT(11), -- Admin who recorded the donation
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`donor_member_id`) REFERENCES `tblchristian`(`ID`) ON DELETE SET NULL,
  FOREIGN KEY (`created_by`) REFERENCES `tbladmin`(`ID`) ON DELETE SET NULL
);

-- Adding communication tools
CREATE TABLE `messages` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `sender_id` INT(11), -- Admin who sent the message
  `recipient_type` ENUM('all', 'branch', 'group', 'individual'),
  `recipient_branch_id` INT(11), -- If sent to a specific branch
  `recipient_group_id` INT(11), -- If sent to a specific group
  `recipient_member_id` INT(11), -- If sent to an individual member
  `subject` VARCHAR(255),
  `message_body` TEXT,
  `attachment_path` VARCHAR(255),
  `send_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `status` ENUM('draft', 'sent', 'failed') DEFAULT 'draft',
  `priority` ENUM('low', 'normal', 'high') DEFAULT 'normal',
  PRIMARY KEY (`id`),
  FOREIGN KEY (`sender_id`) REFERENCES `tbladmin`(`ID`) ON DELETE SET NULL,
  FOREIGN KEY (`recipient_branch_id`) REFERENCES `branches`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`recipient_member_id`) REFERENCES `tblchristian`(`ID`) ON DELETE SET NULL
);

-- Message recipients tracking
CREATE TABLE `message_recipients` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `message_id` INT(11) NOT NULL,
  `recipient_member_id` INT(11),
  `recipient_admin_id` INT(11), -- For admin to admin messages
  `read_status` TINYINT(1) DEFAULT 0,
  `read_date` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`message_id`) REFERENCES `messages`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`recipient_member_id`) REFERENCES `tblchristian`(`ID`) ON DELETE SET NULL,
  FOREIGN KEY (`recipient_admin_id`) REFERENCES `tbladmin`(`ID`) ON DELETE SET NULL
);

-- Adding branch-specific permissions
ALTER TABLE `tbladmin` 
ADD COLUMN `branch_id` INT(11) DEFAULT NULL AFTER `ID`,
ADD FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`) ON DELETE SET NULL;

-- Insert default headquarters branch
INSERT INTO `branches` (`branch_name`, `branch_code`, `location`, `is_headquarters`, `status`) 
VALUES ('Headquarters', 'HQ001', 'Main Campus', 1, 'active');

-- Update existing admins to belong to headquarters
UPDATE `tbladmin` SET `branch_id` = (SELECT `id` FROM `branches` WHERE `is_headquarters` = 1 LIMIT 1);

-- Insert sample branches
INSERT INTO `branches` (`branch_name`, `branch_code`, `location`, `status`) 
VALUES 
('Downtown Branch', 'DT001', 'Downtown Area', 'active'),
('Westside Branch', 'WS001', 'Westside District', 'active'),
('North Branch', 'NB001', 'Northern Region', 'active');

-- Sample worship teams
INSERT INTO `worship_teams` (`team_name`, `branch_id`, `description`) 
VALUES 
('Main Choir', 1, 'Primary worship choir'),
('Youth Band', 1, 'Youth worship team'),
('Downtown Praise Team', 2, 'Downtown branch worship team');

-- Sample equipment
INSERT INTO `church_equipment` (`item_name`, `item_code`, `category`, `branch_id`, `status`) 
VALUES 
('Yamaha Keyboard', 'EQ001', 'Musical Instrument', 1, 'working'),
('Sound Mixing Console', 'EQ002', 'Audio Equipment', 1, 'working'),
('Projector', 'EQ003', 'AV Equipment', 2, 'needs_repair');

-- Sample events
INSERT INTO `church_events` (`event_name`, `branch_id`, `event_type`, `start_date`, `status`) 
VALUES 
('Sunday Service', 1, 'Regular Service', '2023-06-04 09:00:00', 'planned'),
('Youth Camp', 1, 'Camp', '2023-07-15 08:00:00', 'planned'),
('Bible Study', 2, 'Study Group', '2023-06-05 19:00:00', 'planned');