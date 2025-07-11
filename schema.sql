-- for complaints table
CREATE TABLE complaints (
  id INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  contact VARCHAR(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  email VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  complaint_type VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  equipment VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  description TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  attachment VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  status VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT 'New',
  admin_notes TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  user_id INT(11) NOT NULL,
  PRIMARY KEY (id)
);

-- for chat option
CREATE TABLE complaint_messages (
  id INT(11) NOT NULL AUTO_INCREMENT,
  complaint_id INT(11) NOT NULL,
  sender_id INT(11) NOT NULL,
  message TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  is_read TINYINT(1) DEFAULT 0,
  PRIMARY KEY (id),
  INDEX (complaint_id),
  INDEX (sender_id)
);


-- for customers table
CREATE TABLE customers (
  id INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  designation VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  contact VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  email VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  address TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  status ENUM('active', 'inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'active',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  saved_signature VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (id),
  INDEX (contact)
);


-- for email logs
CREATE TABLE email_logs (
  id INT(11) NOT NULL AUTO_INCREMENT,
  subject VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  recipients TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  body TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  sent_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  status VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  error TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (id)
);


-- for adding engineers
CREATE TABLE engineers (
  id INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  signature_path VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

-- for adding hospital reminders
CREATE TABLE hospital_reminders (
  id INT(11) NOT NULL AUTO_INCREMENT,
  hospital_name VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  address TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  last_service_date DATE DEFAULT NULL,
  next_due_date DATE DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);


-- for stroing receipts/reports
CREATE TABLE receipts (
  id INT(11) NOT NULL AUTO_INCREMENT,
  client_name VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  phone VARCHAR(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  email VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  service_type VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  engineer VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  description TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  pdf_path VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);


-- for generating auto incrementing report/ receipt numbers
CREATE TABLE report_numbers (
  id INT(11) NOT NULL AUTO_INCREMENT,
  report_no INT(11) NOT NULL,
  PRIMARY KEY (id)
);

-- for creating users
CREATE TABLE users (
  id INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  email VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  password VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  role ENUM('admin', 'user') CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT 'user',
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  reset_token VARCHAR(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  reset_token_expiry DATETIME DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY email (email)
);


