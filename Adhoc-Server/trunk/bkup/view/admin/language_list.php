<form id="frmLanguage" name="frmLanguage" method="post" action="">
<input type="hidden" name="sort_order" id="sort_order" value="<?php echo $sort_order ?>"></input>
<input type="hidden" name="sort_by" id="sort_by" value="<?php echo $sort_by ?>"></input>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                        <td>
                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                        <td class="admtitlebg">Search<?php //echo index_lang::$Search; ?></td>
                        </tr>
                          <tr>
                           <td valign="top" class="admcontentpadd">
                            <table  border="0" cellspacing="1" cellpadding="4" >

                             <tr>
	                		<td><b><? //echo index_lang::$Search;?>:</b></td>
	                		<td><input  style="width:150px;" name="search_cms" id="search_cms" type="text" class="input" value="<?php echo stripslashes($_REQUEST['search_cms']) ?>" maxlength="50" /></td>
	                		<td class="sbut" valign="top">
	                 			<input name="cmsFormSubmit" type="hidden"  value="1" />
	                			<input name="Search<? //echo index_lang::$Search;?>" type="submit"  class="inputbutton"  value="Search<? //echo index_lang::$Search;?>" title="Search<?php //echo index_lang::$Search;?>"  />
							</td>
                            <td align="left" valign="top">
                            <input type="button" name="Reset" class="subbut" onclick="location.href='./?page=language_list&action=view'" title="Reset<?php //echo index_lang::$reset;?>"  value="Reset<?php //echo index_lang::$reset;?>">
                            </td>
                            </tr>
                            </table></td>
	              		</tr>
                          </table>
                        </td>
                        </tr>
                         	<?php if(!empty($successMsg)) {
					foreach($successMsg as $k=>$d) { ?>
						<tr>
			 			<td width="100%" align="left"  colspan="2" class="greenlabel"><?php echo $d ?></td>
					</tr>
				<?php }}
                else{
                  	foreach($errorMsg as $k=>$d) { ?>
                      	<tr>
			 			<td width="100%" align="left"  colspan="2" class="redlabel"><?php echo $d ?></td>
					</tr>
                 	<?php }} ?>
                <tr>
            <td class="admtitlebg">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td class="admtitle"><?php echo index_lang::translate("Language List");?></td>
                        <td align="right">
                            <a href="./?page=add_language&action=new" class="admtitlelink" title="<?php echo index_lang::translate("Add Language"); ?>"><?php echo index_lang::translate("Add Language"); ?></a>

                        </td>
                    </tr>
                </table></td>
        </tr>
		            	<tr>
		            		<td valign="top" class="admcontentpadd">
                                            <table width="100%" border="0" cellspacing="1" cellpadding="3" class="gridarea">
                                                <tr class="title">
                                                    <th colspan="2"></th>
                                                    <th colspan="2" style="text-align:right;padding-right:10px;">

                                                    </th>
                                                </tr>
                                                <tr class="gridheaderbg">
                                                    <td width="5%"><?php echo index_lang::$no;?></td>
                                                    <td width="35%" >
            											<?php echo index_lang::translate("Language Name");?>
            											<a href="javascript:sortSubmit('frmLanguage','language_name');">
            												<img border="0" src="<?php echo ADMIN_IMAGE_URL ?>tablehead_sort.gif"/>
            											</a>
            										</td>
            										<td width="10%" >
            											<?php echo index_lang::translate("Language Code");?>
            											<a href="javascript:sortSubmit('frmLanguage','lng_code');">
            												<img border="0" src="<?php echo ADMIN_IMAGE_URL ?>tablehead_sort.gif"/>
            											</a>
            										</td>
            										<td width="10%" >
            											<?php echo index_lang::translate("Status");?>
            											<a href="javascript:sortSubmit('frmLanguage','status');">
            												<img border="0" src="<?php echo ADMIN_IMAGE_URL ?>tablehead_sort.gif"/>
            											</a>
            										</td>
            										<td align="center" width="10%"><?php echo index_lang::translate(index_lang::$action);?></td>
                                                </tr>
                                                <?php if (empty($objlanguage)) {
                                                ?>
                                                            <tr>
                                                                <td valign="top" align="center" colspan="4">
                                                                    <span class="redlabel"><?= index_lang::$no_items ?></span>
                                                                </td>
                                                            </tr>
                                                <?php
                                                        }
                                                        else
                                                        {
                                                            $cnt = 1;
                                                            $srno = ((RECORDS_PER_PAGE) * ($pageno - 1)) + 1;
                                                            foreach ($objlanguage as $k => $d) {
                                                                if ($cnt % 2 == 0) {
                                                                    $cls = "nrlbg";
                                                                } else {
                                                                    $cls = "altbg";
                                                                }
                                                                //echo $d['set_default'];
                                                                 ?>
                                                                <tr class="<?php echo $cls ?>" >
                                                                    <td width="5%" style="padding-left: 5px;"><?php echo $cnt++ ?></td>
                    							        			<td width="35%"><?php echo $d['language_name'] ?></td>
                    												<td width="10%"><?php echo $d['lng_code']; ?></td>
                    												<td width="10%"><?php if($d['status']==1) echo "Active"; else echo"Inactive"; ?></td>
                    												<td align="center" width="10%">
                    													<a href="./?page=add_language&action=edit&id=<?php echo $d['id'] ?>" title="<?=index_lang::$edit?>"><img src="<?php echo ADMIN_IMAGE_URL ?>edit.png"  alt="<?=index_lang::$edit?>" border="0" /></a>&nbsp;&nbsp;
                                                                        <?php if($d['set_default'] == 1){ ?>
                                                                         <a onclick="return alert('Cannot delete language, it is set as default.')" href="" title="<?=index_lang::$delete?>"><img src="<?php echo ADMIN_IMAGE_URL ?>delete.png" alt="<?=index_lang::$delete?>" border="0" /></a>
                                                                        <?php } else {?>
                                                                        <a onclick="return confirm('<?php echo index_lang::$suredelete;?>')" href="./?page=language_list&action=delete&id=<?php echo $d['id'] ?>" title="<?=index_lang::$delete?>"><img src="<?php echo ADMIN_IMAGE_URL ?>delete.png" alt="<?=index_lang::$delete?>" border="0" /></a>
                                                                        <?php } ?>
                    												</td>
                                                                </tr>
                                                        <?php }
                                                        } ?>
                                                    </table>
					 	</td>
		            </tr>
                    <?php if(!empty($objlanguage)) { ?>
					   	<tr style="border-left: none;">
							<td align="right" class="manu" style="border-left: none;" >
							 <?=index_lang::$showing?> <?php echo $starting_record ?> <?=index_lang::$to?> <?php echo $ending_record ?> <?=index_lang::$of ?>&nbsp;<?php echo $total_records ?>&nbsp;&nbsp;
                            <?php echo $pagging ?></td>
					   	</tr>
				   <?php } ?>
                </table>
            </form>
<!--- here->