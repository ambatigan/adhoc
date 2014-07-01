<?php
define('CONFIG_FILE_PATH', '../include/config.php');
require_once(CONFIG_FILE_PATH);
include('config.php');

include_once(SITE_PATH . 'services/dbcredentials.php');
include_once(SITE_PATH . 'services/mysql.php');

include_once(SITE_PATH . 'include/table_vars.php');

//echo date_default_timezone_get();

//date_default_timezone_set('Asia/Kuwait');
//pg_query("SET TIME ZONE = '".date('P')."'");

//date_default_timezone_set('Asia/Kuwait');
//pg_query("SET TIME ZONE = '".date('P')."'");


//mysql_query("SET NAMES utf8mb4");
//mysql_set_charset('utf8mb4');

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

	//$json_array = json_decode($_POST['json']);
	$json_array = json_decode(stripslashes($_POST['json']));

	/*********************************/
	// Registration
	/*********************************/
	if($json_array->name == 'register')
	{
        $json_array->body->status = 'Active';
        $json_array->body->created_on = date('Y-m-d H:i:s');
        $json_array->body->modified_on = date('Y-m-d H:i:s');
        $json_array->body->deleted = 0;
        $json_array->body->birthdate = date('Y-m-d H:i:s',strtotime($json_array->body->birthdate));


		echo FBregister($json_array->body);
	}

    /*********************************/
	// GetAllPhotos
	/*********************************/
	if($json_array->name=='GetAllPhotos')
	{

		echo GetAllPhotos($json_array->body);
	}

    /*********************************/
	// Photo Detail Web Service
	/*********************************/
    if($json_array->name=='GetPhotoDetail')
	{
	    $photoID = $json_array->body->photoId;
        $json_array->body->created_on = date('Y-m-d H:i:s');
        $json_array->body->modified_on = date('Y-m-d H:i:s');
        $json_array->body->deleted = 0;
		echo GetPhotoDetail($json_array->body);
	}

    /*********************************/
    // Comment list on photo
	/*********************************/

	if($json_array->name=='GetPhotoComments')
	{
	   	echo GetPhotoComments($json_array->body);
	}

    /*********************************/
	// Flag Photo
	/*********************************/
    if($json_array->name=='FlagPhoto')
	{
        $json_array->body->created_on = date('Y-m-d H:i:s');
        $json_array->body->modified_on = date('Y-m-d H:i:s');
        $json_array->body->deleted = 0;
		echo FlagPhoto($json_array->body);
	}

    /*********************************/
	// Flag Comment
	/*********************************/
	if($json_array->name=='FlagComment')
	{
        $json_array->body->created_on = date('Y-m-d H:i:s');
        $json_array->body->modified_on = date('Y-m-d H:i:s');
        $json_array->body->deleted = 0;
		echo FlagComment($json_array->body);
	}

    /*********************************/
	// GetUserProfile
	/*********************************/
	if($json_array->name=='GetUserProfile')
	{
		echo GetUserProfile($json_array->body);
	}

     /*********************************/
	// updateUserProfile
     /*********************************/

	if($json_array->name=='updateUserProfile')
    {
        echo updateUserProfile($json_array->body);
    }

    /*********************************/
      // Add a new photo
	/*********************************/

	if($json_array->name=='AddPhoto')
	{
        $json_array->body->status = 'Active';
        $json_array->body->flag = 'Inactive';
        $json_array->body->created_on = date('Y-m-d H:i:s');
        $json_array->body->modified_on = date('Y-m-d H:i:s');
        $json_array->body->deleted = 0;

	   	echo AddPhoto($json_array->body);
	}

    /*********************************/
    // Update photo
	/*********************************/

	if($json_array->name=='updatePhoto')

	{
	   	echo updatePhoto($json_array->body);
	}

    /*********************************/
	// AddAccessToken
	/*********************************/
	if($json_array->name=='AddAccessToken')
	{
	   $json_array->body->created_on = date('Y-m-d H:i:s');
		echo AddAccessToken($json_array->body);
	}

	/*********************************/
	// UpdateAccessToken
	/*********************************/
	if($json_array->name=='UpdateAccessToken')
	{
	   $json_array->body->created_on = date('Y-m-d H:i:s');
		echo UpdateAccessToken($json_array->body);
	}

     /*********************************/
	// Add Comment
	/*********************************/
	if($json_array->name=='AddComment')
	{
        $json_array->body->flag = 'Inactive';
        $json_array->body->status = 'Active';
        $json_array->body->created_on = date('Y-m-d H:i:s');
        $json_array->body->modified_on = date('Y-m-d H:i:s');
        $json_array->body->deleted = 0;
		echo AddComment($json_array->body);
	}

    /*********************************/
	// Addlike
	/*********************************/
	if($json_array->name=='Addlike')
	{
		echo Addlike($json_array->body);
	}

}


