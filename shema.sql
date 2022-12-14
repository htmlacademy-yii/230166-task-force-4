DROP DATABASE IF EXISTS taskforce;

CREATE DATABASE taskforce
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_general_ci;

USE taskforce;

CREATE TABLE category (
  id INT AUTO_INCREMENT PRIMARY KEY,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  name VARCHAR(40) NOT NULL,
  label VARCHAR(40) NOT NULL,

  UNIQUE INDEX category_name (name)
);

CREATE TABLE city (
  id INT AUTO_INCREMENT PRIMARY KEY,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  name VARCHAR(100) NOT NULL,
  lat DECIMAL(9, 6) NULL,
  lng DECIMAL(9, 6) NULL
);

CREATE TABLE user (
  id INT AUTO_INCREMENT PRIMARY KEY,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  role ENUM ('customer', 'executor') DEFAULT 'executor',
  rating FLOAT DEFAULT 0,
  count_feedbacks INT DEFAULT 0,
  count_failed_tasks INT DEFAULT 0,
  email VARCHAR(40) NOT NULL,
  name VARCHAR(40) NOT NULL,
  password CHAR(200) NOT NULL,
  avatar TEXT NULL DEFAULT NULL,
  date_of_birth DATE DEFAULT NULL,
  phone CHAR(11) NULL DEFAULT NULL,
  telegram VARCHAR(64) NULL DEFAULT NULL,
  description VARCHAR(1000) NULL DEFAULT NULL,
  city_id INT DEFAULT NULL,

  UNIQUE INDEX user_email (email),

  FOREIGN KEY (city_id) REFERENCES city(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE task (
  id INT AUTO_INCREMENT PRIMARY KEY,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  status ENUM('new', 'cencelled', 'inprogress', 'done', 'failed') DEFAULT 'new',
  title VARCHAR(100) NOT NULL,
  text VARCHAR(1000) NOT NULL,
  price INT UNSIGNED DEFAULT 0,
  deadline TIMESTAMP DEFAULT NULL,
  customer_id INT NOT NULL,
  executor_id INT NULL DEFAULT NULL,
  category_id INT NOT NULL,
  location VARCHAR(1000) NULL DEFAULT NULL,
  lat DECIMAL(9, 6) NULL DEFAULT NULL,
  lng DECIMAL(9, 6) NULL DEFAULT NULL,

  FULLTEXT INDEX post_text (title, text),

  FOREIGN KEY (customer_id) REFERENCES user(id) ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (executor_id) REFERENCES user(id) ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (category_id) REFERENCES category(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE user_category (
  id INT AUTO_INCREMENT PRIMARY KEY,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  user_id INT,
  category_id INT,

  FOREIGN KEY (user_id) REFERENCES user(id) ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (category_id) REFERENCES category(id) ON UPDATE CASCADE ON DELETE CASCADE,

  CONSTRAINT user_category UNIQUE (user_id, category_id)
);

CREATE TABLE response (
  id INT AUTO_INCREMENT PRIMARY KEY,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  task_id INT NOT NULL,
  executor_id INT NOT NULL,
  status ENUM('new', 'refuse', 'aprove') DEFAULT 'new',
  message VARCHAR(500) NOT NULL,
  price INT UNSIGNED DEFAULT NULL,

  FOREIGN KEY (task_id) REFERENCES task(id) ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (executor_id) REFERENCES user(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE feedback (
  id INT AUTO_INCREMENT PRIMARY KEY,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  task_id INT NOT NULL,
  customer_id INT NULL DEFAULT NULL,
  executor_id INT NULL DEFAULT NULL,
  rating TINYINT NOT NULL,
  message VARCHAR(500) NULL DEFAULT NULL,

  FOREIGN KEY (task_id) REFERENCES task(id) ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (customer_id) REFERENCES user(id) ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (executor_id) REFERENCES user(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE file (
  id INT AUTO_INCREMENT PRIMARY KEY,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  task_id INT,
  url VARCHAR(40) NOT NULL,
  name TEXT NULL DEFAULT NULL,
  size INT DEFAULT NULL,

  FOREIGN KEY (task_id) REFERENCES task(id) ON UPDATE CASCADE ON DELETE CASCADE
);
