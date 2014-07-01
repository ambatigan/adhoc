<?php 
include('config.php');
include_once(SITE_PATH . 'services/dbcredentials.php');
include_once(SITE_PATH . 'services/mysql.php');
include_once(SITE_PATH . 'include/table_vars.php');

function subval_sort($a,$subkey)
{
    foreach($a as $k=>$v) {
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
        //echo "JSON body: "; print_r($_POST['json']); exit;
	//$json_array = json_decode($_POST['json']);
	$json_array = json_decode(stripslashes($_POST['json']));
        
	/*********************************/
	// Login
	/*********************************/
	if($json_array->name == 'login')
	{
		echo login($json_array->body);
	}

	/*********************************/
	// Register
	/*********************************/
	if($json_array->name == 'register')
	{
		echo register($json_array->body);
	}

	// Greetings List
	/*********************************/
	if($json_array->name == 'greetings_list')
	{
		echo greetings_list($json_array->body);
	}

        // Greeting Detail
	/*********************************/
	if($json_array->name == 'greeting_detail')
	{
		echo greeting_detail($json_array->body);
	}

        // Add/Edit Greeting
	/*********************************/
	if($json_array->name == 'add_edit_greeting')
	{
		echo add_edit_greeting($json_array->body);
	}

        // Update Greeting Status
	/*********************************/
	if($json_array->name == 'update_greeting_status')
	{
		echo update_greeting_status($json_array->body);
	}

        // Add loved ones
	/*********************************/
	if($json_array->name == 'add_loved_ones')
	{
		echo add_loved_ones($json_array->body);
	}

        // Delete Greeting
	/*********************************/
	if($json_array->name == 'delete_greeting')
	{
		echo delete_greeting($json_array->body);
	}

        // Greeting
	/*********************************/
	if($json_array->name == 'greeting')
	{
		echo greeting($json_array->body);
	}

        // Greeting Android
	/*********************************/
        if($json_array->name == 'greeting_android')
	{
		echo greeting_android($json_array->body);
	}

        // Update Credit
	/*********************************/
	if($json_array->name == 'update_credit')
	{
		echo update_credit($json_array->body);
	}

        // Get User Messages
	/*********************************/
	if($json_array->name == 'get_user_messages')
	{
		echo get_user_messages($json_array->body);
	}
        
        //Delete Message from Inbox
	/*********************************/
	if($json_array->name == 'delete_user_message')
	{
		echo delete_user_message($json_array->body);
	}

        // Get active greetings
	/*********************************/
	if($json_array->name == 'get_active_greetings')
	{
		echo get_active_greetings($json_array->body);
	}
        
        // Forgot Password
	/*********************************/
	if($json_array->name == 'forgot_password')
	{
		echo forgot_password($json_array->body);
	}
        
       // Change Password
       if($json_array->name == 'change_password')
	{
		echo change_password($json_array->body);
	}
}
 
function login($body){
	$login_query = "SELECT id,country_code,phone FROM ".TBL_USERS." WHERE (user_name = '".mysql_real_escape_string($body->username)."' OR email = '".mysql_real_escape_string($body->username)."') and password = '".mysql_real_escape_string(md5($body->password))."' and type='front' and status='Active' AND deleted=0";
        //echo $login_query; exit;
        $login_query_result = mysql_query($login_query);

	if(mysql_num_rows($login_query_result)> 0){
		$data = mysql_fetch_assoc($login_query_result);
                $array = array("status"=>"GREETING_APP_LOGIN_1","message"=>"Login Successfull.",'user_id'=>$data['id'],'country_code'=>$data['country_code'],'phone_number'=>$data['phone']);
                return json_encode($array);
	}else{
		$array = array("status"=>"GREETING_APP_LOGIN_0","message"=>"Invalid username or password.");
                return json_encode($array);
	}
}
 
function register($body){
        //print_r($body); exit;
        $user_name = $body->user_name;
        $user_name_2 = $body->user_name;
        $email = $body->email;
        $password = $body->password;
        $country_code = $body->country_code;
        $phone_number = $body->phone_number;
        $display_name = htmlspecialchars($body->display_name);
        $user_number = $country_code.$phone_number;

        if(strlen($user_name)<6 || strlen($user_name)>25){
            $array = array("status"=>"REGISTER_0","message"=>"Username should be atleast 6 characters and atmost 25 characters.");
            return json_encode($array);
        }

        if(preg_match('/\s/',$user_name)==1){
            $array = array("status"=>"REGISTER_0","message"=>"Please enter username without space.");
            return json_encode($array);
        }

        if(check_username_exists($user_name)==true)
        {
            $array = array("status"=>"REGISTER_0","message"=>"Username already exists. Please try another username.");
            return json_encode($array);
        }

        /*if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)) {
            $array = array("status"=>"REGISTER_0","message"=>"Please enter a valid email.");
            return json_encode($array);
        }*/

        if($country_code==''){
            $country_code = 1;
        }


        if(check_email_exists($email)==true)
        {
            $array = array("status"=>"REGISTER_0","message"=>"Email already exists. Please try another email.");
            return json_encode($array);
        }

        if(strlen($password)<6 || strlen($password)>25){
            $array = array("status"=>"REGISTER_0","message"=>"Password should be atleast 6 characters and atmost 25 characters.");
            return json_encode($array);
        }

        if(check_phone_exists($phone_number)==true)
        {
            $array = array("status"=>"REGISTER_0","message"=>"Phone number already exists. Please try another phone number.");
            return json_encode($array);
        }
       
        //User will be inactive by default. User will be active after SMS is sent via API and user clicks on the link to confirm registration
        $query = sprintf("INSERT INTO ".TBL_USERS ." (type,user_name,display_name,email,password,country_code,phone,status,created_on) VALUES ('front','".$user_name."','".$display_name."','".$email."','".md5($password)."','".$country_code."','".$phone_number."','Inactive','".date('Y-m-d H:i:s')."')");
        //echo $query; exit;
        
        if(mysql_query($query)){
            $user_id = mysql_insert_id();
            $userID = $user_id; 
            //$userID = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5(ENC_KEY), $user_id, MCRYPT_MODE_CBC, md5(ENC_KEY))); 
            //echo $userID; exit; 
            
            $confirmation_link = 'Please click on below link'; 
            $confirmation_link.= '<br />';
            $confirmation_link.= 'http://'.$_SERVER["SERVER_NAME"].SITE_URL.'?page=confirm_registration&id='.$userID;
            
            //echo $confirmation_link; exit; 
            //$message = "Dear $user_name_2, Thank you for your registration with Greeting App! Please click this link ".$_SERVER["SERVER_NAME"].SITE_URL.'?page=confirm_registration&id='.$userID." to confirm your registration.";
            $message = $confirmation_link;
            //echo $message; exit;
            /**** Send SMS with confirmation link ****/
            require TWILIO_LIBRARY_PATH."Services/Twilio.php"; 
            $AccountSid = TWILIO_AccountSid; 
            $AuthToken = TWILIO_AuthToken; 
              
            $client = new Services_Twilio($AccountSid, $AuthToken);
            $sms = $client->account->sms_messages->create(
                    // 'From' number below is valid Twilio number 
                    // that you've purchased, or the (deprecated) Sandbox number
                    "+1914-873-4892",
                    // the number we are sending to
                    "+".$phone_number,
                    // the sms body
                    $message 
            );
            
            $array=array("status"=>"REGISTER_1","message"=>"Sms will be sent, click on link and login.","user_id"=>$user_id);
            return json_encode($array);
    }
} 

function check_username_exists($user_name) {
    $query = "SELECT id FROM ".TBL_USERS ." WHERE user_name = '" . mysql_real_escape_string($user_name) . "' AND deleted = 0";
    //echo $query; exit;
    $getUserId = mysql_query($query);

    if(mysql_num_rows($getUserId)> 0){
        return true;
    }
    else{
        return false;
    }
}

function check_email_exists($email) {
    $query = "SELECT id FROM ".TBL_USERS ." WHERE email = '" . $email . "' AND deleted = 0";
    //echo $query; exit;
    $getUserId = mysql_query($query);

    if(mysql_num_rows($getUserId)> 0){
        return true;
    }
    else{
        return false;
    }
}
 
function check_phone_exists($phone_number) {
    $query = "SELECT id FROM ".TBL_USERS ." WHERE phone = '" . $phone_number . "' AND deleted = 0";
    //echo $query; exit;
    $getPhone = mysql_query($query);

    if(mysql_num_rows($getPhone)> 0){
        return true;
    }
    else{
        return false;
    }
}

function greetings_list($body){
    //If type is loved ones then fetch from user_greetings table
    if($body->type == 1 || $body->type == 2){
        $greeting_list_query = "SELECT id,title,status,phone_number FROM ".TBL_ADMIN_GREETINGS." WHERE deleted = 0 AND type = ".$body->type;
    }
    else{
        $greeting_list_query = "SELECT id,title,status,phone_number FROM ".TBL_USER_GREETINGS." WHERE deleted = 0 AND type = ".$body->type." AND user_id = ".$body->user_id;
    }

    //echo $greeting_list_query; exit;

    $greeting_list = mysql_query($greeting_list_query);

    if(mysql_num_rows($greeting_list)> 0){
        $obj_rows = array();
        $i = 0;
        while($obj_row = mysql_fetch_object($greeting_list))
        {
            $obj_rows[$i]['id'] = $obj_row->id;
            $obj_rows[$i]['title'] = $obj_row->title;

            //If user has added greeting then fetch status from user_greetings table else fetch it from admin_greetings table
            $is_greeting_added = "SELECT id,status FROM ".TBL_USER_GREETINGS ." WHERE deleted = 0 AND user_id = ".$body->user_id." AND greeting_id = ".$obj_row->id;
            $user_greeting = mysql_query($is_greeting_added);

            if(mysql_num_rows($user_greeting)> 0){
                $obj_user_greeting = mysql_fetch_object($user_greeting);
                $obj_rows[$i]['status'] = $obj_user_greeting->status;
            }
            else{
                $obj_rows[$i]['status'] = $obj_row->status;
            }
            $obj_rows[$i]['phone_number'] = $obj_row->phone_number;
            $i++;
        }
        $array=array("status"=>"GREETINGS_LIST_1","message"=>"Greetings found successfully.","data"=>$obj_rows);
        return json_encode($array);
    }
    else{
        $array=array("status"=>"GREETINGS_LIST_0","message"=>"No greeting found.");
        return json_encode($array);
    }
}

function greeting_detail($body){
    //iF greeting type is loved ones then fetch details for loved ones else fetch settings as per conditions
    if($body->type == 3){
        $user_greeting_detail_query = "SELECT id, title, type, start_date, end_date, start_time, end_time, is_pre_conversation, audio_url, text_message, phone_number, priority, status FROM ".TBL_USER_GREETINGS ." WHERE deleted = 0 AND id = ".$body->id;
        //echo $user_greeting_detail_query; exit;
        $greeting_detail = mysql_query($user_greeting_detail_query);
        if(mysql_num_rows($greeting_detail)>0){
            while($obj_row = mysql_fetch_object($greeting_detail))
                {
                    $obj_rows['id'] = $obj_row->id;
                    $obj_rows['title'] = $obj_row->title;
                    $obj_rows['type'] = $obj_row->type;
                    $obj_rows['start_date'] = $obj_row->start_date;
                    $obj_rows['end_date'] = $obj_row->end_date;
                    $obj_rows['start_time'] = $obj_row->start_time;
                    $obj_rows['end_time'] = $obj_row->end_time;
                    $obj_rows['is_pre_conversation'] = $obj_row->is_pre_conversation;
                    if($obj_row->audio_url!='')
                        $obj_rows['audio_url'] = "http://".$_SERVER['HTTP_HOST'].AUDIO_URL.$obj_row->audio_url;
                    else
                        $obj_rows['audio_url'] = "http://".$_SERVER['HTTP_HOST'].AUDIO_URL."love.wav";
                    $obj_rows['text_message'] = $obj_row->text_message;
                    $obj_rows['phone_number'] = $obj_row->phone_number;
                    $obj_rows['priority'] = $obj_row->priority;
                    $obj_rows['status'] = $obj_row->status;
                }
                $array=array("status"=>"GREETINGS_DETAIL_1","message"=>"Greeting details found successfully.","data"=>$obj_rows);
                return json_encode($array);
        }
        else{
            $array=array("status"=>"GREETINGS_DETAIL_0","message"=>"Greeting details not found.");
            return json_encode($array);
        }
    }
    else{
        //Check if user has added this greeting or not. If user has added greeting, fetch details from user_greetings table else fetch details from admin_greetings
        $user_greeting_detail_query = "SELECT id, title, type, start_date, end_date, start_time, end_time, is_pre_conversation, audio_url, text_message, phone_number, priority, status FROM ".TBL_USER_GREETINGS ." WHERE deleted = 0 AND greeting_id = ".$body->id." AND user_id = ".$body->user_id;
        //echo $user_greeting_detail_query; exit;

        $greeting_detail = mysql_query($user_greeting_detail_query);
        if(mysql_num_rows($greeting_detail)>0){
            while($obj_row = mysql_fetch_object($greeting_detail))
                {
                    $obj_rows['id'] = $obj_row->id;
                    $obj_rows['title'] = $obj_row->title;
                    $obj_rows['type'] = $obj_row->type;
                    $obj_rows['start_date'] = $obj_row->start_date;
                    $obj_rows['end_date'] = $obj_row->end_date;
                    $obj_rows['start_time'] = $obj_row->start_time;
                    $obj_rows['end_time'] = $obj_row->end_time;
                    $obj_rows['is_pre_conversation'] = $obj_row->is_pre_conversation;
                    if($obj_row->audio_url!='')
                        $obj_rows['audio_url'] = "http://".$_SERVER['HTTP_HOST'].AUDIO_URL.$obj_row->audio_url;
                    elseif($obj_row->type=1)
                        $obj_rows['audio_url'] = "http://".$_SERVER['HTTP_HOST'].AUDIO_URL."niceday.wav";
                    $obj_rows['text_message'] = $obj_row->text_message;
                    $obj_rows['phone_number'] = $obj_row->phone_number;
                    $obj_rows['priority'] = $obj_row->priority;
                    $obj_rows['status'] = $obj_row->status;
                }
                $array=array("status"=>"GREETINGS_DETAIL_1","message"=>"Greeting details found successfully.","data"=>$obj_rows);
                return json_encode($array);
        }
        else{
            $admin_greeting_detail_query = "SELECT id, title, type, start_date, end_date, start_time, end_time, is_pre_conversation, audio_url, text_message, phone_number, priority, status FROM ".TBL_ADMIN_GREETINGS ." WHERE deleted = 0 AND id = ".$body->id;
            //echo $greeting_detail_query; exit;
            $greeting_detail = mysql_query($admin_greeting_detail_query);
            if(mysql_num_rows($greeting_detail)>0)
            {
                while($obj_row = mysql_fetch_object($greeting_detail))
                {
                    $obj_rows['id'] = $obj_row->id;
                    $obj_rows['title'] = $obj_row->title;
                    $obj_rows['type'] = $obj_row->type;
                    $obj_rows['start_date'] = $obj_row->start_date;
                    $obj_rows['end_date'] = $obj_row->end_date;
                    $obj_rows['start_time'] = $obj_row->start_time;
                    $obj_rows['end_time'] = $obj_row->end_time;
                    $obj_rows['is_pre_conversation'] = $obj_row->is_pre_conversation;
                    if($obj_row->audio_url!='')
                        $obj_rows['audio_url'] = "http://".$_SERVER['HTTP_HOST'].AUDIO_URL.$obj_row->audio_url;
                    elseif($obj_row->type=1)
                        $obj_rows['audio_url'] = "http://".$_SERVER['HTTP_HOST'].AUDIO_URL."niceday.wav";
                    $obj_rows['text_message'] = $obj_row->text_message;
                    $obj_rows['phone_number'] = $obj_row->phone_number;
                    $obj_rows['priority'] = $obj_row->priority;
                    $obj_rows['status'] = $obj_row->status;
                }
                $array=array("status"=>"GREETINGS_DETAIL_1","message"=>"Greeting details found successfully.","data"=>$obj_rows);
                return json_encode($array);
            }
            else{
                $array=array("status"=>"GREETINGS_DETAIL_0","message"=>"Greeting details not found.");
                return json_encode($array);
            }
        }
    }
}

function add_edit_greeting($body){

    if($body->type == 3){
        $is_existing_loved_ones = "SELECT id FROM ".TBL_USER_GREETINGS." WHERE phone_number = '".$body->phone_number."' AND deleted = 0 AND user_id = ".$body->user_id." AND id!=".$body->greeting_id;
        //echo $is_existing_loved_ones; exit;
        $existing_loved_ones = mysql_query($is_existing_loved_ones);
        if(mysql_num_rows($existing_loved_ones) > 0){
            $array=array("status"=>"ADD_GREETING_0","message"=>"Please enter another phone number. This phone number already exists.");
            return json_encode($array);
        }
        //Check if greeting exists.
        $is_existing_greeting = "SELECT id,audio_url FROM ".TBL_USER_GREETINGS." WHERE deleted = 0 AND user_id = ".$body->user_id." AND id = ".$body->greeting_id;
    }
    else{
        //Check if greeting exists.
        $is_existing_greeting = "SELECT id,audio_url FROM ".TBL_USER_GREETINGS." WHERE deleted = 0 AND user_id = ".$body->user_id." AND greeting_id = ".$body->greeting_id;
    }

    //echo $is_existing_greeting; exit;
    $existing_greeting = mysql_query($is_existing_greeting);

    $existing_greeting_detail = mysql_fetch_object($existing_greeting);
    $greeting_audio_url = $existing_greeting_detail->audio_url;
    //echo $greeting_audio_url; exit;

    //Use existing greeting detail for title, type & audio url
    $greeting_audio_file = "SELECT title, type, audio_url, priority FROM ".TBL_ADMIN_GREETINGS." WHERE id = ".$body->greeting_id;
    //echo $greeting_audio_file; exit;

    $existing_audio_file = mysql_query($greeting_audio_file);
    $existing_greeting_audio = mysql_fetch_object($existing_audio_file);
    //print_r($existing_greeting_audio); exit;
    $default_title = $existing_greeting_audio->title;
    $default_priority = $existing_greeting_audio->priority;
    $default_type = $existing_greeting_audio->type;

    if(mysql_num_rows($existing_greeting)>0){

        //End date should be after Start Date
        if( strtotime($body->start_date) > strtotime($body->end_date) ){
            $array=array("status"=>"EDIT_GREETING_0","message"=>"End date should be after start date.");
            return json_encode($array);
        }

        //If start date & end date are equal then end time should be after start time
        if( strtotime($body->start_time) > strtotime($body->end_time) ){
            $array=array("status"=>"EDIT_GREETING_0","message"=>"End time should be after start time.");
            return json_encode($array);
        }

        //Compare audio files
        if($body->audio_url != "http://".$_SERVER['HTTP_HOST'].AUDIO_URL.$greeting_audio_url){
            //Different file is added. Upload new file.
            $audio_file = str_replace(' ','+',$body->audio_url);
            $audio_file_name = "";
            if($audio_file!="")
            {
                $audio_file_name= time()."_user_iphone.wav";
                $fp = fopen(AUDIO_PATH.$audio_file_name, "w");
                fwrite($fp, base64_decode($audio_file));
                fclose($fp);
            }
            $audio_url = $audio_file_name;
        }
        else{
            $audio_url = $greeting_audio_url;
        }
        //echo $audio_url; exit;

        if($body->type == 3){
            //Update greeting in user table
            $update_greeting_query = "UPDATE ".TBL_USER_GREETINGS." SET start_date = '".$body->start_date."', end_date = '".$body->end_date."', start_time = '".$body->start_time."', end_time = '".$body->end_time."', is_pre_conversation = '".$body->is_pre_conversation."', audio_url = '".$audio_url."', text_message = '".$body->text_message."', phone_number = '".$body->phone_number."', status = '".$body->status."', modified_by = ".$body->user_id.", modified_on = '".date('Y-m-d H:i:s')."'  WHERE deleted = 0 AND user_id = ".$body->user_id." AND id = ".$body->greeting_id;
        }
        else{
            //Update greeting in user table
            $update_greeting_query = "UPDATE ".TBL_USER_GREETINGS." SET title = '".$default_title."', start_date = '".$body->start_date."', end_date = '".$body->end_date."', start_time = '".$body->start_time."', end_time = '".$body->end_time."', is_pre_conversation = '".$body->is_pre_conversation."', audio_url = '".$audio_url."', text_message = '".$body->text_message."', phone_number = '".$body->phone_number."', status = '".$body->status."', priority = '".$default_priority."', modified_by = ".$body->user_id.", modified_on = '".date('Y-m-d H:i:s')."'  WHERE deleted = 0 AND user_id = ".$body->user_id." AND greeting_id = ".$body->greeting_id;
        }

        //echo $update_greeting_query; exit;

        if(mysql_query($update_greeting_query)){
            $array=array("status"=>"EDIT_GREETING_1","message"=>"Greeting updated successfully.");
            return json_encode($array);
        }
        else{
            $array=array("status"=>"EDIT_GREETING_0","message"=>"Greeting not updated successfully.");
            return json_encode($array);
        }
    }
    else{

       //End date should be after Start Date
        if( strtotime($body->start_date) > strtotime($body->end_date) ){
            $array=array("status"=>"ADD_GREETING_0","message"=>"End date should be after start date.");
            return json_encode($array);
        }

        //If start date & end date are equal then end time should be after start time
        if( ( strtotime($body->start_date) == strtotime($body->end_date) ) && ( strtotime($body->start_time) > strtotime($body->end_time) ) ){
            $array=array("status"=>"ADD_GREETING_0","message"=>"End time should be after start time.");
            return json_encode($array);
        }

        //Add greeting in user table

        if($body->type == 3){
            $default_audio = "love.wav";
        }
        else{
            $default_audio = $existing_greeting_audio->audio_url;
            $default_title = $existing_greeting_audio->title;
            $default_priority = $existing_greeting_audio->priority;
            $default_type = $existing_greeting_audio->type;
        }

        //Upload audio
        if($body->audio_url!='' && $body->audio_url != "http://".$_SERVER['HTTP_HOST'].AUDIO_URL.$default_audio){
            //Different file is added. Upload new file.
            $audio_file = str_replace(' ','+',$body->audio_url);
            $audio_file_name = "";
            if($audio_file!="")
            {
                $audio_file_name= time()."_user_iphone.wav";
                $fp = fopen(AUDIO_PATH.$audio_file_name, "w");
                fwrite($fp, base64_decode($audio_file));
                fclose($fp);
            }
            $audio_url = $audio_file_name;
        }
        else{
            $audio_url = $default_audio;
        }

        if($body->type == 3){
            $add_greeting_query = "INSERT INTO ".TBL_USER_GREETINGS." (user_id,type,title,start_date,end_date,start_time,end_time,is_pre_conversation,audio_url,text_message,phone_number,status,created_by,created_on) VALUES ('".$body->user_id."','3','".$body->title."','".$body->start_date."','".$body->end_date."','".$body->start_time."','".$body->end_time."','".$body->is_pre_conversation."','".$audio_url."','".$body->text_message."','".$body->phone_number."','".$body->status."','".$body->user_id."','".date('Y-m-d H:i:s')."')";
        }
        else{
            $add_greeting_query = "INSERT INTO ".TBL_USER_GREETINGS." (greeting_id,user_id,type,title,start_date,end_date,start_time,end_time,is_pre_conversation,audio_url,text_message,phone_number,priority,status,created_by,created_on) VALUES ('".$body->greeting_id."','".$body->user_id."',".$default_type.",'".$default_title."','".$body->start_date."','".$body->end_date."','".$body->start_time."','".$body->end_time."','".$body->is_pre_conversation."','".$audio_url."','".$body->text_message."','".$body->phone_number."','".$default_priority."','".$body->status."','".$body->user_id."','".date('Y-m-d H:i:s')."')";
        }
        //echo $add_greeting_query; exit;

        if(mysql_query($add_greeting_query)){
            $array=array("status"=>"ADD_GREETING_1","message"=>"Greeting added successfully.");
            return json_encode($array);
        }
        else{
            $array=array("status"=>"ADD_GREETING_0","message"=>"Greeting not added successfully.");
            return json_encode($array);
        }
    }
}

function add_loved_ones($body){
    $phone_number_check = "SELECT id FROM ".TBL_USER_GREETINGS." WHERE phone_number = '".$body->phone_number."' AND deleted = 0 AND user_id = ".$body->user_id;
    //echo $phone_number_check; exit;
    
    $existing_loved_ones = mysql_query($phone_number_check);
    if(mysql_num_rows($existing_loved_ones) > 0){
        $array=array("status"=>"ADD_GREETING_0","message"=>"Please enter another phone number. This phone number already exists.");
        return json_encode($array);
    }
    else{
        if($body->audio_url!=''){
            $audio_file = str_replace(' ','+',$body->audio_url);
            $audio_file_name = "";
            if($audio_file!="")
            {
                $audio_file_name= time()."_user_iphone.wav";
                $fp = fopen(AUDIO_PATH.$audio_file_name, "w");
                fwrite($fp, base64_decode($audio_file));
                fclose($fp);
            }
            $audio_url = $audio_file_name;
        }
        else{
            $audio_url = "love.wav";
        }
        $add_greeting_query = "INSERT INTO ".TBL_USER_GREETINGS." (user_id,type,title,start_date,end_date,start_time,end_time,audio_url,text_message,phone_number,status,created_by,created_on) VALUES ('".$body->user_id."','3','".$body->title."','".$body->start_date."','".$body->end_date."','".$body->start_time."','".$body->end_time."','".$audio_url."','".$body->text_message."','".$body->phone_number."','".$body->status."','".$body->user_id."','".date('Y-m-d H:i:s')."')";
        //echo $add_greeting_query; exit;

        if(mysql_query($add_greeting_query)){
            $array=array("status"=>"ADD_GREETING_1","message"=>"Greeting added successfully.");
            return json_encode($array);
        }
        else{
            $array=array("status"=>"ADD_GREETING_0","message"=>"Greeting not added successfully.");
            return json_encode($array);
        }
    }
}

function update_greeting_status($body){
    /**
    * Check if greeting exists in user greeting table.
    * If greeting doesn't exist then create new entry with default settings.
    * If greeting exists then update the status, modified_on & modified_by
    */

    if($body->type == 3){
        $update_status = "UPDATE ".TBL_USER_GREETINGS." SET status = '".$body->status."', modified_on = '".date('Y-m-d H:i:s')."', modified_by = ".$body->user_id." WHERE deleted = 0 AND user_id = ".$body->user_id." AND id = ".$body->greeting_id;
       //echo $update_status; exit;

        if(mysql_query($update_status)){
            $array=array("status"=>"UPDATE_GREETING_STATUS_1","message"=>"Greeting status updated successfully.");
            return json_encode($array);
        }
        else{
            $array=array("status"=>"UPDATE_GREETING_STATUS_0","message"=>"Greeting status not updated successfully.");
            return json_encode($array);
        }
    }
    else{
        $does_greeting_exist = "SELECT id FROM ".TBL_USER_GREETINGS." WHERE user_id = ".$body->user_id." AND greeting_id = ".$body->greeting_id." AND deleted = 0";
        //echo $does_greeting_exist; exit;

        $existing_greeting = mysql_query($does_greeting_exist);
        if(mysql_num_rows($existing_greeting)>0){

            //Use existing greeting detail for title, type, priority
            $greeting_audio_file = "SELECT title, priority FROM ".TBL_ADMIN_GREETINGS." WHERE id = ".$body->greeting_id;
            //echo $greeting_audio_file; exit;

            $existing_audio_file = mysql_query($greeting_audio_file);
            $existing_greeting_audio = mysql_fetch_object($existing_audio_file);
            //print_r($existing_greeting_audio); exit;
            $default_title = $existing_greeting_audio->title;
            $default_priority = $existing_greeting_audio->priority;

            $update_status = "UPDATE ".TBL_USER_GREETINGS." SET title = '".$default_title."', status = '".$body->status."', priority = $default_priority, modified_on = '".date('Y-m-d H:i:s')."', modified_by = ".$body->user_id." WHERE deleted = 0 AND user_id = ".$body->user_id." AND greeting_id = ".$body->greeting_id;
            //echo $update_status; exit;

            if(mysql_query($update_status)){
                $array=array("status"=>"UPDATE_GREETING_STATUS_1","message"=>"Greeting status updated successfully.");
                return json_encode($array);
            }
            else{
                $array=array("status"=>"UPDATE_GREETING_STATUS_0","message"=>"Greeting status not updated successfully.");
                return json_encode($array);
            }
        }
        else{
            $get_greeting = "SELECT * FROM ".TBL_ADMIN_GREETINGS." WHERE id = ".$body->greeting_id." AND deleted = 0";
            //echo $get_greeting; exit;

            $greeting_detail = mysql_query($get_greeting);
            if(mysql_num_rows($greeting_detail)>0){
                $obj_row = mysql_fetch_object($greeting_detail);
                $add_greeting = "INSERT INTO ".TBL_USER_GREETINGS." (greeting_id,user_id,title,type,start_date,end_date,start_time,end_time,is_pre_conversation,audio_url,text_message,status,priority,created_by,created_on) VALUES (".$body->greeting_id.",".$body->user_id.",'".$obj_row->title."','$obj_row->type','".$obj_row->start_date."','".$obj_row->end_date."','".$obj_row->start_time."','".$obj_row->end_time."','".$obj_row->is_pre_conversation."','".$obj_row->audio_url."','".$obj_row->text_message."','".$body->status."','".$obj_row->priority."',".$body->user_id.",'".date('Y-m-d H:i:s')."')";
                //echo $add_greeting; exit;
                if(mysql_query($add_greeting)){
                    $array=array("status"=>"UPDATE_GREETING_STATUS_1","message"=>"Greeting status updated successfully.");
                    return json_encode($array);
                }
                else{
                    $array=array("status"=>"UPDATE_GREETING_STATUS_0","message"=>"Greeting status not updated successfully.");
                    return json_encode($array);
                }
            }
            else{
                $array=array("status"=>"UPDATE_GREETING_STATUS_0","message"=>"Greeting status not updated successfully.");
                return json_encode($array);
            }
        }
    }
}

function delete_greeting($body){
    $delete_greeting_query = "UPDATE ".TBL_USER_GREETINGS." set deleted = 1, deleted_by = ".$body->user_id.", deleted_on = '".date('Y-m-d H:i:s')."' WHERE id = ".$body->greeting_id;
    //echo $delete_greeting_query; exit;

    if(mysql_query($delete_greeting_query)){
        $array=array("status"=>"DELETE_GREETING_1","message"=>"Greeting deleted successfully.");
        return json_encode($array);
    }
    else{
        $array=array("status"=>"DELETE_GREETING_0","message"=>"Greeting not deleted successfully.");
        return json_encode($array);
    }
}

function greeting($body){
    /*
    if($body->created_by == "user"){
        $greeting = "SELECT id,audio_url,text_message FROM ".TBL_USER_GREETINGS." WHERE greeting_id = ".$body->greeting_id." AND user_id = ".$body->user_id." AND deleted = 0";
    }

    if($body->created_by == "admin"){
        $greeting = "SELECT id,audio_url,text_message FROM ".TBL_ADMIN_GREETINGS." WHERE id = ".$body->greeting_id." AND deleted = 0";
    }
    //echo $greeting; exit;

    $greeting_detail = mysql_query($greeting);
    if(mysql_num_rows($greeting_detail)>0){
        $obj_row = mysql_fetch_object($greeting_detail);
        $obj_rows = array();
        $obj_rows['audio_url'] = "http://".$_SERVER['HTTP_HOST'].AUDIO_URL.$obj_row->audio_url;
        $obj_rows['text_message'] = $obj_row->text_message;
    }
    else{
        $array=array("status"=>"GREETING_NOT_FOUND","message"=>"Greeting not found.");
        return json_encode($array);
    }
    */

    //Check if called user is registered with greeting app. If user is registered with greeting app then send a message to inbox else send sms.
    $user_exists_query = "SELECT id from ".TBL_USERS." WHERE deleted = 0 AND phone = '".$body->to_number."'";
    //echo $user_exists; exit;

    $user_exists = mysql_query($user_exists_query);
    if(mysql_num_rows($user_exists)>0){
        //User is registered with greeting app, send message to inbox.
        $obj_row = mysql_fetch_object($user_exists);
        $user_id = $obj_row->id;
        $send_message_to_inbox_query = "INSERT INTO ".TBL_INBOX." (`user_id`,`to`,`message`,`audio_url`,`greeting_id`,`created_by`,`created_on`) VALUES (".$user_id.",".$body->to_number.",'".$body->text_message."','".$body->audio_url."',".$body->greeting_id.",".$body->user_id.",'".date('Y-m-d H:i:s')."')";
        //echo $send_message_to_inbox_query; exit;

        mysql_query($send_message_to_inbox_query);
        $array=array("status"=>"GREETING_1","message"=>"Message send to user.");
        return json_encode($array);
    }
    else{
        //User is not registered with greeting app, send sms from iphone.
        $obj_rows = array();
        $obj_rows['text_message'] = $body->text_message;
        $array=array("status"=>"GREETING_0","message"=>"User is not registered with greeting app.","data"=>$obj_rows);
        return json_encode($array);
    }
}
 
function greeting_android($body){
    //Check if called user is registered with greeting app. If user is registered with greeting app then send a message to inbox else send sms.
    $user_exists_query = "SELECT id from ".TBL_USERS." WHERE deleted = 0 AND phone_number = '".$body->to_number."'"; 
    //echo $user_exists_query; exit;

    $user_exists = mysql_query($user_exists_query);
    if(mysql_num_rows($user_exists)>0){
        //User is registered with greeting app, send message to inbox.
        $obj_row = mysql_fetch_object($user_exists);

        $user_id = $obj_row->id;
        $send_message_to_inbox_query = "INSERT INTO ".TBL_INBOX." (`user_id`,`to`,`message`,`audio_url`,`greeting_id`,`created_by`,`created_on`) VALUES (".$user_id.",".$body->to_number.",'".$body->text_message."','".$body->audio_url."',".$body->greeting_id.",".$body->user_id.",'".date('Y-m-d H:i:s')."')";
        //echo $send_message_to_inbox_query; exit;

        mysql_query($send_message_to_inbox_query);
        
        $current_date = $body->current_date;
        $current_time = $body->current_time; 
        
        /*
        * Get active greeting for dialler ("from number")
        * If dialler has set status on for any of the reminders then it will be considered highest priority. 
        * Else get active greeting for "to number" and check conditions  
        */
        
        $dialler = (object)array();
        $dialler->user_id = $body->user_id;
        //print_r($dialler); exit; 
        $get_dialler_active_greetings = get_active_reminders($dialler,$current_date,$current_time);
        $dialler_active_greetings = json_decode($get_dialler_active_greetings);
        //print_r($dialler_active_greetings->data); exit;
        if(!empty($dialler_active_greetings->data)){
            for($i=0;$i<count($dialler_active_greetings->data);$i++){
                if($dialler_active_greetings->data[$i]->title == 'Happy Birthday'){
                    //Fetch 'Happy Birthday' reminder details as set by receiver
                    $receiver = (object)array();
                    $receiver->user_id = $user_id;
                    //print_r($receiver); exit; 
                    $get_receiver_birthday = get_birthday($user_id);
                    //print_r($get_receiver_birthday); exit;
                    
                    //Send message to calling person
                    $send_message_to_inbox_query_2 = "INSERT INTO ".TBL_INBOX." (`user_id`,`to`,`message`,`audio_url`,`greeting_id`,`created_by`,`created_on`) VALUES (".$body->user_id.",".$body->from_number.",'".$get_receiver_birthday['text_message']."','".$get_receiver_birthday['audio_url']."',".$get_receiver_birthday['id'].",".$user_id.",'".date('Y-m-d H:i:s')."')";
                    //echo $send_message_to_inbox_query_2; exit;
                    mysql_query($send_message_to_inbox_query_2);

                    $array=array("status"=>"GREETING_ANDROID_1","message"=>"Message send to user.","data"=>$get_receiver_birthday);
                    return json_encode($array);
                    
                }
                
                if($dialler_active_greetings->data[$i]->title == 'Happy Anniversary'){
                    //Fetch 'Happy Anniversary' reminder details as set by receiver
                    $receiver = (object)array();
                    $receiver->user_id = $user_id;
                    //print_r($receiver); exit; 
                    $get_receiver_anniversary = get_anniversary($user_id);
                    //print_r($get_receiver_anniversary); exit;
                    
                    //Send message to calling person
                    $send_message_to_inbox_query_2 = "INSERT INTO ".TBL_INBOX." (`user_id`,`to`,`message`,`audio_url`,`greeting_id`,`created_by`,`created_on`) VALUES (".$body->user_id.",".$body->from_number.",'".$get_receiver_anniversary['text_message']."','".$get_receiver_anniversary['audio_url']."',".$get_receiver_anniversary['id'].",".$user_id.",'".date('Y-m-d H:i:s')."')";
                    //echo $send_message_to_inbox_query_2; exit;
                    mysql_query($send_message_to_inbox_query_2);

                    $array=array("status"=>"GREETING_ANDROID_1","message"=>"Message send to user.","data"=>$get_receiver_birthday);
                    return json_encode($array);
                }
            }
        }

        /*
        * Get active greeting for "to number"
        * send message to "from number" and return audio URL
        */

        $user = (object)array();
        $user->user_id = $user_id;
        //print_r($user); exit; 
        $get_active_greetings = get_active_greetings($user);
        $active_greetings = json_decode($get_active_greetings);
        //print_r($active_greetings->data); exit;

        if(count($active_greetings)>0){
            $required_greetings = array();
            $have_a_nice_day_greetings = array();
            $final_active_greeting = array();
            $have_a_nice_day_greeting = array();
            $k = 0;
            $l = 0;

            //Get all active greetings which satisfy condition for start date & end date
            for($i=0;$i<count($active_greetings->data);$i++){

                if(strtotime($current_date) >= strtotime($active_greetings->data[$i]->start_date) && strtotime($current_date) <= strtotime($active_greetings->data[$i]->end_date) && strtotime($current_time) >= strtotime($active_greetings->data[$i]->start_time) && strtotime($current_time) <= strtotime($active_greetings->data[$i]->end_time)){
                    $required_greetings[$k]['id'] = $active_greetings->data[$i]->id;
                    $required_greetings[$k]['created_by'] = $active_greetings->data[$i]->created_by;
                    $required_greetings[$k]['type'] = $active_greetings->data[$i]->type;
                    $required_greetings[$k]['title'] = $active_greetings->data[$i]->title;
                    $required_greetings[$k]['phone_number'] = $active_greetings->data[$i]->phone_number;
                    $required_greetings[$k]['start_date'] = $active_greetings->data[$i]->start_date;
                    $required_greetings[$k]['end_date'] = $active_greetings->data[$i]->end_date;
                    $required_greetings[$k]['start_time'] = $active_greetings->data[$i]->start_time;
                    $required_greetings[$k]['end_time'] = $active_greetings->data[$i]->end_time;
                    $required_greetings[$k]['audio_url'] = $active_greetings->data[$i]->audio_url;
                    $required_greetings[$k]['text_message'] = $active_greetings->data[$i]->text_message;
                    $required_greetings[$k]['priority'] = $active_greetings->data[$i]->priority;
                    $k++;
                }

                //Store have a nice day greetings in an array
                if($active_greetings->data[$i]->type == 1 && strtotime($current_date) >= strtotime($active_greetings->data[$i]->start_date) && strtotime($current_date) <= strtotime($active_greetings->data[$i]->end_date) && strtotime($current_time) >= strtotime($active_greetings->data[$i]->start_time) && strtotime($current_time) <= strtotime($active_greetings->data[$i]->end_time)){
                    $have_a_nice_day_greetings[$l]['id'] = $active_greetings->data[$i]->id;
                    $have_a_nice_day_greetings[$l]['created_by'] = $active_greetings->data[$i]->created_by;
                    $have_a_nice_day_greetings[$l]['type'] = $active_greetings->data[$i]->type;
                    $have_a_nice_day_greetings[$l]['title'] = $active_greetings->data[$i]->title;
                    $have_a_nice_day_greetings[$l]['phone_number'] = $active_greetings->data[$i]->phone_number;
                    $have_a_nice_day_greetings[$l]['start_date'] = $active_greetings->data[$i]->start_date;
                    $have_a_nice_day_greetings[$l]['end_date'] = $active_greetings->data[$i]->end_date;
                    $have_a_nice_day_greetings[$l]['start_time'] = $active_greetings->data[$i]->start_time;
                    $have_a_nice_day_greetings[$l]['end_time'] = $active_greetings->data[$i]->end_time;
                    $have_a_nice_day_greetings[$l]['audio_url'] = $active_greetings->data[$i]->audio_url;
                    $have_a_nice_day_greetings[$l]['text_message'] = $active_greetings->data[$i]->text_message;
                    $have_a_nice_day_greetings[$l]['priority'] = $active_greetings->data[$i]->priority;
                    $l++;
                }
                
                //Store have a nice day greeting in an array
                if($active_greetings->data[$i]->type == 1 && $active_greetings->data[$i]->priority == 0){
                    $have_a_nice_day_greeting[0]['id'] = $active_greetings->data[$i]->id;
                    $have_a_nice_day_greeting[0]['created_by'] = $active_greetings->data[$i]->created_by;
                    $have_a_nice_day_greeting[0]['type'] = $active_greetings->data[$i]->type;
                    $have_a_nice_day_greeting[0]['title'] = $active_greetings->data[$i]->title;
                    $have_a_nice_day_greeting[0]['phone_number'] = $active_greetings->data[$i]->phone_number;
                    $have_a_nice_day_greeting[0]['start_date'] = $active_greetings->data[$i]->start_date;
                    $have_a_nice_day_greeting[0]['end_date'] = $active_greetings->data[$i]->end_date;
                    $have_a_nice_day_greeting[0]['start_time'] = $active_greetings->data[$i]->start_time;
                    $have_a_nice_day_greeting[0]['end_time'] = $active_greetings->data[$i]->end_time;
                    $have_a_nice_day_greeting[0]['audio_url'] = $active_greetings->data[$i]->audio_url;
                    $have_a_nice_day_greeting[0]['text_message'] = $active_greetings->data[$i]->text_message;
                    $have_a_nice_day_greeting[0]['priority'] = $active_greetings->data[$i]->priority;
                }
            }
            
            if(count($have_a_nice_day_greetings)==0 && count($required_greetings)==0 && count($have_a_nice_day_greeting)==0){
                $array=array("status"=>"GREETING_ANDROID_2","message"=>"No greeting found.");
                return json_encode($array);
            }
            
            //If more than one greetings found then sort it according to priority
            if(count($have_a_nice_day_greetings)>1){
                usort($have_a_nice_day_greetings, 'sortByOrder');
            }
            
            $x = 0; $greeting = array();
            $y = 0; $reminder = array();
            $z = 0; $loved_ones = array();
            
            for($j=0;$j<count($required_greetings);$j++){
                if($required_greetings[$j]['type']==1){
                    $greeting[$x]['id'] = $required_greetings[$j]['id'];
                    $greeting[$x]['created_by'] = $required_greetings[$j]['created_by'];
                    $greeting[$x]['type'] = $required_greetings[$j]['type'];
                    $greeting[$x]['title'] = $required_greetings[$j]['title'];
                    $greeting[$x]['phone_number'] = $required_greetings[$j]['phone_number'];
                    $greeting[$x]['start_date'] = $required_greetings[$j]['start_date'];
                    $greeting[$x]['end_date'] = $required_greetings[$j]['end_date'];
                    $greeting[$x]['start_time'] = $required_greetings[$j]['start_time'];
                    $greeting[$x]['end_time'] = $required_greetings[$j]['end_time'];
                    $greeting[$x]['audio_url'] = $required_greetings[$j]['audio_url'];
                    $greeting[$x]['text_message'] = $required_greetings[$j]['text_message'];
                    $greeting[$x]['priority'] = $required_greetings[$j]['priority'];
                    $x++;
                }
                if($required_greetings[$j]['type']==2){
                    $reminder[$y]['id'] = $required_greetings[$j]['id'];
                    $reminder[$y]['created_by'] = $required_greetings[$j]['created_by'];
                    $reminder[$y]['type'] = $required_greetings[$j]['type'];
                    $reminder[$y]['title'] = $required_greetings[$j]['title'];
                    $reminder[$y]['phone_number'] = $required_greetings[$j]['phone_number'];
                    $reminder[$y]['start_date'] = $required_greetings[$j]['start_date'];
                    $reminder[$y]['end_date'] = $required_greetings[$j]['end_date'];
                    $reminder[$y]['start_time'] = $required_greetings[$j]['start_time'];
                    $reminder[$y]['end_time'] = $required_greetings[$j]['end_time'];
                    $reminder[$y]['audio_url'] = $required_greetings[$j]['audio_url'];
                    $reminder[$y]['text_message'] = $required_greetings[$j]['text_message'];
                    $reminder[$y]['priority'] = $required_greetings[$j]['priority'];
                    $y++;
                }
                if($required_greetings[$j]['type']==3 && $required_greetings[$j]['phone_number'] == $body->from_number){
                    $loved_ones[$z]['id'] = $required_greetings[$j]['id'];
                    $loved_ones[$z]['created_by'] = $required_greetings[$j]['created_by'];
                    $loved_ones[$z]['type'] = $required_greetings[$j]['type'];
                    $loved_ones[$z]['title'] = $required_greetings[$j]['title'];
                    $loved_ones[$z]['phone_number'] = $required_greetings[$j]['phone_number'];
                    $loved_ones[$z]['start_date'] = $required_greetings[$j]['start_date'];
                    $loved_ones[$z]['end_date'] = $required_greetings[$j]['end_date'];
                    $loved_ones[$z]['start_time'] = $required_greetings[$j]['start_time'];
                    $loved_ones[$z]['end_time'] = $required_greetings[$j]['end_time'];
                    $loved_ones[$z]['audio_url'] = $required_greetings[$j]['audio_url'];
                    $loved_ones[$z]['text_message'] = $required_greetings[$j]['text_message'];
                    $loved_ones[$z]['priority'] = $required_greetings[$j]['priority'];
                    $z++;
                }
            }
            
            $required_greetings = array_merge($reminder,$greeting,$loved_ones);
            
            //print_r($required_greetings); //exit;
            //print_r($reminder); //exit;
            //print_r($greeting); //exit;
            //print_r($loved_ones); //exit;
            //print_r($have_a_nice_day_greetings); exit;  
            
            if( count($have_a_nice_day_greeting)==1  && count($loved_ones)==0 && count($have_a_nice_day_greetings)==0 && count($reminder)==0){
                    $final_active_greeting['id'] = $have_a_nice_day_greeting[0]['id'];
                    $final_active_greeting['text_message'] = $have_a_nice_day_greeting[0]['text_message'];
                    $final_active_greeting['audio_url'] = $have_a_nice_day_greeting[0]['audio_url'];
            }
            
            //$loved_ones_check = 0;
            
            for($j=0;$j<count($required_greetings);$j++){
                
                //First check if any reminder is active
                if($required_greetings[$j]['type']==2){
                    //Birthday has higher priority
                    if($required_greetings[$j]['title']=="Happy Birthday"){
                        $final_active_greeting['id'] = $required_greetings[$j]['id'];
                        $final_active_greeting['text_message'] = $required_greetings[$j]['text_message'];
                        $final_active_greeting['audio_url'] = $required_greetings[$j]['audio_url'];
                        break;
                    }

                    //Birthday has second higher priority
                    if($required_greetings[$j]['title']=="Happy Anniversary"){
                        $final_active_greeting['id'] = $required_greetings[$j]['id'];
                        $final_active_greeting['text_message'] = $required_greetings[$j]['text_message'];
                        $final_active_greeting['audio_url'] = $required_greetings[$j]['audio_url'];
                        break;
                    }
                }
                else{
                    $final_active_greeting['id'] = $required_greetings[$j]['id'];
                    $final_active_greeting['text_message'] = $required_greetings[$j]['text_message'];
                    $final_active_greeting['audio_url'] = $required_greetings[$j]['audio_url'];
                    break;
                }

                /* 
                if($required_greetings[$j]['type']==1){
                    $final_active_greeting['id'] = $required_greetings[$j]['id'];
                    $final_active_greeting['text_message'] = $required_greetings[$j]['text_message'];
                    $final_active_greeting['audio_url'] = $required_greetings[$j]['audio_url'];
                    break;
                }
                
                if($required_greetings[$j]['type']==3){
                    $final_active_greeting['id'] = $required_greetings[$j]['id'];
                    $final_active_greeting['text_message'] = $required_greetings[$j]['text_message'];
                    $final_active_greeting['audio_url'] = $required_greetings[$j]['audio_url'];
                    break;
                }
                
                if(count($have_a_nice_day_greeting)==1){
                    $loved_ones_check = 1;
                }
                
                if(count($have_a_nice_day_greetings)==1 && $have_a_nice_day_greetings[0]['priority']!=0){
                    $final_active_greeting['id'] = $have_a_nice_day_greetings[0]['id'];
                    $final_active_greeting['text_message'] = $have_a_nice_day_greetings[0]['text_message'];
                    $final_active_greeting['audio_url'] = $have_a_nice_day_greetings[0]['audio_url'];
                    break;
                }

                if( ($loved_ones_check==1 || count($have_a_nice_day_greeting)==0) && count($loved_ones)>0 ){
                    //Check if loved ones greetings are active
                    if($required_greetings[$j]['type']==3 && $required_greetings[$j]['phone_number']==$body->from_number){
                        $final_active_greeting['id'] = $required_greetings[$j]['id'];
                        $final_active_greeting['text_message'] = $required_greetings[$j]['text_message'];
                        $final_active_greeting['audio_url'] = $required_greetings[$j]['audio_url'];
                        break;
                    }
                }*/
            }

            //print_r($final_active_greeting); exit;
            
            if(empty($final_active_greeting)){
                $array=array("status"=>"GREETING_ANDROID_2","message"=>"No greeting found.");
                return json_encode($array);
            }
            else{
                //Send message to calling person
                $send_message_to_inbox_query_2 = "INSERT INTO ".TBL_INBOX." (`user_id`,`to`,`message`,`audio_url`,`greeting_id`,`created_by`,`created_on`) VALUES (".$body->user_id.",".$body->from_number.",'".$final_active_greeting['text_message']."','".$final_active_greeting['audio_url']."',".$final_active_greeting['id'].",".$user_id.",'".date('Y-m-d H:i:s')."')";
                //echo $send_message_to_inbox_query_2; exit;
                mysql_query($send_message_to_inbox_query_2);

                $array=array("status"=>"GREETING_ANDROID_1","message"=>"Message send to user.","data"=>$final_active_greeting);
                return json_encode($array);
            }
        }
        else{
            //No active greeting found for the user "to number"
            $array=array("status"=>"GREETING_ANDROID_2","message"=>"No greeting found."); 
            return json_encode($array);
        }
    }
    else{
        //User is not registered with greeting app, send sms from iphone.
        $obj_rows = array();
        $obj_rows['text_message'] = $body->text_message;
        $array=array("status"=>"GREETING_ANDROID_0","message"=>"User is not registered with greeting app.","data"=>$obj_rows);
        return json_encode($array);
    }
}

function sortByOrder($a, $b) {
    return $a['priority'] - $b['priority'];
}

function update_credit($body){
    $update_credit_query = "UPDATE ".TBL_USERS." SET credits = ".$body->credit." WHERE id = ".$body->user_id." AND deleted = 0";
    //echo $update_credit_query; exit;

    if(mysql_query($update_credit_query)){
        $array=array("status"=>"UPDATE_CREDIT_1","message"=>"Credits updated successfully.");
        return json_encode($array);
    }
    else{
        $array=array("status"=>"UPDATE_CREDIT_0","message"=>"Credits not updated successfully.");
        return json_encode($array);
    }
}

function get_user_messages($body){
    $get_user_messages_query = "SELECT i.*,u.user_name, i.id as message_id FROM ".TBL_INBOX." AS i LEFT JOIN ".TBL_USERS." AS u ON i.created_by = u.id WHERE i.deleted = 0 AND u.deleted = 0 AND i.user_id = ".$body->user_id." ORDER BY created_on DESC";
    //echo $get_user_messages_query; exit;

    $get_user_messages = mysql_query($get_user_messages_query);
    if(mysql_num_rows($get_user_messages)>0){
        $obj_rows = array(); 
        $i=0;
        while($obj_row = mysql_fetch_object($get_user_messages)){
            $obj_rows[$i]['message_id'] = $obj_row->message_id;
            $obj_rows[$i]['from'] = $obj_row->from;
            $obj_rows[$i]['to'] = $obj_row->to;
            $obj_rows[$i]['message'] = $obj_row->message;
            $obj_rows[$i]['audio_url'] = $obj_row->audio_url;
            $obj_rows[$i]['user_name'] = $obj_row->user_name;
            $i++;
        }
        $array=array("status"=>"USER_MESSAGE_1","message"=>"Message found successfully.","data"=>$obj_rows);
        return json_encode($array);
    }
    else{
        $array=array("status"=>"USER_MESSAGE_0","message"=>"No message found.");
        return json_encode($array);
    }
}

function delete_user_message($body){
    $message_id = $body->id;
    $query = "UPDATE ".TBL_INBOX." SET deleted = 1, deleted_on = '".date("Y-m-d H:i:s")."' WHERE id = ".$message_id;
    //echo $query; exit;
    mysql_query($query);
    $array = array("status"=>"DELETE_MESSAGE_1","message"=>"Message deleted successfully.");
    return json_encode($array);
}


function get_active_greetings($body){
    $get_admin_active_greetings_query = "SELECT * FROM ".TBL_ADMIN_GREETINGS." WHERE deleted = 0 AND status = 'On' ORDER BY type ASC";
    //echo $get_admin_active_greetings_query; exit;

    $get_admin_active_greetings = mysql_query($get_admin_active_greetings_query);

    $get_user_active_greetings_query = "SELECT * FROM ".TBL_USER_GREETINGS." WHERE deleted = 0 AND user_id = ".$body->user_id." ORDER BY type ASC";
    //echo $get_user_active_greetings_query; exit;

    $get_user_active_greetings = mysql_query($get_user_active_greetings_query);

    if(mysql_num_rows($get_user_active_greetings)>0){
        //First fetch user added greetings from user_greetings table
        $user_greetings = array();

        /*
        //Get all greeting ids for user created greetings, this ids will be compared with admin created greetings for fetching greetings with status 'On'
        $get_user_greetings_query = "SELECT id FROM ".TBL_USER_GREETINGS." WHERE deleted = 0 AND user_id = ".$body->user_id." ORDER BY type ASC";
        //echo $get_user_greetings_query; exit;
        $get_user_greetings = mysql_query($get_user_greetings_query);
        */

        $user_greeting_id = array();

        $i = 0;
        while($obj_row = mysql_fetch_object($get_user_active_greetings)){
             array_push($user_greeting_id, $obj_row->greeting_id); //Get all greetings id for user
             if($obj_row->status == 'On')
             {
                $user_greetings[$i]['id'] = $obj_row->id;
                $user_greetings[$i]['created_by'] = "user";
                $user_greetings[$i]['type'] = $obj_row->type;
                $user_greetings[$i]['title'] = $obj_row->title;
                $user_greetings[$i]['phone_number'] = $obj_row->phone_number;
                $user_greetings[$i]['start_date'] = $obj_row->start_date;
                $user_greetings[$i]['end_date'] = $obj_row->end_date;
                $user_greetings[$i]['start_time'] = $obj_row->start_time;
                $user_greetings[$i]['end_time'] = $obj_row->end_time;
                if($obj_row->audio_url!='')
                    $user_greetings[$i]['audio_url'] = "http://".$_SERVER['HTTP_HOST'].AUDIO_URL.$obj_row->audio_url;
                else
                    $user_greetings[$i]['audio_url'] = "";
                $user_greetings[$i]['text_message'] = $obj_row->text_message;
                $user_greetings[$i]['priority'] = $obj_row->priority;
            }
            $i++;
        }

        $admin_greetings = array();

        $get_default_active_greetings_query = "SELECT * FROM ".TBL_ADMIN_GREETINGS." WHERE deleted = 0 AND status = 'On' AND id NOT IN (".implode(',', $user_greeting_id).") ORDER BY type ASC";
        //echo $get_default_active_greetings_query; exit;

        $get_default_active_greetings = mysql_query($get_default_active_greetings_query);

        if(mysql_num_rows($get_default_active_greetings)>0){
            $j = 0;
            while($obj_row = mysql_fetch_object($get_default_active_greetings)){
                $admin_greetings[$j]['id'] = $obj_row->id;
                $admin_greetings[$j]['created_by'] = "admin";
                $admin_greetings[$j]['type'] = $obj_row->type;
                $admin_greetings[$j]['title'] = $obj_row->title;
                $admin_greetings[$j]['start_date'] = $obj_row->start_date;
                $admin_greetings[$j]['end_date'] = $obj_row->end_date;
                $admin_greetings[$j]['start_time'] = $obj_row->start_time;
                $admin_greetings[$j]['end_time'] = $obj_row->end_time;
                if($obj_row->audio_url!='')
                    $admin_greetings[$j]['audio_url'] = "http://".$_SERVER['HTTP_HOST'].AUDIO_URL.$obj_row->audio_url;
                else
                    $admin_greetings[$j]['audio_url'] = "";
                $admin_greetings[$j]['text_message'] = $obj_row->text_message;
                $admin_greetings[$j]['priority'] = $obj_row->priority;
                $j++;
            }
        }

        $result = array_merge($user_greetings,$admin_greetings);

        $array=array("status"=>"ACTIVE_GREETING_1","message"=>"Greetings found successfully.","data"=>$result);
        return json_encode($array);
    }
    elseif(mysql_num_rows($get_admin_active_greetings)>0){
        //Fetch details of admin created greetings
        $obj_rows = array();
        $i = 0;
        while($obj_row = mysql_fetch_object($get_admin_active_greetings)){
            $obj_rows[$i]['id'] = $obj_row->id;
            $obj_rows[$i]['created_by'] = "admin";
            $obj_rows[$i]['type'] = $obj_row->type;
            $obj_rows[$i]['title'] = $obj_row->title;
            $obj_rows[$i]['start_date'] = $obj_row->start_date;
            $obj_rows[$i]['end_date'] = $obj_row->end_date;
            $obj_rows[$i]['start_time'] = $obj_row->start_time;
            $obj_rows[$i]['end_time'] = $obj_row->end_time;
            if($obj_row->audio_url!='')
                $obj_rows[$i]['audio_url'] = "http://".$_SERVER['HTTP_HOST'].AUDIO_URL.$obj_row->audio_url;
            else
                $obj_rows[$i]['audio_url'] = "";
            $obj_rows[$i]['text_message'] = $obj_row->text_message;
            $obj_rows[$i]['priority'] = $obj_row->priority;
            $i++;
        }

        $array=array("status"=>"ACTIVE_GREETING_1","message"=>"Greetings found successfully.","data"=>$obj_rows);
        return json_encode($array);
    }
    else{
        $array=array("status"=>"ACTIVE_GREETING_0","message"=>"No message found.");
        return json_encode($array);
    }
}

function get_active_reminders($body,$date,$time){
    $get_admin_active_greetings_query = "SELECT * FROM ".TBL_ADMIN_GREETINGS." WHERE deleted = 0 AND status = 'On' AND type = 2 AND DATE('$date')>=start_date AND DATE('$date')<=end_date AND TIME('$time') >= start_time AND TIME('$time') <= end_time ORDER BY type ASC";
    //echo $get_admin_active_greetings_query; exit;

    $get_admin_active_greetings = mysql_query($get_admin_active_greetings_query);

    $get_user_active_greetings_query = "SELECT * FROM ".TBL_USER_GREETINGS." WHERE deleted = 0 AND type = 2 AND DATE('$date')>=start_date AND DATE('$date')<=end_date AND TIME('$time') >= start_time AND TIME('$time') <= end_time AND user_id = ".$body->user_id." ORDER BY type ASC";
    //echo $get_user_active_greetings_query; exit;

    $get_user_active_greetings = mysql_query($get_user_active_greetings_query);

    if(mysql_num_rows($get_user_active_greetings)>0){
        //First fetch user added greetings from user_greetings table
        $user_greetings = array();

        /*
        //Get all greeting ids for user created greetings, this ids will be compared with admin created greetings for fetching greetings with status 'On'
        $get_user_greetings_query = "SELECT id FROM ".TBL_USER_GREETINGS." WHERE deleted = 0 AND user_id = ".$body->user_id." ORDER BY type ASC";
        //echo $get_user_greetings_query; exit;
        $get_user_greetings = mysql_query($get_user_greetings_query);
        */

        $user_greeting_id = array();

        $i = 0;
        while($obj_row = mysql_fetch_object($get_user_active_greetings)){
             array_push($user_greeting_id, $obj_row->greeting_id); //Get all greetings id for user
             if($obj_row->status == 'On')
             {
                $user_greetings[$i]['id'] = $obj_row->id;
                $user_greetings[$i]['created_by'] = "user";
                $user_greetings[$i]['type'] = $obj_row->type;
                $user_greetings[$i]['title'] = $obj_row->title;
                $user_greetings[$i]['phone_number'] = $obj_row->phone_number;
                $user_greetings[$i]['start_date'] = $obj_row->start_date;
                $user_greetings[$i]['end_date'] = $obj_row->end_date;
                $user_greetings[$i]['start_time'] = $obj_row->start_time;
                $user_greetings[$i]['end_time'] = $obj_row->end_time;
                if($obj_row->audio_url!='')
                    $user_greetings[$i]['audio_url'] = "http://".$_SERVER['HTTP_HOST'].AUDIO_URL.$obj_row->audio_url;
                else
                    $user_greetings[$i]['audio_url'] = "";
                $user_greetings[$i]['text_message'] = $obj_row->text_message;
                $user_greetings[$i]['priority'] = $obj_row->priority;
            }
            $i++;
        }

        $admin_greetings = array();

        $get_default_active_greetings_query = "SELECT * FROM ".TBL_ADMIN_GREETINGS." WHERE deleted = 0 AND status = 'On' AND type = 2 AND DATE('$date')>=start_date AND DATE('$date')<=end_date AND TIME('$time') >= start_time AND TIME('$time') <= end_time AND id NOT IN (".implode(',', $user_greeting_id).") ORDER BY type ASC";
        //echo $get_default_active_greetings_query; exit;

        $get_default_active_greetings = mysql_query($get_default_active_greetings_query);

        if(mysql_num_rows($get_default_active_greetings)>0){
            $j = 0;
            while($obj_row = mysql_fetch_object($get_default_active_greetings)){
                $admin_greetings[$j]['id'] = $obj_row->id;
                $admin_greetings[$j]['created_by'] = "admin";
                $admin_greetings[$j]['type'] = $obj_row->type;
                $admin_greetings[$j]['title'] = $obj_row->title;
                $admin_greetings[$j]['start_date'] = $obj_row->start_date;
                $admin_greetings[$j]['end_date'] = $obj_row->end_date;
                $admin_greetings[$j]['start_time'] = $obj_row->start_time;
                $admin_greetings[$j]['end_time'] = $obj_row->end_time;
                if($obj_row->audio_url!='')
                    $admin_greetings[$j]['audio_url'] = "http://".$_SERVER['HTTP_HOST'].AUDIO_URL.$obj_row->audio_url;
                else
                    $admin_greetings[$j]['audio_url'] = "";
                $admin_greetings[$j]['text_message'] = $obj_row->text_message;
                $admin_greetings[$j]['priority'] = $obj_row->priority;
                $j++;
            }
        }

        $result = array_merge($user_greetings,$admin_greetings);

        $array=array("status"=>"ACTIVE_GREETING_1","message"=>"Greetings found successfully.","data"=>$result);
        return json_encode($array);
    }
    elseif(mysql_num_rows($get_admin_active_greetings)>0){
        //Fetch details of admin created greetings
        $obj_rows = array();
        $i = 0;
        while($obj_row = mysql_fetch_object($get_admin_active_greetings)){
            $obj_rows[$i]['id'] = $obj_row->id;
            $obj_rows[$i]['created_by'] = "admin";
            $obj_rows[$i]['type'] = $obj_row->type;
            $obj_rows[$i]['title'] = $obj_row->title;
            $obj_rows[$i]['start_date'] = $obj_row->start_date;
            $obj_rows[$i]['end_date'] = $obj_row->end_date;
            $obj_rows[$i]['start_time'] = $obj_row->start_time;
            $obj_rows[$i]['end_time'] = $obj_row->end_time;
            if($obj_row->audio_url!='')
                $obj_rows[$i]['audio_url'] = "http://".$_SERVER['HTTP_HOST'].AUDIO_URL.$obj_row->audio_url;
            else
                $obj_rows[$i]['audio_url'] = "";
            $obj_rows[$i]['text_message'] = $obj_row->text_message;
            $obj_rows[$i]['priority'] = $obj_row->priority;
            $i++;
        }
        $array=array("status"=>"ACTIVE_GREETING_1","message"=>"Greetings found successfully.","data"=>$obj_rows);
        return json_encode($array);
    }
    else{
        $data = array();
        $array=array("status"=>"ACTIVE_GREETING_0","message"=>"No message found.","data"=>$data);
        return json_encode($array);
    }
}

function forgot_password($body){    
    $email = $body->email;
    
    if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)) 
    {
        $array = array("status"=>"FORGOT_PASSWORD_0","message"=>"Please enter a valid email.");
        return json_encode($array);
    } 
    else
    {
        if(check_email_exists($email)==true)
        {
            $to = $email;
            $from = FROM_EMAIL; 
            $password = generate_password(); 
            $ret_details= array();
            $ret_details = update_pass_byemail($password,$email);
            $sub = "Your password for Greeting App";
            $query_1 = "SELECT id,user_name FROM ".TBL_USERS." WHERE email = '".$email."' AND status='Active' AND deleted='0'";
            $object_data = mysql_query($query_1);
            $data = mysql_fetch_object($object_data);
            $user_name =  $data->user_name; 
            $user_id =  $data->id; 
            $message = '<html>
                            <head>
                            </head>
                            <body>	
                                <p>Dear '.$user_name.',</p>
                                <br/>
                                <p>Your password for Greeting App has been updated. Please use below mentioned password to login.</p>
                                <br />
                                <table width="100%">
                                        <tr><td>Username : '.$user_name.'</td></tr>
                                        <tr><td>Password : '.$password.'</td></tr>
                                </table>
                                    <table width="100%">
                                            <br />    
                                            <tr>Kind Regards,</tr>
                                            <tr>Greeting App Team</tr>
                                    </table>
                            </body>
                        </html>';
            //echo $message; exit;
            $headers = '';
            $headers .= "MIME-Version: 1.0' \r\n";
            $headers .= "From: $from \r\n";
            $headers .= "Content-type: text/html; charset=iso-8859-1 \r\n";
            if(mail($to, $sub, $message, $headers))
            {
                $array=array("status"=>"FORGOT_PASSWORD_1","message"=>"Email sent to user successfully.","user_name"=>$user_name,"password"=>$password,"user_id"=>$user_id);
                return json_encode($array);
            }
            else
            {
                $array=array("status"=>"FORGOT_PASSWORD_0","message"=>"Error in sending email.");
                return json_encode($array);
            }
        }
        else
        {			
            $array=array("status"=>"FORGOT_PASSWORD_0","message"=>"Email address does not exist.");
            return json_encode($array);
        }
    }
}

