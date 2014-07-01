//
//  AppDelegate.h
//  Aviary
//
//  Created by Nidhi on 01/07/13.
//  Copyright (c) 2013 Riddham. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <CoreData/CoreData.h>
#import "Flurry.h"
#import "Photo.h"


@protocol NotificationNavigationProtocol <NSObject>

-(void)goToNextPageForNotification;

@end

@class LoginViewController;
@class HomeViewController;
@class MainViewController;

@interface AppDelegate : UIResponder <UIApplicationDelegate, UITabBarControllerDelegate>
{
    UINavigationController *navigationController;
    NSString *strUserId,*strUserName,*strUserPic,*strBrands;
    NSMutableArray *arrExpandedPaths;
    NSInteger intTotalRecord;
    
    //For Pushnotification
    BOOL isFromNotification;
    NSString *strNotificationPhotoId,*strNotificationUserId;
    
    id<NotificationNavigationProtocol> delegateNotification;
    
    BOOL isLoginUser,isPresentedView;
    
}

@property(nonatomic,assign) BOOL isLoginUser,isPresentedView,isIOS7;
@property (retain, nonatomic) UINavigationController *navigationController;
@property(nonatomic,assign) id<NotificationNavigationProtocol> delegateNotification;
@property (retain, nonatomic) NSString *strNotificationPhotoId,*strNotificationUserId;
@property(nonatomic,assign) BOOL isFromNotification;
@property(nonatomic,assign) NSInteger intTotalRecord;
@property (retain, nonatomic) NSMutableArray *arrExpandedPaths;
@property (retain, nonatomic)NSString *strUserId,*strUserName,*strUserPic,*strBrands;
@property (strong, nonatomic) UIWindow *window;
@property (strong, nonatomic) LoginViewController *loginViewController;
@property (strong, nonatomic) HomeViewController *homeViewController;
@property (strong, nonatomic) MainViewController *mainViewController;
@property (nonatomic, retain) Photo *newAddedPhotoAppdel;


@property (readonly, strong, nonatomic) NSManagedObjectContext *managedObjectContext;
@property (readonly, strong, nonatomic) NSManagedObjectModel *managedObjectModel;
@property (readonly, strong, nonatomic) NSPersistentStoreCoordinator *persistentStoreCoordinator;

- (void)saveContext;
- (NSURL *)applicationDocumentsDirectory;


- (BOOL)isNetWorkAvailable;
+(AppDelegate *)setDelegate;

- (void)adjustFontSizeToFit:(UILabel *)lable;
- (void)adjustFontSizeToFitForButton:(UIButton *)button;

@end
