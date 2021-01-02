<?php
    require("./utils.php");
    header("Content-type:text/html;charset=utf-8");
    include_once "db_conn.php";

    $query = ("select * from `menu`");
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll();

    echo json_encode($result);
