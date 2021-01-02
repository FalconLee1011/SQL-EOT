<?php
    require("./utils.php");
    header("Content-type:text/html;charset=utf-8");
    include_once "db_conn.php";

    $number = $_POST["order_ID"];
    $query = ("select context_ID,menu.menu_name,menu.menu_ID,price,remarks
    from order_connect,menu
    where order_connect.menu_ID=menu.menu_ID and order_connect.order_ID=".$number);
    
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll();

    echo json_encode($result);