<script language="javascript">
$(document).ready(function() {
     //$(".close").hide();
     $(".close").click(function () {
         $(this).parent().slideUp();
       });
});
</script>
<?php //if(logged_in_user::id()!=0 && $_REQUEST['page']!='login' && $_REQUEST['page']!=''){?>
    	<div class="header clearfix">
        	<h1 class="logo" style="padding:0px;">
            <div style="float:left;"><a href="." title="Scanner App"><img src="../images/theme_images/aviary.png" alt="Logo" height="80px;" width="100px;"/></a></div>
            <div style="float:left;padding:25px 0 0 10px;">Adhoc </div>
            </h1>
          <div class="welcome"><a class="logout" href="./?page=login&action=logout" title="Logout"><img src="../images/theme_images/logout.png" alt="Logout" /></a> <p class="welcome-text">Welcome, <span><?php echo ucfirst($_SESSION['logged_in_user_username']);?></span></p> <img class="avatar" src="../images/theme_images/user_icon.png" alt="User" /></div>
        </div>
        <div id="menu" class="navigation">

        </div>
<?php //} ?>