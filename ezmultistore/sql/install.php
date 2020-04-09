<?php


// On utilise ce fichier pour toutes les installations de tables

$sql_requests = [];

$sql_requests[] = 'CREATE TABLE IF NOT EXISTS ' . _DB_PREFIX_ . 'ezmultistore
(
    `id_disii` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `firstname` VARCHAR(128),
    `lastname` VARCHAR(250),
    `birthdate` DATE NULL,
    `gender` INT(1)
)
ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';