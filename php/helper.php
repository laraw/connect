<?php


// check valid number

function checkisNum($val) {
	//$num = (int) $val
	
	if (preg_match('/^\d+$/', $val)
		&&
		strval(intval($val)) == strval($val)
		) {
		return true;
	}
	else
	{
		return false;
	}
}

?>