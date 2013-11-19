<?php

/* kku
 *  @comment
 *    accepts request from Android client to register its GCM registration ID
 *  REQUIRES:
 *    GET request containing 'user_id', 'gcm_reg_id', 'user_pw'
 *   ENSURES: returns json object with 'success' = 1 on success, 
 *    'success' = 0 otherwise.
 *    'error' field contains error message if the request failed
 */

require_once "../db/db_connection.php";
require_once "../common/constants.php";
require_once "../common/util.php";

$jsonpCallback = "registerAndroid";

if(!isset($_GET['user_id']) 
    || !isset($_GET['gcm_reg_id']) 
    || !isset($_GET['user_pw'])){
  replyJSONP($jsonpCallback, 0, "data field not set", NULL);
}

$uid = $_GET['user_id'];
$pw = $_GET['user_pw'];
$gcm_reg_id = $_GET['gcm_reg_id'];

$db = new DBConnection();
if(!$db->connect())
  replyJSONP($jsonpCallback, 0, "failed to connect to database", NULL);

if(!$db->verifyPassword($uid, $pw)){
  replyJSONP($jsonpCallback, 0, "wrong password", NULL);
}

//store gcm registration id
$stored = $db->addAndroidDevice($uid, $gcm_reg_id);
if($stored == NULL){
  replyJSONP($jsonpCallback, 0, "failed to add device", NULL);
}

//success
$db->close();
replyJSONP($jsonpCallback, 1, "", NULL);

?>
