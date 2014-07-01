//
//  AdvertisementDetailViewController.h
//  Aviary
//
//  Created by MAC8 on 7/18/13.
//  Copyright (c) 2013 Riddham. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "UIImageView+WebCache.h"
#import "SSTextView.h"
#import "URLConnection.h"
#import "JSON.h"
#import <Social/Social.h>

@interface AdvertisementDetailViewController : UIViewController<UINavigationControllerDelegate, UITableViewDataSource,UITableViewDelegate,UITextViewDelegate>
{
    NSMutableArray *arrayComments;
    UIScrollView *scrollView;
    UITableView *tblViewComments;
    IBOutlet UIImageView *imgViewAdvertise;
    
    IBOutlet UILabel *lblNoLikes,*lblNoComments,*lbltags;
    
    IBOutlet UIButton *btnLike,*btnComment,*btnFacebook,*btnTwitter,*btnFlag,*btnEdit;
    
    IBOutlet UIToolbar *toolbar;
    IBOutlet UIBarButtonItem *btnAddComment;
    IBOutlet UIScrollView *scrollLikeUsers;;
    IBOutlet UILabel *lblLike;

    UITextView *txtViewComment;
    
    BOOL isAddComment,isEditPhoto;
    
    NSString *strPhotoId;
    
    CGFloat scrollHeight;
    
    NSString *strIfUserLike;
    
    NSMutableDictionary *dictPhotoDetail;
    int originalScrollContent;
    NSTimer *scrollTimer;
    BOOL isTapped;
    NSMutableArray *arrLikeUsers;
    int valxorigin;
    int arrIndexCount;

}

@property(nonatomic,assign) BOOL isEditPhoto;
@property(nonatomic,retain) NSMutableDictionary *dictPhotoDetail;
@property(nonatomic,retain) NSString *strIfUserLike;
@property(nonatomic,retain) NSString *strPhotoId;
@property(nonatomic,retain) UITextView *txtViewComment;
@property(nonatomic,retain) IBOutlet UIScrollView *scrollView;
@property(nonatomic,retain) IBOutlet UITableView *tblViewComments;
@property(nonatomic,retain) NSMutableArray *arrayComments;

-(IBAction)backClicked:(id)sender;
-(IBAction)flagClicked:(id)sender;
-(IBAction)editClicked:(id)sender;

-(IBAction)likeClicked:(id)sender;
-(IBAction)commentClicked:(id)sender;
-(IBAction)facebookClicked:(id)sender;
-(IBAction)twitterClicked:(id)sender;

-(IBAction)addCommentDoneKeyboadClicked:(id)sender;
-(IBAction)cancelKeyboadClicked:(id)sender;

@end
