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
                                    <li><a href="?page=photo_listing" title="<?php echo $heading;?>"><?php echo $heading;?></a><img src="../images/theme_images/bcarrow.png" alt="arrow" /></li>
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
                            	<div class="add-new"><a href="<?= ADMIN_URL ?>?page=photo_listing" title="View All Users">View All Photo</a></div>
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
                                            <td ><input name="name" class="validate[required]" value="<? if ($_REQUEST['name'] != "") echo $_REQUEST['name']; else echo $photo['name']; ?>" type="text" id="name" size="35" maxlength="100"  /></td>
                                        </tr>
                                
                                <?php  if($action =='edit') { ?>

                                 <tr>
                                    <td align="right">

                                    </td>
                                    <td>

                                        <img src="<?=SITE_URL?>timthumb.php?src=<?php echo PHOTO_IMAGE_URL.$photo['image']?>&h=160&w=160&q=100" alt="">
                                        <input type="hidden" name="photo_delete" id="image_delete" value="<?php echo $photo['image'];?>">
                                    </td>
                                </tr>
                                  <?php } ?>
                                  <?php if($objPhoto->id == '' && $action == "add") { $class = "validate[required]"; } ?>
                                <tr>
                                    <td  align="right" valign="top" ><span class="star">*&nbsp;</span><?php echo "Image" ?>:</td>
                                    <td >
                                        <table cellpadding="0" cellspacing="0">
                                             <tr>
                                                <td>
                                                    <input type="file" class="<?php echo $class;?>" name="image" id="image" /><br /><span><?php echo "Please upload" ?>&nbsp;<?php echo IMAGE_EXTENSIONS ?>&nbsp;<?php echo "files only" ?></span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" ><span class="star">*&nbsp;</span><?php  echo "Tag"?>:</td>
                                    <td ><textarea id="tag" name="tag" class="validate[required]" rows="6" cols="32"><?php echo stripslashes($photo[tag]) ?></textarea></td>
                                </tr>
                                <tr>
                                    <td width="" align="right" ><?php  echo "Status" ?>:</td>
                                    <td >
                                        <select  class="txtwidth" name='status' id='status'>
                                            <option   value="Active" <? if ($_REQUEST['status'] == 'Active')echo "selected"; elseif ($photo['status'] == 'Active') echo "selected"; ?> ><?php  echo "Active"?></option>
                                            <option  value="Inactive" <? if ($_REQUEST['status'] == 'Inactive') echo "selected"; elseif ($photo['status'] == 'Inactive') echo "selected"; ?>><?php  echo "Inactive"?></option>
                                        </select>
                                    </td>
                                </tr>
                                <input name="userFormSubmit" type="hidden" value="1" />
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
                                  <a style="text-decoration: none" href="./?page=photo_listing&action=view&search=<?php echo $_REQUEST['search']; ?>&searchtext=<?php echo $_REQUEST['searchtext'];?>">
                                  <b><input type="button" value="Cancel" title="Cancel" class="inputbutton" name="cancel"></b></a>
                                  <?php } else { ?>
                                  <b><input type="button" value="Cancel" title="Cancel" class="inputbutton" name="cancel" onclick="javascript:cancelForm('?page=photo_listing&amp;action=view')"></b>
                                  <?php } ?>


                            </div>
                        </div>
    </div>
    </form>