function FBregister($body)
{
    if($body->firstname == '' || $body->lastname == '' ||$body->emailid == '' || $body->profileimage == ''  )
	{
		$array = array("status"=>"REGGISTERATION_0","message"=>"Please Fill All Fields");
		return json_encode($array);
	}
    else
    {

        if(!filter_var($body->emailid , FILTER_VALIDATE_EMAIL))
        {
            $array = array("status"=>"REGGISTERATION_0","message"=>"Please Enter Valid Email Id");
		    return json_encode($array);
        }
        else
        {
            $block_user_chk = " SELECT id FROM users WHERE (user_name = '".mysql_real_escape_string($body->firstname.' '.$body->lastname)."' OR email = '".mysql_real_escape_string($body->emailid)."') AND status = 'Blocked'";

            if(mysql_num_rows($result = mysql_query($block_user_chk)))
        	{
		        $array = array("status"=>"REGGISTERATION_0","message"=>"User Blocked!");
		        return json_encode($array);

	        }else{
	            $qry_chk = " SELECT id FROM users WHERE (user_name = '".mysql_real_escape_string($body->firstname.' '.$body->lastname)."' OR email = '".mysql_real_escape_string($body->emailid)."')";
	            if(mysql_num_rows($res = mysql_query($qry_chk)) > 0)
	            {
		            $array = array("status"=>"REGGISTERATION_0","message"=>"User Already Exist!");
		            return json_encode($array);
	            }
	            else
	            {
		        // id field is not auto increment so have to get max id for insert new data

		        //$qry_get_max_id = " SELECT max(id) AS max_id  FROM bmuser ";
	            //	$max_id = pg_fetch_row(pg_query($qry_get_max_id));

		        $qry = " INSERT INTO users (
                    first_name,
                    last_name,
                    user_name,
                    email,
                    gender,
                    birthdate,
                    status,
                    created_on,
                    modified_on,
                    deleted
                    )
                 VALUES
                    (
                    '".$body->firstname."',
                    '".$body->lastname."',
                    '".$body->firstname." ".$body->lastname."',
                    '".$body->emailid."',
                    '".$body->sex."',
                    '".$body->birthdate."',
                    '".$body->status."',
                    '".$body->created_on."',
                    '".$body->modified_on."',
                    '".$body->deleted."'
                    )";
                    
                    
            $profileResult = mysql_query($qry);
            $lastUserid =  mysql_insert_id();
        
            $profile_image = str_replace(' ','+',$body->profileimage);
            $imgname= "user_iphone_".$lastUserid.".jpg";
            $fp = fopen(USER_IMAGE_PATH.$imgname, "w");
            fwrite($fp, base64_decode($profile_image));
            fclose($fp);
            $profile_image = $imgname;
        
            $qryUpdate = "UPDATE users SET image = '".$profile_image."'
                    WHERE id = '".$lastUserid."' ";


		    if(mysql_query($qryUpdate))
		    {
			$result = array();
			$result['user_id'] =  $lastUserid;
            
                $array = array("status"=>"REGGISTERATION_1","message"=>"Registered Successfully","data"=>$result);
			    return json_encode($array);
		    }else{
                $array = array("status"=>"REGGISTERATION_0","message"=>"Registration failed");
			    return json_encode($array);
	            }
	        }

            }
        }
   }
}


function GetAllPhotos($body)

