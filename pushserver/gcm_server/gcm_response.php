<?php
class GCMResponse{
  public $success;
  public $error_msg;

  public function __construct($success, $msg){
    $this->success = $success;
    $this->error_msg = $msg;
  }
}
?>
