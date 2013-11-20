<?php
if(isset($_POST["message"]) && isset($_POST["gcm_reg_id"])){
  require_once("gcm_handler.php");

  echo "Sending message...<br/>";

  $gcm = new GCMHandler();

  $gcm_reg_id = array($_POST["gcm_reg_id"]);
  $data = array(
    "message" => $_POST["message"],
    "title" => "Room 703",
    "test_id" => 123
  );

  $gcm->sendNotification($gcm_reg_id, $data);

  echo "Notification sent.<br/>";

  exit(0);
}

else{
  echo "
    <form action='gcm_debug.php' method='POST'>
      GCM Registration ID:<br/>
      <textarea name='gcm_reg_id' rows='3' cols='80'></textarea><br/>
      <br/>
      Enter message:<br/>
      <textarea name='message' rows='7' cols='80'></textarea><br/>
      <button type'submit'>Send</button>
    </form>
  ";
}
?>