{
  if($body->start == '' || $body->total_record == '')
	{
		$array = array("status"=>"GET_ALL_PHOTOS_0","message"=>"Please Fill All Fields");
		return json_encode($array);
	}
    else
    {
    $searchText = '';
    if($body->searchText){

        $searchText .= " AND u.user_name LIKE '%".$body->searchText."%' OR t.name LIKE '%".$body->searchText."%'";
    }
		$qry = "SELECT COUNT(c.id) as no_of_comments, t.name as tagsName, p.id as photoId,p.user_id,p.name,p.created_on,p.image,p.tag,p.status,p.created_by,p.no_of_likes,u.user_name ,p.deleted
                FROM photo as p INNER JOIN users as u ON  u.id = p.user_id  LEFT JOIN comments as c ON c.photo_id = p.id AND c.status = 'Active' LEFT JOIN tags as t ON t.photo_id = p.id
                WHERE p.status = 'Active' AND p.deleted = 0 $searchText

                GROUP BY p.id ORDER BY p.id DESC LIMIT ".$body->start.", ".$body->total_record." ";


            $result = array();
            $resultFinal = array();

            //$results = mysql_query($qry_tags);
		    $iConter = 0;
        if(@mysql_num_rows($res = @mysql_query($qry))){
			while ($row = mysql_fetch_assoc($res))
			{
			    $result['data'][$iConter] = array(
                    'photoId' => $row['photoId'],
                    'photoUrl'=> $row['image'],
                    'createdUsreid' => $row['user_id'],
                    'createdUsername' => $row['user_name'],
                    'numberOfLikes' => $row['no_of_likes'],
                    'numberOfComments' => $row['no_of_comments'],
                    'createdDate' => $row['created_on'],
                    );
        $qry_tags = "SELECT t.name, t.id, t.user_id, t.photo_id
                FROM tags as t, photo as p
                WHERE p.status = 'Active' AND t.photo_id = p.id AND t.status = 'Active' AND t.photo_id = '".$row['photoId']."' ";
        if(@mysql_num_rows($results = @mysql_query($qry_tags))){
            $resultTags = array();
               	while ($rows = mysql_fetch_assoc($results))
			     {

                    $resultTags[] = array('name' => $rows['name'],
                                    'id' => $rows['id']);
			     }
        }

            $result['data'][$iConter]['tags'] = $resultTags;
            $iConter ++;
			}

			$array = array("status"=>"GET_ALL_PHOTOS_1","message"=>"Request Success","data"=>$result);
			return json_encode($array);
		}
		else
		{
			$array = array("status"=>"GET_ALL_PHOTOS_0","message"=>"Not any Inquiry Submitted By This User");
			return json_encode($array);
		}
        }
	}

function GetPhotoDetail($body)
{
  if($body->photoId == '')
	{
		$array = array("status"=>"GET_PHOTO_DETAIL_0","message"=>"Please Fill All Fields");
		return json_encode($array);
	}
    else
    {

    $comment_query = "SELECT c.photo_id,c.comment FROM comments as c WHERE c.deleted=0 AND c.photo_id = '".mysql_real_escape_string($body->photoId)."' AND c.flag='inactive' ";
    $comment = mysql_query($comment_query);

    $photo_query = "SELECT p.id,p.user_id,p.image,u.user_name,p.no_of_likes,p.created_on,p.tag,p.deleted,u.deleted from photo as p INNER JOIN users as u ON u.id = p.user_id where p.id = '".mysql_real_escape_string($body->photoId)."' and p.deleted=0 and u.deleted=0";
    $photo_detail = mysql_query($photo_query);
    $photo_details = mysql_fetch_array($photo_detail);

    $count_comment = mysql_num_rows($comment);

    $photo_tags = explode(',',$photo_details['tag']);


		if(mysql_query($comment_query) && mysql_num_rows($res = mysql_query($photo_query)) > 0)
		{
		    $result = array();
            $result['PhotoId'] = $photo_details['id'];
            $result['PhotoUrl'] = $photo_details['image'];
            $result['createdUserid'] = $photo_details['user_id'];
            $result['createdUserName'] = $photo_details['user_name'];
            $result['numberOfLikes'] = $photo_details['no_of_likes'];
            $result['NumberOfComments'] = $count_comment;
            $result['createdDate'] = $photo_details['created_on'];
            $result['tags']['name'] = $photo_tags;
			$array = array("status"=>"GET_PHOTO_DETAIL_1","message"=>"Request Success","detail"=>$result);
			return json_encode($array);
		}else{
			$array = array("status"=>"GET_PHOTO_DETAIL_0","message"=>"Request Failed");
			return json_encode($array);
	     }
    }
}


