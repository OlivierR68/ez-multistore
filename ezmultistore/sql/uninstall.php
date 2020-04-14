<?php

$sql_requests = [];

$sql_requests[] = 'DROP TABLE ' . _DB_PREFIX_ . 'ezmultistore_checkout';
$sql_requests[] = 'DROP TABLE ' . _DB_PREFIX_ . 'ezmultistore_order';
$sql_requests[] = 'DROP TABLE ' . _DB_PREFIX_ . 'ezmultistore_store_info';
$sql_requests[] = 'DROP TABLE ' . _DB_PREFIX_ . 'ezmultistore_employees_stores';
