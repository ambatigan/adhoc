<?php 
define('CONFIG_FILE_PATH', '../include/config.php');
include('apns_files/apns.php');
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
        
        $json_array->body->username = mb_substr($json_array->body->firstname,0,5).ucfirst(mb_substr($json_array->body->lastname,0,1));
        
        //$json_array->body->lastname = mb_substr($json_array->body->lastname,0,1);
        
        $json_array->body->birthdate = date('Y-m-d H:i:s',strtotime($json_array->body->birthdate));
        
        //$json_array->body->emailid = trim($json_array->body->emailid);

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
    
    
     /*********************************/
	// GetAllPhotos
	/*********************************/
	if($json_array->name=='ListOfUserAdvertisement')
	{

		echo ListOfUserAdvertisement($json_array->body);
	}
    
    /*********************************/
	// Delete Advertisement
	/*********************************/
	if($json_array->name=='DeleteUserAdvertisement')
	{

		echo deleteadvertisement($json_array->body);
	}
    
     /*********************************/
	// List of users who liked ads
	/*********************************/
	if($json_array->name=='listofuserswholikedads')
	{

		echo listofuserswholikedads($json_array->body);
	}
    
    

}


function FBregister($body)
{

/* Commented on 12-07-2013 */

   // if($body->firstname == '' || $body->lastname == '' ||$body->emailid == '' || $body->profileimage == ''  )
//	{
//		$array = array("status"=>"REGGISTERATION_0","message"=>"Please Fill All Fields");
//		return json_encode($array);
//	}

    if( $body->emailid == '' )
	{
		$array = array("status"=>"REGGISTERATION_0","message"=>"Please Enter Email Address!");
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
           
            $block_user_chk = " SELECT id FROM users WHERE email = ( '".mysql_real_escape_string($body->emailid)."') AND status = 'Blocked'";

            if(mysql_num_rows($result = mysql_query($block_user_chk)))
        	{
		        $array = array("status"=>"REGGISTERATION_0","message"=>"User Blocked!");
		        return json_encode($array);

	        }else{
	            $qry_chk = " SELECT id,user_name,image FROM users WHERE email = ('".mysql_real_escape_string($body->emailid)."') AND deleted = 0";
	            if(mysql_num_rows($res = mysql_query($qry_chk)) > 0)
	            {
	               while ($rows = mysql_fetch_assoc($res))
			         {

                        $resultUser['data'] = array(
                                            'user_id' => $rows['id'],
                                            'user_name' => $rows['user_name'],
                                            'userProfileImage' => $rows['image']
                                            );
			         }
		            $array = array("status"=>"REGGISTERATION_1","message"=>"User Already Exist!","data"=>$resultUser);
		            return json_encode($array);
	            }
	            else
	            {
                 $qry_chk = " SELECT id FROM users WHERE user_name = ('".mysql_real_escape_string($body->username)."')";
                 $userName = $body->username;
                 
	            if(mysql_num_rows($res = mysql_query($qry_chk)) > 0)
	            {
	               $record_exists = true;
                   $uniqueNum = randomDigits(4);
                   $userNameNew = $userName.$uniqueNum;
                    $i = 0;
                    while($record_exists) {
                       // $q=mysql_query("SELECT Title FROM posts WHERE Title = '$title'");
                       
                       $q=mysql_query("SELECT id FROM users WHERE user_name = ('".mysql_real_escape_string($userNameNew)."')");
                       
                       $num=mysql_num_rows($q);
                       
                        if($num==0) {
                            $record_exists = false;
                            // Exit the loop.
                        }else {
                            $i++;
                            $uniqueNumber = randomDigits(4);
                            $userNameNew = $userName.$uniqueNumber;
                        }
                    }
                   
	            }

		        $qry = " INSERT INTO users (
                    first_name,
                    last_name,
                    user_name,
                    email,
                    gender,
                    birthdate,
                    status,
                    hometown,
                    created_on,
                    modified_on,
                    deleted
                    )
                 VALUES
                    (
                    '".$body->firstname."',
                    '".$body->lastname."',
                    '".$userName."',
                    '".$body->emailid."',
                    '".$body->sex."',
                    '".$body->birthdate."',
                    '".$body->status."',
                    '".$body->hometown."',
                    '".$body->created_on."',
                    '".$body->modified_on."',
                    '".$body->deleted."'
                    )";
            
            
          if(mysql_query($qry))
		  {
		      
                $lastUserid =  mysql_insert_id();
            
            if($body->profileimage != ''){
                
                $profile_image = str_replace(' ','+',$body->profileimage);
                $imgname= "user_iphone_".$lastUserid."_".time().".jpg";
                //echo USER_IMAGE_PATH.$imgname;exit;
                $fp = fopen(USER_IMAGE_PATH.$imgname, "w");
                fwrite($fp, base64_decode($profile_image));
                fclose($fp);
                $profile_image = $imgname;
            
                $qryUpdate = "UPDATE users SET image = '".$profile_image."'
                        WHERE id = '".$lastUserid."' ";
                mysql_query($qryUpdate);
            }
		   
			$result = array();
            $results = array();
			$result['user_id'] =  $lastUserid;
            $result['user_name'] = $userName;
            $result['userProfileImage'] = $profile_image;
            
            $results['data'] = $result;
            
                $array = array("status"=>"REGGISTERATION_1","message"=>"Registered Successfully","data"=>$results);
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
		$qry = "SELECT t.name as tagsName, p.id as photoId,p.user_id,p.name,p.created_on,p.image,p.tag,p.status,p.created_by,p.no_of_likes,u.user_name,u.image as userImage,p.deleted
                FROM photo as p INNER JOIN users as u ON  u.id = p.user_id  LEFT JOIN comments as c ON c.photo_id = p.id AND c.status = 'Active' LEFT JOIN tags as t ON t.photo_id = p.id  
                WHERE p.status = 'Active' AND u.deleted = 0 AND u.status = 'Active' AND p.deleted = 0 $searchText

                GROUP BY p.id ORDER BY p.id DESC LIMIT ".$body->start.", ".$body->total_record." ";


            $result = array();
            $resultFinal = array();

            //$results = mysql_query($qry_tags);
		    $iConter = 0;
            $isUserHasLiked = 0;
        if(@mysql_num_rows($res = @mysql_query($qry))){
			while ($row = mysql_fetch_assoc($res))
			{
			 
             $createdOn = dayAgo($row['created_on']);
             
			    $result['data'][$iConter] = array(
                    'photoId' => $row['photoId'],
                    'photoUrl'=> $row['image'],
                    'createdUsreid' => $row['user_id'],
                    'createdUsername' => $row['user_name'],
                    'profileImage' => $row['userImage'],
                    'numberOfLikes' => $row['no_of_likes'],
                    'createdDate' => $createdOn,
                    'created_on' => $row['created_on']
                    );
            $qry_user_likes = "SELECT PULike.*  
                    FROM photo_user_like as PULike, users as u
                    WHERE u.status = 'Active' AND u.deleted = 0 AND PULike.user_id = u.id AND PULike.user_id = '".$body->user_id."' AND PULike.photo_id = '".$row['photoId']."' ";
                
            $qry_tags = "SELECT t.name, t.id, t.user_id, t.photo_id
                FROM tags as t, photo as p
                WHERE p.status = 'Active' AND t.photo_id = p.id AND t.status = 'Active' AND t.photo_id = '".$row['photoId']."' ";
                
            
            $qry_comments = "SELECT COUNt(c.id) as no_of_comments
                FROM comments as c
                WHERE c.status = 'Active' AND c.photo_id = '".$row['photoId']."' GROUP BY c.photo_id ";
                
                
            if(@mysql_num_rows($resultsArr = @mysql_query($qry_tags))){
                $resultTags = array();
                   	while ($rows = mysql_fetch_assoc($resultsArr))
    			     {
    
                        $resultTags[] = array('name' => $rows['name'],
                                        'id' => $rows['id']);
    			     }
            }
            
            if(@mysql_num_rows($resultsArrLikes = @mysql_query($qry_user_likes))){
                
                   $isUserHasLiked = '1';
                 
            }else{
                    $isUserHasLiked = '0';
            }

                
            if(@mysql_num_rows($resultComments = @mysql_query($qry_comments))){
                $no_of_comments = array();
                   $rows = mysql_fetch_assoc($resultComments);
                   
                   $no_of_comments = $rows['no_of_comments'];
                 
            }else{
                $no_of_comments = array();
                    $no_of_comments = '0';
            }
            $result['data'][$iConter]['numberOfComments'] = $no_of_comments;
            $result['data'][$iConter]['tags'] = $resultTags;
            $result['data'][$iConter]['isUserHasLiked'] = $isUserHasLiked; 
            
            
            $iConter ++;
			}
             

			$array = array("status"=>"GET_ALL_PHOTOS_1","message"=>"Request Success","data"=>$result);
			return json_encode($array);
		}
		else
		{
			$array = array("status"=>"GET_ALL_PHOTOS_2","message"=>"No Records Found");
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

    $photo_query = "SELECT p.id,p.user_id,p.image,u.user_name,p.no_of_likes,p.created_on,p.tag,p.deleted,u.deleted FROM photo as p 
                    INNER JOIN users as u ON u.id = p.user_id 
                    WHERE p.id = '".mysql_real_escape_string($body->photoId)."' AND p.deleted=0 AND u.deleted=0";
    $photo_detail = mysql_query($photo_query);
    $photo_details = mysql_fetch_array($photo_detail);

    $count_comment = mysql_num_rows($comment);

    $photo_tags = explode(',',$photo_details['tag']);
    $results = array();


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
            
            $results['data'] = $result;
            
			$array = array("status"=>"GET_PHOTO_DETAIL_1","message"=>"Request Success","data"=>$results);
			return json_encode($array);
		}else{
			$array = array("status"=>"GET_PHOTO_DETAIL_2","message"=>"No Records Found");
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
        $qry = " SELECT c.comment,c.id,c.photo_id,c.user_id,u.user_name,u.status,c.created_on,p.status,c.status,c.flag,u.image,c.deleted,p.deleted,u.deleted
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
           
            
            //print_r($result);exit;
             $array = array("status"=>"GET_PHOTO_COMMENTS_1","message"=>"Request Success","data"=>$result);
             return json_encode($array);

         }
         else
		{
			$array = array("status"=>"GET_PHOTO_COMMENTS_2","message"=>"No Records Found");
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

       $qryUser = "SELECT COUNT(p.user_id) AS totalPost, u.first_name, u.hometown, u.last_name, SUM(no_of_likes) as totalLikes, u.user_name, u.id as userid, u.image, u.deleted
                FROM users as u LEFT JOIN photo as p ON u.id = p.user_id 
                WHERE u.status = 'Active' AND u.status != 'Blocked' AND u.deleted = 0 AND u.id = '".$body->userid."' AND p.deleted = 0 GROUP BY u.id ";


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
                    'first_name' => $row['first_name'],
                    'last_name' => $row['last_name'],
                    'profilePhoto'=> $row['image'],
                    'NumberOfPost'=> $row['totalPost'],
                    'NumberOfLikes'=> $row['totalLikes'],
                    'UserRank' => 'Senior Copywriter',
                    'hometown' => $row['hometown'],
                    
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
			$array = array("status"=>"GET_USER_PROFILE_2","message"=>"No Records Found");
			return json_encode($array);
		}
    }
}

function updateUserProfile($body){

    $successFlag = 0;

    $profile_image = str_replace(' ','+',$body->profile_image);
    $imgname= "user_iphone_".$body->userid."_".time().".jpg";
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

        $array = array("status"=>"UPDATE_USER_PROFILE_2","message"=>"No Records Found!");
		return json_encode($array);
    }

    if( $body->profile_image != '' )
    {

    $qryUpdateImage = "UPDATE users SET image = '".$profile_image."'
            WHERE id = '".$body->userid."' ";

            $response = mysql_query($qryUpdateImage);
            $successFlag = 1;
    }
    
     if( $body->last_name != '' )
    {

    $qryUpdateLastName = "UPDATE users SET last_name = '".$body->last_name."'
            WHERE id = '".$body->userid."' ";

            $response = mysql_query($qryUpdateLastName);
            $successFlag = 1;
    }
    
     if( $body->first_name != '' )
    {

    $qryUpdateFirstName = "UPDATE users SET first_name = '".$body->first_name."'
            WHERE id = '".$body->userid."' ";

            $response = mysql_query($qryUpdateFirstName);
            $successFlag = 1;
    }
    
     if( $body->hometown != '' )
    {

    $qryUpdateHomeTown = "UPDATE users SET hometown = '".$body->hometown."'
            WHERE id = '".$body->userid."' ";

            $response = mysql_query($qryUpdateHomeTown);
            $successFlag = 1;
    }
    
    if($body->username != ''){
        
        
                $qry_chk = " SELECT id FROM users WHERE user_name = ('".mysql_real_escape_string($body->username)."') AND id != '".$body->userid."' ";
                 $userName = $body->username;
                 
	            if(mysql_num_rows($res = mysql_query($qry_chk)) > 0)
	            {
	               /*$record_exists = true;
                   $uniqueNum = randomDigits(4);
                   $userName = $userName.$uniqueNum;
                    $i = 0;
                    while($record_exists) {
                       // $q=mysql_query("SELECT Title FROM posts WHERE Title = '$title'");
                       
                       $q=mysql_query("SELECT id FROM users WHERE user_name = ('".mysql_real_escape_string($userName)."')");
                       
                       $num=mysql_num_rows($q);
                       
                        if($num==0) {
                            $record_exists = false;
                            // Exit the loop.
                        }else {
                            $i++;
                            $uniqueNum = randomDigits(4);
                            $userName = $userName.$uniqueNum;
                        }
                    }*/
                    
                    
                    $array = array("status"=>"UPDATE_USER_PROFILE_0","message"=>"Username Already Exist! Please try with another username.");
			         return json_encode($array);
                   
	            }else{
	               
                $qry = "UPDATE users SET user_name = '".$userName."'
                        WHERE id = '".$body->userid."' ";
                        $response = mysql_query($qry);
                        $successFlag = 1;
                }
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
            $imgname= "photo_".$photoId."_".time().".jpg";
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
			$result['data']['photo_id'] =  $photoId;
            $result['data']['photourl'] =  $image_text;
            $result['data']['created_on'] =  $body->created_on;
            
            
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


    if($body->ImageData != ''){
        $profile_image = str_replace(' ','+',$body->ImageData);
        $imgname= "photo_".$body->Pid."_".time().".jpg";
        unlink(PHOTO_IMAGE_URL.$imgname);
        $fp = fopen(PHOTO_IMAGE_PATH.$imgname, "w");
        fwrite($fp, base64_decode($profile_image));
        fclose($fp);
        $profile_image = $imgname;
    }else{
        $profile_image = '';
    }
    
    /*$profile_image = str_replace(' ','+',$body->profile_image);
    $imgname= "photo_".$body->Pid.".jpg";
    $fp = fopen(PHOTO_IMAGE_PATH.$imgname, "w");
    fwrite($fp, base64_decode($profile_image));
    fclose($fp);
    $profile_image = $imgname;*/

    if($body->Pid == '')
	{
		$array = array("status"=>"UPDATE_PHOTO_0","message"=>"Please Fill All Fields");
		return json_encode($array);
	}
    else
    {
        $qry_photo = " SELECT * FROM photo WHERE id = '".$body->Pid."'  AND deleted = 0";
        if(mysql_num_rows($res = mysql_query($qry_photo)) > 0)
       	{
       	    while ($row = mysql_fetch_assoc($res))
			{
                $user = $row['user_id'];
                $photoimage = $row['image'];
                $photoname = $row['name'];
                $photoTag = $row['tag'];
                $photoCreatedOn = $row['created_on'];
			}
            if($profile_image == ''){
                $profile_image = $photoimage; 
            }
            if($body->Caption == ''){
                $body->Caption = $photoname; 
            }
            if($body->Tag == ''){
                $body->Tag = $photoTag; 
            }
            
            $qry=" UPDATE photo SET name = '".$body->Caption."',
                                    image ='".$profile_image."',
                                    tag = '".$body->Tag."',
                                    modified_on = '".date('y-m-d')."'
                                    WHERE id = '".$body->Pid."' ";

	        if(mysql_query($qry))
		    {
		      
                $Tags = array();
                $flag = 0;
                $Tags = explode(',',$body->Tag);
                
               
                $deleteTags = "DELETE FROM tags WHERE photo_id = '".$body->Pid."' AND user_id = '".$user."' ";
                mysql_query($deleteTags);
                
                 //echo '<pre>';print_r($Tags);exit;
                
		      for($i=0;$i<count($Tags);$i++){
		         
                  
                    $insert = "INSERT INTO tags(name,user_id,photo_id,created_on,modified_on) VALUES('".trim($Tags[$i])."','".$user."','".$body->Pid."','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."')";
                    mysql_query($insert);

              
		        //$qry_check = "SELECT name,user_id FROM tags WHERE photo_id = '".$body->Pid."' AND name = '".$body->Tag."' AND user_id = '".$user."' ";
                /*if(mysql_num_rows($result = mysql_query($qry_check)) > 0)
       	        {
       	            //$array = array("status"=>"UPDATE_PHOTO_1","message"=>"Request Success");
			        //return json_encode($array);
                    $flag = 0;
                    continue;
       	        }
                else
                {
                    $insert = "INSERT INTO tags(name,user_id,photo_id,created_on,modified_on) VALUES('".$body->Tag."','".$user."','".$body->Pid."','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."')";
                    if(mysql_query($insert))
                    {
                        $array = array("status"=>"UPDATE_PHOTO_1","message"=>"Success");
			            return json_encode($array);
                    }
                }*/
	           }
               
               $data['data']['photourl'] = $profile_image;
               $data['data']['photo_id'] = $body->Pid;
               $data['data']['created_on'] = $photoCreatedOn;
                
               $array = array("status"=>"UPDATE_PHOTO_1","message"=>"Request Success",'data'=>$data);
                return json_encode($array);
               
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
        $check_token="SELECT device_token FROM devicetoken WHERE device_token = '".$body->token."' AND user_id = '".$body->user_id."' ";
        if(mysql_num_rows($res = mysql_query($check_token)) > 0)
            {
                $array = array("status"=>"ADD_ACCESS_TOKEN_0","message"=>"token already exist");
                return json_encode($array);
            }
            else
            {
            $qry = "INSERT INTO devicetoken SET device_token = '".$body->token."', user_id = '".$body->user_id."', badge_number = '".$body->badge."',
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
           $qryUserName = " SELECT u.id, u.user_name FROM users as u, photo as p WHERE p.id = '".$body->photoId."' AND u.id=p.user_id AND p.deleted = 0 ";
           $resultUsername = mysql_query($qryUserName);
           $rows = mysql_fetch_assoc($resultUsername);
           
           $qryLoggedUseDetails = " SELECT id, user_name FROM users WHERE id = '".$body->userId."' AND deleted = 0 ";
           $resultLoggedUseDetails = mysql_query($qryLoggedUseDetails);
           
           $resultLoggedUseRows = mysql_fetch_assoc($resultLoggedUseDetails);
            
             // Initiate notifications object
           $apnNotification = new apnNotifications();
           $action_loc_key = 'Answer';
           $badge = 1;
           $sound = 'default';
           $match = '1';
           $nm = $resultLoggedUseRows['user_name'];
           
           $message = ucfirst($nm).' commented your advertisement';
           
           $data['photo_user_id'] = $rows['id'];
           $data['login_user_id'] = $body->userId;
           $data['photo_id'] = $body->photoId;
           
           //$from = 'Aviary IOS App';
        
           // Get device tokens from db
           $deviceTokens = getDeviceTokens($rows['id']);
                                            
           //Send notification if device tokens available
           if(!empty($deviceTokens) && $deviceTokens != '' ){
               
                     $apnNotification->sendNotification($deviceTokens,$message,$action_loc_key,$badge,$sound,$match,$data);
           }
                           
		  
          $qry_photo = " UPDATE photo SET no_of_comments = no_of_comments +1  WHERE id = '".$body->photoId."' ";
          mysql_query($qry_photo);
                             
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
        	     $qry_user = " SELECT user_id , id FROM photo WHERE id = '".$body->Pid."' AND deleted = 0 ";
            	if(mysql_num_rows($res = mysql_query($qry_user)) > 0)
	           	{
			           $qry = "INSERT INTO photo_user_like (user_id,photo_id,no_of_like,created_on) 
                                VALUES ('".$body->Uid."','".$body->Pid."','1','".date('y-m-d')."')";
                      
                       if(mysql_query($qry))
		               {
		                  $lastLikeId = mysql_insert_id();
                           $rowsPhotoUser = mysql_fetch_assoc($res);   
                           $qryUserName = " SELECT id, user_name FROM users WHERE id = '".$rowsPhotoUser['user_id']."' AND deleted = 0 ";
                           $resultUsername = mysql_query($qryUserName);
                           $rows = mysql_fetch_assoc($resultUsername);
                           
                           $qryLoggedUseDetails = " SELECT id, user_name FROM users WHERE id = '".$body->Uid."' AND deleted = 0 ";
                           $resultLoggedUseDetails = mysql_query($qryLoggedUseDetails);
                           $resultLoggedUseRows = mysql_fetch_assoc($resultLoggedUseDetails);
                            
                             // Initiate notifications object
                           $apnNotification = new apnNotifications();
                           $action_loc_key = 'Answer';
                           $badge = 1;
                           $sound = 'default';
                           $match = '1';
                           $nm = $resultLoggedUseRows['user_name'];
                           
                           $message = ucfirst($nm).' liked your Advertisement';
                           
                           $data['photo_user_id'] = $rows['id'];
                           $data['login_user_id'] = $body->Uid;
                           $data['photo_id'] = $body->Pid;
                        
                           //$from = 'Aviary IOS App';
                        
                           // Get device tokens from db
                           $deviceTokens = getDeviceTokens($rows['id']);
                        
                           //Send notification if device tokens available
                           if(!empty($deviceTokens) && $deviceTokens != '' ){
                            $apnNotification->sendNotification($deviceTokens,$message,$action_loc_key,$badge,$sound,$match,$data);
                           }
                            $resultarray = array();
                            $resultarray['data']['lastLikeId'] = $lastLikeId;
                            $qry_photo = " UPDATE photo SET no_of_likes = no_of_likes +1  WHERE id = '".$body->Pid."' ";
                            if(mysql_query($qry_photo))
                            {
                                    
			                        $array = array("status"=>"ADD_LIKE_1","message"=>"Add like Successfull","data"=>$resultarray);
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
                      $array = array("status"=>"ADD_LIKE_0","message"=>"Invalid Photo Id");
			          return json_encode($array);

                }
    }

}




function ListOfUserAdvertisement($body)
{
  if($body->start == '' || $body->total_record == '' || $body->user_id == '')
	{
		$array = array("status"=>"ListOfUserAdvertisement_0","message"=>"Please Fill All Fields");
		return json_encode($array);
	}else{
    
		$qry = "SELECT COUNT(c.id) as no_of_comments, t.name as tagsName, p.id as photoId,p.user_id,p.name,p.created_on,p.image,p.tag,p.status,p.created_by,p.no_of_likes,u.user_name,u.image as userImage,p.deleted
                FROM photo as p INNER JOIN users as u ON  u.id = p.user_id  LEFT JOIN comments as c ON c.photo_id = p.id AND c.status = 'Active' LEFT JOIN tags as t ON t.photo_id = p.id
                WHERE p.status = 'Active' AND p.deleted = 0 AND u.id = '".$body->user_id."'
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
                    'profileImage' => $row['userImage'],
                    'numberOfLikes' => $row['no_of_likes'],
                    'numberOfComments' => $row['no_of_comments'],
                    'createdDate' => dayAgo($row['created_on']),
                    'created_on' => $row['created_on'],
                    );
        $qry_tags = "SELECT t.name, t.id, t.user_id, t.photo_id
                FROM tags as t, photo as p
                WHERE p.status = 'Active' AND t.photo_id = p.id AND t.status = 'Active' AND t.photo_id = '".$row['photoId']."' ";
        if(@mysql_num_rows($resultsArr = @mysql_query($qry_tags))){
            $resultTags = array();
               	while ($rows = mysql_fetch_assoc($resultsArr))
			     {

                    $resultTags[] = array('name' => $rows['name'],
                                    'id' => $rows['id']);
			     }
        }

            $result['data'][$iConter]['tags'] = $resultTags;
            
            $iConter ++;
			}

			$array = array("status"=>"ListOfUserAdvertisement_1","message"=>"Request Success","data"=>$result);
			return json_encode($array);
		}
		else
		{
			$array = array("status"=>"ListOfUserAdvertisement_2","message"=>"Not records found!");
			return json_encode($array);
		}
    }
}

function deleteadvertisement($body){
 
 if($body->user_id == '' ||  $body->photo_id == '' )
	{
		$array = array("status"=>"deleteadvertisement_0","message"=>"Please Fill All Fields");
		return json_encode($array);
	}else{
	   
       $deleteAds = "DELETE p,pl,c FROM users as u 
                      INNER JOIN photo as p ON u.id = p.user_id 
                      LEFT JOIN  photo_user_like as pl ON p.id = pl.photo_id 
                      LEFT JOIN comments as c ON p.id = c.photo_id WHERE p.user_id = '".$body->user_id."' AND p.id = '".$body->photo_id."'";
                      
       if(mysql_query($deleteAds)){
        $array = array("status"=>"deleteadvertisement_1","message"=>"Advertisement deleted successfully!");
		return json_encode($array);
       }  else  {
        $array = array("status"=>"deleteadvertisement_0","message"=>"Advertisement deletion Failed!");
		return json_encode($array);
       }     
	}
       
}


function listofuserswholikedads($body){
 
 if($body->photo_id == '')
	{
		$array = array("status"=>"listofuserswholikedads_0","message"=>"Please enter photo id");
		return json_encode($array);
	}else{
	   
       $userlist = "SELECT u.id, u.user_name FROM users as u 
                    INNER JOIN photo_user_like as pl ON u.id = pl.user_id 
                    INNER JOIN photo as p  ON pl.photo_id = p.id WHERE p.id = '".$body->photo_id."' GROUP BY u.id ";
                    
                    
                      
       if($res = mysql_query($userlist)){
        $result = array();
        $iConter = 0;
        while ($row = mysql_fetch_assoc($res))
			{
			 
			    $result['data'][$iConter] = array(
                    'user_id' => $row['id'],
                    'user_name'=> $row['user_name'],
                    
                    );
       $iConter++;
       }
        $array = array("status"=>"listofuserswholikedads_1","message"=>"success!", "data"=>$result);
		return json_encode($array);
       }  else  {
        $array = array("status"=>"listofuserswholikedads_0","message"=>"Failed!");
		return json_encode($array);
       }     
	}
       
}


function randomDigits($length){
    $numbers = range(0,9);
    shuffle($numbers);
    for($i = 0;$i <=$length;$i++)
       $digits .= $numbers[$i];
    return $digits;
}

/*function ago($when) {
    
        $diff = date("U") - strtotime($when);

        // Days
        $day = floor($diff / 86400);
        $diff = $diff - ($day * 86400);

        // Hours
        $hrs = floor($diff / 3600);
        $diff = $diff - ($hrs * 3600);

        // Mins
        $min = floor($diff / 60);
        $diff = $diff - ($min * 60);

        // Secs
        $sec = $diff;

        // Return how long ago this was. eg: 3d 17h 4m 18s ago
        // Skips left fields if they aren't necessary, eg. 16h 0m 27s ago / 10m 7s ago
        $str = sprintf("%s%s%s%s",
                $day != 0 ? $day."d " : "",
                ($day != 0 || $hrs != 0) ? $hrs."h " : "",
                ($day != 0 || $hrs != 0 || $min != 0) ? $min."m " : "",
                $sec."s ago"
        );

        return $str;
}*/


function dayAgo ($time)
{
    
    $timeNew = time() - strtotime($time); // to get the time since that moment

    $tokens = array (
        3600 => 'Hour',
        60 => 'Minute',
        1 => 'Second'
    );

    foreach ($tokens as $unit => $text) {
        if ($timeNew < $unit) continue;
        
        $numberOfUnits = floor($timeNew / $unit);
        
        if($text == 'Hour' && $numberOfUnits > 23){
        
            return $time;
        
        }else{
        
            return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s Ago':' Ago');    
            
         }
           
    }

}

function getDeviceTokens($userid){
    
    $query = "SELECT * from devicetoken WHERE user_id = '".$userid."' ";
    if(@mysql_num_rows($resultsArr = @mysql_query($query))){
            $resultToken = array();
               	while ($rows = mysql_fetch_assoc($resultsArr))
			     {

                    $resultToken[] = array('user_id' => $rows['user_id'],
                                    'badge_number' => $rows['badge_number'],
                                    'device_token' => $rows['device_token'],
                                    'created_on' => $rows['created_on'],
                                    );
			     }
        }
        return $resultToken;
        
}


?>