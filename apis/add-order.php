<?php
    require("./utils.php");
    header("Content-type:text/html;charset=utf-8");
    include_once "db_conn.php";

    $json = json_decode(file_get_contents('php://input'), true);

    $take_type = $json["take_type"];
    $payment_method = $json["payment_method"];
    $price_ID = "'0'";
    $date = $json["date"];
    $table_number = $json["table_number"];
    $order_name = $json["order_name"];
    $phone_number = $json["phone_number"];
    $order_address = $json["order_address"];
    $remarks = $json["remarks"];
    $items = $json["items"];
    
/*
    $take_type = "delivery";
    $payment_method = "credit_card";
    $price_ID = "'0'";
    $date = "2020-10-20";
    $table_number =null;
    $order_name = "海大資工系";
    $phone_number = "0224622192#660";
    $order_address = "基隆市中正區北寧路二號";
    $remarks = "請將食物放在警衛室即可，謝謝";
    $items = array(
        0=>array("menu_ID"=>"001",
        "remarks"=>"加大"),
        1=>array("menu_ID"=>"005",
        "remarks"=>"米酒多一點"),
        );
*/

    $take_type = "'" . $take_type . "'";
    $payment_method = "'" . $payment_method . "'";
    $date = "'" . $date . "'";
    if(isset($table_number)){ $table_number = "'" . $table_number . "'"; }
    else{ $table_number ="NULL"; }
    if(isset($order_name)){ $order_name = "'" . $order_name . "'"; }
    if(isset($phone_number)){ $phone_number = "'" . $phone_number . "'"; }
    if(isset($order_address)){ $order_address = "'" . $order_address . "'"; }
    if(isset($remarks)){ $remarks = "'" . $remarks . "'"; }


    //var_dump($items);
    
    if($take_type=="'delivery'"){
        //insert delivery_contact
        $query = ("insert into `delivery_contact`
        values(NULL,". $order_name ."," . $phone_number ."," . $order_address ."," . $remarks .")");
        $stmt = $db->prepare($query);
        $stmt->execute();
    
        $delievery_ID = $db->lastInsertId();
        $delievery_ID = "'" . $delievery_ID . "'";
    }
    else{
        $delievery_ID ="NULL";
    }

    //insert order
    $query = ("insert into _order 
    values(NULL,". $take_type ."," . $payment_method .",0," . $date ."," . $table_number ."," . $delievery_ID.")");
    $stmt = $db->prepare($query);
    $stmt->execute();

    $order_ID = $db->lastInsertId();
 
    //insert order_connect
    for($i=0; $i<count($items); $i++){
        $query = ("insert into order_connect 
        values(NULL,'". $order_ID ."','" . $items[$i]['menu_ID'] ."','" . $items[$i]['remarks']."')");
        echo $i.":".$query;
        $stmt = $db->prepare($query);
        $stmt->execute();
    }

    $query = ("select sum(menu.price) as sum
    from order_connect,menu
    where order_connect.menu_ID=menu.menu_ID and order_ID=".$order_ID);
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll();
    $sum = $result[0]['sum'];
    echo "sum:".$sum;

    $query = ("update _order
    set price_ID='".$sum."'
    where order_ID='" . $order_ID . "';");
    $stmt = $db->prepare($query);
    $stmt->execute();

    echo "done";