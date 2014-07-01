<script>
    
    // error-messages hide shows
    $(document).ready(function(){
        $(".close").click(function(){
            
            $(".alert-success").hide();
        });
    });
    
    

</script>
<script>
    function sorting(value){
        var val = value;
        // alert(value);return false;
        document.getElementById("hdn_sorting").value = val;
        document.frmLanguageVar.submit();
    }
</script>
<div class="main-content">
    <div class="main-content-top">
        <div class="breadcrumb">
            <ul class="clearfix">
                <li><a href="<?php echo  ADMIN_URL ?>" title="Home">Home</a><img src="../images/theme_images/bcarrow.png" alt="arrow" /></li>
                <li><a href="<?php echo  ADMIN_URL ?>?page=language_var_list" title="Language Variable Management">Language Variable Management</a><img src="../images/theme_images/bcarrow.png" alt="arrow" /></li>
                <li>List Language Variable</li>
            </ul>
        </div>
        <div class="page-title"><h1>List Language Variable</h1></div>
    </div>





    <?php if (count($successMsg) > 0) { ?>
        <div class="msg-content-box">
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <?php
                foreach ($successMsg as $successMsg) {
                    echo $successMsg . '<br/>';
                }
                ?>
            </div>
        </div>
    <?php } ?>

    <div class="main-container">

        <div class="search-box">
            <table cellspacing="2" cellpadding="4" border="0">
                <tbody>
                    <tr>
                <form action="" method="post" name="frmLanguageVar">
                    <input type="hidden" name="hdn_sorting" id="hdn_sorting" value="<?php echo $sorting; ?>"></input>
                    <td align="right">Text to Search:</td>
                    <td align="left"> <input type="text" name="searchtext" value="<?php echo $search_user; ?>" style="width:150px;"></td>
                    <td align="left" colspan="2">
                        <input type="submit" class="inputbutton" title="Apply Filter" value="Apply Filter">
                        &nbsp;<input type="submit" class="inputbutton" title="Reset" value="Reset" name="reset">
                    </td>
                </form>
                </tr>
                </tbody>
            </table>
        </div><?php if(!empty($categories)){ ?>
        <div class="grid-data grid-data-table">
            
            
            <div class="add-new"><a href="<?php echo  ADMIN_URL ?>?page=add_language_var" title="Add New Language Variable">Add New Language Variable</a></div>
            <table cellspacing="1" cellpadding="4" border="0" bgcolor="#e6ecf2" width="100%">
                <tbody bgcolor="#fff">
                    <tr>
                        <th>No</th>
                        <th>
                            <span class="user-title">Language Variable Name</span>
                            <!--<div class="sorting"><a href="javascript:sorting('ASC');"><img src="<?php echo ADMIN_IMAGE_URL; ?>theme_images/srt_up.png" alt="Sort-up" /></a><a href="javascript:sorting('DESC');"><img src="<?php echo ADMIN_IMAGE_URL; ?>theme_images/srt_down.png" alt="Sort-down" /></a></div>-->
                        </th>
                        <th>Actions</th>
                      </tr>
                <?php $i = $startOffset; ?>
                <?php foreach ($categories as $_categories) { ?>
                    <tr class="odd-row">
                        <td><?php echo $i;
                $i++ ?></td>
                        <td><?php echo $_categories['name']; ?></td>

                        <td>
                            <div class="action">
                                <div class="edit"><a href="<?php echo  ADMIN_URL ?>?page=add_language_var&action=edit&id=<?php echo $_categories['id']; ?>" title="Edit"><img src="../images/theme_images/edit.png" alt="Edit" /></a></div>
                                <div class="delete">
                                    <a style="text-decoration: none" onclick="return confirm('<?php echo "Are you sure want to delete?" ?>')" href="./?page=language_var_list&action=delete&id=<?php echo $_categories['id'] ?>" title="Delete">
                                        <img src="../images/theme_images/delete.png" alt="Delete" /></a></div>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="paging clearfix">
            <div class="pagination">
                <!--                                	<ul class="clearfix">
                                                        <li class="paging-tp"><a href="#" title="First"><img src="../images/theme_images/first.png" alt="First" /></a></li>
                                                        <li class="paging-tp"><a href="#" title="Prev"><img src="../images/theme_images/prev.png" alt="Prev" /></a></li>
                                                        <li class="active"><a href="#" title="1">1</a></li>
                                                        <li><a href="#" title="2">2</a></li>
                                                        <li class="paging-tp"><a href="#" title="Next"><img src="../images/theme_images/next.png" alt="Next" /></a></li>
                                                        <li class="paging-tp"><a href="#" title="Last"><img src="../images/theme_images/last.png" alt="last" /></a></li>
                                                    </ul>
                -->
                <?php echo $pagging; ?>
            </div>
            <div class="pagination-info">
                    <!--<div class="page-size">Page Size: <select style="wi"><option value="10" selected="selected">10</option><option value="20" selected="selected">20</option><option value="25" selected="selected">25</option></select></div>-->
                <div class="items-info">Displaying items <?php echo $startOffset ?> to <?php echo $endOffset ?> of <?php echo $totalResults ?></div>
            </div>
        </div>

        <?php }else{
        echo 'No Items found';
        } ?>
        
        
        
        
    </div>
</div>