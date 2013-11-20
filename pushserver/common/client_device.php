<?php
require_once("constants.php");

//generic client device
class ClientDevice{
	public $device_type;
	public $user_id;
  /* string indexed array */
  public $device_info;
	
	public function __construct($type, $id, $info){
		$this->device_type = $type;
		$this->user_id = $id;
    $this->device_info = $info;
	}
}
?>
