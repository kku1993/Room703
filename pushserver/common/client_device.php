<?php
require_once "constants.php";

//generic client device
class ClientDevice{
	public final int $device_type;
	public final int $uid;
  /* string indexed array */
  public final $device_info;
	
	public function __construct($type, $id, $info){
		$this->device_type = $type;
		$this->uid = $uid;
    $this->device_info = $info;
	}
}
?>
