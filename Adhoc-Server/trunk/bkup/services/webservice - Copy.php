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
        
		echo FBregister($json_array->body);
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
	// GetUserProfile
	/*********************************/
	if($json_array->name=='GetUserProfile')
	{
		echo GetUserProfile($json_array->body);
	}

	
	/*********************************/
	// GetAllPhotos
	/*********************************/
	if($json_array->name=='GetAllPhotos')
	{
	   
		echo GetAllPhotos($json_array->body);
	}

    /*********************************/
	// updateUserProfile

     /*********************************/

	if($json_array->name=='updateUserProfile')
    {
        echo updateUserProfile($json_array->body);
    }



    /*********************************/

   /*********************************/

	// Addlike
	/*********************************/
	if($json_array->name=='Addlike')
	{
		echo Addlike($json_array->body);
	}

   /*********************************/
   // Comment list on photo
	/*********************************/

	if($json_array->name=='GetPhotoComments')

	{
	   	echo GetPhotoComments($json_array->body);


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

}

function FBregister($body)
{
    
    $block_user_chk = " SELECT * FROM users WHERE (user_name = '".mysql_real_escape_string($body->firstname.' '.$body->lastname)."' OR email = '".mysql_real_escape_string($body->emailid)."') AND status = 'Blocked'";

    if(mysql_num_rows($result = mysql_query($block_user_chk)))
	{	
		$array = array("status"=>"User Blocked!");
		return json_encode($array);
        
	}else{
    
	$qry_chk = " SELECT * FROM users WHERE (user_name = '".mysql_real_escape_string($body->firstname.' '.$body->lastname)."' OR email = '".mysql_real_escape_string($body->emailid)."')";
    
	
	if(mysql_num_rows($res = mysql_query($qry_chk)) > 0)
	{	
		$array = array("status"=>"User already Exists");
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
                    image, 
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
                    '".$body->profileimage."',
                    '".$body->sex."',
                    STR_TO_DATE($body->birthdate,'%Y-%m-%d %H:%i:%s'),
                    '".$body->status."',
                    '".$body->created_on."',
                    '".$body->modified_on."',
                    '".$body->deleted."'
                    )";
                    
         
		if(mysql_query($qry))
		{
			$result = array();
			$result['user_id'] =  mysql_insert_id();				

			$array = array("status"=>"Registration success","data"=>$result);
			return json_encode($array);
		}else{
			$array = array("status"=>"Registration fail");
			return json_encode($array);
	     }
	   }
    
    }
	
}
    

function GetAllPhotos($body)

{
    $searchText = '';
    if($body->searchText){
        
        $searchText .= " AND u.user_name LIKE '%".$body->searchText."%' OR t.name LIKE '%".$body->searchText."%'";
    }
		$qry = "SELECT COUNT(c.id) as no_of_comments, t.name as tagsName, p.id as photoId,p.user_id,p.name,p.created_on,p.image,p.tag,p.status,p.created_by,p.no_of_likes,u.user_name
                FROM photo as p INNER JOIN users as u ON  u.id = p.user_id  LEFT JOIN comments as c ON c.photo_id = p.id AND c.status = 'Active' LEFT JOIN tags as t ON t.photo_id = p.id  
                WHERE p.status = 'Active'  $searchText 

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
            
			$array = array("status"=>"Success","message"=>"Request Success","data"=>$result);
			return json_encode($array);
		}
		else
		{
			$array = array("status"=>"Failed","message"=>"Not any Inquiry Submitted By This User");
			return json_encode($array);
		}
	}


function AddAccessToken($body){
    
    $qry = "INSERt INTO devicetoken SET device_token = '".$body->token."', badge_number = '".$body->badge."',
    created_on = '".$body->created_on."' ";
         
		if(mysql_query($qry))
		{
			$array = array("status"=>"Success");
			return json_encode($array);
		}else{
			$array = array("status"=>"Failed");
			return json_encode($array);
	     }
         
}

function UpdateAccessToken($body){
    
    $qry = "UPDATE devicetoken SET badge_number = '".$body->badge."',created_on = '".$body->created_on."' 
            WHERE device_token = '".$body->token."' ";
         
		if(mysql_query($qry))
		{
			$array = array("status"=>"Success");
			return json_encode($array);
		}else{
			$array = array("status"=>"Failed");
			return json_encode($array);
	     }
   }





    




