<form name="frmPhotoFlagListing" id="frmPhotoFlagListing" action="" method="post">
                    <div class="main-content">
                    	<div class="main-content-top">
                            <div class="breadcrumb">
                                <ul class="clearfix">
                                    <li><a href="<?php echo ADMIN_URL ?>" title="Home">Home</a><img src="../images/theme_images/bcarrow.png" alt="arrow" /></li>
                                  <li><a href="<?php echo ADMIN_URL ?>?page=photo_flag_listing" title="Product Management">Photo Flag Management</a><img src="../images/theme_images/bcarrow.png" alt="arrow" /></li>
                                    <li>List Photo Flag</li>
                                </ul>
                            </div>
            				<div class="page-title"><h1>List Photo Flag</h1></div>
                        </div>
                             <?php action_message(2) ?>
                        <div class="main-container">
                        	<div class="search-box">
                            	<!--<table cellspacing="2" cellpadding="4" border="0">
                                	<tbody>
                                    	<tr>
                                        <form action="" method="post">
                                          <td align="right">Text to Search By User Name :</td>
                                          <td align="left"> <input type="text" name="searchtext" value="<?php echo $search_user;?>" style="width:150px;"></td>
                                          <td align="left" colspan="2">
                                          	<input type="submit" class="inputbutton" title="Apply Filter" value="Apply Filter">
											&nbsp;<input type="submit" class="inputbutton" title="Reset" value="Reset" name="reset">
                                           </td>
                                           </form>
                                         </tr>
                                     </tbody>
                                 </table>-->


                                  <table  border="0" cellspacing="1" cellpadding="4" >
                                      <tr>
                                          <td><strong><?php  echo "Search Option"?>: </strong></td>
                                          <td>
                                              <select class="txtwidth3" name="search" id="search" >
                                                  <option value="1" <?php if ($_REQUEST['search'] == 1) echo"selected" ?> title="name" ><?php  echo "Name"?></option>
                                                  <option value="2" <?php if ($_REQUEST['search'] == 2) echo"selected" ?> title="username" ><?php  echo "Username"?></option>
                                              </select>
                                          </td>
                                          <td><strong><?php  echo "Text to Search"?> : </strong></td>
                                          <td ><input name="searchtext" id="searchtext" maxlength="50" onkeypress="" value="<?php echo stripcslashes($_REQUEST['searchtext']); ?>" type="text"  /></td>
                                          <td align="left"><input name="btsearch" id="btsearch"  type="submit"  class="inputbutton"  value="<?php  echo "Search"?>"  /></td>
                                          <td align="left">
                                              <input type="button" name="Reset" class="inputbutton" onclick="location.href='./?page=photo_flag_listing'"  value="<?php  echo "Reset"?>">
                                          </td>
                                      </tr>
                                  </table>

                            </div>
                        <div class="grid-data grid-data-table">
                            	<!-- <div class="add-new"><a href="<?php echo ADMIN_URL ?>?page=add-product" title="Add New Product">Add New Product</a></div> -->
                        <?php if (count($photo_flag)>0){?>

                               <input type="hidden" name="sort_order" id="sort_order" value="<?php echo $sort_order ?>" />
                                <input type="hidden" name="sort_by" id="sort_by" value="<?php echo $sort_by ?>" />

                                <table cellspacing="1" cellpadding="4" border="0" bgcolor="#e6ecf2" width="100%">
                                	<tbody bgcolor="#fff">
                                    	<tr>
                                        	<th>No</th>

                                            <th>Name
                                              <a href="javascript:sortSubmit('frmPhotoFlagListing','p.name');">
                                                <img border="0" src="<?php echo ADMIN_IMAGE_URL ?>tablehead_sort.gif"/>
                                              </a>
                                            </th>
                                            <th>Photo
                                              <a href="javascript:sortSubmit('frmPhotoFlagListing','p.image');">
                                                <img border="0" src="<?php echo ADMIN_IMAGE_URL ?>tablehead_sort.gif"/>
                                              </a>
                                            </th>
                                            <th>User Name
                                              <a href="javascript:sortSubmit('frmPhotoFlagListing','p.user_id');">
                                                <img border="0" src="<?php echo ADMIN_IMAGE_URL ?>tablehead_sort.gif"/>
                                              </a>
                                            </th>
                                           <!-- <th>Created On</th> -->
                                            <th>Actions</th>
                                        </tr>
                                    <?php $i=$starting_record;?>
                                    <?php foreach ($photo_flag as $photo_flags){ ?>
                                        <tr class="odd-row">
                                        	<td><?php echo $i;$i++?></td>
                                            <td><?php echo $photo_flags['name'] ?> </td>
                                            <td align="center" style="text-align: center;">
                                                                <?php //echo PHOTO_IMAGE_URL.$photo_flags['image']; ?>
                        				        <?php if( $photo_flags['image'] == '' ) { ?>
                        				         <img align="left" src="<?php echo ADMIN_IMAGE_URL.'default_image.gif'; ?>" height="80px;" width="80px;" alt="" >
                        				        <?php } else { ?>
                        				         <img align="left" src="<?php echo PHOTO_IMAGE_URL.$photo_flags['image']; ?>" height="80px;" width="80px;" alt="" >
                        				         <?php } ?>
                        				    </td>
                                            <td>
                                                <?php
                                                    $user_name = $objPhotoFlag->get_user_details($photo_flags['user_id']);
                                                    echo $user_name['user_name'];
                                                ?>
                                            </td>
                                            <td>
                                            	<div class="action">

                                                 <?php
                                                         $etvars = '';
                                                        if(isset($_REQUEST['pageno'])){
                                                            $etvars = '&pageno='.$_REQUEST['pageno'];
                                                        }
                                                        if(isset($_REQUEST['searchtext'])){
                                                                $etvars .= '&searchtext='.$_REQUEST['searchtext'];
                                                        }
                                                        if(isset($_REQUEST['search'])){
                                                                $etvars .= '&search='.$_REQUEST['search'];
                                                        }
                                                    ?>
                                                   <div class="edit"> <?php if( $photo_flags['flag'] == 'Active' ) { ?>
                                                         <a href="./?page=photo_flag_listing&action=edit&id=<?php echo $photo_flags['id'] ?><?php echo $etvars?>" title="Remove Flag">
                                                            <img src="<?php echo ADMIN_IMAGE_URL ?>flag_green.png"  alt="" border="0" />
                                                         </a>
                                                   <?php }else {?>
                                                         <a  href="./?page=photo_flag_listing&action=edit&id=<?php echo $photo_flags['id'] ?><?php echo $etvars?>" title="Remove Flag">
                                                            <img src="<?php echo ADMIN_IMAGE_URL ?>flag_red.gif" alt="" border="0" />
                                                         </a>
                                                        <?php }?></div>
                                                    <!--<div class="delete"><a href="#" title="Delete"><img src="../images/theme_images/delete.png" alt="Delete" /></a></div>-->
                                                    <div class="delete"><a onclick="return confirm('Are you sure you want to delete?')" href="<?php echo ADMIN_URL?>?page=photo_flag_listing&action=delete&id=<?php echo $photo_flags['id'] ?>" title="Delete"><img src="../images/theme_images/delete.png" alt="Delete" /></a></div>
                                                
                                                  
                                                     
                                                </div>
                                            </td>
                                        </tr>
                                    <?php }?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="paging clearfix">
                            	<div class="pagination">
<!--                               	 <ul class="clearfix">
                                    	<li class="paging-tp"><a href="#" title="First"><img src="../images/theme_images/first.png" alt="First" /></a></li>
                                        <li class="paging-tp"><a href="#" title="Prev"><img src="../images/theme_images/prev.png" alt="Prev" /></a></li>
                                        <li class="active"><a href="#" title="1">1</a></li>
                                        <li><a href="#" title="2">2</a></li>
                                        <li class="paging-tp"><a href="#" title="Next"><img src="../images/theme_images/next.png" alt="Next" /></a></li>
                                        <li class="paging-tp"><a href="#" title="Last"><img src="../images/theme_images/last.png" alt="last" /></a></li>
                                    </ul>
-->
                                    <?php print_r($pagging); ?>
                                </div>
                                <div class="pagination-info">
                                	<!--<div class="page-size">Page Size: <select style="wi"><option value="10" selected="selected">10</option><option value="20" selected="selected">20</option><option value="25" selected="selected">25</option></select></div>-->
                                    <div class="items-info">Displaying items <?php echo $starting_record?> to <?php echo $ending_record?> of <?php echo $total_records?></div>
                                </div>
                            </div>
                                <!--<div style="text-align: right;padding: 5px;" >
                                  <img src="../images/theme_images/active.png" title="Active" alt="Active" /> Active
                                  <img src="../images/theme_images/inactive.png" title="Inctive" alt="Inactive" /> Inactive
                                </div>-->
                            <?php }else{echo "No Items found";}?>
                        </div>
            		</div> </form>