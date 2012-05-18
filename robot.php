<?php

if(!empty($argv[1]) && file_exists($argv[1])){
	$f = fopen($argv[1], "r");
}

$robot = new Robot();
if(empty($f)){
	$f = fopen("php://stdin", "r");
	echo "----------------------\nPlease input following commands\n";
	echo "PLACE 0,0,NORTH\n";
	echo "LEFT\n";
	echo "RIGHT\n";
	echo "MOVE\n";
	echo "REPORT\n";
	echo "EXIT\n----------------------\n";
}
while ($line = fgets($f)){
	if(strstr($line,"EXIT")) exit;
	$line = trim($line);
	$array = array_filter(explode(" ",$line));
	
	if(empty($array)) continue;
	
	switch($array[0]){
		case "PLACE":
			$args = implode($array);
			$args = str_replace("PLACE",'',$args);
			$args = explode(",",$args);
			if(count($args)!=3) {
				echo "Invald Arguments.\n";	
				break;
			}
			$robot->PLACE($args[0],$args[1],$args[2]);	
			break;
		case "LEFT":
			$robot->LEFT();
			break;
		case "RIGHT":
			$robot->RIGHT();
			break;
		case "MOVE":
			$robot->MOVE();
			break;
		case "REPORT":
			$robot->REPORT();
			break;
		case "EXIT":
			exit;
			break;
		default:
			echo "Invalid Command.\n";
			break;
	}
}

class Status{
	const ACTIVE = '1';
	const INACTIVE = '0';
}

class Robot{
	private $x;
	private $y;
	private $f;
	private $status;
	private $FACE;
	private $firstPlacement;
	
	public function __construct(){
		$this->FACE = array(
			0 => "NORTH",
			1 => "EAST",
			2 => "SOUTH",
			3 => "WEST");
		$this->status = Status::INACTIVE;
		$this->firstPlacement = true;
	}
	
	public function PLACE($x,$y,$fString){
	
		
	
		if(!in_array($fString, $this->FACE)) {
			echo "Invald Argument.\n";	
			return;
		}
		
		if($this->firstPlacement && $this->_isOutOfRange($x,$y)){
			echo "Initial placement cannot out of table\N";
			return;
		}
		
		$this->x = $x;
		$this->y = $y;
		
		
		$this->f = array_search($fString, $this->FACE);
				
		if($this->_isOutOfRange($this->x,$this->y)){
			$this->status = Status::INACTIVE;

		}else{
			$this->status = Status::ACTIVE;               
		}
		
		$this->firstPlacement = false;
	}
		
	public function MOVE(){
		if($this->status == Status::INACTIVE){
			echo "WARNING:Command is ignored.\n";
			return;
		}
		$resultArray = $this->_tryToMove($this->x,$this->y,$this->f);
		if(empty($resultArray)){
			echo "WARNING:Robot cannot go further.\n";		
		}else{
			$this->x = $resultArray['x'];
			$this->y = $resultArray['y'];		
		}
	}
		
	public function LEFT(){
		if($this->status == Status::INACTIVE){
			echo "WARNING:Command is ignored.\n";
			return;
		}
		$this->f = $this->f - 1;
		if($this->f < 0){
			$this->f = $this->f + 4;
		}
		  
	}
	
	public function RIGHT(){
		if($this->status == Status::INACTIVE){
			echo "WARNING:Command is ignored.\n";
			return;
		}
		$this->f = $this->f + 1;
		if($this->f > 3){
			$this->f = $this->f - 4;
		}
		
	}
	
	public function REPORT(){
		if($this->status == Status::INACTIVE){
			echo "WARNING:Command is ignored.\n";
			return;
		}
		echo "Output: $this->x,$this->y,".$this->FACE[$this->f]."\n";
	}
	
	private function _isOutOfRange($x,$y){
		if($x>5 || $x<0 || $y>5 || $y<0){
			return true;
		}else{
			return false;
		}
	}
	
	private function _tryToMove($x,$y,$f){
		switch ($f) {
    		case 0:
        		$y++;
        		break;
    		case 1:
        		$x++;
        		break;
    		case 2:
    		    $y--;
     	   		break;
     	   	case 3:
    		    $x--;
     	   		break;
     	   	default:
     	   		break;
		}
		if($this->_isOutOfRange($x,$y))	{
			return array();
		}else{
			return array('x'=>$x,'y'=>$y);
		}	
	}
}

?>