function GetPhotoComments($body)
{
	if($body->Pid == '')
	{
		$array = array("status"=>"GET_PHOTO_COMMENTS_0","message"=>"Please Fill All Fields");
		return json_encode($array);
	}
    else
    {
        $qry = " SELECT c.comment,c.id,c.photo_id,c.user_id,u.user_name,u.status,c.created_on,p.status,c.status,c.flag,p.image,c.deleted,p.deleted,u.deleted
                FROM comments AS c
                JOIN users AS u ON c.user_id = u.id
                JOIN photo AS p ON c.photo_id = p.id
                WHERE c.photo_id = '".$body->Pid. "'
                AND p.status = 'Active' AND c.status = 'Active' AND u.status = 'Active'
                AND c.flag = 'Inactive'  AND p.flag = 'Inactive'
                AND c.deleted = 0 AND p.deleted = 0 AND u.deleted = 0
                GROUP BY c.id ORDER BY c.id DESC";
        $result = array();
        if(@mysql_num_rows($res = @mysql_query($qry))){
			while ($row = mysql_fetch_assoc($res))
			{
			    $result['data'][] = array(
                    'CommentId' => $row['id'],
                    'CommentText' => $row['comment'],
                    'Usreid' => $row['user_id'],
                    'Username' => $row['user_name'],
                    'photoUrl'=> $row['image'],
                    'createDate' => $row['created_on'],
                    );


            }
             $array = array("status"=>"GET_PHOTO_COMMENTS_1","message"=>"Request Success","data"=>$result);
             return json_encode($array);

         }
         else
		{
			$array = array("status"=>"GET_PHOTO_COMMENTS_0","message"=>"No Records Found");
			return json_encode($array);
		}
     }

}


function FlagPhoto($body)
{
  if($body->photoId == '' || $body->loginUserId == '' || $body->createduserId == '' )
	{
		$array = array("status"=>"FLAG_PHOTO_0","message"=>"Please Fill All Fields");
		return json_encode($array);
	}
    else
    {
        $flag = "UPDATE photo SET flag = 'Active' WHERE id = '".$body->photoId."'";
        if(mysql_query($flag))
        {
        $query = "INSERT INTO photo_flag(photo_id,login_user_id,created_user_id,created_on,modified_on,deleted)VALUES('".$body->photoId."','".$body->loginUserId."','".$body->createduserId."','".$body->created_on."','".$body->modified_on."','".$body->deleted."')";
		if(mysql_query($query))
		{
			$array = array("status"=>"FLAG_PHOTO_1","message"=>"Request Success");
			return json_encode($array);
		}else{
			$array = array("status"=>"FLAG_PHOTO_0","message"=>"Request Failed");
			return json_encode($array);
	     }
    }
    else
    {
        $array = array("status"=>"FLAG_PHOTO_0","message"=>"Invalid Photo Id");
		return json_encode($array);
    }
  }
}



function FlagComment($body)
{
  if($body->commentId == '' || $body->loginUserId == '' || $body->createduserId == '' )
	{
		$array = array("status"=>"FLAG_COMMENT_0","message"=>"Please Fill All Fields");
		return json_encode($array);
	}
    else
    {
	    $flag = "UPDATE comments SET flag = 'Active' WHERE id = '".$body->commentId."'";
        if(mysql_query($flag))
        {
            $query = "INSERT INTO comment_flag(comment_id,login_user_id,created_user_id,created_on,modified_on,deleted)VALUES('".$body->commentId."','".$body->loginUserId."','".$body->createduserId."','".$body->created_on."','".$body->modified_on."','".$body->deleted."')";
		    if(mysql_query($query))
		    {

                $array = array("status"=>"FLAG_COMMENT_1","message"=>"Request Success");
			    return json_encode($array);
            }
            else
            {
                $array = array("status"=>"FLAG_COMMENT_0","message"=>"Request Failed");
			    return json_encode($array);
            }
        }
        else
        {
            $array = array("status"=>"FLAG_COMMENT_0","message"=>"Invalid Comment Id");
			return json_encode($array);
        }
    }
}


