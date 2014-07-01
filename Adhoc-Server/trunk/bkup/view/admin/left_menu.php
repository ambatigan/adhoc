<?php
function findWordInUrl($word){
   $findWordInUrl = stristr($_SERVER['REQUEST_URI'],$word);

    if ($findWordInUrl){
      return true;
    }else{
      return false;
    }
}

$UserManagementClass = '';
$CommentManagementClass = '';
$TagManagementClass = '';
$CommentFlagModerationClass = '';
$PhotoFlagModerationClass = '';
$PhotoManagementClass = '';

if(findWordInUrl('users_listing') || findWordInUrl('add_user')){
    $UserManagementClass = 'class="selected"';

}
else if(findWordInUrl('comment_listing') || findWordInUrl('add-comment')){
    $CommentManagementClass = 'class="selected"';
}else if(findWordInUrl('photo_listing') || findWordInUrl('add-photo')){
    $PhotoManagementClass = 'class="selected"';
}else if(findWordInUrl('tag_listing') || findWordInUrl('add-tag')){
    $TagManagementClass = 'class="selected"';
}else if(findWordInUrl('photo_flag_listing')){
    $PhotoFlagModerationClass = 'class="selected"';
}else if(findWordInUrl('comment_flag_listing')){
    $CommentFlagModerationClass = 'class="selected"';
}


?>

      	<div class="left-menu">
              <ul class="clearfix">
              <li <?php echo $UserManagementClass;?> ><a href="<?= ADMIN_URL ?>?page=users_listing" title="User Management">User Management</a></li>
              <li <?php echo $CommentManagementClass;?> ><a href="<?= ADMIN_URL ?>?page=comment_listing" title="Category Management">Comment Management</a></li>
              <li <?php echo $PhotoManagementClass;?> ><a href="<?= ADMIN_URL ?>?page=photo_listing" title="Category Management">Photo Management</a></li>
              <li <?php echo $TagManagementClass;?> ><a href="<?= ADMIN_URL ?>?page=tag_listing" title="Category Management">Tag Management</a></li>
              <li <?php echo $PhotoFlagModerationClass;?> ><a href="<?= ADMIN_URL ?>?page=photo_flag_listing" title="Category Management">Photo Flag Moderation</a></li>
              <li <?php echo $CommentFlagModerationClass;?> ><a href="<?= ADMIN_URL ?>?page=comment_flag_listing" title="Product Management">Comment Flag Moderation</a></li>
              </ul>
          </div>
