//
//  MainViewController.h
//  Aviary
//
//  Created by MAC8 on 8/26/13.
//  Copyright (c) 2013 Riddham. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "HomeViewController.h"
#import "ProfileViewController.h"
#import "NewEditAdvertismentViewController.h"

@interface MainViewController : UIViewController<UINavigationControllerDelegate>
{
    UIView *baseView;
    UIButton *btnFeed,*btnCreateAdv,*btnProfile;
    
//    HomeViewController *objHome;
//    ProfileViewController *objProfile;
    
    UINavigationController *navControllerHome,*navControllerProfile;
}
@property(nonatomic,retain) UINavigationController *navControllerHome,*navControllerProfile;
//@property(nonatomic,retain) HomeViewController *objHome;
//@property(nonatomic,retain) ProfileViewController *objProfile;
@property(nonatomic,retain) IBOutlet UIView *baseView;
@property(nonatomic,retain) IBOutlet UIButton *btnFeed,*btnCreateAdv,*btnProfile;

-(IBAction)btnFeedClicked:(id)sender;
-(IBAction)btnCreateAdvClicked:(id)sender;
-(IBAction)btnProfileClicked:(id)sender;

@end
