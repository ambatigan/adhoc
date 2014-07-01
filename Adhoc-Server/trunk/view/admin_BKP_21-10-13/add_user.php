<script type="text/javascript">
    $(document).ready(function() {
        $('#type').focus();
//        $("#addEditUser").validationEngine();
    });
</script>

  <form name="addEditUser" id="addEditUser" method="post" enctype="multipart/form-data">
            <div class="main-content">
                    <div class="main-content-top">
                        	<div class="breadcrumb">
                            	<ul class="clearfix">
                                	<li><a href="." title="Home">Home</a><img src="../images/theme_images/bcarrow.png" alt="arrow" /></li>
                                    <li><a href="?page=users_listing" title="<?php echo $heading;?>"><?php echo $heading;?></a><img src="../images/theme_images/bcarrow.png" alt="arrow" /></li>
                                    <li><?php echo $title;?></li>
                                </ul>
                            </div>
            				<div class="page-title"><h1><?php echo $title;?></h1></div>
                        </div>

                            <?php if (count($errorMsg)>0){?>
                            <div class="msg-content-box">
                                <div class="alert alert-error">
                                    <button type="button" class="close" data-dismiss="alert">x</button>
                                    <?php foreach ($errorMsg as $_errorMsg){
                                        echo $_errorMsg."<br />";
                                     }?>
                                </div>
                            </div>
                            <?php }?>

                    <div class="main-container">
                        	<div class="grid-data">
                            	<div class="add-new"><a href="<?php echo  ADMIN_URL ?>?page=users_listing" title="View All Users">View All Users</a></div>
                                <table cellspacing="1" cellpadding="4" border="0" bgcolor="#e6ecf2" width="100%">
                                	<tbody bgcolor="#fff">
                                    	<tr>
                                        	<th><?php echo $title;?></th>
                                        </tr>
                                        <tr>
                                        	<td class="add-user-form-box">
                                            	<table width="100%" cellpadding="5" cellspacing="1" border="0">
                                                 <tr>
                                                    <td align="right" ><span class="star">*&nbsp;</span><?php  echo "Type"?>:</td>
                                                    <td><input name="user_name" class="validate[required]" value="<?php if ($_REQUEST['user_name'] != "") echo $_REQUEST['user_name']; else echo $user['user_name']; ?>" type="text" id="username" style="width:198px;" tabindex="1" maxlength="35"  /></td>
                                                </tr>
                                                 <tr>
                                                    <td align="right" ><span class="star">*&nbsp;</span><?php  echo "Username"?>:</td>
                                                    <td><input name="user_name" class="validate[required]" value="<?php if ($_REQUEST['user_name'] != "") echo $_REQUEST['user_name']; else echo $user['user_name']; ?>" type="text" id="username" style="width:198px;" tabindex="1" maxlength="35"  /></td>
                                                </tr>

                                                 <?php
                                    if ($objUser->id == '' && $action != 'edit') {
                                        $class = "validate[required]";
                                        $class1 = "validate[required,equals[password]]";
                                    }
                                ?>
                                <tr>
                                    <td align="right" ><?php if($action == 'add'){ ?><span class="star">*&nbsp;</span><?php } ?><?php  echo "Password"?>:</td>
                                    <td><input name="password" class="<?php echo $class; ?>" value="" type="password" id="password" style="width:198px;" tabindex="2" maxlength="35"   />
                                        <?php if ($objUser->id != '' && $action == 'edit') { ?><br /><?php echo "Keep password and confirm password blank if you don't want to change.";} ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="135" align="right" ><?php if($action == 'add'){ ?><span class="star">*&nbsp;</span><?php } ?><?php  echo "Confirm Password"?>:</td>
                                    <td><input type="password" class="<?php echo $class1; ?>" name="confirmpassword" value="" id="confirmpassword" style="width:198px;" tabindex="3" maxlength="35"   /></td>
                                </tr>
                                <tr>
                                    <td align="right" ><?php  echo "First Name" ?>:</td>
                                    <td><input name="first_name" maxlength="50" value="<?php if ($_REQUEST['first_name'] != "") echo $_REQUEST['first_name']; else echo $user['first_name']; ?>" type="text" id="firstname" style="width:198px;" tabindex="4"  /></td>
                                </tr>
                                <tr>
                                    <td align="right" ><?php  echo "Last Name" ?> :</td>
                                    <td><input  name="last_name" maxlength="30" value="<?php if ($_REQUEST['last_name'] != "") echo $_REQUEST['last_name']; else echo $user['last_name']; ?>" type="text" id="lastname" style="width:198px;" tabindex="5"   /></td>
                                </tr>
                                 <tr>
                                    <td align="right" ><span class="star">*&nbsp;</span><?php  echo "Email Address"?>:</td>
                                    <td><input name="email" maxlength="50" class="validate[required,custom[email]]" value="<?php if ($_REQUEST['email'] != "") echo $_REQUEST['email']; else echo $user['email']; ?>" type="text" id="email" style="width:198px;" tabindex="6"   /></td>
                                </tr>
                                    <?php  if($action =='edit') { ?>

                                 <tr>
                                    <td align="right">

                                    </td>
                                    <td>

                                        <img src="<?php echo USER_IMAGE_URL.$user['image']?>" height="160" width="160" alt="">
                                        <input type="hidden" name="photo_delete" id="image_delete" value="<?php echo $user['image'];?>">
                                    </td>
                                </tr>
                                  <?php } ?>
                                <tr>
                                    <td  align="right" valign="top" ><?php echo "Image" ?>:</td>
                                    <td>
                                        <table cellpadding="0" cellspacing="0">
                                             <tr>
                                                <td>
                                                    <input type="file" name="image" id="image" /><br /><span><?php echo "Please upload" ?>&nbsp;<?php echo IMAGE_EXTENSIONS ?>&nbsp;<?php echo "files only" ?></span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="" align="right" ><?php  echo "Status" ?>:</td>
                                    <td>
                                        <select  class="txtwidth" name='status' id='status'>
                                            <option  value="Active" <?php if ($_REQUEST['status'] == 'Active')echo "selected"; elseif ($user['status'] == 'Active') echo "selected"; ?> ><?php  echo "Unblock"?></option>
                                            <option  value="Inactive" <?php if ($_REQUEST['status'] == 'Inactive') echo "selected"; elseif ($user['status'] == 'Inactive') echo "selected"; ?>><?php  echo "Blocked"?></option>
                                        </select>
                                    </td>
                                </tr>
                                <input name="userFormSubmit" type="hidden" value="1" />
                                <!--<tr>
                                    <td align="right" valign="top" >&nbsp;</td>
                                    <td valign="top" class="fieldbg">
                                        <span class="pad_lt_tp">
                                            <input name="addEditUserSubmit" id="addEditUserSubmit" type="submit" class="inputbutton"  value="<?php  echo "Submit"?>"  />
                                            &nbsp;

                                            <?php if($_REQUEST['searchtext']!='' && $_REQUEST['search']!='' ) { ?>
                                            <a style="text-decoration: none" href="./?page=user_listing&action=view&search=<?php echo $_REQUEST['search']; ?>&searchtext=<?php echo $_REQUEST['searchtext'];?>">
                                            <b><input name="cancel" id="cancel" type="button" class="inputbutton" value="<?php  echo "Cancel"?>"  /></b></a>
                                            <?php } else { ?>
                                            <b><input name="cancel" id="cancel" type="button" class="inputbutton" onclick="location.href='./?page=user_listing'"  value="<?php  echo "Cancel"?>"  /></b>
                                            <?php } ?>
                                        </span>
                                    </td>
                                </tr>-->
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="submit-btns clearfix">
                            	<input type="submit" value="Submit" title="Submit" class="inputbutton" name="addEditUserSubmit" >

                                 <?php if($_REQUEST['searchtext']!='' && $_REQUEST['search']!='' ) { ?>
                                  <a style="text-decoration: none" href="./?page=users_listing&action=view&search=<?php echo $_REQUEST['search']; ?>&searchtext=<?php echo $_REQUEST['searchtext'];?>">
                                  <b><input type="button" value="Cancel" title="Cancel" class="inputbutton" name="cancel"></b></a>
                                  <?php } else { ?>
                                  <b><input type="button" value="Cancel" title="Cancel" class="inputbutton" name="cancel" onclick="javascript:cancelForm('?page=users_listing&amp;action=view')"></b>
                                  <?php } ?>


                            </div>
                        </div>
    </div>
    </form>