function generate_password($length=8, $strength=4)
{
       $vowels = 'aeuy';
       $consonants = 'bdghjmnpqrstvz';
       if ($strength & 1) 
       {
            $consonants .= 'BDGHJLMNPQRSTVWXZ';
       }
       if ($strength & 2) 
       {
            $vowels .= "AEUY";
       }
       if ($strength & 4) 
       {
            $consonants .= '23456789';
       }
       if ($strength & 8) 
       {
            $consonants .= '@#$%';
       }
       $password = '';
       $alt = time() % 2;
       for ($i = 0; $i < $length; $i++) 
       {
                if ($alt == 1) 
                    {
                    $password .= $consonants[(rand() % strlen($consonants))];
                    $alt = 0;
                } 
                    else 
                    {
                    $password .= $vowels[(rand() % strlen($vowels))];
                    $alt = 1;
                }
       }
       return $password;
} 

function update_pass_byemail($password,$email)
{  
        $query = "UPDATE ".TBL_USERS." SET password='".md5($password)."' WHERE email='".$email."' ";
        mysql_query($query);
}

function change_password($body)
{  
        $old_password = $body->old_password;
        $password = $body->password;
        $confirm_password = $body->confirm_password;
        $user_id = $body->user_id;
        
        $check_old_password = "SELECT id FROM ".TBL_USERS." WHERE password = '".md5($old_password)."' AND id = ".$user_id;
        //echo $check_old_password; exit;
        
        $check_old_password_result = mysql_query($check_old_password);

        //Check old password with existing password
	if(mysql_num_rows($check_old_password_result)> 0){
            if($password == $confirm_password){
                if(strlen($password)<6 || strlen($password)>25){
                    $array = array("status"=>"CHANGE_PASSWORD_0","message"=>"Password should be atleast 6 characters and atmost 25 characters.");
                    return json_encode($array);
                }

                $query = "UPDATE ".TBL_USERS." SET password = '".md5($password)."' WHERE id = ".$user_id;
                //echo $query; exit;
                mysql_query($query);

                $array = array("status"=>"CHANGE_PASSWORD_1","message"=>"Password changed successfully.");
                return json_encode($array);
            }
            else{
                $array = array("status"=>"CHANGE_PASSWORD_0","message"=>"Password doesn't match confirm password.");
                return json_encode($array);
            }
        }
        else{
            $array = array("status"=>"CHANGE_PASSWORD_0","message"=>"Old password doesn't match existing password.");
            return json_encode($array);
        }
}

