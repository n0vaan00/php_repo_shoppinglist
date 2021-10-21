<?php
require_once 'inc/headers.php';
require_once 'inc/functions.php';

$input =json_decode(file_get_contents('php://input'));
$input2 =json_decode(file_get_contents('php://input'));
$description = filter_var($input->description,FILTER_SANITIZE_STRING);
$amount = filter_var($input2->amount,FILTER_SANITIZE_STRING);

 try {
    $db = openDb();
    
    $query = $db->prepare('insert into item(description, amount) values (:description, :amount)');
    $query->bindValue(':description',$description,PDO::PARAM_STR);
    $query->bindValue(':amount',$amount);
    $query->execute();

    header('HTTP/1.1 200 OK');
    $data = array('id' => $db->lastInsertId(),'description' => $description,'amount' => $amount);
    print json_encode($data);
 } catch (PDOException $pdoex) {
    returnError($pdoex);
}