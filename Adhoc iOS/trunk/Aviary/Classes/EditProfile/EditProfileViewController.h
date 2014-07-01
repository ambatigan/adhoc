//
//  EditProfileViewController.h
//  Aviary
//
//  Created by MAC8 on 7/19/13.
//  Copyright (c) 2013 Riddham. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <AssetsLibrary/AssetsLibrary.h>
#import "Base64.h"
#import "User.h"

@interface EditProfileViewController : UIViewController<UIImagePickerControllerDelegate,UINavigationControllerDelegate,UIActionSheetDelegate,UITextFieldDelegate>
{
    UITextField *txtUserName,*txtName,*txtHomeTown;
    
    UIImageView *imgProfileView;
    
    IBOutlet UILabel *lblNoPosts,*lblLikes,*lblTitle,*lblRank;
    IBOutlet UIButton *btnClose,*btnUpdate;
    IBOutlet UIImageView *imgTopbar;
    IBOutlet UIView *viewDetail;
    
    BOOL isSelectCamera;
    
    NSString *strUserName,*strName,*strRank,*strNoPost,*strNoLikes,*strHomeTown;
    
}
@property(nonatomic,retain) NSString *strUserName,*strName,*strRank,*strNoPost,*strNoLikes,*strHomeTown;
@property (nonatomic, strong) ALAssetsLibrary * assetLibrary;
@property(nonatomic,retain) IBOutlet UITextField *txtUserName,*txtName,*txtHomeTown;
@property(nonatomic,retain) IBOutlet UIImageView *imgProfileView;

-(IBAction)updateClicked:(id)sender;
-(IBAction)closeClicked:(id)sender;
-(IBAction)editPhotoClicked:(id)sender;

@end
