<script>
    function changeStatus(statusVal,commentId){
        window.location = "<?php echo ADMIN_URL ?>?page=add_prompt&action=statusupdate&status="+statusVal+"&id="+commentId;
        return true;

    }
</script>
<form name="frmPromptListing" id="frmCommentListing" action="" method="post">
    <div class="main-content">
        <div class="main-content-top">
            <div class="breadcrumb">
                <ul class="clearfix">
                    <li><a href="<?php echo ADMIN_URL ?>" title="Home">Home</a><img src="../images/theme_images/bcarrow.png" alt="arrow" /></li>
                    <li><a href="<?php echo ADMIN_URL ?>?page=prompt_listing" title="Prompt Management">Prompt Management</a><img src="../images/theme_images/bcarrow.png" alt="arrow" /></li>
                    <li>Prompt List</li>
                </ul>
            </div>
            <div class="page-title"><h1>Prompt List</h1></div>
        </div>
        <?php action_message(2) ?>
        <div class="main-container">
           
            <div class="search-box">

                             

                <table  border="0" cellspacing="1" cellpadding="4" width="100%">
                    <tr>
                        <td><strong><?php echo "Prompt Search" ?> : </strong></td>
                        <td ><input name="searchtext" id="searchtext" maxlength="50" onkeypress="" value="<?php echo stripcslashes($_REQUEST['searchtext']); ?>" type="text"  /></td>
                        <td align="left"><input name="btsearch" id="btsearch"  type="submit"  class="inputbutton"  value="<?php echo "Search" ?>"  /></td>
                        <td align="left">
                            <input type="button" name="Reset" class="inputbutton" onclick="location.href='./?page=prompt_listing'"  value="<?php echo "Reset" ?>">
                        </td>
                         <td class="add-new" align="right" width="50%"><a href="<?php echo  ADMIN_URL ?>?page=add_prompt" title="Add Prompts"> Add Prompt</a></td>

                    </tr>
                </table>
    
            </div>
            <div class="grid-data grid-data-table">
                <?php if (count($prompt) > 0) { ?>

                    <input type="hidden" name="sort_order" id="sort_order" value="<?php echo $sort_order ?>" />
                    <input type="hidden" name="sort_by" id="sort_by" value="<?php echo $sort_by ?>" />

                    <table cellspacing="1" cellpadding="4" border="0" bgcolor="#e6ecf2" width="100%">
                        <tbody bgcolor="#fff">


                            <tr>
                                <th>No</th>
                                <th>Prompt Message
                                    <a href="javascript:sortSubmit('frmPromptListing','prompt');">
                                        <img border="0" src="<?php echo ADMIN_IMAGE_URL ?>tablehead_sort.gif"/>
                                    </a>
                                </th>



                                <th><?php echo "Status" ?>
                                    <a href="javascript:sortSubmit('frmPromptListing','status');">
                                        <img border="0" src="<?php echo ADMIN_IMAGE_URL ?>tablehead_sort.gif"/>
                                    </a>
                                </th>


                                <th><?php echo "Action" ?></th>
                            </tr>


                            <?php $i = $starting_record; ?>
                            <?php foreach ($prompt as $prompts) { ?>
                                <tr class="odd-row">
                                    <td><?php echo $i;
                        $i++ ?></td>
                                    <td><?php echo $prompts['prompt'] ?> </td>
                                    <td><img style="cursor: pointer;" <?php if ($prompts['status'] == 'Active') { ?>src="../images/theme_images/active.png" title="Active"<?php } else { ?>src="../images/theme_images/inactive.png" title="Inactive"<?php } ?> onclick="changeStatus(this.title,'<?php echo $prompts["id"]; ?>');" alt="Active" /></td>
                                    <td>
                                        <div class="action">

                                            <?php
                                            $etvars = '';
                                            if (isset($_REQUEST['pageno'])) {
                                                $etvars = '&pageno=' . $_REQUEST['pageno'];
                                            }
                                            if (isset($_REQUEST['searchtext'])) {
                                                $etvars .= '&searchtext=' . $_REQUEST['searchtext'];
                                            }
                                            if (isset($_REQUEST['search'])) {
                                                $etvars .= '&search=' . $_REQUEST['search'];
                                            }
                                            ?>

                                            <div class="edit"><a href="<?php echo ADMIN_URL ?>?page=add_prompt&action=edit&id=<?php echo $prompts['id']; ?><?php echo $etvars ?>" title="Edit"><img src="../images/theme_images/edit.png" alt="Edit" /></a></div>
                                            <div class="delete"><a onclick="return confirm('Are you sure you want to delete?')" href="<?php echo ADMIN_URL ?>?page=prompt_listing&action=delete&id=<?php echo $prompts['id'] ?>" title="Delete"><img src="../images/theme_images/delete.png" alt="Delete" /></a></div>
                                        </div>
                                    </td>
                                </tr>
    <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="paging clearfix">
                    <div class="pagination">
    <?php print_r($pagging); ?>
                    </div>
                    <div class="pagination-info">
                        <div class="items-info">Displaying items <?php echo $starting_record ?> to <?php echo $ending_record ?> of <?php echo $total_records ?></div>
                    </div>
                </div>
                <div style="text-align: right;padding: 5px;" >
                    <img src="../images/theme_images/active.png" title="Active" alt="Active" /> Active
                    <img src="../images/theme_images/inactive.png" title="Inctive" alt="Inactive" /> Inactive
                </div>
<?php } else {
    echo "No Items found";
} ?>
        </div>
    </div> </form>