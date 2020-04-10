<?php

$sql_requests = [];

$sql_requests[] = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'ezmultistore_checkout
(
    `customer_id` INT(10) NOT NULL PRIMARY KEY,
    `store_id` INT(10) NOT NULL
)
ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';