<?php
    require("./utils.php");
    include_once "db_conn.php";

    $name = $_POST["menu_name"];
    $price = $_POST["price"];

    $query = ("update `menu` set menu_name=?, price=? where menu_name=?");
    $stmt = $db->prepare($query);
    $result = $stmt->execute(array($menu_name, $price, $menu_name));
    header("Location:inform.php");
    
    echo json_encode($result);