function GetUserProfile($body){
  if($body->userid == '')
	{
		$array = array("status"=>"GET_USER_PROFILE_0","message"=>"Please Fill All Fields");
		return json_encode($array);
	}
    else
    {

   	    $qryUserTags = "SELECT COUNT(t.id) as no_of_tags, t.name as tagsName,t.id as tagid, u.user_name, u.id as userid, u.image
                FROM users as u  INNER JOIN tags as t  ON  u.id = t.user_id
                WHERE u.status = 'Active' AND u.status != 'Blocked' AND u.id = '".$body->userid."'
                GROUP BY t.name ORDER BY no_of_tags DESC ";

        $qryUser = "SELECT u.user_name, u.id as userid, u.image, u.deleted
                FROM users as u
                WHERE u.status = 'Active' AND u.status != 'Blocked' AND u.deleted = 0 AND u.id = '".$body->userid."' ";


            $result = array();
            $resultFinal = array();

            //$results = mysql_query($qry_tags);
		    $iConter = 0;
        if(@mysql_num_rows($res = @mysql_query($qryUser))){
			while ($row = mysql_fetch_assoc($res))
			{
			    $result['data'] = array(
                    'userid' => $row['userid'],
                    'userName' => $row['user_name'],
                    'profilePhoto'=> $row['image'],
                    );

        if(@mysql_num_rows($results = @mysql_query($qryUserTags))){
            //$resultTags = array();
               	while ($rows = mysql_fetch_assoc($results))
			     {

                    $resultTags[] = array('name' => $rows['tagsName'],
                                    'id' => $rows['tagid']);
			     }
        }

            $result['data']['tags'] = $resultTags;
            $iConter ++;
			}

			$array = array("status"=>"GET_USER_PROFILE_1","message"=>"Request Success","data"=>$result);
			return json_encode($array);
		}
		else
		{
			$array = array("status"=>"GET_USER_PROFILE_0","message"=>"Not any Inquiry Submitted By This User");
			return json_encode($array);
		}
    }
}

function updateUserProfile($body){

    $successFlag = 0;

    $profile_image = str_replace(' ','+',$body->profile_image);
    $imgname= time()."_user_iphone.jpg";
    $fp = fopen(USER_IMAGE_PATH.$imgname, "w");
    fwrite($fp, base64_decode($profile_image));
    fclose($fp);
    $profile_image = $imgname;

    if($body->userid == '')
	{
		$array = array("status"=>"UPDATE_USER_PROFILE_0","message"=>"Please Fill All Fields");
		return json_encode($array);
	}
    if( $body->username == '' && $body->profile_image == '' ){

        $array = array("status"=>"UPDATE_USER_PROFILE_0","message"=>"No data found to update!");
		return json_encode($array);
    }

    if( $body->profile_image != '' )
    {

    $qryUpdateImage = "UPDATE users SET image = '".$profile_image."'
            WHERE id = '".$body->userid."' ";

            $response = mysql_query($qryUpdateImage);
            $successFlag = 1;
    }
    if($body->username != ''){

    $qry = "UPDATE users SET user_name = '".$body->username."'
                WHERE id = '".$body->userid."' ";
                $response = mysql_query($qry);
                $successFlag = 1;
    }

		if($successFlag == 1)
		{
			$array = array("status"=>"UPDATE_USER_PROFILE_1","message"=>"Request Success");
			return json_encode($array);
		}else{
			$array = array("status"=>"UPDATE_USER_PROFILE_0","message"=>"Request Failed");
			return json_encode($array);
	     }
   }

