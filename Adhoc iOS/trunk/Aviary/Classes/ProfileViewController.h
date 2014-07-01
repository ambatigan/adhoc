//
//  ProfileViewController.h
//  Aviary
//
//  Created by Nidhi on 01/07/13.
//  Copyright (c) 2013 Riddham. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <QuartzCore/QuartzCore.h>
//#import "HBTabBarManager.h"
#import "MyImageView.h"
#import "User.h"
#import "UserTag.h"
#import "User.h"

@interface ProfileViewController : UIViewController <UITableViewDataSource,UITableViewDelegate,UIGestureRecognizerDelegate, UIAlertViewDelegate> {
 
    IBOutlet UILabel *lblName,*lblHomeTown,*lblCurrentRank,*lblNoLikes,*lblNoPost,*lblBrands,*lblTitle;
    UIImageView *imhUserProfile;
    NSMutableArray *arrayUserAdvertisements;
    
    UITableView *tblView;
    IBOutlet UIButton *btnEdit,*btnClose;
    IBOutlet UIView *viewProfile,*viewNoInternet;
    NSInteger starRecord;
    
    NSMutableDictionary *dictUserProfile;
    BOOL isNetworkAvailable;

    int fetchBatch;
    BOOL loading;
    BOOL noMoreResultsAvail;
    NSString *stringUserId,*strUserImage;
    
    //UINavigationController *navController;
    
    NSMutableDictionary *heightsCache;
    
    User *objUserDetail;
}

@property (nonatomic, retain) User *objUserDetail;
//@property(nonatomic,assign) UINavigationController *navController;
@property (nonatomic, retain) NSString *stringUserId,*strUserImage;
@property (nonatomic) int fetchBatch;
@property (nonatomic) BOOL loading;
@property (nonatomic) BOOL noMoreResultsAvail;

@property (nonatomic, assign) BOOL isNetworkAvailable;
@property (nonatomic, retain) NSMutableDictionary *dictUserProfile;
@property(nonatomic,assign) NSInteger starRecord;
//@property (nonatomic, retain) HBTabBarManager *tabBar;
@property (nonatomic, retain) IBOutlet UITableView *tblView;
@property(nonatomic,retain) IBOutlet UIImageView *imhUserProfile;
@property(nonatomic,retain) NSMutableArray *arrayUserAdvertisements;
@property(nonatomic,retain) NSMutableDictionary *heightsCache;

-(IBAction)editClicked:(id)sender;
-(IBAction)closeClicked:(id)sender;

@end
