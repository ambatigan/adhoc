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
    	// 1) Today's offer/product detail
    	/*********************************/
    	if($json_array->name == 'get_today_product')
    	{
    		echo get_today_product($json_array->body);
    	}

         /*********************************/
    	// 2) List of all offers/product
    	/*********************************/
    	if($json_array->name == 'get_all_product_list')
    	{
    		echo get_all_product_list($json_array->body);
    	}

         /*********************************/
    	// 3) Category List
    	/*********************************/
    	if($json_array->name == 'get_category_list')
    	{
    		echo get_category_list($json_array->body);
    	}
         /*********************************/
    	// 4) About Us Page
    	/*********************************/
    	if($json_array->name == 'get_about_us_page')
    	{
    		echo get_about_us_page($json_array->body);
    	}

         /*********************************/
    	// 5) About Us Page
    	/*********************************/
    	if($json_array->name == 'get_contact_us_page')
    	{
    		echo get_contact_us_page($json_array->body);
    	}

        /*********************************/
    	// 6) Select User Categories
    	/*********************************/
    	if($json_array->name=='user_categories')
    	{
    		echo user_categories($json_array->body);
    	}

        /*********************************/
    	// 7) Update User Categories
    	/*********************************/
    	if($json_array->name=='user_categories_update')
    	{
    		echo user_categories_update($json_array->body);
    	}

        /*********************************/
    	// 8) View User Notification
    	/*********************************/
    	if($json_array->name=='user_notification')
    	{
    		echo user_notification($json_array->body);
    	}

        /*********************************/
    	// 9) Update User Notification
    	/*********************************/
    	if($json_array->name=='user_notification_update')
    	{
    		echo user_notification_update($json_array->body);
    	}

        /*********************************/
    	// 10) Select Lauguage Variable
    	/*********************************/
    	if($json_array->name=='language_variable')
    	{
    		echo language_variable($json_array->body);
    	}

        /*********************************/
    	// 11) User Registration
    	/*********************************/
    	if($json_array->name=='user_register')
    	{
    		echo user_register($json_array->body);
    	}

        /*********************************/
    	// 12) Push Notification
    	/*********************************/
    	if($json_array->name=='push_notification')
    	{
    		echo push_notification($json_array->body);
    	}

        /*********************************/
    	// 13) Setting Time
    	/*********************************/
    	if($json_array->name=='setting_time')
    	{
    		echo setting_time($json_array->body);
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

function get_today_product($body)
{

	$query = sprintf("SELECT p.mark_product_for,p.icon_logo,pl.product_id,pl.price,pl.price_with_subtitle,pl.small_free_text,
                                        pl.download_link,pl.product_name,pl.production_house,pl.app_detail_1,pl.app_detail_2,pl.app_detail_3,
                                        pl.image1,pl.image2,pl.image3,pl.image4,pl.image5,pl.share_model_title,pl.share_model_description
                                        FROM products AS p
                                        INNER JOIN products_lng AS pl ON p.id = pl.product_id
                                        WHERE pl.lng_id = 1 AND status = 'A'
                                        ORDER BY p.created_on DESC
                                        LIMIT 1
                    ");
    $res = @mysql_query($query);
    if(@mysql_num_rows($res))
    {
		 $result = array();
         while($row = mysql_fetch_assoc($res))
         {
            if ($row['icon_logo']!=''){$row['icon_logo'] = PRODUCT_IMG_PATH.'icon_logo/'.$row['icon_logo'];}
            if ($row['image1']!=''){$row['image1'] = PRODUCT_IMG_PATH.$row['image1'];}
            if ($row['image2']!=''){$row['image2'] = PRODUCT_IMG_PATH.$row['image2'];}
            if ($row['image3']!=''){$row['image3'] = PRODUCT_IMG_PATH.$row['image3'];}
            if ($row['image4']!=''){$row['image4'] = PRODUCT_IMG_PATH.$row['image4'];}
            if ($row['image5']!=''){$row['image5'] = PRODUCT_IMG_PATH.$row['image5'];}
            $result[] = $row;
         }
        $array = array("status"=>"PRODUCT_LIST_1","message"=>"Product List","data"=>$result);
	}
    else
    {
		$array=array("status"=>"PRODUCT_LIST_0","message"=>"No Product Available");
	}
    return json_encode($array);
}

function get_all_product_list($body){

    //-- Language Id 1 for Dutch language and this recoard is for dutch language only --//

	$query = sprintf("  SELECT p.icon_logo,pl.product_id,pl.price,
                                  pl.download_link,pl.product_name,pl.production_house
                                  FROM products as p
                                  INNER JOIN products_lng AS pl ON p.id = pl.product_id
                                  WHERE pl.lng_id = 1 AND status = 'A'
                                  ORDER BY p.created_on DESC
                                  LIMIT 1, 4
                    ");

/* for testing purpose
	$query = sprintf("  SELECT    p.icon_logo,pl.product_id,pl.price,
                                  pl.download_link,pl.product_name,pl.production_house
                                  FROM products as p
                                  inner join products_lng as pl on p.id = pl.product_id
                                  where pl.lng_id = 1
                                  AND (p.id = 4 OR p.id = 5 OR p.id = 6 OR p.id = 7)
                                  ORDER BY p.created_on DESC
                    ");
*/
    $res = @mysql_query($query);
    if(@mysql_num_rows($res)){
		 $result = array();
         while($row = mysql_fetch_assoc($res))
         {
            if ($row['icon_logo']!=''){$row['icon_logo'] = PRODUCT_IMG_PATH.'icon_logo/'.$row['icon_logo'];}
            if ($row['image1']!=''){$row['image1'] = PRODUCT_IMG_PATH.$row['image1'];}
            if ($row['image2']!=''){$row['image2'] = PRODUCT_IMG_PATH.$row['image2'];}
            if ($row['image3']!=''){$row['image3'] = PRODUCT_IMG_PATH.$row['image3'];}
            if ($row['image4']!=''){$row['image4'] = PRODUCT_IMG_PATH.$row['image4'];}
            if ($row['image5']!=''){$row['image5'] = PRODUCT_IMG_PATH.$row['image5'];}
            $result[] = $row;
         }
        $array = array("status"=>"PRODUCT_LIST_1","message"=>"Product List","data"=>$result);
	}else{
		$array=array("status"=>"PRODUCT_LIST_0","message"=>"No Product Available");
	}
    return json_encode($array);
}

function get_category_list($body)
{
    //-- Language Id 1 for Dutch language and this recoard is for dutch language only --//
	$query = sprintf("SELECT c.id,cl.title FROM categories as c inner join categories_lng as cl on c.id = cl.category_id where cl.lng_id = 1 AND c.status = 'A' ORDER BY cl.title ASC ");
    $res = @mysql_query($query);
    if(@mysql_num_rows($res)){
		 $result = array();
         while($row = mysql_fetch_assoc($res))
         {
         //$row['Category_name'] = $row['title'];
            $row['title'] = utf8_decode($row['title']);
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

function get_about_us_page($body){

    //-- Language Id 1 for Dutch language and this recoard is for dutch language only --//
	$query = sprintf("SELECT title,description FROM cms_lng where cms_id = 1 AND lng_id = 1 ");
    $res = @mysql_query($query);
    if(@mysql_num_rows($res)){
		 $result = array();
         while($row = mysql_fetch_assoc($res))
         {
         $result[] = $row;

         }
        $array = array("status"=>"ABOUT_US_PAGE_1","message"=>"About Us Page","data"=>$result);
	}else{
		$array=array("status"=>"ABOUT_US_PAGE_0","message"=>"No About Us Page Available");
	}
    return json_encode($array);
}

function get_contact_us_page($body){

    //-- Language Id 1 for Dutch language and this recoard is for dutch language only --//
	//$query = sprintf("SELECT title,description FROM cms_lng where cms_id = 2 AND lng_id = 1 ");
    $query = sprintf("select lt.txt from language_texts as lt inner join language_var as lv on lt.lng_var_id = lv.id  where lv.name = 'contact_setting_text_1' OR lv.name = 'contact_setting_text_2' OR lv.name = 'contact_setting_text_3'  ");
    $res = @mysql_query($query);
    if(@mysql_num_rows($res)){
		 $result = array();
         while($row = mysql_fetch_assoc($res))
         {
         $result[] = $row;

         }
        $array = array("status"=>"CONTACT_US_PAGE_1","message"=>"Contact Us Page","data1"=>utf8_encode($result[0]),"data2"=>utf8_encode($result[1]),"data3"=>utf8_encode($result[2]));
	}else{
		$array=array("status"=>"CONTACT_US_PAGE_0","message"=>"No Contact Us Page Available");

	}
    return json_encode($array);
}

function user_categories($body)
{
    $user_id = $body->user_id;
    if($user_id != '')
    {
        $query = sprintf("SELECT categories FROM gcm_users WHERE id= '".$user_id."' ");
        $res = mysql_query($query);
        if(@mysql_num_rows($res))
        {
            $result = array();
            while($row = mysql_fetch_assoc($res))
            {
                $result[] = $row;
            }
            $array = array("status"=>"USER_CATEGORIES_1","message"=>"User all categories","data"=>$result);
        }
        else
        {
           $array=array("status"=>"USER_CATEGORIES_0","message"=>"No Categories for User Available");
        }
	}
    else
    {

        $array=array("status"=>"USER_CATEGORIES_0","message"=>"Enter User ID");
	}
    return json_encode($array);
}

function user_categories_update($body)
{
	    $user_id = $body->user_id;
	    $category_update_id = $body->category_update_id;
        /*$input_category_status = $body->input_category_status;*/

        /*$query = sprintf("select categories from gcm_users where id= '".$user_id."' ");
        $res = mysql_query($query);
        $data = mysql_fetch_row($res);

        $userCategoryArray = explode(',',$data[0]);
        if ($input_category_status==1){
          if (!in_array($category_update_id,$userCategoryArray)){
              $userCategoryArray[] = $category_update_id;
          }
        }else if ($input_category_status==0){
          if (($key = array_search($category_update_id, $userCategoryArray)) !== false) {
              unset($userCategoryArray[$key]);
          }
        } */

        //$key = array_search('', $userCategoryArray);//----- remove null recoards from array -----//
        //unset($userCategoryArray[$key]);
        //return print_r($userCategoryArray);

        //$userCategoryString = implode(',',$userCategoryArray);
        $query = sprintf("UPDATE gcm_users set categories = '".$category_update_id."'  where id= '".$user_id."' ");
        $res = mysql_query($query);

    if($res){
        $array = array("status"=>"USER_CATEGORIES_UPDATE_1","message"=>"User categories updated success","data"=>$result);
	}else{
		$array=array("status"=>"USER_CATEGORIES_UPDATE_1","message"=>"User categories updated unsuccess");
	}
    return json_encode($array);
}

function user_notification($body)
{
     $user_id= $body->user_id;
    $query = sprintf("select notification from gcm_users where id= '".$user_id."' ");
    $res = mysql_query($query);
    if(@mysql_num_rows($res)){
		 $result = array();
         while($row = mysql_fetch_assoc($res))
         {
         $result[] = $row;

         }
        $array = array("status"=>"USER_CATEGORIES_1","message"=>"User all notification","data"=>$result);
	}else{
		$array=array("status"=>"USER_CATEGORIES_0","message"=>"No Notification for user Available");
	}
    return json_encode($array);
}

function user_notification_update($body)
{
	    $user_id= $body->user_id;
        $notification_update_status = $body->notification_update_status;

        $query = sprintf("update gcm_users set notification = '".$notification_update_status."' where id= '".$user_id."' ");


    if(mysql_query($query))
    {
        $array = array("status"=>"USER_CATEGORIES_1","message"=>"Update user notification SUCCESS","data"=>$result);
	}
    else
    {
		$array=array("status"=>"USER_CATEGORIES_0","message"=>"Update user notification NOT SUCCESS");
	}
    return json_encode($array);
}

function language_variable($body)
{
	$language_variable= $body->input_language_variable;
    $query = sprintf("select lt.txt from language_texts as lt inner join language_var as lv on lt.lng_var_id = lv.id  where lv.name = '".$language_variable."' ");
    $res = mysql_query($query);
    if(@mysql_num_rows($res))
    {
        $row = mysql_fetch_assoc($res);
        $row['txt'] = utf8_decode($row['txt']);
        $array = array("status"=>"USER_CATEGORIES_1","message"=>"Output Language Variable","data"=>$row);
	}else{
		$array=array("status"=>"USER_CATEGORIES_0","message"=>"No Language Variable for ".$language_variable);
	}
    return json_encode($array);
}

function user_register($body)
{
	$user_register= $body->input_user_register;

    $query = sprintf("select id from gcm_users where gcm_regid = '".$user_register."' ");
    $res = mysql_query($query);
    $mysqlNumRows = mysql_num_rows($res);
    if ($mysqlNumRows>0){
      if(@mysql_num_rows($res)){
  		 $result = array();
           while($row = mysql_fetch_assoc($res))
           {
               $userRegistrationId = $row['id'];
           }
      }
      $array = array("status"=>"USER_REGISTER_1","message"=>"User already exists","data"=>$userRegistrationId);
    } else{

      $query = sprintf("SELECT * FROM categories");
      $res = mysql_query($query);
      if(@mysql_num_rows($res)){
		 $result = array();
         while($row = mysql_fetch_assoc($res))
         {
            $result[] = $row['id'];
         }
      }
      $categoriesIdString = implode(',',$result);
      if ($user_register!=''){
        $query = sprintf("Insert into gcm_users set gcm_regid = '".$user_register."', categories = '".$categoriesIdString."' ");
        $res = mysql_query($query);
      }
        $userRegistrationId = mysql_insert_id();
        $array = array("status"=>"USER_REGISTER_1","message"=>"Successfully added new","data"=>$userRegistrationId);
    }

    return json_encode($array);
}

function push_notification($body)
{
    $push_notification_msg = $body->push_notification_msg;

    $limit = 1000;
    $message = array("data" => $push_notification_msg);

    $query = sprintf("SELECT * FROM gcm_users");
    $res = mysql_query($query);
    $GcmUsersCnt = mysql_num_rows($res);
    $senttimes = ceil($GcmUsersCnt/$limit);

    $start_limit = 0;
    $end_limit =  $limit;
    $cnt = 0;
    $errcnt=0;
    for($i=0;$i<$senttimes;$i++)
    {
        $regId = array();
        //$GcmUsersList = $objGcmUsers->selectGcmUsers($start_limit, $limit);
        $query = "SELECT gcm_regid FROM gcm_users as g ORDER BY g.id DESC LIMIT ".$start_limit. " , ".$limit;
        $res = mysql_query($query);     //-- Rewrited variable here --//

        $start_limit = $end_limit;
        $end_limit =  $end_limit+$limit;
        // Send Notification messages

        if(@mysql_num_rows($res)){
           $result = array();
           while($row = mysql_fetch_assoc($res))
           {
              $regId[] = $row['gcm_regid'];
           }
        }

        if (!empty($regId))
        {
            $sendnotify = send_notification($regId, $message); //-- Changes in this functions to slove errors --//
            if($sendnotify){
                $cnt++;
            }else{
                $errcnt++;
            }
        }
            return "ddd";
        if($errcnt > 0)
        {   //Display error message
            $commonFunction->Redirect('./?page=push-notification&action=view&msg=1');
            exit;
        }
        if($cnt > 0 && $cnt == $i)
        {   //Display success message
            $commonFunction->Redirect('./?page=push-notification&action=view&msg=2');
            exit;
        }
    }

    if(1==1){ //-- If success, Please add a condition or need to change -- (07/05/2013) --//
        $array = array("status"=>"ABOUT_US_PAGE_1","message"=>"About Us Page","data"=>$result);
	}else{
		$array=array("status"=>"ABOUT_US_PAGE_0","message"=>"No About Us Page Available");
	}

    return json_encode($array);
}

function setting_time($body){

    $query = "select time_in_minuts from settings where id = 1 ";
    $res = @mysql_query($query);
    if(@mysql_num_rows($res)){
		 $result = array();
         while($row = mysql_fetch_assoc($res))
         {
         $result['time_in_minuts'] = $row['time_in_minuts'];

         }
        $array = array("status"=>"SETTING_TIME_1","message"=>"Setting time","setting_time"=>$result['time_in_minuts']);
	}else{
		$array=array("status"=>"SETTING_TIME_0","message"=>"No Setting time Available");

	}
    return json_encode($array);
}

//-- Sending Push Notification --//
function send_notification($registatoin_ids, $message) {

    // Set POST variables
    $url = 'https://android.googleapis.com/gcm/send';

    $fields = array(
        'registration_ids' => $registatoin_ids,
        'data' => $message,
    );

    $headers = array(
        'Authorization: key=' . GOOGLE_API_KEY,
        'Content-Type: application/json'
    );
    // Open connection
    $ch = curl_init();

    // Set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Disabling SSL Certificate support temporarly
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

    // Execute post
    $result = curl_exec($ch);

    if ($result === FALSE) {
        die('Curl failed: ' . curl_error($ch));
    }

    // Close connection
    curl_close($ch);
   echo $result;
}

?>