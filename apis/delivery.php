<?php
    require("./utils.php");
    header("Content-type:text/html;charset=utf-8");
    include_once "db_conn.php";

    $number = $_POST["order_ID"];
    $query = ("select order_name,phone_number,order_address,remarks
    from _order natural join delivery_contact
    where _order.order_ID=".$number);
    
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll();

    echo json_encode($result);