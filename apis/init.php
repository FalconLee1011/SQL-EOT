<?php

  require("./utils.php");
  header("Content-type:text/html;charset=utf-8");
  include_once "db_conn.php";


  $drop = true;

  echo "Purging table...<br>";
  $db->exec("DROP TABLE IF EXISTS `type_phrase`;");
  $db->exec("DROP TABLE IF EXISTS `payment_phrase`;");
  $db->exec("DROP TABLE IF EXISTS `menu`;");
  $db->exec("DROP TABLE IF EXISTS `delivery_contact`;");
  $db->exec("DROP TABLE IF EXISTS `_order`;");
  $db->exec("DROP TABLE IF EXISTS `order_connect`;");
  echo "Done purging tables<br>";

  echo "Creating table type_phrase...<br>";
  $sql_type_phrase = "CREATE TABLE IF NOT EXISTS type_phrase(
    phrase_ID varchar(20) not null PRIMARY KEY,
    `name` varchar(10) not null
  )";
  $db->exec($sql_type_phrase);

  echo "Creating table payment_phrase...<br>";
  $sql_payment_phrase = "CREATE TABLE IF NOT EXISTS payment_phrase(
    phrase_ID varchar(20) not null primary key,
    `name` varchar(10) not null
  )";
  $db->exec($sql_payment_phrase);

  echo "Creating table menu...<br>";
  $sql_menu = "CREATE TABLE IF NOT EXISTS menu(
    menu_ID INT(3) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    menu_name VARCHAR(10) not null ,
    price INT(4) NOT NULL
    )";
  $db->exec($sql_menu);

  echo "Creating table delivery_contact...<br>";
  $sql_delivery_contact = "CREATE TABLE IF NOT EXISTS delivery_contact(
    delivery_ID int(10)  AUTO_INCREMENT primary key,
    order_name varchar(5),
    phone_number varchar(20),
    order_address varchar(50),
    remarks varchar(150)
  )";   
  $db->exec($sql_delivery_contact);

  echo "Creating table _order...<br>";
  $sql_order = "CREATE TABLE IF NOT EXISTS _order(
    order_ID INT(10) not null  AUTO_INCREMENT primary key,
    take_type VARCHAR(10) not null,
    payment_method VARCHAR(15) not null,
    price_ID  INT(4) not null,
    `date` DATE,
    table_number int(3),
    delivery_ID varchar(10)
    -- foreign key(delivery_ID) references delivery_contact(delivery_ID),
    -- foreign key(order_ID) references type_phrase(phrase_ID),
    -- foreign key(payment_method) references payment_phrase(phrase_ID)
  )"; 
  $db->exec($sql_order);

  echo "Creating table order_connect...<br>";
  $sql_order_connect = "CREATE TABLE IF NOT EXISTS order_connect(
    context_ID int(12) not null AUTO_INCREMENT primary key,
    order_ID int(10) not null,
    menu_ID int(3) not null,
    remarks varchar(150)
    -- foreign key(order_ID) references _order(order_ID),
    -- foreign key(menu_ID) references menu(menu_ID)
  )";
  $db->exec($sql_order_connect);

  echo "Done creating table.<br>";

  if($drop){
    echo "Init table content.<br>";

    echo "Generating payment_phrase table content.<br>";
    $query = (
      "INSERT INTO payment_phrase VALUES ('cash', '現金'), ('credit_card', '信用卡')"
    );
    $stmt = $db->prepare($query);
    $stmt->execute();
  
    echo "Generating type_phrase table content.<br>";
    $query = (
      "INSERT INTO type_phrase VALUES ('take_out', '外帶'), ('delivery', '外送'), ('for_here', '內用')"
    );
    $stmt = $db->prepare($query);
    $stmt->execute();
  }

  $db = null;
?>