function AddPhoto($body){
    if($body->userId){

    $qry = "INSERT INTO photo
            SET
            user_id = '".$body->userId."',
            name = '".$body->captionText."',
            tag = '".$body->tags."',
            status = '".$body->status."',
            flag = '".$body->flag."',
            created_on = '".$body->created_on."',
            modified_on = '".$body->modified_on."',
            deleted = '".$body->deleted."' ";

            $results = mysql_query($qry);

            $photoId = mysql_insert_id();

            $image_text = str_replace(' ','+',$body->photoBase64String);
            $imgname= "photo_".$photoId.".jpg";
            $fp = fopen(PHOTO_IMAGE_PATH.$imgname, "w");
            fwrite($fp, base64_decode($image_text));
            fclose($fp);
            $image_text = $imgname;

           $Tags = array();
           $Tags = explode(',',$body->tags);

            for($i=0;$i<count($Tags);$i++){

            $qryTags = "INSERT INTO tags
                    SET
                    name = '".$Tags[$i]."',
                    user_id = '".$body->userId."',
                    photo_id = '".$photoId."',
                    status = 'Active',
                    created_on = '".$body->created_on."',
                    modified_on = '".$body->modified_on."',
                    deleted = '".$body->deleted."'
                    ";
                    $results = mysql_query($qryTags);

              }

    $qryUpdate = "UPDATE photo SET image = '".$image_text."'
                  WHERE id = '".$photoId."' ";


		if(mysql_query($qryUpdate))
		{
            $result = array();
			$result['photo_id'] =  $photoId;
			$array = array("status"=> "PHOTO_ADD_1" ,"message" => "Photo Successfully Added","data"=>$result);
			return json_encode($array);
		}else{
			$array = array("status"=> "PHOTO_ADD_0" ,"message" => "Fail to add photo");
			return json_encode($array);
	     }
      }else{

        $array = array("status"=> "PHOTO_ADD_0" ,"message" => "Please Fill All Fields");
		return json_encode($array);

      }
   }


 function updatePhoto($body){

    $profile_image = str_replace(' ','+',$body->profile_image);
    $imgname= "photo_".$body->Pid.".jpg";
    $fp = fopen(PHOTO_IMAGE_PATH.$imgname, "w");
    fwrite($fp, base64_decode($profile_image));
    fclose($fp);
    $profile_image = $imgname;

    if($body->Pid == '')
	{
		$array = array("status"=>"UPDATE_PHOTO_0","message"=>"Please Fill All Fields");
		return json_encode($array);
	}
    else
    {
        $qry_photo = " SELECT id,user_id FROM photo WHERE id = '".$body->Pid."'  AND deleted = 0";
        if(mysql_num_rows($res = mysql_query($qry_photo)) > 0)
       	{
       	    while ($row = mysql_fetch_assoc($res))
			{
                $user = $row['user_id'];
			}
            $qry=" UPDATE photo SET name = '".$body->Caption."',
                                    image ='".$profile_image."',
                                    tag = '".$body->Tag."',
                                    modified_on = '".date('y-m-d')."'
                                    WHERE id = '".$body->Pid."' ";

	        if(mysql_query($qry))
		    {
		        $qry_check = "SELECT name,user_id FROM tags WHERE photo_id = '".$body->Pid."' AND name = '".$body->Tag."' AND user_id = '".$user."' ";
                if(mysql_num_rows($result = mysql_query($qry_check)) > 0)
       	        {
       	            $array = array("status"=>"UPDATE_PHOTO_1","message"=>"Request Success");
			        return json_encode($array);
       	        }
                else
                {
                    $insert = "INSERT INTO tags(name,user_id,photo_id,created_on,modified_on) VALUES('".$body->Tag."','".$user."','".$body->Pid."','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."')";
                    if(mysql_query($insert))
                    {
                        $array = array("status"=>"UPDATE_PHOTO_1","message"=>"Success");
			            return json_encode($array);
                    }
                }
	        }
            else
            {
			    $array = array("status"=>"UPDATE_PHOTO_0","message"=>"Request Failed");
		        return json_encode($array);
	        }
        }
        else
        {
            $array = array("status"=>"UPDATE_PHOTO_0","message"=>"Invalid PhotoId");
		    return json_encode($array);
        }
    }

 }

