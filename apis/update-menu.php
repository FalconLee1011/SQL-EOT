<?php
    require("./utils.php");
    include_once "db_conn.php";

    $id = $_POST["menu_id"];
    $name = $_POST["menu_name"];
    $price = $_POST["price"];

    $query = ("update `menu` set menu_name=?, price=? where menu_ID=?");
    $stmt = $db->prepare($query);
    $result = $stmt->execute(array($name, $price, $id));
    
    echo json_encode($result);