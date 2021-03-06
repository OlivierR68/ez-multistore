<?php


$sql_requests = [];

$sql_requests[] = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'ezmultistore_checkout
(
    `customer_id` INT(10) NOT NULL PRIMARY KEY,
    `store_id` INT(10) NOT NULL
)
ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

$sql_requests[] = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'ezmultistore_order
(
    `order_id` INT(10) NOT NULL PRIMARY KEY,
    `store_id` INT(10) NOT NULL,
    `customer_id` INT(10) NOT NULL,
    `address_id` INT(10) NOT NULL
)
ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

$sql_requests[] = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'ezmultistore_store_info
(
    `store_id` INT(10) NOT NULL PRIMARY KEY,
    `information` TEXT
)
ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

$sql_requests[] = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'ezmultistore_employees_stores
(
    `employee_id` INT(10) NOT NULL PRIMARY KEY,
    `store_id_array` VARCHAR(255)
)
ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';