/*--------------- neha------------------------ */
function Addlike($body)
{
	if($body->Uid == '' || $body->Pid == '')
	{
		$array = array("status"=>"Addlike_0","message"=>"Invalid Data");
		return json_encode($array);
	}
    else
    {
        	$qry_user = " SELECT user_id , id FROM photo WHERE user_id = '".$body->Uid."' AND id = '".$body->Pid."' ";
            	if(mysql_num_rows($res = mysql_query($qry_user)) > 0)
	           	{
			           $qry = "INSERT INTO photo_user_like (user_id,photo_id,no_of_like,created_on) VALUES ('".$body->Uid."',
                       '".$body->Pid."','1','".date('y-m-d')."')";
                       if(mysql_query($qry))
		               {
                            $qry_photo = " UPDATE photo SET no_of_likes = no_of_likes +1  WHERE user_id = '(".$body->Uid."'
                             AND id = '".$body->Pid.")' ";
                            if(mysql_query($qry_photo))
                            {
			                        $array = array("status"=>"Addlike_1","message"=>"Add like Successfull","data"=>$result);
			                        return json_encode($array);
                            }
                            else
                            {
                                   $array = array("status"=>"Addlike_0","message"=>"Add Like is Failed");
				                   return json_encode($array);
                            }

			            }
                        else
                        {
                            $array = array("status"=>"Addlike_0","message"=>"Add Like Failed");
				            return json_encode($array);
                        }
		        }
                 else
                {
                      $array = array("status"=>"Addlike_0","message"=>"Invalid User Id or Photo Id");
			          return json_encode($array);
                }
 }

}

function GetUserProfile($body){

   	    $qryUserTags = "SELECT COUNT(t.id) as no_of_tags, t.name as tagsName,t.id as tagid, u.user_name, u.id as userid, u.image
                FROM users as u  INNER JOIN tags as t  ON  u.id = t.user_id
                WHERE u.status = 'Active' AND u.status != 'Blocked' AND u.id = '".$body->userid."'
                GROUP BY t.name ORDER BY no_of_tags DESC ";
                
        $qryUser = "SELECT u.user_name, u.id as userid, u.image
                FROM users as u
                WHERE u.status = 'Active' AND u.status != 'Blocked' AND u.id = '".$body->userid."' ";


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
           
			$array = array("status"=>"Success","message"=>"Request Success","data"=>$result);
			return json_encode($array);
		}
		else
		{
			$array = array("status"=>"Failed","message"=>"Not any Inquiry Submitted By This User");
			return json_encode($array);
		}
}

function updateUserProfile($body){
   
    $profile_image = str_replace(' ','+',$body->profile_image);
    $imgname= time()."_user_iphone.jpg";
    $fp = fopen(USER_IMAGE_PATH.$imgname, "w");
    fwrite($fp, base64_decode($profile_image));
    fclose($fp);
    $profile_image = $imgname;
    
    
    $qry = "UPDATE users SET user_name = '".$body->username."',image = '".$profile_image."' 
            WHERE id = '".$body->userid."' ";
         
		if(mysql_query($qry))
		{
			$array = array("status"=>"Success");
			return json_encode($array);
		}else{
			$array = array("status"=>"Failed");
			return json_encode($array);
	     }

 }

    



function AddPhoto($body){
    
    $qry = "INSERT INTO photo 
            SET 
            user_id = '".$body->token."', 
            name = '".$body->badge."',
            image = '".$body->created_on."',
            tag = '".$body->created_on."',
            status = '".$body->created_on."',
            flag = '".$body->created_on."',
            created_by = '".$body->created_on."',
            modified_on = '".$body->created_on."',
            deleted = '".$body->created_on."' ";
         
		if(mysql_query($qry))
		{
			$array = array("status"=>"Success");
			return json_encode($array);
		}else{
			$array = array("status"=>"Failed");
			return json_encode($array);
	     }
      }

         
/*--------------- neha------------------------ */

