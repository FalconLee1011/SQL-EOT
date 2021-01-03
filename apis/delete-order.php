<?php
    require("./utils.php");
    header("Content-type:text/html;charset=utf-8");
    include_once "db_conn.php";
    
    $number = $_POST["order_ID"];

    //delete delivery_contact
    $query = ("delete from `delivery_contact` where delievery_ID in (select delievery_ID from `_order` where order_ID=" . $number . ")");
    $stmt = $db->prepare($query);
    $stmt->execute();

    //delete order_connect
    $query = ("delete from `order_connect` where order_ID=".$number);
    $stmt = $db->prepare($query);
    $stmt->execute();

    //delete order
    $query = ("delete from `_order` where order_ID=".$number);
    $stmt = $db->prepare($query);
    $stmt->execute();
   
    echo "done";