function get_birthday($id){
    $user_birthday_query = "SELECT * FROM ".TBL_USER_GREETINGS." WHERE user_id = ".$id." AND type=2 AND deleted = 0 AND title = 'Happy Birthday'";
    //echo $user_birthday_query; exit;
    
    $admin_birthday_query = "SELECT * FROM ".TBL_ADMIN_GREETINGS." WHERE type=2 AND deleted = 0 AND title = 'Happy Birthday'";
    //echo $admin_birthday_query; exit;
    
    $user_birthday_detail = mysql_query($user_birthday_query);
    
    if(mysql_num_rows($user_birthday_detail)>0){
        $birthday_detail = mysql_fetch_object($user_birthday_detail);
        $reminder_detail['id'] = $birthday_detail->id;
        $reminder_detail['audio_url'] = "http://".$_SERVER['HTTP_HOST'].AUDIO_URL.$birthday_detail->audio_url;
        $reminder_detail['text_message'] = $birthday_detail->text_message;
        return $reminder_detail;
    }
    else{
        $admin_birthday_detail = mysql_query($admin_birthday_query);
        if(mysql_num_rows($admin_birthday_detail)>0){
            $birthday_detail = mysql_fetch_object($admin_birthday_detail);
            $reminder_detail['id'] = $birthday_detail->id;
            $reminder_detail['audio_url'] = "http://".$_SERVER['HTTP_HOST'].AUDIO_URL.$birthday_detail->audio_url;
            $reminder_detail['text_message'] = $birthday_detail->text_message;
            return $reminder_detail;
        }
        else{
            return false;
        }
    } 
}

