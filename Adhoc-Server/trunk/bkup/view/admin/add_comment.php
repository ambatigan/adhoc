<!--<script type="text/javascript">
    $(document).ready(function() {
        $('#type').focus();
        $("#addEditComment").validationEngine();
    });
</script>
<form method="post" action="" id="addEditComment" name="addEditComment">
    <table  align="center" border="0" cellpadding="0" cellspacing="0"  width="100%">
        <tr>
            <td valign="top">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                    <?php if (!empty($errorMsg)) {
                        foreach ($errorMsg as $k => $d) {
                    ?>
                        <tr>
                            <td width="100%" align="left" colspan="2" class="redlabel"><?php echo $d ?></td>
                        </tr>
                    <?php }
                    }
                    ?>
                    <tr>
                        <td  class="titlebg" >
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="padding: 5px;color:#ffffff"><?php if (isset($_REQUEST['id']) && $_REQUEST['id'] != "") echo "Edit Comment"; else echo "Add Comment"; ?> </td>
                                    <td align="right" style="padding: 5px;color:#ffffff">
                                        <a href="./?page=comment_listing" style="color: #FFFFFF" class="admtitlelink"><?php  echo "View All Comments"?></a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" syle="height:5px;"></td>
                    </tr>
                    <tr>
                        <td valign="top" class="admcontentpadd">
                            <table width="100%" border="0" cellspacing="1" cellpadding="3" class="tablebgcolor" >

                                <input name="userFormSubmit" type="hidden" value="1" />
                                <tr>
                                    <td align="right" valign="top" >&nbsp;</td>
                                    <td valign="top" class="fieldbg">
                                        <span class="pad_lt_tp">
                                            <input name="addEditCommentSubmit" id="addEditCommentSubmit" type="submit" class="inputbutton"  value="<?php  echo "Submit"?>"  />
                                            &nbsp;

                                            <?php if($_REQUEST['searchtext']!='' && $_REQUEST['search']!='' ) { ?>
                                            <a style="text-decoration: none" href="./?page=comment_listing&action=view&search=<?php echo $_REQUEST['search']; ?>&searchtext=<?php echo $_REQUEST['searchtext'];?>">
                                            <b><input name="cancel" id="cancel" type="button" class="inputbutton" value="<?php  echo "Cancel"?>" /></b></a>
                                            <?php } else { ?>
                                            <b><input name="cancel" id="cancel" type="button" class="inputbutton" onclick="location.href='./?page=comment_listing'"  value="<?php  echo "Cancel"?>"  /></b>
                                            <?php } ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</form>
-->




<script type="text/javascript">
    $(document).ready(function() {
        $('#type').focus();
        $("#addEditComment").validationEngine();
    });
</script>

  <form name="addEditComment" id="addEditComment" method="post" enctype="multipart/form-data">
            <div class="main-content">
                    <div class="main-content-top">
                        	<div class="breadcrumb">
                            	<ul class="clearfix">
                                	<li><a href="." title="Home">Home</a><img src="../images/theme_images/bcarrow.png" alt="arrow" /></li>
                                    <li><a href="?page=comment_listing" title="<?php echo $heading;?>"><?php echo $heading;?></a><img src="../images/theme_images/bcarrow.png" alt="arrow" /></li>
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
                            	<div class="add-new"><a href="<?= ADMIN_URL ?>?page=comment_listing" title="View All Comment">View All Comment</a></div>
                                <table cellspacing="1" cellpadding="4" border="0" bgcolor="#e6ecf2" width="100%">
                                	<tbody bgcolor="#fff">
                                    	<tr>
                                        	<th><?php echo $title;?></th>
                                        </tr>
                                        <tr>
                                        	<td class="add-user-form-box">
                                            	<table width="100%" cellpadding="5" cellspacing="1" border="0">

                                               <tr>
                                    <td align="right" ><strong><span class="star">*&nbsp;</span><?php  echo "Photo ID"?>:</strong></td>
                                    <td class="fieldbg">
                                        <select  class="validate[required] txtwidth" name='photo_id' id='photo_id'>
                                            <option value='' <?php echo ($userDetail['photo_id']=='0')? "selected":"";?> >Select Photo Id&nbsp;&nbsp;</option>
                                            <?php foreach($photo as $photo_id) {?>
                                            <option <?php if ($_REQUEST['id'] == $photo_id['id'])echo "selected"; elseif ($comment['photo_id'] == $photo_id['id']) echo "selected"; ?>  value = <?php echo $photo_id['id']; ?> ><?php echo $photo_id['name']; ?></option><?php }?>
                                        </select></td>
                                </tr>
                                <tr>
                                    <td align="right" ><strong><span class="star">*&nbsp;</span><?php echo "Comment"?>:</strong></td>
                                    <td class="fieldbg"><textarea name="comment" class="validate[required]" id="comment" rows="3" cols="30" ><? if ($_REQUEST['comment'] != "") echo $_REQUEST['comment']; else echo $comment['comment']; ?></textarea> </td>
                                </tr>
                                <tr>
                                    <td align="right" ><strong><span class="star">*&nbsp;</span><?php  echo "User ID"?>:</strong></td>
                                    <td class="fieldbg">
                                        <select  class="validate[required] txtwidth" name='user_id' id='user_id'>
                                            <option value=''  >Select User Id&nbsp;&nbsp;</option>
                                            <?php foreach($userDetail as $user_id) {?>
                                            <option <?php if ($_REQUEST['id'] == $user_id['id'])echo "selected"; elseif ($comment['user_id'] == $user_id['id']) echo "selected"; ?>  value = <?php echo $user_id['id']; ?>><?php echo $user_id['user_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                </tr>

                                <tr>
                                    <td width="" align="right" ><strong><?php  echo "Status" ?>:</strong></td>
                                    <td class="fieldbg">
                                        <select  class="txtwidth" name='status' id='status'>
                                            <option   value="Active" <? if ($_REQUEST['status'] == 'Active')echo "selected"; elseif ($comment['status'] == 'Active') echo "selected"; ?> ><?php  echo "Active"?></option>
                                            <option  value="Inactive" <? if ($_REQUEST['status'] == 'Inactive') echo "selected"; elseif ($comment['status'] == 'Inactive') echo "selected"; ?>><?php  echo "Inactive"?></option>
                                        </select>
                                    </td>
                                </tr>
                                <input name="addEditCommentSubmit" type="hidden" value="1" />
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
                            	<input type="submit" value="Submit" title="Submit" class="inputbutton" name="addEditCommentSubmit" >

                                 <?php if($_REQUEST['searchtext']!='' && $_REQUEST['search']!='' ) { ?>
                                  <a style="text-decoration: none" href="./?page=comment_listing&action=view&search=<?php echo $_REQUEST['search']; ?>&searchtext=<?php echo $_REQUEST['searchtext'];?>">
                                  <b><input type="button" value="Cancel" title="Cancel" class="inputbutton" name="cancel"></b></a>
                                  <?php } else { ?>
                                  <b><input type="button" value="Cancel" title="Cancel" class="inputbutton" name="cancel" onclick="javascript:cancelForm('?page=comment_listing&amp;action=view')"></b>
                                  <?php } ?>


                            </div>
                        </div>
    </div>
    </form>