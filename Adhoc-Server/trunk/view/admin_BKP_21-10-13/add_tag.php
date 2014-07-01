<script type="text/javascript">
    $(document).ready(function() {
        $('#type').focus();
       $("#addEditPhoto").validationEngine();
    });
</script>

  <form name="addEditPhoto" id="addEditPhoto" method="post" enctype="multipart/form-data">
            <div class="main-content">
                    <div class="main-content-top">
                        	<div class="breadcrumb">
                            	<ul class="clearfix">
                                	<li><a href="." title="Home">Home</a><img src="../images/theme_images/bcarrow.png" alt="arrow" /></li>
                                    <li><a href="?page=tag_listing" title="<?php echo $heading;?>"><?php echo $heading;?></a><img src="../images/theme_images/bcarrow.png" alt="arrow" /></li>
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
                            	<div class="add-new"><a href="<?php echo  ADMIN_URL ?>?page=tag_listing" title="View All Users">View All Tag</a></div>
                                <table cellspacing="1" cellpadding="4" border="0" bgcolor="#e6ecf2" width="100%">
                                	<tbody bgcolor="#fff">
                                    	<tr>
                                        	<th><?php echo $title;?></th>
                                        </tr>
                                        <tr>
                                        	<td class="add-user-form-box">
                                            	<table width="100%" cellpadding="5" cellspacing="1" border="0">

                                        <tr>
                                    <td align="right" ><span class="star">*&nbsp;</span><?php echo "Name"?>:</td>
                                    <td ><input name="name" class="validate[required]" value="<?php if ($_REQUEST['name'] != "") echo $_REQUEST['name']; else echo $tag['name']; ?>" type="text" id="name" size="35" maxlength="100"  /></td>
                                </tr>
                                <!--<tr>
                                    <td align="right" ><?php echo "User ID"?>:</td>
                                    <td >
                                        <select  class="txtwidth select validate[required]" name='user_id' id='user_id'>
                                        <option  value="">--<?php echo "Select" ?>--</option>
                                        <?php foreach($user as $k => $d) { ?>
                                        <option value="<?php echo $d['id']; ?>" <?php if ($_REQUEST['user_id'] == $d['id'])echo "selected"; elseif ($photo['user_id'] == $d['id']) echo "selected"; ?> ><?php echo $d['user_name']; ?></option>
                                        <?php } ?>
                                        </select>
                                    </td>
                                </tr>-->
                                <tr>
                                    <td align="right" ><span class="star">*&nbsp;</span><?php  echo "Photo ID"?>:</td>
                                    <td >
                                        <select  class="validate[required] txtwidth" name='photo_id' id='photo_id'>
                                            <option value='' <?php echo ($userDetail['photo_id']=='0')? "selected":"";?> >Select Photo Id&nbsp;&nbsp;</option>
                                            <?php foreach($photo as $photo_id) {?>
                                            <option <?php if ($_REQUEST['id'] == $photo_id['id'])echo "selected"; elseif ($tag['photo_id'] == $photo_id['id']) echo "selected"; ?>  value = <?php echo $photo_id['id']; ?> ><?php echo $photo_id['name']; ?></option>
                                            <?php }?>
                                        </select></td>
                                </tr>

                                 <tr>
                                    <td align="right" ><span class="star">*&nbsp;</span><?php  echo "User ID"?>:</td>
                                    <td >
                                        <select  class="validate[required] txtwidth" name='user_id' id='user_id'>
                                            <option value=''  >Select User Id&nbsp;&nbsp;</option>
                                            <?php foreach($user as $user_id) {?>
                                            <option <?php if ($_REQUEST['id'] == $user_id['id'])echo "selected"; elseif ($tag['user_id'] == $user_id['id']) echo "selected"; ?>  value = <?php echo $user_id['id']; ?>><?php echo $user_id['user_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="" align="right" ><?php  echo "Status" ?>:</td>
                                    <td >
                                        <select  class="txtwidth" name='status' id='status'>
                                            <option   value="Active" <?php if ($_REQUEST['status'] == 'Active')echo "selected"; elseif ($tag['status'] == 'Active') echo "selected"; ?> ><?php  echo "Active"?></option>
                                            <option  value="Inactive" <?php if ($_REQUEST['status'] == 'Inactive') echo "selected"; elseif ($tag['status'] == 'Inactive') echo "selected"; ?>><?php  echo "Inactive"?></option>
                                        </select>
                                    </td>
                                </tr>
                                <input name="addEditTagSubmit" type="hidden" value="1" />
                                <!--<tr>
                                    <td align="right" valign="top" >&nbsp;</td>
                                    <td valign="top" >
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
                            	<input type="submit" value="Submit" title="Submit" class="inputbutton" name="addEditPhotoSubmit" >

                                 <?php if($_REQUEST['searchtext']!='' && $_REQUEST['search']!='' ) { ?>
                                  <a style="text-decoration: none" href="./?page=tag_listing&action=view&search=<?php echo $_REQUEST['search']; ?>&searchtext=<?php echo $_REQUEST['searchtext'];?>">
                                  <b><input type="button" value="Cancel" title="Cancel" class="inputbutton" name="cancel"></b></a>
                                  <?php } else { ?>
                                  <b><input type="button" value="Cancel" title="Cancel" class="inputbutton" name="cancel" onclick="javascript:cancelForm('?page=tag_listing&amp;action=view')"></b>
                                  <?php } ?>


                            </div>
                        </div>
    </div>
    </form>