function get_anniversary($id){
    $user_birthday_query = "SELECT * FROM ".TBL_USER_GREETINGS." WHERE user_id = ".$id." AND type=2 AND deleted = 0 AND title = 'Happy Anniversary'";
    //echo $user_birthday_query; exit;
    
    $admin_birthday_query = "SELECT * FROM ".TBL_ADMIN_GREETINGS." WHERE type=2 AND deleted = 0 AND title = 'Happy Anniversary'";
    //echo $admin_birthday_query; exit;
    
    $user_birthday_detail = mysql_query($user_birthday_query);
    
    if(mysql_num_rows($user_birthday_detail)>0){
        $birthday_detail = mysql_fetch_object($user_birthday_detail);
        $reminder_detail['id'] = $birthday_detail->id;
        $reminder_detail['audio_url'] = "http://".$_SERVER['HTTP_HOST'].AUDIO_URL.$birthday_detail->audio_url;
        $reminder_detail['text_message'] = $birthday_detail->text_message;
        return $reminder_detail;
    }
    else{
        $admin_birthday_detail = mysql_query($admin_birthday_query);
        if(mysql_num_rows($admin_birthday_detail)>0){
            $birthday_detail = mysql_fetch_object($admin_birthday_detail);
            $reminder_detail['id'] = $birthday_detail->id;
            $reminder_detail['audio_url'] = "http://".$_SERVER['HTTP_HOST'].AUDIO_URL.$birthday_detail->audio_url;
            $reminder_detail['text_message'] = $birthday_detail->text_message;
            return $reminder_detail;
        }
        else{
            return false;
        }
    }
}

?> 