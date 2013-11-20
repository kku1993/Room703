<?php

require_once("../common/util.php");
require_once("../db/db_connection.php");

$jsonpCallback = "itemAdded";

if(!isset($_GET['item_name']) 
  || !isset($_GET['item_price'])
  || !isset($_GET['user_id'])
  || !isset($_GET['room_id']))
  replyJSONP($jsonpCallback, 0, "data fields not set", NULL);

$item_name = $_GET['item_name'];
$item_price = $_GET['item_price'];
$uid = $_GET['user_id'];
$rid = $_GET['room_id'];

$db = new DBConnection();
if(!$db->connect())
  replyJSONP($jsonpCallback, 0, "failed to connect to database", NULL);

$item_id = $db->addItem($item_name, $item_price, $uid, $rid);
if($item_id == NULL)
  replyJSONP($jsonpCallback, 0, "failed to add item into database", NULL);

$db->close();

$data = array("item_id" => $item_id);
replyJSONP($jsonpCallback, 1, "", $data);

?>
