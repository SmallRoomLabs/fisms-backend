#
# owner  |  system    |  msg
# -------+------------+------------
# id     |  id        |  id
# email  |  owner_id  |  system_id
# pw     |  country   |  dt
#        |  phone     |  sender
#        |  token     |  msg
#

DROP DATABASE IF EXISTS fisms;
CREATE DATABASE fisms;
USE fisms;

# The owners of the SMS phones/modems
CREATE TABLE owner (
  id         INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  email      VARCHAR(100) NOT NULL,
  pw         VARCHAR(100) NOT NULL
);
ALTER TABLE owner ADD UNIQUE INDEX ix_email (email);


# The individual SMS phones/modems connected to fisms
CREATE TABLE system (
  id         INT UNSIGNED  NOT NULL AUTO_INCREMENT PRIMARY KEY,
  owner_id   INT NOT NULL,
  country    VARCHAR(20) NOT NULL,
  phone      VARCHAR(20) NOT NULL,
  token      VARCHAR(100) NOT NULL
);
ALTER TABLE system ADD UNIQUE INDEX ix_phone (phone);

# The SMS messages received
CREATE TABLE msg (
  id         INT UNSIGNED  NOT NULL AUTO_INCREMENT PRIMARY KEY,
  system_id  INT NOT NULL,
  dt         DATETIME NOT NULL,
  sender     VARCHAR(100) NOT NULL,
  msg        VARCHAR(200) NOT NULL
);
