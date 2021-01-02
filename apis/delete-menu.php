<?php
    require("./utils.php");
    header("Content-type:text/html;charset=utf-8");
    include_once "db_conn.php";

    $number = $_POST["menu_ID"];;

    //delete menu
    $query = ("delete from `menu` where menu_ID in (select menu_ID from `menu` where menu_ID=" . $number . ")");
    $stmt = $db->prepare($query);
    $stmt->execute();

    echo "done";