function AddAccessToken($body){
  if($body->token == '')
	{
		$array = array("status"=>"ADD_ACCESS_TOKEN_0","message"=>"Please Fill All Fields");
		return json_encode($array);
	}
    else
    {
        $check_token="SELECT device_token FROM devicetoken WHERE device_token = '".$body->token."'";
        if(mysql_num_rows($res = mysql_query($check_token)) > 0)
            {
                $array = array("status"=>"ADD_ACCESS_TOKEN_0","message"=>"token already exist");
                return json_encode($array);
            }
            else
            {
            $qry = "INSERT INTO devicetoken SET device_token = '".$body->token."', badge_number = '".$body->badge."',
            created_on = '".$body->created_on."' ";

		    if(mysql_query($qry))
		    {
			    $array = array("status"=>"ADD_ACCESS_TOKEN_1","message"=>"Request Success");
			    return json_encode($array);
		    }else{
			    $array = array("status"=>"ADD_ACCESS_TOKEN_0","message"=>"Request Failed");
			    return json_encode($array);
	        }
            }
     }
}

function UpdateAccessToken($body){
  if($body->token == '' || $body->badge == '')
	{
		$array = array("status"=>"UPDATE_ACCESS_TOKEN_0","message"=>"Please Fill All Fields");
		return json_encode($array);
	}
    else
    {

    $qry = "UPDATE devicetoken SET badge_number = '".$body->badge."',created_on = '".$body->created_on."'
            WHERE device_token = '".$body->token."' ";

		if(mysql_query($qry))
		{
			$array = array("status"=>"UPDATE_ACCESS_TOKEN_1","message"=>"Request Success");
			return json_encode($array);
		}else{
			$array = array("status"=>"UPDATE_ACCESS_TOKEN_0","message"=>"Request Failed");
			return json_encode($array);
	     }
        }
   }


function AddComment($body)
{

if($body->photoId == '' || $body->commentText == '' || $body->userId == '' )
	{
		$array = array("status"=>"ADD_COMMENT_0","message"=>"Please Fill All Fields");
		return json_encode($array);
	}
    else
    {

$query = " INSERT INTO comments (
                  user_id,
                  photo_id,
                  comment,
                  flag,
                  status,
                  created_on,
                  modified_on,
                  deleted
                  )
          VALUES
                  (
                  '".$body->userId."',
                  '".$body->photoId."',
                  '".$body->commentText."',
                  '".$body->flag."',
                  '".$body->status."',
                  '".$body->created_on."',
                  '".$body->modified_on."',
                  '".$body->deleted."'
                  )";


		if(mysql_query($query))
		{
			$array = array("status"=>"ADD_COMMENT_1","message"=>"Request Success");
			return json_encode($array);
		}else{
			$array = array("status"=>"ADD_COMMENT_0","message"=>"Request Failed");
			return json_encode($array);
	     }
    }
}


function Addlike($body)
{
	if($body->Uid == '' || $body->Pid == '')
	{
		$array = array("status"=>"ADD_LIKE_0","message"=>"Please Fill All Fields");
		return json_encode($array);
	}
    else
    {
        	     $qry_user = " SELECT user_id , id FROM photo WHERE user_id = '".$body->Uid."' AND id = '".$body->Pid."' AND deleted = 0 ";
            	if(mysql_num_rows($res = mysql_query($qry_user)) > 0)
	           	{
			           $qry = "INSERT INTO photo_user_like (user_id,photo_id,no_of_like,created_on) VALUES ('".$body->Uid."',
                       '".$body->Pid."','1','".date('y-m-d')."')";
                       if(mysql_query($qry))
		               {
                            $qry_photo = " UPDATE photo SET no_of_likes = no_of_likes +1  WHERE user_id = '".$body->Uid."'
                             AND id = '".$body->Pid."' ";
                            if(mysql_query($qry_photo))
                            {
			                        $array = array("status"=>"ADD_LIKE_1","message"=>"Add like Successfull","data"=>$result);
			                        return json_encode($array);
                            }
                            else
                            {
                                   $array = array("status"=>"ADD_LIKE_0","message"=>"Add Like is Failed");
				                   return json_encode($array);
                            }

			            }
                        else
                        {
                            $array = array("status"=>"ADD_LIKE_0","message"=>"Add Like Failed");
				            return json_encode($array);
                        }
		        }
                 else
                {
                      $array = array("status"=>"ADD_LIKE_0","message"=>"Invalid User Id or Photo Id");
			          return json_encode($array);

                }
    }

}

?>