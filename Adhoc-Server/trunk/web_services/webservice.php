<?php
error_reporting(0);
include('config.php');
include_once(SITE_PATH . 'dbcredentials.php');
include_once(SITE_PATH . 'mysql.php');
include_once(SITE_PATH . 'class-phpass.php');
function subval_sort($a,$subkey)
{
	foreach($a as $k=>$v)
    {
		$b[$k] = strtolower($v[$subkey]);
	}
	arsort($b);
	foreach($b as $key=>$val) {
		$c[] = $a[$key];
	}
	return $c;
}

    if(isset($_POST['json']))
    {
       $json_array=json_decode(stripslashes($_POST['json']));


         /*********************************/
    	// 1) List of all product
    	/*********************************/
    	if($json_array->name == 'get_all_product_list')
    	{
    		echo get_all_product_list($json_array->body);
    	}

         /*********************************/
    	// 2) Category List
    	/*********************************/
    	if($json_array->name == 'get_category_list')
    	{
    		echo get_category_list($json_array->body);
    	}


}

function getSignedURL($resource, $timeout)
{
	//This comes from key pair you generated for cloudfront
    $keyPairId = "APKAIQQXVGYVY3RUOYBQ";

	$expires = time() + $timeout; //Time out in seconds
	$json = '{"Statement":[{"Resource":"'.$resource.'","Condition":{"DateLessThan":{"AWS:EpochTime":'.$expires.'}}}]}';
    //$pem = 'http://'.$_SERVER["HTTP_HOST"].SITE_URL.'pk-APKAIQQXVGYVY3RUOYBQ.pem';
    //Read Cloudfront Private Key Pair
    $fp=fopen("http://121.247.170.47/everydaycomic/pk-APKAIQQXVGYVY3RUOYBQ.pem","r");
	$priv_key=fread($fp,8192);
	fclose($fp);

	//Create the private key
	$key = openssl_get_privatekey($priv_key);
	if(!$key)
	{
		echo "<p>Failed to load private key!</p>";
		return;
	}

	//Sign the policy with the private key
	if(!openssl_sign($json, $signed_policy, $key, OPENSSL_ALGO_SHA1))
	{
		echo '<p>Failed to sign policy: '.openssl_error_string().'</p>';
		return;
	}

	//Create url safe signed policy
	$base64_signed_policy = url_safe_base64_encode($signed_policy);
	$signature = str_replace(array('+','=','/'), array('-','_','~'), $base64_signed_policy);

	//Construct the URL
	$url = $resource.'?Expires='.$expires.'&Signature='.$signature.'&Key-Pair-Id='.$keyPairId;

	return $url;
}

function url_safe_base64_encode($value)
{
    $encoded = base64_encode($value);
    // replace unsafe characters +, = and / with
    // the safe characters -, _ and ~
    return str_replace(
        array('+', '=', '/'),
        array('-', '_', '~'),
        $encoded);
}





function get_all_product_list($body)
{
    $date = $body->SyncDate;

	$query = sprintf("  SELECT p.*,pc.*,c.name,c.id as cid FROM products as p LEFT JOIN product_category as pc ON p.id = pc.product_id LEFT JOIN categories as c ON pc.category_id = c.id WHERE p.created_on >='".$date."' OR p.modified_on >='".$date."' ORDER BY p.id ASC ");

    $res = @mysql_query($query);
    if(@mysql_num_rows($res)){
		 $result = array();
		 $result1 = array();
         while($row = mysql_fetch_assoc($res))
         {
            $result['product_id'] = $row['id'];
            $result['product_name'] = $row['title'];
            $result['category_name'] = $row['name'];
            $result['category_id'] = $row['cid'];
            $result['barcode'] =  $row['barcode'];
            $result['bottle_size'] = $row['size'];
            $result['bottle_weight'] = $row['weight'];
            $result['(1 ml)g for 100 ml weight'] = $row['weightg'];
            $result['date_created'] = $row['created_on'];
            $result['date_modified'] = $row['modified_on'];
            if($row['status'] == 'A'){$result['IsActive'] = 'Yes';} else {$result['IsActive'] = 'No';}
            if($row['deleted'] == '1'){$result['Isdeleted'] = 'Yes';} else {$result['Isdeleted'] = 'No';}
            $result1[] = $result;
         }
        $array = array("status"=>"PRODUCT_LIST_1","message"=>"Product List","data"=>$result1);
	}else{
		$array=array("status"=>"PRODUCT_LIST_0","message"=>"No Product Available");
	}
    return json_encode($array);
}




function get_category_list($body){

	$query = sprintf("SELECT id,name,grms_100ml FROM categories  where status = 'A' ORDER BY id ASC ");
    $res = @mysql_query($query);
    if(@mysql_num_rows($res)){
		 $result = array();
         while($row = mysql_fetch_assoc($res))
         {
            //$row['title'] = utf8_decode($row['title']);
            $result[] = $row;
         }
        $array = array("status"=>"CATEGORY_LIST_1","message"=>"Category List","data"=>$result);
	}
    else
    {
		$array=array("status"=>"CATEGORY_LIST_0","message"=>"No Category Available");
	}
    return json_encode($array);
}



?>