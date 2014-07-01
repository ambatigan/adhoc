<?php
function sort_devide($value)
{
	$result=explode(":",$value);
	return $result;
}

function GetLanguageIdByCode($code){
	$query = "SELECT id 
						FROM ".TBL_LANGUAGES."
						WHERE lng_code = '".$code."'";
						
	$result = mysql_query($query);
    $languageid = mysql_fetch_object($result);
	return $languageid->id;
}	
function GetLimitedStr($limit="",$string){
	return substr(strip_tags($string), 0, $limit) . '...'; 
}






    
    
    
    
    
/*
Convert Whole object in to array Recursively 
*/
function RecurArray($obj){ 
 if(is_object($obj)){
    $obj = recurArray((array)$obj);
 }elseif(is_array($obj)){
    foreach($obj as $k=>$v){
      if(is_array($v) || is_object($v))
       $obj[$k] = recurArray((array)$v);
   else
    $obj[$k] = $v;
    } 
 }else{}
 return $obj;
}

/**
 * The letter l (lowercase L) and the number 1
 * have been removed, as they can be mistaken
 * for each other.
 */

function createRandomPassword() {
    $chars = "abcdefghijkmnopqrstuvwxyz023456789";
    srand((double)microtime()*1000000);
    $i = 0;
    $pass = '' ;

    while ($i <= 7) {
        $num = rand() % 33;
        $tmp = substr($chars, $num, 1);
        $pass = $pass . $tmp;
        $i++;
    }
    return $pass;
}
?>