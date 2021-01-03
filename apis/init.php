<?php

require("./utils.php");
header("Content-type:text/html;charset=utf-8");
include_once "db_conn.php";

$sql_type_phrase = "CREATE TABLE type_phrase(
  phrase_ID varchar(20) not null PRIMARY KEY,
  _name varchar(10) not null
)";
$db->exec($sql_type_phrase);

$sql_payment_phrase = "CREATE TABLE payment_phrase(
  phrase_ID varchar(20) not null primary key,
  _name varchar(10) not null
)";
$db->exec($sql_payment_phrase);

$sql_memu = "CREATE TABLE memu(
  memu_ID INT(3) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  memu_name VARCHAR(10) not null ,
  price INT(4) NOT NULL
  )";
$db->exec($sql_memu);

$sql_delivery_contact = "CREATE TABLE delivery_contact(
  delivery_ID int(10)  AUTO_INCREMENT primary key,
  order_name varchar(5),
  phone_number varchar(20),
  order_address varchar(50),
  remarks varchar(150)
)";   
$db->exec($sql_delivery_contact);

$sql_order = "CREATE TABLE _order(
  order_ID INT(10) not null  AUTO_INCREMENT primary key,
  take_type VARCHAR(10) not null,
  payment_method VARCHAR(15) not null,
  price_ID  INT(4) not null,
  date_ varchar(10) not null,
  table_number int(3),
  delivery_ID varchar(10)
  -- foreign key(delivery_ID) references delivery_contact(delivery_ID),
  -- foreign key(order_ID) references type_phrase(phrase_ID),
  -- foreign key(payment_method) references payment_phrase(phrase_ID)
)"; 
$db->exec($sql_order);

$sql_order_connect = "CREATE TABLE order_connect(
  context_ID int(12) not null AUTO_INCREMENT primary key,
  order_ID int(10) not null,
  memu_ID int(3) not null,
  remarks varchar(150)
  -- foreign key(order_ID) references _order(order_ID),
  -- foreign key(memu_ID) references memu(memu_ID)
)";
$db->exec($sql_order_connect);


$db = null;
?>