function Addlike($body)
{
	if($body->Uid == '' || $body->Pid == '')
	{
		$array = array("status"=>"Addlike_0","message"=>"Invalid Data");
		return json_encode($array);
	}
    else
    {
        	$qry_user = " SELECT user_id , id FROM photo WHERE user_id = '".$body->Uid."' AND id = '".$body->Pid."' ";
            	if(mysql_num_rows($res = mysql_query($qry_user)) > 0)
	           	{
			           $qry = "INSERT INTO photo_user_like (user_id,photo_id,no_of_like,created_on) VALUES ('".$body->Uid."',
                       '".$body->Pid."','1','".date('y-m-d')."')";
                       if(mysql_query($qry))
		               {
                            $qry_photo = " UPDATE photo SET no_of_likes = no_of_likes +1  WHERE user_id = '(".$body->Uid."'
                             AND id = '".$body->Pid.")' ";
                            if(mysql_query($qry_photo))
                            {
			                        $array = array("status"=>"Addlike_1","message"=>"Add like Successfull","data"=>$result);
			                        return json_encode($array);
                            }
                            else
                            {
                                   $array = array("status"=>"Addlike_0","message"=>"Add Like is Failed");
				                   return json_encode($array);
                            }

			            }
                        else
                        {
                            $array = array("status"=>"Addlike_0","message"=>"Add Like Failed");
				            return json_encode($array);
                        }
		        }
                 else
                {
                      $array = array("status"=>"Addlike_0","message"=>"Invalid User Id or Photo Id");
			          return json_encode($array);
                }
 }

}



function GetPhotoComments($body)
{
	if($body->Pid == '')
	{
		$array = array("status"=>"GetPhotoComments_0","message"=>"Invalid Data");
		return json_encode($array);
	}
    else
    {
        $qry= " SELECT c.comment,c.id,c.photo_id,c.user_id,u.user_name,u.status,c.created_on,p.status,c.status,c.flag,p.image FROM comments AS c JOIN users AS u ON c.user_id = u.id JOIN photo AS p ON c.photo_id = p.id WHERE c.photo_id = '".$body->Pid. "' AND p.status = 'Active' AND c.status = 'Active' AND c.flag = 'Inactive'  AND p.flag = 'Inactive' AND u.status = 'Active'
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
             $array = array("status"=>"Success","message"=>"Request Success","data"=>$result);
             return json_encode($array);

         }
         else
		{
			$array = array("status"=>"Failed","message"=>"No Comments Available");
			return json_encode($array);
		}
     }

}

function AddComment($body)
{
//echo "<pre>"; print_r($body); exit;
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
			$array = array("status"=>"Success");
			return json_encode($array);
		}else{
			$array = array("status"=>"Failed");
			return json_encode($array);
	     }
}

function FlagComment($body)
{

$query = "INSERT INTO comment_flag(comment_id,login_user_id,created_user_id,created_on,modified_on,deleted)VALUES('".$body->commentId."','".$body->loginUserId."','".$body->createduserId."','".$body->created_on."','".$body->modified_on."','".$body->deleted."')";


		if(mysql_query($query))
		{
			$array = array("status"=>"Success");
			return json_encode($array);
		}else{
			$array = array("status"=>"Failed");
			return json_encode($array);
	     }
}


function FlagPhoto($body)
{

$query = "INSERT INTO photo_flag(photo_id,login_user_id,created_user_id,created_on,modified_on,deleted)VALUES('".$body->photoId."','".$body->loginUserId."','".$body->createduserId."','".$body->created_on."','".$body->modified_on."','".$body->deleted."')";


		if(mysql_query($query))
		{
			$array = array("status"=>"Success");
			return json_encode($array);
		}else{
			$array = array("status"=>"Failed");
			return json_encode($array);
	     }
}

function GetPhotoDetail($body)
{

$comment_query = "SELECT c.photo_id,c.comment FROM comments as c WHERE c.deleted=0 AND c.photo_id = '".mysql_real_escape_string($body->photoId)."' AND c.flag='inactive' ";
$comment = mysql_query($comment_query);

$photo_query = "SELECT p.id,p.user_id,p.image,u.user_name,p.no_of_likes,p.created_on,p.tag from photo as p INNER JOIN users as u ON u.id = p.user_id where p.id = '".mysql_real_escape_string($body->photoId)."' and p.deleted=0 and u.deleted=0";
$photo_detail = mysql_query($photo_query);
$photo_details = mysql_fetch_array($photo_detail);

$count_comment = mysql_num_rows($comment);

$photo_tags = explode(',',$photo_details['tag']);
//print_r($photo_tags); exit;


		if(mysql_query($comment_query) && mysql_query($photo_query))
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
			$array = array("status"=>"Success","detail"=>$result);
			return json_encode($array);
		}else{
			$array = array("status"=>"Failed");
			return json_encode($array);
	     }
}




?>