//
//  HomeViewController.h
//  Aviary
//
//  Created by Nidhi on 01/07/13.
//  Copyright (c) 2013 Riddham. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "CellHomeFeed.h"
#import "URLConnection.h"
#import "JSON.h"
#import "UIImageView+WebCache.h"
#import "UIButton+WebCache.h"
#import "AdvertisementDetailViewController.h"
#import "Photo.h"
#import "Tag.h"
#import "ProfileViewController.h"
#import <AVFoundation/AVFoundation.h>

@interface HomeViewController : UIViewController <UINavigationControllerDelegate,UITableViewDataSource,UITableViewDelegate,UISearchBarDelegate,UIGestureRecognizerDelegate,NotificationNavigationProtocol>
{
    UITableView *tblView;
     UISearchBar *searchBar;
    IBOutlet UIImageView *imgTopBar;
    
    NSMutableArray *arrayListAdv, *arrayCopyListAdv,*arrayOfflineData,*arryIndexPaths;
    BOOL isSearching,isNetworkRechable,isFirstLoad;
    
    NSMutableDictionary *heightsCache;
    
    NSString *strSearchtext;
    NSInteger starRecord,intLike,intLocalRecords;
    
    MBProgressHUD *HUD;
    
    IBOutlet UIView *viewNoInternetConnection;
    
    NSManagedObjectContext *context;
    
    // The counter of fetch batch.
    int fetchBatch;
    
    // Indicates whether the data is already loading.
    // Don't load the next batch of data until this batch is finished.
    // You MUST set loading = NO when the fetch of a batch of data is completed.
    // See line 29 in DataLoader.m for example.
    BOOL loading;
    
    // noMoreResultsAvail indicates if there are no more search results.
    // Implement noMoreResultsAvail in your app.
    // For demo purpsoses here, noMoreResultsAvail = NO.
    BOOL noMoreResultsAvail;
    NSMutableArray *arrDeleteId;
    NSMutableArray *arrUpdatedId;

    
    //UINavigationController *navController;
}

//@property(nonatomic,assign) UINavigationController *navController;

// The counter of fetch batch.
@property (nonatomic) int fetchBatch;

// Indicates whether the data is already loading.
// Don't load the next batch of data until this batch is finished.
// You MUST set loading = NO when the fetch of a batch of data is completed.
// See line 29 in DataLoader.m for example.
@property (nonatomic) BOOL loading;

// noMoreResultsAvail indicates if there are no more search results.
// Implement noMoreResultsAvail in your app.
// For demo purpsoses here, noMoreResultsAvail = NO.
@property (nonatomic) BOOL noMoreResultsAvail;

@property(nonatomic,retain) MBProgressHUD *HUD;
@property(nonatomic,assign) NSInteger starRecord,intLike,intLocalRecords;
@property(nonatomic,retain) NSString *strSearchtext;
@property(nonatomic,retain) NSMutableArray *arrayListAdv, *arrayCopyListAdv,*arrayOfflineData,*arryIndexPaths;
@property(nonatomic,retain) NSMutableDictionary *heightsCache;
@property(nonatomic,assign) BOOL isSearching,isNetworkRechable;
@property(nonatomic,retain) IBOutlet UITableView *tblView;
@property(nonatomic,retain) IBOutlet UISearchBar *searchBar;
@property(nonatomic,retain)  NSArray *arrOfflineByPhotoid;


//@property (nonatomic, retain) HBTabBarManager *tabBar;
@property (nonatomic, retain) NSManagedObjectContext *context;


@end
