<?php

// check valid number

function checkisNum(int $val) {
	$num = (int) $val;
	if (preg_match('/^\d+$/', $num)
		&&
		strval(intval($num)) == strval($num)
		) {
		return true;
	}
	else
	{
		return false;
	}
}

?>