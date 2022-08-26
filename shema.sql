DROP DATABASE IF EXISTS task_force;

CREATE DATABASE task_force
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_general_ci;

USE task_force;

CREATE TABLE category (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(32) NOT NULL,

  UNIQUE INDEX category_name (name)
);

CREATE TABLE location (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  city VARCHAR(100),
  lat DECIMAL (9, 6) NOT NULL,
  lng DECIMAL (9, 6) NOT NULL
);

CREATE TABLE user (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  is_customer TINYINT(1) DEFAULT 0,
  raiting TINYINT DEFAULT NULL,
  email VARCHAR(64) NOT NULL,
  login VARCHAR(64) NOT NULL,
  password CHAR(128) NOT NULL,
  avatar VARCHAR(128) NULL DEFAULT NULL,
  date_of_birth TIMESTAMP DEFAULT NULL,
  phone CHAR(11) NULL DEFAULT NULL,
  telegram CHAR(64) NULL DEFAULT NULL,
  location_id INT(11) NULL DEFAULT NULL,

  UNIQUE INDEX user_email (email),
  UNIQUE INDEX user_login (login),

  FOREIGN KEY (location_id) REFERENCES location(id)
);

CREATE TABLE task (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  user_id INT(11),
  category_id INT(11),
  status ENUM('new', 'cencelled', 'in_progress', 'done', 'failed') DEFAULT 'new',
  title VARCHAR(500) NOT NULL,
  text VARCHAR(1000) NOT NULL,
  budget DECIMAL(10, 2) UNSIGNED NULL DEFAULT NULL,
  deadline TIMESTAMP DEFAULT NULL,

  FULLTEXT INDEX post_text (title, text),

  FOREIGN KEY (user_id) REFERENCES user(id) ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (category_id) REFERENCES category(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE user_category (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  user_id INT(11),
  category_id INT(11),

  FOREIGN KEY (user_id) REFERENCES user(id) ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (category_id) REFERENCES category(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE response (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  task_id INT(11),
  user_id INT(11),

  FOREIGN KEY (user_id) REFERENCES user(id) ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (task_id) REFERENCES task(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE feedback (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  task_id INT(11),
  mark TINYINT NOT NULL,
  text VARCHAR(500) NULL DEFAULT NULL,

  FOREIGN KEY (task_id) REFERENCES task(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE file (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  task_id INT(11),
  user_id INT(11),
  url VARCHAR(128) NOT NULL,

  FOREIGN KEY (user_id) REFERENCES user(id) ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (task_id) REFERENCES task(id) ON UPDATE CASCADE ON DELETE CASCADE
);
