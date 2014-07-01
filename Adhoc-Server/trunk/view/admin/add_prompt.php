<script type="text/javascript">
    $(document).ready(function() {
        $('#type').focus();
        $("#addEditPrompt").validationEngine();
    });
</script>

  <form name="addEditPrompt" id="addEditPrompt" method="post" >
            <div class="main-content">
                    <div class="main-content-top">
                        	<div class="breadcrumb">
                            	<ul class="clearfix">
                                	<li><a href="." title="Home">Home</a><img src="../images/theme_images/bcarrow.png" alt="arrow" /></li>
                                    <li><a href="?page=prompt_listing" title="<?php echo $heading;?>"><?php echo $heading;?></a><img src="../images/theme_images/bcarrow.png" alt="arrow" /></li>
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
                            	<div class="add-new"><a href="<?php echo  ADMIN_URL ?>?page=prompt_listing" title="View All Prompts">View All Prompts</a></div>
                                <table cellspacing="1" cellpadding="4" border="0" bgcolor="#e6ecf2" width="100%">
                                	<tbody bgcolor="#fff">
                                    	<tr>
                                        	<th><?php echo $title;?></th>
                                        </tr>
                                        <tr>
                                        	<td class="add-user-form-box">
                                            	<table width="100%" cellpadding="5" cellspacing="1" border="0">

                                            <tr>
                                    <td align="right" ><strong><span class="star">*&nbsp;</span><?php echo "Prompt"?>:</strong></td>
                                    <td class="fieldbg"><textarea name="prompt" class="validate[required]" id="prompt" rows="3" cols="30" ><?php if ($_REQUEST['prompt'] != "") echo $_REQUEST['prompt']; else echo $prompt['prompt']; ?></textarea> </td>
                                </tr>
                              

                                <tr>
                                    <td width="" align="right" ><strong><?php  echo "Status" ?>:</strong></td>
                                    <td class="fieldbg">
                                        <select  class="txtwidth" name='status' id='status'>
                                            <option   value="Active" <?php if ($_REQUEST['status'] == 'Active')echo "selected"; elseif ($prompt['status'] == 'Active') echo "selected"; ?> ><?php  echo "Active"?></option>
                                            <option  value="Inactive" <?php if ($_REQUEST['status'] == 'Inactive') echo "selected"; elseif ($prompt['status'] == 'Inactive') echo "selected"; ?>><?php  echo "Inactive"?></option>
                                        </select>
                                    </td>
                                </tr>
                                <input name="addEditPromptSubmit" type="hidden" value="1" />
                
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="submit-btns clearfix">
                            	<input type="submit" value="Submit" title="Submit" class="inputbutton" name="addEditPromptSubmit" >

                                 <?php if($_REQUEST['searchtext']!='' && $_REQUEST['search']!='' ) { ?>
                                  <a style="text-decoration: none" href="./?page=prompt_listing&action=view&search=<?php echo $_REQUEST['search']; ?>&searchtext=<?php echo $_REQUEST['searchtext'];?>">
                                  <b><input type="button" value="Cancel" title="Cancel" class="inputbutton" name="cancel"></b></a>
                                  <?php } else { ?>
                                  <b><input type="button" value="Cancel" title="Cancel" class="inputbutton" name="cancel" onclick="javascript:cancelForm('?page=prompt_listing&amp;action=view')"></b>
                                  <?php } ?>

                            </div>
                        </div>
    </div>
    </form>