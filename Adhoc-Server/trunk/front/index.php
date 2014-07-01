<?php 
define('CONFIG_FILE_PATH', '../include/config.php');
require_once(CONFIG_FILE_PATH);
include('config.php');
include_once(SITE_PATH . 'services/dbcredentials.php');
include_once(SITE_PATH . 'services/mysql.php');
include_once(SITE_PATH . 'include/table_vars.php');

function dayAgo ($time)
{
    
    $timeNew = time() - strtotime($time); // to get the time since that moment
    

    $tokens = array (
        /*31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',*/
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text) {
        if ($timeNew < $unit) continue;
        
        $numberOfUnits = floor($timeNew / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s Ago':' Ago');
        /*if($text == 'Hour' && $numberOfUnits > 23){
        
            return $time;
        
        }else{
        
            return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s Ago':' Ago');    
            
         }*/
           
    }

}


    $comment_query = "SELECT c.photo_id,c.comment,c.created_on,u.user_name,u.image FROM comments as c, users as u WHERE c.user_id = u.id AND c.deleted=0 AND c.photo_id = '".mysql_real_escape_string($_GET['photo_id'])."' AND c.flag='inactive' ";
    $comment = mysql_query($comment_query);
    while($rows = mysql_fetch_assoc($comment)){
        $photoComments[] =$rows;
        
    }
    
    $photo_query = "SELECT p.id,p.user_id,p.image as photoimg,u.user_name,u.image as userphoto,p.no_of_likes,p.created_on,p.tag,p.deleted,u.deleted FROM photo as p 
                    INNER JOIN users as u ON u.id = p.user_id 
                    WHERE p.id = '".mysql_real_escape_string($_GET['photo_id'])."' AND p.deleted=0 AND u.deleted=0";
    $photo_detail = mysql_query($photo_query);
    $photo_details = mysql_fetch_array($photo_detail);

    $count_comment = mysql_num_rows($comment);

    $photo_tags = explode(',',$photo_details['tag']);
    $results = array();

    $createdOn = dayAgo($photo_details['created_on']);
		if(mysql_query($comment_query) && mysql_num_rows($res = mysql_query($photo_query)) > 0)
		{
		    $result = array();
            $result['PhotoId'] = $photo_details['id'];
            $result['PhotoUrl'] = $photo_details['photoimg'];
            $result['userphotoUrl'] = $photo_details['userphoto'];
            $result['createdUserid'] = $photo_details['user_id'];
            $result['createdUserName'] = $photo_details['user_name'];
            $result['numberOfLikes'] = $photo_details['no_of_likes'];
            $result['NumberOfComments'] = $count_comment;
            $result['createdDate'] = $createdOn;
            $result['tags']['name'] = $photo_tags;
            
            $results['data'] = $result;
            
			//$array = array("status"=>"GET_PHOTO_DETAIL_1","message"=>"Request Success","data"=>$results);
			//return json_encode($array);
		}
        $results['data']['comments'] = $photoComments;
        //echo '<pre>';print_r($results);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Adhoc</title>
<link href="css/stylesheet.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="wrapper">
  <div class="header-container"><a href="#"><img src="images/logo.png" alt="" /></a></div>
  <div class="main-container">
    <div class="content">
      <h1>Get the free Adhoc app.</h1>
      <div class="app-store"><a href="https://itunes.apple.com/tr/app/zamr-full-length-sat-subject/id642754805?mt=8"><img src="images/app_btn.png" alt="" /></a></div>
      <div class="content-box">
        <div class="content-top">
          <div class="user-info"><img src="<?php echo USER_IMAGE_URL.$results['data']['userphotoUrl']; ?>" height="68" width="68" alt="" /> <?php echo $results['data']['createdUserName'];?></div>
          <div class="user-text"><?php echo $results['data']['createdDate'];?></div>
        </div>
        <div class="banner"><img src="<?php echo PHOTO_IMAGE_URL.$results['data']['PhotoUrl']; ?>" height="512" width="512" alt="" /></div>
        <div class="like-box"><img src="images/comm_icon.png" alt="" /> <?php echo $results['data']['NumberOfComments'];?></div>
        <div class="like-box"><img src="images/heart_icon.png" alt="" /> <?php echo $results['data']['numberOfLikes'];?></div>
        <?php foreach($results['data']['comments'] as $commentValues){?>
            
        <div class="user-blog-box">
          <div class="img-box"><img src="<?php echo USER_IMAGE_URL.$commentValues['image']; ?>" height="49" width="49" alt="" /></div>
          <div class="comment-box">
            <div class="comment-text"> <img src="images/comm_icon1.png" alt="" /> <?php echo $commentValues['comment'];?></div>
            <p><?php echo $commentValues['user_name'];?></p>
            <span><?php echo dayAgo($commentValues['created_on']);?></span> </div>
        </div>
        <?php } ?>
       <!-- <div class="user-blog-box">
          <div class="img-box"><img src="images/user-img1.jpg" alt="" /></div>
          <div class="comment-box">
            <div class="comment-text"> <img src="images/comm_icon1.png" alt="" /> I love that car!!!</div>
            <p>Jane Alver</p>
            <span>3 days ago</span> </div>
        </div>
        <div class="user-blog-box">
          <div class="img-box"><img src="images/user-img1.jpg" alt="" /></div>
          <div class="comment-box">
            <div class="comment-text"> <img src="images/comm_icon1.png" alt="" /> I love that car!!!</div>
            <p>Jane Alver</p>
            <span>3 days ago</span> </div>
        </div> -->
        
      </div>
      <img src="images/bt_bg.jpg" alt="" /></div>
  </div>
</div>
</body>
</html>
