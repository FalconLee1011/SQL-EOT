<?php
    require("./utils.php");
    header("Content-type:text/html;charset=utf-8");
    include_once "db_conn.php";

    $query = ("select order_ID, type_phrase.name as 'take_type', payment_phrase.name as 'payment_method', price_ID, date, table_number
    from _order,type_phrase,payment_phrase 
    where _order.take_type=type_phrase.phrase_ID and _order.payment_method=payment_phrase.phrase_ID");
    
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll();

    echo json_encode($result);