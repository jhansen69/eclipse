<?php
  /*
  *  A nice collection of commonly used functions
  */
  
  
function generate_random_string($length)
{
  $randstr = "";
  for($i=0; $i<$length; $i++){
     $randnum = mt_rand(0,61);
     if($randnum < 10){
        $randstr .= chr($randnum+48);
     }else if($randnum < 36){
        $randstr .= chr($randnum+55);
     }else{
        $randstr .= chr($randnum+61);
     }
  }
  return $randstr;
}
?>
