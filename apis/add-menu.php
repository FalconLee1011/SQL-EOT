<?php
    require("./utils.php");
    include_once "db_conn.php";

    $name = $_POST["menu_name"];
    $price = $_POST["price"];

    $query = ("insert into `menu` values(?, ?, ?)");
    $stmt = $db->prepare($query);
    $result = $stmt->execute(array(NULL, $name, $price));

    echo json_encode($result);