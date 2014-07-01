//
//  NewEditAdvertismentViewController.h
//  Aviary
//
//  Created by MAC8 on 7/13/13.
//  Copyright (c) 2013 Riddham. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <AssetsLibrary/AssetsLibrary.h>
#import <QuartzCore/QuartzCore.h>
#import "AFPhotoEditorController.h"
#import "AFPhotoEditorCustomization.h"
#import "AFOpenGLManager.h"
#import "Photo.h"
#import "Tag.h"


@interface NewEditAdvertismentViewController : UIViewController<UITextFieldDelegate,UIGestureRecognizerDelegate,UIActionSheetDelegate,UIImagePickerControllerDelegate, UINavigationControllerDelegate, AFPhotoEditorControllerDelegate, UIPopoverControllerDelegate,MBProgressHUDDelegate>
{
    IBOutlet UIImageView * imagePreviewView;
    UITextField *txtBrands;
    UILabel *lblTitle;
    
    BOOL isSelectCamera;
    
    IBOutlet UIView *advertiseView;
    
    MBProgressHUD *HUD;
    long long expectedLength;
	long long currentLength;
    
    NSMutableData *responseData;
    
    BOOL isEditPhoto;
    UIImage *imgEditAdvertise;
    
    NSString *strPhotoId;
    
    IBOutlet UIButton *btnPost;
    IBOutlet UIButton *btnBack;
    IBOutlet UIImageView *imgTopbar;

}

@property(nonatomic,retain) NSString *strPhotoId;
@property(nonatomic,retain) UIImage *imgEditAdvertise;
@property(nonatomic,assign) BOOL isEditPhoto;
@property(nonatomic,retain) NSMutableData *responseData;

@property(nonatomic,retain) IBOutlet UITextField *txtBrands;
@property(nonatomic,retain) IBOutlet UILabel *lblTitle;

@property (strong, nonatomic)IBOutlet UIImageView * imagePreviewView;
@property (nonatomic, strong) UIView * borderView;
//@property (nonatomic, strong) UIPopoverController * popover;
//@property (nonatomic, assign) BOOL shouldReleasePopover;

@property (nonatomic, strong) ALAssetsLibrary * assetLibrary;
@property (nonatomic, strong) NSMutableArray * sessions;

-(IBAction)backClicked:(id)sender;
-(IBAction)postClicked:(id)sender;


@end
