//
//  HomeViewController.m
//  Aviary
//
//  Created by Nidhi on 01/07/13.
//  Copyright (c) 2013 Riddham. All rights reserved.
//

#import "HomeViewController.h"
#import "ProfileViewController.h"
#import "NewEditAdvertismentViewController.h"

#define TAG_CONTENT_WIDTH 142.0f
#define TAG_MARGIN 5.0f
#define RECORD_TO_LOAD 3

@interface HomeViewController ()

@end

@implementation HomeViewController
@synthesize searchBar;
@synthesize arrayListAdv,arrayCopyListAdv,arrayOfflineData,isSearching;
@synthesize tblView,strSearchtext;
@synthesize starRecord,intLike,HUD;
@synthesize fetchBatch,loading,noMoreResultsAvail;
@synthesize context,isNetworkRechable,heightsCache;
@synthesize intLocalRecords;
@synthesize arrOfflineByPhotoid;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        // Custom initialization
    }
    return self;
}



- (void)viewDidLoad
{
    [super viewDidLoad];
    // custom tab bar
    //tabBar = [[HBTabBarManager alloc]initWithViewController:self topView:self.view delegate:self selectedIndex:0];
 
    // initialize array
    arrDeleteId = [[NSMutableArray alloc]init];
    arrUpdatedId = [[NSMutableArray alloc]init];
    NSMutableArray *arr=[[NSMutableArray alloc]init];
    self.arrayListAdv = arr;
    [arr release];
    
    NSMutableArray *arr1=[[NSMutableArray alloc]init];
    self.arrayCopyListAdv = arr1;
    [arr1 release];
    
    NSMutableDictionary *dict=[[NSMutableDictionary alloc]init];
    self.heightsCache = dict;
    [dict release];
    
    NSMutableArray *arrTemp=[[NSMutableArray alloc]init];
    self.arrOfflineByPhotoid = arrTemp;
    [arrTemp release];
    
    self.arrOfflineByPhotoid = [self getOfflinePhotoDetailByPhotoId];

    
    //Custome SearchBar
    searchBar.showsScopeBar = NO;
    [searchBar sizeToFit];
    searchBar.delegate = self;
    searchBar.autocapitalizationType = UITextAutocapitalizationTypeNone;
    searchBar.autocorrectionType = UITextAutocorrectionTypeNo;
   
    
    //if (floor(NSFoundationVersionNumber) <= NSFoundationVersionNumber_iOS_6_1) {
        // Load resources for iOS 6.1 or earlier
        
        UITextField *searchField = nil;
        for (UIView *subview in searchBar.subviews) {
            if ([subview isKindOfClass:[UITextField class]]) {
                searchField = (UITextField *)subview;
                searchField.keyboardAppearance=UIKeyboardAppearanceAlert;
                searchField.placeholder=@"Search people or brands";
                searchField.font=[UIFont fontWithName:@"American Typewriter" size:12];
                
                break;
            }
        }
        
        [searchBar setBackgroundImage:[UIImage new]];
        [searchBar setTranslucent:YES];
        
        if (searchField) {
            UIImage *image = [UIImage imageNamed: @"search_icon.png"];
            UIImageView *iView = [[UIImageView alloc] initWithImage:image];
            searchField.leftView = iView;
            [iView release];
        }
        
        id barButtonAppearanceInSearchBar = [UIBarButtonItem appearanceWhenContainedIn:[UISearchBar class], nil];
        [barButtonAppearanceInSearchBar setBackgroundImage:[UIImage imageNamed:@"searchCancelButton.png"] forState:UIControlStateNormal barMetrics:UIBarMetricsDefault];
        [barButtonAppearanceInSearchBar setTitleTextAttributes:[NSDictionary dictionaryWithObjectsAndKeys:
                                                                [UIFont fontWithName:@"American Typewriter" size:12],UITextAttributeFont, nil]
                                                      forState:UIControlStateNormal];
        //[barButtonAppearanceInSearchBar setTitle:@"Cancel"];
        //[barButtonAppearanceInSearchBar setTitle:@"Cancel"];
        
        [[UIBarButtonItem appearanceWhenContainedIn:[UISearchBar class], nil] setTitleTextAttributes:[NSDictionary dictionaryWithObjectsAndKeys:
                                                                                                      [UIColor whiteColor],
                                                                                                      UITextAttributeTextColor,
                                                                                                      [UIColor darkGrayColor],
                                                                                                      UITextAttributeTextShadowColor,
                                                                                                      [NSValue valueWithUIOffset:UIOffsetMake(0, -1)],
                                                                                                      UITextAttributeTextShadowOffset,
                                                                                                      [UIFont fontWithName:@"American Typewriter Bold" size:15],
                                                                                                      UITextAttributeFont,
                                                                                                      nil]
                                                                                            forState:UIControlStateNormal];
        
        searchBar.frame=CGRectMake(10, 0, 300, 44);
        //self.tblView.frame=CGRectMake(0, 44, 320, 440);
        
        [searchBar setSearchFieldBackgroundImage:[UIImage imageNamed:@"topbar_searchtextfield.png"] forState:UIControlStateNormal];
        
//    } else {
//        // Load resources for iOS 7 or later
//    }
    
    

    self.intLike=0;
    
    // No searching
    self.starRecord=0;
    self.isSearching=NO;
    self.strSearchtext=@"";
    
    fetchBatch = 0;
    noMoreResultsAvail = NO;
    isFirstLoad=YES;
    
    self.loading=NO;
    
    //Pull to refresh tableview data
    UIRefreshControl *refresh = [[UIRefreshControl alloc] init];
    
    NSString *title=@"Pull to Refresh";
    NSMutableAttributedString *titleAttString =  [[NSMutableAttributedString alloc] initWithString:title];
    [titleAttString addAttribute:NSFontAttributeName value:[UIFont fontWithName:@"American Typewriter" size:13.0f] range:NSMakeRange(0, [title length])];
    [titleAttString addAttribute:NSForegroundColorAttributeName value:[UIColor blackColor] range:NSMakeRange(0, [title length])];
    refresh.attributedTitle = titleAttString; //[[NSAttributedString alloc] initWithString:@"Pull to Refresh"];
    [refresh addTarget:self action:@selector(refreshView:) forControlEvents:UIControlEventValueChanged];
    [self.tblView addSubview:refresh];
    
    //Tap Gesture on UItableview
    UITapGestureRecognizer *tapper = [[UITapGestureRecognizer alloc] initWithTarget:self action:@selector(tableTapped)];
    [tapper setDelegate:self];
    [self.tblView addGestureRecognizer:tapper];
    [tapper release];
    
    
//    if (IS_IPHONE_5) {
//        
//        self.tblView.frame=CGRectMake(0,44, 320, 400);
//        
//    }else
//    {
//        self.tblView.frame=CGRectMake(0, 44, 320,461 );
//    }
    
    self.context = [[AppDelegate setDelegate] managedObjectContext];
}

-(void)viewWillAppear:(BOOL)animated
{
    
    if([AppDelegate setDelegate].newAddedPhotoAppdel !=nil)
    {
        if(![arrUpdatedId containsObject:[AppDelegate setDelegate].newAddedPhotoAppdel])
            [arrUpdatedId addObject:[AppDelegate setDelegate].newAddedPhotoAppdel.photoid];
        [AppDelegate setDelegate].newAddedPhotoAppdel = nil;
    }
    if (![AppDelegate setDelegate].isFromNotification)
    {
    
        if (!self.isSearching) {
            
            //[self performSelector:@selector(updateArayLatestTop) withObject:self afterDelay:0.1];
            [self updateArayLatestTop];
            
//            if (arrayNewAdd.count > 0) {
//                
//                [self.tblView scrollToRowAtIndexPath:[NSIndexPath indexPathForItem:0 inSection:0] atScrollPosition:UITableViewScrollPositionTop animated:NO];
//            }
            
            //call webservice
            if ([[AppDelegate setDelegate] isNetWorkAvailable]) {
                
                self.isNetworkRechable=YES;
                [self performSelector:@selector(getAllAdvertisements) withObject:nil afterDelay:0.01];
                
            }else{
                
                self.isNetworkRechable=NO;
            }
        }
        
        [self performSelector:@selector(noInternetConnection) withObject:nil afterDelay:1];
        
    }else{
        
        [self goToNextPageForNotification];
    }
}


-(void)goToNextPageForNotification
{
    [AppDelegate setDelegate].isFromNotification=NO;
    
    NSManagedObjectContext *contextNew = [[AppDelegate setDelegate] managedObjectContext];
    
    NSArray *array=[[[NSArray alloc]init]autorelease];
    // Construct a fetch request
    NSFetchRequest *fetchRequest = [[NSFetchRequest alloc] init];
    NSEntityDescription *entity = [NSEntityDescription entityForName:@"Photo"
                                              inManagedObjectContext:contextNew];
    [fetchRequest setEntity:entity];
    
//    NSPredicate *predicate = [NSPredicate predicateWithFormat:@"photoid == %@",[AppDelegate setDelegate].strNotificationPhotoId];
//    [fetchRequest setPredicate:predicate];
    
    NSError *error = nil;
    array = [contextNew executeFetchRequest:fetchRequest error:&error];
    
    Photo *objPhoto=nil;
    for (Photo *objPhoto in array) {
        
       // NSLog(@"objPhoto.photoid : %@ photoId : %@",objPhoto.photoid,[AppDelegate setDelegate].strNotificationPhotoId);
        
        if ([objPhoto.photoid isEqualToString:[AppDelegate setDelegate].strNotificationPhotoId]) {
            
            objPhoto= (Photo *)objPhoto;
            break;
        }
    }

    
    //Photo *objPhoto = [array objectAtIndex:0];
    
    AdvertisementDetailViewController *obj=[[AdvertisementDetailViewController alloc]initWithNibName:@"AdvertisementDetailViewController" bundle:[NSBundle mainBundle]];
    obj.strPhotoId=objPhoto.photoid;
    
    NSSet *tagset = objPhoto.tags;
    NSString *strTags=@"";
    
    int i=0;
    for (Tag *tag in tagset) {
        
        NSString *str1=@"#";
        
        if (![tag.name isEqualToString:@""]) {
            
            NSString *Tags=[str1 stringByAppendingString:tag.name];
            
            if (i < tagset.count - 1) {
                
                Tags=[Tags stringByAppendingString:@","];
            }
            
            strTags = [strTags stringByAppendingString:Tags];
        }
        
        i = i+1;
    }
    
    [AppDelegate setDelegate].strBrands=strTags;
    
    if ([objPhoto.createduserid isEqualToString:[AppDelegate setDelegate].strUserId]) {
        
        obj.isEditPhoto=YES;
        
    }else{
        
        obj.isEditPhoto=NO;
    }
    
    obj.strIfUserLike=objPhoto.isuserhasliked;
    [self.navigationController pushViewController:obj animated:YES];
    //[obj release];
}

-(void)registerUserDeviceToken
{
//    if (![[NSUserDefaults standardUserDefaults] boolForKey:@"isRegisteredDeviceToken"])
//    {
        NSString *strDeviceToken =[NSString stringWithFormat:@"%@",[[NSUserDefaults standardUserDefaults] objectForKey:@"DEVICE TOKEN"]];
        
        NSMutableDictionary *dictHeader=[[NSMutableDictionary alloc]init];
        [dictHeader setValue:@"AddAccessToken" forKey:@"name"];
        
        NSMutableDictionary *dictBody=[[NSMutableDictionary alloc]init];
        [dictBody setValue:[AppDelegate setDelegate].strUserId forKey:@"user_id"];
        [dictBody setValue:strDeviceToken forKey:@"token"];
        [dictHeader setObject:dictBody forKey:@"body"];
        [dictBody release];
        
        NSMutableURLRequest *request = [NSMutableURLRequest requestWithURL:[NSURL URLWithString:WEBSERVICE] cachePolicy:NSURLRequestReloadIgnoringCacheData timeoutInterval:60.0];
        [request setHTTPMethod:@"POST"];
        
        NSString *jsonString = [dictHeader JSONRepresentation];
        NSString *postString=[NSString stringWithFormat:@"json=%@",jsonString];
        //NSLog (@"postString : %@",postString);
        
        [dictHeader release];
        [request setHTTPBody:[postString dataUsingEncoding:NSUTF8StringEncoding]];
        
        [URLConnection asyncConnectionWithRequest:request completionBlock:^(NSData *data, NSURLResponse *response) {
            
            NSString *responseString = [[NSString alloc] initWithData:data encoding:NSUTF8StringEncoding];
            NSDictionary *temp=[responseString JSONValue];
            [responseString release];
            
            NSLog(@"AddAccessToken : %@",temp);
//            
//            if ([[temp objectForKey:@"status"] isEqualToString:@"ADD_ACCESS_TOKEN_1"]) {
//                
//                [[NSUserDefaults standardUserDefaults] setBool:YES forKey:@"isRegisteredDeviceToken"];
//                
//            }else{
//                
//                [[NSUserDefaults standardUserDefaults] setBool:NO forKey:@"isRegisteredDeviceToken"];
//            }
            
        } errorBlock:^(NSError *error) {
            
            //[[NSUserDefaults standardUserDefaults] setBool:NO forKey:@"isRegisteredDeviceToken"];
            NSLog(@"Error - add device token");
        }];
//    }
}

-(void)noInternetConnection
{
    if (self.isNetworkRechable) {
        
        [UIView animateWithDuration:0.60 animations:^{
            
            [viewNoInternetConnection setFrame:CGRectMake(0,18,320,25)];
            
        }completion:^(BOOL isFinished){
            
            viewNoInternetConnection.hidden=YES;
            
        }];
        
    }else{
        
        viewNoInternetConnection.hidden=NO;
        
        [UIView animateWithDuration:0.60 animations:^{
            
            [viewNoInternetConnection setFrame:CGRectMake(0,44,320,25)];
        }];
    }
}

-(void)tableTapped
{
    if ([searchBar isFirstResponder]) {
        
        [searchBar resignFirstResponder];
        //[self.expandedPaths removeAllObjects];
        [self.tblView reloadData];
    }
}

-(void)refreshView:(UIRefreshControl *)refresh
{
    NSString *title=@"Refreshing data...";
    NSMutableAttributedString *titleAttString =  [[NSMutableAttributedString alloc] initWithString:title];
    //NSMutableAttributedString *subTitleAttString =  [[NSMutableAttributedString alloc] initWithString:subText];
    
    [titleAttString addAttribute:NSFontAttributeName value:[UIFont fontWithName:@"American Typewriter" size:13.0f] range:NSMakeRange(0, [title length])];
    [titleAttString addAttribute:NSForegroundColorAttributeName value:[UIColor blackColor] range:NSMakeRange(0, [title length])];
    refresh.attributedTitle = titleAttString;
    
    // custom refresh logic would be placed here...
    NSDateFormatter *formatter = [[NSDateFormatter alloc] init];
    [formatter setDateFormat:@"MMM d, h:mm a"];
    NSString *lastUpdated = [NSString stringWithFormat:@"Last updated on %@",[formatter stringFromDate:[NSDate date]]];
    
    NSMutableAttributedString *titleAttString1 =  [[NSMutableAttributedString alloc] initWithString:lastUpdated];
    [titleAttString1 addAttribute:NSFontAttributeName value:[UIFont fontWithName:@"American Typewriter" size:11.0f] range:NSMakeRange(0, [lastUpdated length])];
    [titleAttString1 addAttribute:NSForegroundColorAttributeName value:[UIColor blackColor] range:NSMakeRange(0, [lastUpdated length])];
    refresh.attributedTitle = titleAttString1;
    [refresh endRefreshing];
    
    NSLog(@"Refresh");
    
    NSLog(@"self.loading : %d",self.loading);
    [arrUpdatedId removeAllObjects];
    self.arrOfflineByPhotoid = [self getOfflinePhotoDetailByPhotoId];
    if ([[AppDelegate setDelegate] isNetWorkAvailable]) {
        
        self.isNetworkRechable=YES;
        
//        if (self.isSearching) {
//            
//            [self.arrayCopyListAdv removeAllObjects];
//            
//        }
//        
        [self.arrayCopyListAdv removeAllObjects];
       self.starRecord = 0;
        [self performSelector:@selector(getAllAdvertisements) withObject:nil afterDelay:0.01];
        
    }else{
        
        self.isNetworkRechable=NO;
        [self performSelector:@selector(noInternetConnection) withObject:nil afterDelay:1];

       
    }
    
}

#pragma mark - WebService Methods

- (void)loadData
{
    self.fetchBatch ++;
    self.starRecord=self.starRecord + RECORD_TO_LOAD ;
    [self performSelector:@selector(getAllAdvertisements) withObject:nil afterDelay:0.1];
}

-(void)getAllAdvertisements
{
    NSMutableDictionary * headerDic = [NSMutableDictionary dictionary];
    [headerDic setObject:@"GetAllPhotos" forKey:@"name"];
    NSMutableDictionary * bodyDic = [NSMutableDictionary dictionary];
    
    NSString *strStart=[NSString stringWithFormat:@"%d",self.starRecord];
    NSString *strTotal=[NSString stringWithFormat:@"%d",RECORD_TO_LOAD];
    
    // Release the dateFormatter
    [bodyDic setValue:strStart forKey:@"start"];
    [bodyDic setValue:strTotal forKey:@"total_record"];
    [bodyDic setValue:[AppDelegate setDelegate].strUserId forKey:@"user_id"];
    [bodyDic setValue:self.strSearchtext forKey:@"searchText"];
    [headerDic setObject:bodyDic forKey:@"body"];
    
    NSURL * url = [NSURL URLWithString:WEBSERVICE];
    
    //Request
    NSMutableURLRequest *request = [[NSMutableURLRequest alloc] initWithURL:url];
    NSString *postString=[NSString stringWithFormat:@"json=%@",[headerDic JSONRepresentation]];
    NSLog(@"postString : %@",postString);
    //prepare http body
    [request setHTTPMethod:@"POST"];
    [request setHTTPBody:[postString dataUsingEncoding:NSUTF8StringEncoding]];
    
    [URLConnection asyncConnectionWithRequest:request completionBlock:^(NSData *data, NSURLResponse *response) {
        
        NSString *responseString = [[NSString alloc] initWithData:data encoding:NSUTF8StringEncoding];
        NSDictionary *temp=[responseString JSONValue];
        
       // NSLog(@"GetAllPhotos : %@",temp);
        [responseString release];
        
        if ([[temp objectForKey:@"status"] isEqualToString:@"GET_ALL_PHOTOS_2"]) {
            
            
            if (self.isSearching) {
                
                [self.HUD hide:YES];
                
                noMoreResultsAvail = YES;
                [self.tblView reloadData];
                
            }else{
                
                noMoreResultsAvail = YES;
                [self.tblView reloadData];
            }
            
        }else{
            
            if (self.isSearching) {
                
                [self.HUD hide:YES];
            }

            if(self.starRecord+RECORD_TO_LOAD <= self.arrayListAdv.count)
            {
                //[self performSelector:@selector(loadData) withObject:array afterDelay:0.1];
                [self loadData];
            }

            NSArray *array=[[temp objectForKey:@"data"] objectForKey:@"data"];
            
            [self performSelector:@selector(fillTableData:) withObject:array afterDelay:0.1];
           
            
        }       
        
    } errorBlock:^(NSError *error) {

        UIAlertView *alert=[[UIAlertView alloc] initWithTitle:AppName message:@"The request timed out. Please try again later." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles: nil];
        [alert show];
        [alert release];
        
        NSLog(@"Error!");
    }];
}

-(void)fillTableData:(NSArray *)array
{
    if (self.isSearching) {
        
        [self.arrayCopyListAdv addObjectsFromArray:array];
         NSLog(@"self.arrayCopyListAdv : %d",self.arrayCopyListAdv.count);
        [self.tblView reloadData];
        
    }else{

//        [self.arrayListAdv addObjectsFromArray:array];
//        NSLog(@"self.arrayListAdv : %d",self.arrayListAdv.count);
        
        [self savePhotoDetails:array];
    }
    self.loading=NO;
}

#pragma mark - Core data operations

-(void)savePhotoDetails:(NSArray *)array
{
    // Construct a fetch request
    NSFetchRequest *fetchRequest = [[NSFetchRequest alloc] init];
    NSEntityDescription *entity = [NSEntityDescription entityForName:@"Photo"                                           inManagedObjectContext:context];
    
    NSSortDescriptor *dateSort = [[NSSortDescriptor alloc] initWithKey:@"createdon" ascending:NO];
    [fetchRequest setSortDescriptors:[NSArray arrayWithObject:dateSort]];
    [dateSort release];
    
    [fetchRequest setEntity:entity];
    NSError *error = nil;
    NSMutableArray *storedArray =(NSMutableArray *) [self.context executeFetchRequest:fetchRequest error:&error];
    
    for (NSMutableDictionary *dictPhoto in array) {
//        if(![arrUpdatedId containsObject:[dictPhoto objectForKey:@"photoId"]])
//            [arrUpdatedId addObject:[dictPhoto objectForKey:@"photoId"]];

       // NSLog(@"created_on from dict %@",[dictPhoto objectForKey:@"created_on"]);
        if (storedArray.count != 0) {
            
            BOOL isUpdate = NO;
            Photo *updatePhoto=nil;
            
            for (Photo *objPhoto in storedArray) {

               // NSLog(@"objPhoto.photoid : %@ photoId : %@",objPhoto.photoid,[dictPhoto objectForKey:@"photoId"]);
                
                if ([objPhoto.photoid isEqualToString:[dictPhoto objectForKey:@"photoId"]]) {
                    
                    isUpdate=YES;
                    updatePhoto= (Photo *)objPhoto;
                    [arrUpdatedId addObject:objPhoto.photoid];
                    break;
                }else
                {
                    isUpdate=NO;
                }
            }
            
            if (isUpdate) {
                
                NSLog(@"Update Photo %@",[NSString stringWithFormat:@"%@",[dictPhoto objectForKey:@"photoId"]]);
                
                //[updatePhoto setValue:<#(id)#> forKey:<#(NSString *)#>]
                
                updatePhoto.photoid =[NSString stringWithFormat:@"%@",[dictPhoto objectForKey:@"photoId"]];
                updatePhoto.photourl =[NSString stringWithFormat:@"%@", [dictPhoto objectForKey:@"photoUrl"]];
                updatePhoto.createduserid =[NSString stringWithFormat:@"%@", [dictPhoto objectForKey:@"createdUsreid"]];
                updatePhoto.createdusername =[NSString stringWithFormat:@"%@", [dictPhoto objectForKey:@"createdUsername"]];
                updatePhoto.profileimage =[NSString stringWithFormat:@"%@", [dictPhoto objectForKey:@"profileImage"]];
                updatePhoto.numberoflikes =[NSString stringWithFormat:@"%@", [dictPhoto objectForKey:@"numberOfLikes"]];
                updatePhoto.createddate =[NSString stringWithFormat:@"%@", [dictPhoto objectForKey:@"createdDate"]];
                updatePhoto.createdon =[NSString stringWithFormat:@"%@", [dictPhoto objectForKey:@"created_on"]];
                updatePhoto.numberofcomments =[NSString stringWithFormat:@"%@", [dictPhoto objectForKey:@"numberOfComments"]];
                updatePhoto.isuserhasliked =[NSString stringWithFormat:@"%@", [dictPhoto objectForKey:@"isUserHasLiked"]];
                //NSLog(@"updatePhoto.createdon %@",updatePhoto.createdon);
                NSMutableArray *arrayTags=[dictPhoto objectForKey:@"tags"];
                
                [updatePhoto removeTags:updatePhoto.tags];
                
                for (NSMutableDictionary *dicTag in arrayTags) {
                    
                    // Insert the Tag entity
                    Tag *tag = [NSEntityDescription insertNewObjectForEntityForName:@"Tag" inManagedObjectContext:context];
                    
                    // Set the Tag attributes
                    tag.tagid = [dicTag objectForKey:@"id"];
                    tag.name = [dicTag objectForKey:@"name"];
                    
                    // Set relationships
                    [updatePhoto addTagsObject:tag];
                    [tag setPhoto:updatePhoto];
                }
                [[AppDelegate setDelegate] saveContext];
                
                
//                for (int i=0; i<self.arrayListAdv.count; i++) {
//                    
//                    Photo *objPhoto=[self.arrayListAdv objectAtIndex:i];
//                    
//                    if ([objPhoto.photoid isEqualToString:updatePhoto.photoid]) {
//                        
//                        [self.arrayListAdv removeObjectAtIndex:i];
//                        [self.arrayListAdv insertObject:updatePhoto atIndex:i];
//                        
//                        break;
//                    }
//                }
                
            }
            else
            {
                
                NSLog(@"Add photoid %@",[NSString stringWithFormat:@"%@",[dictPhoto objectForKey:@"photoId"]]);
                
                
                Photo *photoDetail  = [NSEntityDescription insertNewObjectForEntityForName:@"Photo" inManagedObjectContext:self.context];
                photoDetail.photoid =[NSString stringWithFormat:@"%@",[dictPhoto objectForKey:@"photoId"]];
                photoDetail.photourl = [NSString stringWithFormat:@"%@",[dictPhoto objectForKey:@"photoUrl"]];
                photoDetail.createduserid = [NSString stringWithFormat:@"%@",[dictPhoto objectForKey:@"createdUsreid"]];
                photoDetail.createdusername = [NSString stringWithFormat:@"%@",[dictPhoto objectForKey:@"createdUsername"]];
                photoDetail.profileimage = [NSString stringWithFormat:@"%@",[dictPhoto objectForKey:@"profileImage"]];
                photoDetail.numberoflikes = [NSString stringWithFormat:@"%@",[dictPhoto objectForKey:@"numberOfLikes"]];
                photoDetail.createddate = [NSString stringWithFormat:@"%@",[dictPhoto objectForKey:@"createdDate"]];
                photoDetail.createdon =[NSString stringWithFormat:@"%@", [dictPhoto objectForKey:@"created_on"]];
                photoDetail.numberofcomments = [NSString stringWithFormat:@"%@",[dictPhoto objectForKey:@"numberOfComments"]];
                photoDetail.isuserhasliked =[NSString stringWithFormat:@"%@", [dictPhoto objectForKey:@"isUserHasLiked"]];
                [arrUpdatedId addObject:photoDetail.photoid];

                
                NSMutableArray *arrayTags=[dictPhoto objectForKey:@"tags"];
                
                for (NSMutableDictionary *dicTag in arrayTags) {
                    
                    // Insert the Tag entity
                    Tag *tag = [NSEntityDescription insertNewObjectForEntityForName:@"Tag" inManagedObjectContext:context];
                    
                    // Set the Tag attributes
                    tag.tagid = [dicTag objectForKey:@"id"];
                    tag.name = [dicTag objectForKey:@"name"];
                    
                    // Set relationships
                    [photoDetail addTagsObject:tag];
                    [tag setPhoto:photoDetail];
                }
                
                //        // Save everything
                //        NSError *error = nil;
                //        if ([context save:&error]) {
                //            NSLog(@"The save was successful!");
                //        } else {
                //            NSLog(@"The save wasn't successful: %@", [error userInfo]);
                //        }
                
                [[AppDelegate setDelegate] saveContext];
                
                //[self.arrayListAdv addObject:photoDetail];
                //[self.arrayListAdv insertObject:photoDetail atIndex:0];
            }
            
        }
        else{
            
            NSLog(@"Add id %@",[dictPhoto objectForKey:@"photoId"]);
            
            Photo *photoDetail  = [NSEntityDescription insertNewObjectForEntityForName:@"Photo" inManagedObjectContext:self.context];
            photoDetail.photoid =[NSString stringWithFormat:@"%@",[dictPhoto objectForKey:@"photoId"]];
            photoDetail.photourl = [NSString stringWithFormat:@"%@",[dictPhoto objectForKey:@"photoUrl"]];
            photoDetail.createduserid = [NSString stringWithFormat:@"%@",[dictPhoto objectForKey:@"createdUsreid"]];
            photoDetail.createdusername = [NSString stringWithFormat:@"%@",[dictPhoto objectForKey:@"createdUsername"]];
            photoDetail.profileimage = [NSString stringWithFormat:@"%@",[dictPhoto objectForKey:@"profileImage"]];
            photoDetail.numberoflikes = [NSString stringWithFormat:@"%@",[dictPhoto objectForKey:@"numberOfLikes"]];
            photoDetail.createddate = [NSString stringWithFormat:@"%@",[dictPhoto objectForKey:@"createdDate"]];
            photoDetail.createdon =[NSString stringWithFormat:@"%@", [dictPhoto objectForKey:@"created_on"]];
            photoDetail.numberofcomments = [NSString stringWithFormat:@"%@",[dictPhoto objectForKey:@"numberOfComments"]];
            photoDetail.isuserhasliked = [NSString stringWithFormat:@"%@",[dictPhoto objectForKey:@"isUserHasLiked"]];
            [arrUpdatedId addObject:photoDetail.photoid];

            
            NSMutableArray *arrayTags=[dictPhoto objectForKey:@"tags"];
            
            for (NSMutableDictionary *dicTag in arrayTags) {
                
                // Insert the Tag entity
                Tag *tag = [NSEntityDescription insertNewObjectForEntityForName:@"Tag" inManagedObjectContext:context];
                
                // Set the Tag attributes
                tag.tagid = [dicTag objectForKey:@"id"];
                tag.name = [dicTag objectForKey:@"name"];
                
                // Set relationships
                [photoDetail addTagsObject:tag];
                [tag setPhoto:photoDetail];
            }
            
            //        // Save everything
            //        NSError *error = nil;
            //        if ([context save:&error]) {
            //            NSLog(@"The save was successful!");
            //        } else {
            //            NSLog(@"The save wasn't successful: %@", [error userInfo]);
            //        }
            
            [[AppDelegate setDelegate] saveContext];
            
            //[self.arrayListAdv addObject:photoDetail];
            //[self.arrayListAdv insertObject:photoDetail atIndex:0];
            
        }
    }
    
//    NSArray *arrayLocalDta= [self getOfflinePhotoDetail];
//
//    for (int i = self.intLocalRecords; i < arrayLocalDta.count; i++) {
//    
//        [self.arrayListAdv addObject:[arrayLocalDta objectAtIndex:i]];
//    }
    
    //[AppDelegate setDelegate].intTotalRecord = self.arrayListAdv.count;
    for (int i = 0 ; i <[self.self.arrOfflineByPhotoid count]; i++)
    {
        Photo *obj = [self.self.arrOfflineByPhotoid objectAtIndex:i];
        if(arrUpdatedId.count)
        {
            //Photo *objLast = [arrUpdatedId objectAtIndex:arrUpdatedId.count-1];
            NSString *strTemp = [arrUpdatedId objectAtIndex:arrUpdatedId.count-1];
            //NSLog(@"photoid from local %@ , from update %@",obj.photoid,strTemp);
            if(![arrUpdatedId containsObject:obj.photoid])
            {
                if([obj.photoid  intValue] >= [strTemp intValue])
                {
                    if(![arrDeleteId containsObject:obj.photoid])
                        
                        [arrDeleteId addObject: obj.photoid];
                }
                else
                    break;
                
            }
        }
    }
    
    NSLog(@"%@, arrDeleteId count %d",arrDeleteId,arrDeleteId.count);
    if(arrDeleteId.count)
       [self deletePhotoDetail];

    
    [self performSelector:@selector(updateArayLatestTop) withObject:self afterDelay:0.1];
    //[self updateArayLatestTop];
}

-(void)updateArayLatestTop
{
    NSMutableArray *arrayList=[NSMutableArray arrayWithArray:[self getOfflinePhotoDetail]];
    self.arrayListAdv = arrayList;
    [self.tblView reloadData];
    
}

- (void)deletePhotoDetail
{
   
    if(arrDeleteId.count)
    {
        for (int i = 0; i < arrDeleteId.count; i++)
        {
            
            Photo *obj = nil;// = [arrDeleteId objectAtIndex:i];

            NSString *str =[arrDeleteId objectAtIndex:i];
            
            NSManagedObjectContext *contextTemp = [[AppDelegate setDelegate] managedObjectContext];
            NSEntityDescription *productEntity=[NSEntityDescription entityForName:@"Photo" inManagedObjectContext:contextTemp];
            NSFetchRequest *fetch=[[NSFetchRequest alloc] init];
            [fetch setEntity:productEntity];
            NSPredicate *p=[NSPredicate predicateWithFormat:@"photoid == %@ ", str];
            [fetch setPredicate:p];
            //... add sorts if you want them
            NSError *fetchError;
            NSArray *fetchedProducts=[contextTemp executeFetchRequest:fetch error:&fetchError];
            if(fetchedProducts.count)
            {
                NSLog(@"Deleted record %@",str);
                obj = [fetchedProducts objectAtIndex:0];
                [contextTemp deleteObject:[fetchedProducts objectAtIndex:0]];
            }
            [[AppDelegate setDelegate]saveContext];

            if(obj)
            {
            if([self.arrayListAdv containsObject:obj])
                [self.arrayListAdv removeObject:obj];
            }
        }
    }
    //[arrDeleteId removeObject:obj];
}

-(NSArray *)getOfflinePhotoDetailByPhotoId
{
    
    NSManagedObjectContext *contextTemp = [[AppDelegate setDelegate] managedObjectContext];

    NSArray *array=[[[NSArray alloc]init]autorelease];
    // Construct a fetch request
    NSFetchRequest *fetchRequest = [[NSFetchRequest alloc] init];
    NSEntityDescription *entity = [NSEntityDescription entityForName:@"Photo"
                                              inManagedObjectContext:contextTemp];
    NSSortDescriptor *dateSort = [[NSSortDescriptor alloc] initWithKey:@"photoid" ascending:NO];
    [fetchRequest setSortDescriptors:[NSArray arrayWithObject:dateSort]];
    [dateSort release];
    [fetchRequest setEntity:entity];
    
    NSError *error = nil;
    array = [contextTemp executeFetchRequest:fetchRequest error:&error];
    return array;
}


-(NSArray *)getOfflinePhotoDetail
{
    
    NSArray *array=[[[NSArray alloc]init]autorelease];
    // Construct a fetch request
    NSFetchRequest *fetchRequest = [[NSFetchRequest alloc] init];
    NSEntityDescription *entity = [NSEntityDescription entityForName:@"Photo"
                                              inManagedObjectContext:self.context];
    NSSortDescriptor *dateSort = [[NSSortDescriptor alloc] initWithKey:@"createdon" ascending:NO];
    [fetchRequest setSortDescriptors:[NSArray arrayWithObject:dateSort]];
    [dateSort release];
    [fetchRequest setEntity:entity];
    
    NSError *error = nil;
    array = [context executeFetchRequest:fetchRequest error:&error];
    
    return array;
}

#pragma mark - UITableView delegate methods

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    if (self.isSearching){
        
        if (self.arrayCopyListAdv.count < 3) {
            
            return [self.arrayCopyListAdv count];
            
        }else{
            
            return [self.arrayCopyListAdv count] + 1;
        }
    }
    else {
        NSLog(@"%d",self.arrayListAdv.count);
        if (self.arrayListAdv.count < 3) {
            
            return [self.arrayListAdv count];
            
        }else{
            
            return [self.arrayListAdv count] + 1;
        }
    }
}

- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath
{
    
    NSMutableDictionary *tempDict = nil;
    
    if (self.isSearching){
        
        if (indexPath.row >= self.arrayCopyListAdv.count)
        {
            return 25;
        }
//Commented for stop dynamic height count for tags
/*
        
        tempDict=[self.arrayCopyListAdv objectAtIndex:indexPath.row];
        NSMutableArray *arraTag =[tempDict objectForKey:@"tags"];
        
        NSString *strTags=@"";
        if (arraTag.count != 0) {
            
            for (int i=0; i<arraTag.count; i++) {
                
                NSMutableDictionary *dict=[arraTag objectAtIndex:i];
                NSString *str1=@"#";
                
                if (![[dict objectForKey:@"name"] isEqualToString:@""]) {
                    
                    NSString *Tags=[str1 stringByAppendingString:[dict objectForKey:@"name"]];
                    
                    if (i < arraTag.count - 1) {
                        
                        Tags=[Tags stringByAppendingString:@","];
                    }
                    
                    strTags = [strTags stringByAppendingString:Tags];
                }
            }
        }
        
        strTags = [strTags stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceCharacterSet]];
        CGSize constraint = CGSizeMake(TAG_CONTENT_WIDTH , 20000.0f);
        CGSize size = [strTags sizeWithFont:[UIFont fontWithName:@"American Typewriter" size:13] constrainedToSize:constraint lineBreakMode:UILineBreakModeWordWrap];
        CGFloat height = MAX(size.height + 5, 25.0f);
    */
        CGFloat height =  25.0f;
        NSString *strImage=[NSString stringWithFormat:@"%@",[tempDict objectForKey:@"photoUrl"]];
        strImage=[ADVERTISEPHOTO_URL stringByAppendingString:strImage];
        
        CGFloat catchHeight = [[self.heightsCache objectForKey:strImage] floatValue];
        
        if (catchHeight) {
            
            return 75 + height + catchHeight;
            
        }else{
            
            return 350 + height;
        }
        
    }else{
        
        if (indexPath.row >= self.arrayListAdv.count)
        {
            return 25;
        }
        
        Photo *photoObj =[self.arrayListAdv objectAtIndex:indexPath.row];
        //Commented for stop dynamic height count for tags
        /*
        NSSet *tagset = photoObj.tags;
        
        NSString *strTags=@"";
        int i=0;
        for (Tag *tag in tagset) {
            
            NSString *str1=@"#";
            
            if (![tag.name isEqualToString:@""]) {
                
                NSString *Tags=[str1 stringByAppendingString:tag.name];
                
                if (i < tagset.count - 1) {
                    
                    Tags=[Tags stringByAppendingString:@","];
                }
                
                strTags = [strTags stringByAppendingString:Tags];
            }
            i = i+1;
        }
        
        strTags = [strTags stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceCharacterSet]];
        CGSize constraint = CGSizeMake(TAG_CONTENT_WIDTH , 20000.0f);
        CGSize size = [strTags sizeWithFont:[UIFont fontWithName:@"American Typewriter" size:13] constrainedToSize:constraint lineBreakMode:NSLineBreakByWordWrapping];
        CGFloat height = MAX(size.height + 8, 25.0f);
 */
         CGFloat height =  25.0f;
        
        NSString *strImage=[NSString stringWithFormat:@"%@",photoObj.photourl];
        strImage=[ADVERTISEPHOTO_URL stringByAppendingString:strImage];
        
        CGFloat catchHeight = [[self.heightsCache objectForKey:strImage] floatValue];
        
        if (catchHeight) {
            //NSLog(@"in 1 %f",75 + height + catchHeight);
            
            return 75 + height + catchHeight;
            
        }else{
            //NSLog(@"in 2 %f",350 + height);

            return 350 + height;
        }
    }
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    static NSString *CellIdentifier =@"cellIdentifire";
    static NSString *CellIdentifier1 =@"cellIdentifire1";
    
    CellHomeFeed *cell = [tableView dequeueReusableCellWithIdentifier:CellIdentifier];
    
    if (cell == nil)
    {
        NSArray *Objects = [[NSBundle mainBundle] loadNibNamed:@"CellHomeFeed" owner:nil options:nil];
        
        for(id currentObject in Objects)
        {
            if([currentObject isKindOfClass:[CellHomeFeed class]])
            {
                cell = (CellHomeFeed *)currentObject;
                break;
            }
        }
        
        cell.selectionStyle=UITableViewCellSelectionStyleNone;
        cell.imgViewBG.image=[UIImage imageNamed:@"profile_background_blue.png"];
        //cell.backgroundColor=[UIColor redColor];
    }
    
    NSDictionary *dictionary = nil;
    
    if (self.isSearching)
    {
        // If scrolled beyond two thirds of the table, load next batch of data.
        if (indexPath.row >= self.arrayCopyListAdv.count - 2) {
            if (!self.loading) {
                self.loading = YES;
                // loadRequest is the method that loads the next batch of data.
                //[self loadData];
                [self performSelector:@selector(loadData) withObject:nil afterDelay:1];
            }
        }
        
        // Only starts populating the table if data source is not empty.
        if (self.arrayCopyListAdv.count != 0) {
            // If the currently requested cell is not the last one, display normal data.
            // Else dispay @"Loading More..." or @"(No More Results Available)"
            if (indexPath.row < self.arrayCopyListAdv.count) {
                
                dictionary = [self.arrayCopyListAdv objectAtIndex:indexPath.row];
                
            } else {
                // The currently requested cell is the last cell.
                if (!noMoreResultsAvail) {
                    // If there are results available, display @"Loading More..." in the last cell
                    UITableViewCell *cell = [[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault
                                                                   reuseIdentifier:CellIdentifier1];
                    
                    UIView *contentView=[[UIView alloc]initWithFrame:CGRectMake(0, 0, 320, 25)];
                    
                    [contentView setBackgroundColor:[UIColor clearColor]];
                    
                    UIActivityIndicatorView *indicator=[[UIActivityIndicatorView alloc]initWithActivityIndicatorStyle:UIActivityIndicatorViewStyleWhite];
                    indicator.frame=CGRectMake(152.5, 2, 15, 15);
                    [indicator startAnimating];
                    [contentView addSubview:indicator];
                    [indicator release];
                    
                    [cell.contentView addSubview:contentView];
                    
                    return cell;
                    
                } else {
                    // If there are no results available, display @"Loading More..." in the last cell
                    UITableViewCell *cell = [[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault
                                                                   reuseIdentifier:CellIdentifier1];
                    cell.textLabel.font = [UIFont fontWithName:@"American Typewriter" size:13.0f];
                    cell.textLabel.textColor=[UIColor blackColor];
                    cell.backgroundColor=[UIColor clearColor];
                    cell.textLabel.text = @"No More Results Available";
                    cell.textLabel.textAlignment = UITextAlignmentCenter;
                    return cell;
                }
            }
        } else {
            
            //[self.expandedPaths removeAllObjects];
            UITableViewCell *cell = [[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault
                                                           reuseIdentifier:CellIdentifier1];
            cell.textLabel.font = [UIFont fontWithName:@"American Typewriter" size:13.0f];
            cell.textLabel.textColor=[UIColor blackColor];
            cell.backgroundColor=[UIColor clearColor];
            
            if (isFirstLoad) {
                
                isFirstLoad=NO;
                cell.textLabel.text = @"";
            }else{
                
                cell.textLabel.text = @"No Results Available";
            }
            cell.textLabel.textAlignment = UITextAlignmentCenter;
            return cell;
        }
        NSString *strUserName=[NSString stringWithFormat:@"%@",[dictionary objectForKey:@"createdUsername"]];
        
        if (strUserName.length>10) {
            
            strUserName=[strUserName substringToIndex:10];
        }
        
        [cell.btnUserName setTitle:strUserName forState:UIControlStateNormal];
        
        NSMutableArray *arraTag=[dictionary objectForKey:@"tags"];
        NSString *strTags=@"";
        if (arraTag.count != 0) {
            
            for (int i=0; i<arraTag.count; i++) {
                
                NSMutableDictionary *dict=[arraTag objectAtIndex:i];
                NSString *str1=@"#";
                
                if (![[dict objectForKey:@"name"] isEqualToString:@""]) {
                    
                    NSString *Tags=[str1 stringByAppendingString:[dict objectForKey:@"name"]];
                    
                    if (i < arraTag.count - 1) {
                        
                        Tags=[Tags stringByAppendingString:@","];
                    }
                    
                    strTags = [strTags stringByAppendingString:Tags];
                }
            }
        }
        
        if (![strTags isEqualToString:@""]) {
            
            cell.lblTags.text=strTags;
           cell.lblTags.layer.shadowColor = [[UIColor blackColor] CGColor];
            cell.lblTags.layer.shadowOffset = CGSizeMake(0.0, 0.0);
            cell.lblTags.layer.shadowRadius = 3.0;
            cell.lblTags.layer.shadowOpacity = 0.8;
        }
        
        strTags = [strTags stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceCharacterSet]];
        CGSize constraint = CGSizeMake(TAG_CONTENT_WIDTH , 20000.0f);
        CGSize size = [strTags sizeWithFont:[UIFont fontWithName:@"American Typewriter" size:13] constrainedToSize:constraint lineBreakMode:NSLineBreakByWordWrapping];
        CGFloat height = MAX(size.height + 8, 25.0f);
        
        
        if (height > 25)
        {
            [cell.viewCellBotom setFrame:CGRectMake(cell.viewCellBotom.frame.origin.x , cell.frame.size.height - height, cell.viewCellBotom.frame.size.width,height + 15)];
        }
        
        //[cell.lblTags sizeToFit];
        
        cell.lblComments.text=[NSString stringWithFormat:@"%@",[dictionary objectForKey:@"numberOfComments"]];
        cell.lblLikes.text=[NSString stringWithFormat:@"%@",[dictionary objectForKey:@"numberOfLikes"]];
        
        NSDateFormatter *formater=[[NSDateFormatter alloc]init];
        
        [formater setDateFormat:@"yyyy-MM-dd HH:mm:ss"];
        [formater setTimeZone:[NSTimeZone systemTimeZone]];
        NSDate *date=[formater dateFromString:[dictionary objectForKey:@"createdDate"]];
        
        if (date) {
            
            [formater setDateFormat:@"MMM dd yyyy HH:mm a"];
            NSString *strDate=[formater stringFromDate:date];
            
            cell.lblTimeAgo.text=strDate;
            
        }else{
            
            cell.lblTimeAgo.text=[NSString stringWithFormat:@"%@",[dictionary objectForKey:@"createdDate"]];
        }
        
        if ([[dictionary objectForKey:@"isUserHasLiked"] isEqualToString:@"1"]) {
            
            cell.btnLike.enabled=NO;
        }else{
            
            cell.btnLike.enabled=YES;
            [cell.btnLike addTarget:self action:@selector(likedAdvertisementClicked:) forControlEvents:UIControlEventTouchUpInside];
        }
        
        [[AppDelegate setDelegate] adjustFontSizeToFit:cell.lblTags];
        [[AppDelegate setDelegate] adjustFontSizeToFit:cell.lblComments];
        [[AppDelegate setDelegate] adjustFontSizeToFit:cell.lblLikes];
        [[AppDelegate setDelegate] adjustFontSizeToFit:cell.lblTimeAgo];
        
        
        //[cell.btnProfileImage addTarget:self action:@selector(userProfileClicked:) forControlEvents:UIControlEventTouchUpInside];
        [cell.btnUserName addTarget:self action:@selector(userNameClicked:) forControlEvents:UIControlEventTouchUpInside];
        
        cell.imgAdvertiseMent.tag=indexPath.row;
        cell.btnLike.tag=indexPath.row;
        cell.imgViewProfile.tag=indexPath.row;
        cell.btnUserName.tag=indexPath.row;
        
        SDWebImageManager *manager = [SDWebImageManager sharedManager];
        
        if ([[dictionary objectForKey:@"createdUsreid"] isEqualToString:[AppDelegate setDelegate].strUserId]) {
            
            NSData* imageData = [[NSUserDefaults standardUserDefaults] objectForKey:@"USER PIC"];
            UIImage *image= [UIImage imageWithData:imageData]; //[UIImage imageNamed:@"test.png"];
            
            if (image) {
                
                [cell.imgViewProfile setImage:image];
                cell.imgViewProfile.contentMode=UIViewContentModeScaleAspectFill;
                cell.imgViewProfile.clipsToBounds=YES;
                
            }else{
                
                [cell.imgViewProfile setImage:[UIImage imageNamed:@"profileImage.png"]];
            }
        }else{
            
            if ([[dictionary objectForKey:@"profileImage"] isKindOfClass:[NSNull class]]) {
                
                cell.imgViewProfile.image=[UIImage imageNamed:@"profileImage.png"];
                
            }else{
                
                UIActivityIndicatorView *spinner = [[[UIActivityIndicatorView alloc] initWithActivityIndicatorStyle:UIActivityIndicatorViewStyleGray] autorelease];
                spinner.frame=CGRectMake(cell.imgViewProfile.frame.size.width/2-15,cell.imgViewProfile.frame.size.height/2-15, 30, 30);
                [cell.imgViewProfile addSubview:spinner];
                [spinner startAnimating];
                
                NSString *strUserImage=[NSString stringWithFormat:@"%@",[dictionary objectForKey:@"profileImage"]];
                strUserImage=[USERPHOTO_URL stringByAppendingString:strUserImage];
                //NSLog(@"strUserImage : %@",strUserImage);
                
                [manager downloadWithURL:[NSURL URLWithString:strUserImage] delegate:self options:0 success:^(UIImage *image)
                 {
                     for (UIView *subview in cell.imgViewProfile.subviews)
                     {
                         if([subview isKindOfClass:[UIActivityIndicatorView class]])
                         {
                             [subview removeFromSuperview];
                             break;
                         }
                     }
                     
                     cell.imgViewProfile.image = image;
                     cell.imgViewProfile.contentMode=UIViewContentModeScaleAspectFill;
                     cell.imgViewProfile.clipsToBounds=YES;
                     
                 } failure:^(NSError *error) {
                     
                     //NSLog(@"Error While getting image : %@",strImage);
                 }];
            }
        }
        
        UIActivityIndicatorView *spinner = [[[UIActivityIndicatorView alloc] initWithActivityIndicatorStyle:UIActivityIndicatorViewStyleGray] autorelease];
        spinner.frame=CGRectMake(cell.imgAdvertiseMent.frame.size.width/2-15,cell.imgAdvertiseMent.frame.size.height/2-15, 30, 30);
        [cell.imgAdvertiseMent addSubview:spinner];
        [spinner startAnimating];
        
        NSString *strImage=[NSString stringWithFormat:@"%@",[dictionary objectForKey:@"photoUrl"]];
        strImage=[ADVERTISEPHOTO_URL stringByAppendingString:strImage];
        [manager downloadWithURL:[NSURL URLWithString:strImage] delegate:self options:0 success:^(UIImage *image)
         {
             
             CGSize size = CGSizeAspectFit(image.size, cell.imgAdvertiseMent.frame.size);
             //NSLog(@"size : %@",NSStringFromCGSize(size));

             [cell.imgAdvertiseMent setFrame:CGRectMake(cell.imgAdvertiseMent.frame.origin.x + (cell.imgAdvertiseMent.frame.size.width-size.width)/2, cell.imgAdvertiseMent.frame.origin.y, size.width, size.height)];//MIN(size.height, 280) )];
             
             cell.imgAdvertiseMent.contentMode=UIViewContentModeScaleToFill;
             cell.imgAdvertiseMent.image = image;


             cell.lblTags.frame =CGRectMake(cell.imgAdvertiseMent.frame.origin.x,  (cell.imgAdvertiseMent.frame.origin.y+cell.imgAdvertiseMent.frame.size.height)-cell.lblTags.frame.size.height,  cell.imgAdvertiseMent.frame.size.width,  cell.lblTags.frame.size.height);

             cell.viewCellBotom.frame = CGRectMake( cell.viewCellBotom.frame.origin.x,  (cell.imgAdvertiseMent.frame.origin.y+cell.imgAdvertiseMent.frame.size.height)+8,  cell.viewCellBotom.frame.size.width,  cell.viewCellBotom.frame.size.height);
            // NSLog(@"Tag %@, bottomframe %@",cell.lblTags.text , cell.viewCellBotom);

             for (UIView *subview in cell.imgAdvertiseMent.subviews)
             {
                 if([subview isKindOfClass:[UIActivityIndicatorView class]])
                 {
                     [subview removeFromSuperview];
                     break;
                 }
             }
             
             if (![self.heightsCache objectForKey:strImage]) {
                 
                 [self.heightsCache setObject:[NSNumber numberWithFloat:size.height] forKey:strImage];
                 [self.tblView reloadData];

//                 [self.tblView beginUpdates];
//                 [self.tblView reloadRowsAtIndexPaths:@[indexPath] withRowAnimation:UITableViewRowAnimationNone];
//                 [self.tblView endUpdates];
             }
             
         } failure:^(NSError *error) {
             
             //NSLog(@"Error While getting image : %@",strImage);
         }];
        
               
                
        UITapGestureRecognizer *tapper = [[UITapGestureRecognizer alloc] initWithTarget:self action:@selector(advertisementTapped:)];
        [tapper setDelegate:self];
        [cell.imgAdvertiseMent addGestureRecognizer:tapper];
        [tapper release];
        
        UITapGestureRecognizer *tapper1 = [[UITapGestureRecognizer alloc] initWithTarget:self action:@selector(userProfileClicked:)];
        [tapper1 setDelegate:self];
        [cell.imgViewProfile addGestureRecognizer:tapper1];
        [tapper1 release];
                
        return cell;
        
    }else{
        
        // Offline Data
        
        Photo *photoObj=nil;
        
        // If scrolled beyond two thirds of the table, load next batch of data.
        if (indexPath.row >= self.arrayListAdv.count - 2) {
            if (!self.loading) {
                self.loading = YES;
                // loadRequest is the method that loads the next batch of data.
                
                [self performSelector:@selector(loadData) withObject:nil afterDelay:0.5];
            }
        }
        
        // Only starts populating the table if data source is not empty.
        if (self.arrayListAdv.count != 0) {
            // If the currently requested cell is not the last one, display normal data.
            // Else dispay @"Loading More..." or @"(No More Results Available)"
            if (indexPath.row < self.arrayListAdv.count) {
                
                photoObj = [self.arrayListAdv objectAtIndex:indexPath.row];
                
            } else {
                // The currently requested cell is the last cell.
                if (!noMoreResultsAvail) {
                    // If there are results available, display @"Loading More..." in the last cell
                    UITableViewCell *cell = [[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault
                                                                   reuseIdentifier:CellIdentifier1];
                    UIView *contentView=[[UIView alloc]initWithFrame:CGRectMake(0, 0, 320, 25)];
                    
                    [contentView setBackgroundColor:[UIColor clearColor]];
                    
                    UIActivityIndicatorView *indicator=[[UIActivityIndicatorView alloc]initWithActivityIndicatorStyle:UIActivityIndicatorViewStyleWhite];
                    indicator.frame=CGRectMake(152.5, 2, 15, 15);
                    [indicator startAnimating];
                    [contentView addSubview:indicator];
                    [indicator release];
                    
                    [cell.contentView addSubview:contentView];
                    
                    return cell;
                    
                } else {
                    // If there are no results available, display @"Loading More..." in the last cell
                    UITableViewCell *cell = [[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault
                                                                   reuseIdentifier:CellIdentifier1];
                    cell.textLabel.text = @"No More Results Available";
                    cell.textLabel.font = [UIFont fontWithName:@"American Typewriter" size:13.0f];
                    cell.textLabel.textColor=[UIColor blackColor];
                    cell.textLabel.textAlignment = UITextAlignmentCenter;
                    cell.backgroundColor = [UIColor clearColor];
                    cell.backgroundView = nil;
                    return cell;
                }
            }
            
        }else{
            
            UITableViewCell *cell = [[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault
                                                           reuseIdentifier:CellIdentifier1];
            cell.textLabel.font = [UIFont fontWithName:@"American Typewriter" size:13.0f];
            cell.textLabel.textColor=[UIColor blackColor];
            
            if (isFirstLoad) {
                
                isFirstLoad=NO;
                cell.textLabel.text = @"";
            }else{
                
                cell.textLabel.text = @"No Results Available";
            }
            //cell.textLabel.text = @"No Results Available";
            cell.textLabel.textAlignment = UITextAlignmentCenter;
            return cell;
        }
        
        
        NSString *strUserName=[NSString stringWithFormat:@"%@",photoObj.createdusername];
        NSLog(@"CELL photoid %@",photoObj.photoid);
        
        if (strUserName.length>10) {
            
            strUserName=[strUserName substringToIndex:10];
        }
        
        [cell.btnUserName setTitle:strUserName forState:UIControlStateNormal];
        
        NSSet *tagset = photoObj.tags;
        NSString *strTags=@"";
        
        int i=0;
        for (Tag *tag in tagset) {
            
            NSString *str1=@"#";
            
            if (![tag.name isEqualToString:@""]) {
                
                NSString *Tags=[str1 stringByAppendingString:tag.name];
                
                if (i < tagset.count - 1) {
                    
                    Tags=[Tags stringByAppendingString:@","];
                }
                
                strTags = [strTags stringByAppendingString:Tags];
            }
            
            i = i+1;
        }
        
        if (![strTags isEqualToString:@""]) {
            
            cell.lblTags.text=strTags;
            cell.lblTags.layer.shadowColor = [[UIColor blackColor] CGColor];
            cell.lblTags.layer.shadowOffset = CGSizeMake(0.0, 0.0);
            cell.lblTags.layer.shadowRadius = 3.0;
            cell.lblTags.layer.shadowOpacity = 0.8;
            
        }
        
        strTags = [strTags stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceCharacterSet]];
        CGSize constraint = CGSizeMake(TAG_CONTENT_WIDTH , 20000.0f);
        CGSize size = [strTags sizeWithFont:[UIFont fontWithName:@"American Typewriter" size:13] constrainedToSize:constraint lineBreakMode:NSLineBreakByWordWrapping];
        CGFloat height = MAX(size.height + 8, 25.0f);
        

        if (height > 25)
        {
            [cell.viewCellBotom setFrame:CGRectMake(cell.viewCellBotom.frame.origin.x , cell.frame.size.height - height, cell.viewCellBotom.frame.size.width,height + 15)];
        }
        cell.lblComments.text=[NSString stringWithFormat:@"%@",photoObj.numberofcomments];
        cell.lblLikes.text=[NSString stringWithFormat:@"%@",photoObj.numberoflikes];
        
        NSDateFormatter *formater=[[NSDateFormatter alloc]init];
        [formater setDateFormat:@"yyyy-MM-dd HH:mm:ss"];
        NSDate *date=[formater dateFromString:photoObj.createddate];
        if (date)
        {
            [formater setDateFormat:@"MMM dd yyyy hh:mm a"];
            NSString *strDate=[formater stringFromDate:date];
            cell.lblTimeAgo.text=strDate;
            
        }else{
            
            cell.lblTimeAgo.text=[NSString stringWithFormat:@"%@",photoObj.createddate];
        }
        
        if ([photoObj.isuserhasliked isEqualToString:@"1"]) {
            
            cell.btnLike.enabled=NO;
            
        }else{
            
            cell.btnLike.enabled=YES;
            [cell.btnLike addTarget:self action:@selector(likedAdvertisementClicked:) forControlEvents:UIControlEventTouchUpInside];
        }
        
        [[AppDelegate setDelegate] adjustFontSizeToFit:cell.lblTags];
        [[AppDelegate setDelegate] adjustFontSizeToFit:cell.lblComments];
        [[AppDelegate setDelegate] adjustFontSizeToFit:cell.lblLikes];
        [[AppDelegate setDelegate] adjustFontSizeToFit:cell.lblTimeAgo];
        
        //[cell.btnProfileImage addTarget:self action:@selector(userProfileClicked:) forControlEvents:UIControlEventTouchUpInside];
        [cell.btnUserName addTarget:self action:@selector(userNameClicked:) forControlEvents:UIControlEventTouchUpInside];
        
        cell.imgAdvertiseMent.tag=indexPath.row;
        cell.btnLike.tag=indexPath.row;
        cell.imgViewProfile.tag=indexPath.row;
        cell.btnUserName.tag=indexPath.row;
        
        SDWebImageManager *manager = [SDWebImageManager sharedManager];
        
        if ([photoObj.createduserid isEqualToString:[AppDelegate setDelegate].strUserId]) {
            
            NSData* imageData = [[NSUserDefaults standardUserDefaults] objectForKey:@"USER PIC"];
            UIImage *image= [UIImage imageWithData:imageData]; //[UIImage imageNamed:@"test.png"];
            
            cell.imgViewProfile.contentMode=UIViewContentModeScaleAspectFill;
            cell.imgViewProfile.clipsToBounds=YES;
            
            if (image) {
                
                [cell.imgViewProfile setImage:image];
            }else{
                
                [cell.imgViewProfile setImage:[UIImage imageNamed:@"profileImage.png"]];
            }
        }else{
            
            NSString *strUserImage=[NSString stringWithFormat:@"%@",photoObj.profileimage];
            
            
            if ([strUserImage isEqualToString:@"<null>"]) {
                
                cell.imgViewProfile.image=[UIImage imageNamed:@"profileImage.png"];
                
            }else{
                
                strUserImage=[USERPHOTO_URL stringByAppendingString:strUserImage];
                //NSLog(@"strUserImage : %@",strUserImage);
                
                
                UIActivityIndicatorView *spinner = [[[UIActivityIndicatorView alloc] initWithActivityIndicatorStyle:UIActivityIndicatorViewStyleGray] autorelease];
                spinner.frame=CGRectMake(cell.imgViewProfile.frame.size.width/2-15,cell.imgViewProfile.frame.size.height/2-15, 30, 30);
                [cell.imgViewProfile addSubview:spinner];
                [spinner startAnimating];
                
                [manager downloadWithURL:[NSURL URLWithString:strUserImage] delegate:self options:0 success:^(UIImage *image)
                 {
                     for (UIView *subview in cell.imgViewProfile.subviews)
                     {
                         if([subview isKindOfClass:[UIActivityIndicatorView class]])
                         {
                             [subview removeFromSuperview];
                             break;
                         }
                     }
                     
                     cell.imgViewProfile.image = image;
                     cell.imgViewProfile.contentMode=UIViewContentModeScaleAspectFill;
                     cell.imgViewProfile.clipsToBounds=YES;
                     
                 } failure:^(NSError *error) {
                     
                     //NSLog(@"Error While getting image : %@",strImage);
                 }];
            }
        }
        

        UIActivityIndicatorView *spinner = [[[UIActivityIndicatorView alloc] initWithActivityIndicatorStyle:UIActivityIndicatorViewStyleGray] autorelease];
        spinner.frame=CGRectMake(cell.imgAdvertiseMent.frame.size.width/2-15,cell.imgAdvertiseMent.frame.size.height/2-15, 30, 30);
        [cell.imgAdvertiseMent addSubview:spinner];
        [spinner startAnimating];
        
        NSString *strImage=[NSString stringWithFormat:@"%@",photoObj.photourl];
        strImage=[ADVERTISEPHOTO_URL stringByAppendingString:strImage];
        
        [manager downloadWithURL:[NSURL URLWithString:strImage] delegate:self options:0 success:^(UIImage *image)
         {
             
             CGSize size = CGSizeAspectFit(image.size, cell.imgAdvertiseMent.frame.size);
             //NSLog(@"size : %@",NSStringFromCGSize(size));
             
             [cell.imgAdvertiseMent setFrame:CGRectMake(cell.imgAdvertiseMent.frame.origin.x + (cell.imgAdvertiseMent.frame.size.width-size.width)/2, cell.imgAdvertiseMent.frame.origin.y, size.width,size.height)]; //MIN(size.height, 280))];
              cell.imgAdvertiseMent.contentMode=UIViewContentModeScaleToFill;
             cell.imgAdvertiseMent.image = image;
             cell.lblTags.frame =CGRectMake(cell.imgAdvertiseMent.frame.origin.x,  (cell.imgAdvertiseMent.frame.origin.y+cell.imgAdvertiseMent.frame.size.height)-cell.lblTags.frame.size.height,  cell.imgAdvertiseMent.frame.size.width,  cell.lblTags.frame.size.height);

             cell.viewCellBotom.frame = CGRectMake( cell.viewCellBotom.frame.origin.x,  (cell.imgAdvertiseMent.frame.origin.y+cell.imgAdvertiseMent.frame.size.height)+8,  cell.viewCellBotom.frame.size.width,  cell.viewCellBotom.frame.size.height);
             //NSLog(@"Tag %@, bottomframe %@",cell.lblTags.text , cell.viewCellBotom);

             for (UIView *subview in cell.imgAdvertiseMent.subviews)
             {
                 if([subview isKindOfClass:[UIActivityIndicatorView class]])
                 {
                     [subview removeFromSuperview];
                     break;
                 }
             }
             
             if (![self.heightsCache objectForKey:strImage])
             {
                 //[self.heightsCache setObject:[NSNumber numberWithFloat:MIN(size.height, 280)] forKey:strImage];
                 [self.heightsCache setObject:[NSNumber numberWithFloat:size.height] forKey:strImage];

                 [self.tblView reloadData];
//                 [self.tblView beginUpdates];
//                 [self.tblView reloadRowsAtIndexPaths:@[indexPath] withRowAnimation:UITableViewRowAnimationNone];
//                 [self.tblView endUpdates];
             }
             
         } failure:^(NSError *error) {
             
             //NSLog(@"Error While getting image : %@",strImage);
         }];
        
        UITapGestureRecognizer *tapper = [[UITapGestureRecognizer alloc] initWithTarget:self action:@selector(advertisementTapped:)];
        [tapper setDelegate:self];
        [cell.imgAdvertiseMent addGestureRecognizer:tapper];
        [tapper release];
        
        UITapGestureRecognizer *tapper1 = [[UITapGestureRecognizer alloc] initWithTarget:self action:@selector(userProfileClicked:)];
        [tapper1 setDelegate:self];
        [cell.imgViewProfile addGestureRecognizer:tapper1];
        [tapper1 release];
        cell.backgroundColor = [UIColor clearColor];
        cell.backgroundView = nil;
        
        return cell;
    }
}

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
    
}

#pragma mark - Resize Image with Aspect Ratio


CGSize CGSizeAspectFit(CGSize aspectRatio, CGSize boundingSize)
{
     NSLog(@"aspectRatio %@",NSStringFromCGSize(aspectRatio));
     NSLog(@"imagesize %@",NSStringFromCGSize(boundingSize));
    float mW = boundingSize.width / aspectRatio.width;
    float mH = boundingSize.height / aspectRatio.height;
    if( mH < mW )
    {
        boundingSize.width = boundingSize.width;//(boundingSize.height / aspectRatio.height * aspectRatio.width);
        boundingSize.height = (boundingSize.width*aspectRatio.height)/aspectRatio.width;
        
    }
    else if( mW < mH )
        boundingSize.height = boundingSize.width / aspectRatio.width * aspectRatio.height;
    NSLog(@"boundingSize %@",NSStringFromCGSize(boundingSize));
    return boundingSize;
}

CGSize CGSizeAspectFill(CGSize aspectRatio, CGSize minimumSize)
{
    float mW = minimumSize.width / aspectRatio.width;
    float mH = minimumSize.height / aspectRatio.height;
    if( mH > mW )
        minimumSize.width = minimumSize.height / aspectRatio.height * aspectRatio.width;
    else if( mW > mH )
        minimumSize.height = minimumSize.width / aspectRatio.width * aspectRatio.height;
    return minimumSize;
}

#pragma mark - Action Methods

-(void)likedAdvertisementClicked:(id)sender
{
    if (self.isNetworkRechable) {
       
        if ([searchBar isFirstResponder])
        {
            
            [searchBar resignFirstResponder];
            [self.tblView reloadData];
            
        }else{
            
            UIButton *btn=(UIButton *)sender;
            
            NSLog(@"Liked : %d",btn.tag);
            
            self.intLike=btn.tag;
            
            NSIndexPath *indexPath=[NSIndexPath indexPathForRow:self.intLike inSection:0];
            
            CellHomeFeed *cell=(CellHomeFeed *)[self.tblView cellForRowAtIndexPath:indexPath];
            cell.lblLikes.text=[NSString stringWithFormat:@"%d",[cell.lblLikes.text integerValue] + 1];
            cell.btnLike.enabled=FALSE;
            
            if(self.isSearching) {
                
                NSMutableDictionary *dictionary = [self.arrayCopyListAdv objectAtIndex:self.intLike];
                
                NSString *likeCount = [NSString stringWithFormat:@"%@",[dictionary objectForKey:@"numberOfLikes"]];
                [dictionary setObject:[NSString stringWithFormat:@"%d",[likeCount integerValue] + 1] forKey:@"numberOfLikes"];
                [dictionary setObject:@"1" forKey:@"isUserHasLiked"];
                //[dictionary objectForKey:@"isUserHasLiked"] isEqualToString:@"1"]
                
                 [self addLikeOnPhotoWebservice:[dictionary objectForKey:@"photoId"]];
            }
            else {
                
                Photo *objPhoto = [self.arrayListAdv objectAtIndex:self.intLike];
                
                NSString *likeCount = [NSString stringWithFormat:@"%@",objPhoto.numberoflikes];
                [objPhoto setNumberoflikes:[NSString stringWithFormat:@"%d",[likeCount integerValue] + 1]];
                [objPhoto setIsuserhasliked:@"1"];
                
                 [self addLikeOnPhotoWebservice:objPhoto.photoid];
            }
        }
        
    }else{
        
        if ([searchBar isFirstResponder]) {
            
            [searchBar resignFirstResponder];
            
        }else{
        
            UIAlertView *alert=[[UIAlertView alloc] initWithTitle:AppName message:@"Please check your internet connection and try again." delegate:self cancelButtonTitle:@"OK" otherButtonTitles:nil];
            [alert show];
            [alert release];
        }
    }
}

-(void)addLikeOnPhotoWebservice:(NSString *)strPhotoId
{
    NSMutableDictionary * headerDic = [NSMutableDictionary dictionary];
    [headerDic setObject:@"Addlike" forKey:@"name"];
    NSMutableDictionary * bodyDic = [NSMutableDictionary dictionary];
    
    // Release the dateFormatter
    [bodyDic setValue:strPhotoId forKey:@"Pid"];
    [bodyDic setValue:[AppDelegate setDelegate].strUserId forKey:@"Uid"];
    [headerDic setObject:bodyDic forKey:@"body"];
    
    NSURL * url = [NSURL URLWithString:WEBSERVICE];
    
    //Request
    NSMutableURLRequest *request = [[NSMutableURLRequest alloc] initWithURL:url];
    NSString *postString=[NSString stringWithFormat:@"json=%@",[headerDic JSONRepresentation]];
    //prepare http body
    [request setHTTPMethod:@"POST"];
    [request setHTTPBody:[postString dataUsingEncoding:NSUTF8StringEncoding]];
    
    [URLConnection asyncConnectionWithRequest:request completionBlock:^(NSData *data, NSURLResponse *response) {
        
        NSString *responseString = [[NSString alloc] initWithData:data encoding:NSUTF8StringEncoding];
        NSDictionary *temp=[responseString JSONValue];
        [responseString release];
        
        NSLog(@"Addlike : %@",temp);

        if ([[temp objectForKey:@"status"] isEqualToString:@"ADD_LIKE_1"]) {
        
            //[self performSelector:@selector(addLikeSuccessCal) withObject:nil afterDelay:0.1];
            NSLog(@"Sucess : AddLike");
            
        }else{
            
            NSIndexPath *indexPath=[NSIndexPath indexPathForRow:self.intLike inSection:0];
            CellHomeFeed *cell=(CellHomeFeed *)[self.tblView cellForRowAtIndexPath:indexPath];
            cell.lblLikes.text=[NSString stringWithFormat:@"%d",[cell.lblLikes.text integerValue] - 1];
            cell.btnLike.enabled=TRUE;
                        
            if (self.isSearching)
            {
                NSMutableDictionary *dictionary = [self.arrayCopyListAdv objectAtIndex:self.intLike];
                
                NSString *likeCount = [NSString stringWithFormat:@"%@",[dictionary objectForKey:@"numberOfLikes"]];
                [dictionary setObject:[NSString stringWithFormat:@"%d",[likeCount integerValue] - 1] forKey:@"numberOfLikes"];
                [dictionary setObject:@"0" forKey:@"isUserHasLiked"];
            }else
            {
                Photo *objPhoto = [self.arrayListAdv objectAtIndex:self.intLike];
                
                NSString *likeCount = [NSString stringWithFormat:@"%@",objPhoto.numberoflikes];
                [objPhoto setNumberoflikes:[NSString stringWithFormat:@"%d",[likeCount integerValue] - 1]];
                [objPhoto setIsuserhasliked:@"0"];
            }
            NSLog(@"Failed : AddLike");
        }
    } errorBlock:^(NSError *error) {
        
        UIAlertView *alert=[[UIAlertView alloc] initWithTitle:AppName message:@"The request timed out. Please try again later." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles: nil];
        [alert show];
        [alert release];
        
        NSLog(@"Error!");
    }];
}

-(void)addLikeSuccessCal
{
    NSLog(@"intLike : %d",self.intLike);
    NSIndexPath *indexPath=[NSIndexPath indexPathForRow:self.intLike inSection:0];
    
    CellHomeFeed *cell=(CellHomeFeed *)[self.tblView cellForRowAtIndexPath:indexPath];
    cell.lblLikes.text=[NSString stringWithFormat:@"%d",[cell.lblLikes.text integerValue] + 1];
    cell.btnLike.enabled=FALSE;
    
}

#pragma mark -
#pragma mark GestureRecognizer Methods

-(void)advertisementTapped:(UITapGestureRecognizer *)recognizer {
    
     if (self.isNetworkRechable)
     {
         if ([searchBar isFirstResponder]) {
             
             [searchBar resignFirstResponder];
             [self.tblView reloadData];
             
         }else{
             
             int tag=recognizer.view.tag;
             NSLog(@"tap tag : %d",tag);
             
             if(self.isSearching) {
                 
                 NSDictionary *dictionary = [self.arrayCopyListAdv objectAtIndex:tag];
                 
                 AdvertisementDetailViewController *obj=[[AdvertisementDetailViewController alloc]initWithNibName:@"AdvertisementDetailViewController" bundle:[NSBundle mainBundle]];
                 obj.strPhotoId=[dictionary objectForKey:@"photoId"];
                 
                 
                 NSMutableArray *arraTag=[dictionary objectForKey:@"tags"];
                 
                 NSString *strTags=@"";
                 if (arraTag.count != 0) {
                     
                     for (int i=0; i<arraTag.count; i++) {
                         
                         NSMutableDictionary *dict=[arraTag objectAtIndex:i];
                         NSString *str1=@"#";
                         
                         if (![[dict objectForKey:@"name"] isEqualToString:@""]) {
                             
                             NSString *Tags=[str1 stringByAppendingString:[dict objectForKey:@"name"]];
                             
                             if (i < arraTag.count - 1) {
                                 
                                 Tags=[Tags stringByAppendingString:@","];
                             }
                             
                             strTags = [strTags stringByAppendingString:Tags];
                         }
                     }
                 }
                 
                 [AppDelegate setDelegate].strBrands=strTags;
                 
                 if ([[NSString stringWithFormat:@"%@",[dictionary objectForKey:@"createdUsreid"]] isEqualToString:[AppDelegate setDelegate].strUserId]) {
                     
                     obj.isEditPhoto=YES;
                     
                 }else{
                     
                     obj.isEditPhoto=NO;
                 }
                 
                 obj.strIfUserLike=[dictionary objectForKey:@"isUserHasLiked"];
                 [self.navigationController pushViewController:obj animated:YES];
                 //[obj release];
             }
             else {
                 
                 Photo *objPhoto = [self.arrayListAdv objectAtIndex:tag];
                 AdvertisementDetailViewController *obj=[[AdvertisementDetailViewController alloc]initWithNibName:@"AdvertisementDetailViewController" bundle:[NSBundle mainBundle]];
                 obj.strPhotoId=objPhoto.photoid;
                 
                 
                 NSSet *tagset = objPhoto.tags;
                 NSString *strTags=@"";
                 
                 int i=0;
                 for (Tag *tag in tagset) {
                     
                     NSString *str1=@"#";
                     
                     if (![tag.name isEqualToString:@""]) {
                         
                         NSString *Tags=[str1 stringByAppendingString:tag.name];
                         
                         if (i < tagset.count - 1) {
                             
                             Tags=[Tags stringByAppendingString:@","];
                         }
                         
                         strTags = [strTags stringByAppendingString:Tags];
                     }
                     
                     i = i+1;
                 }

                 [AppDelegate setDelegate].strBrands=strTags;
                 
                 if ([objPhoto.createduserid isEqualToString:[AppDelegate setDelegate].strUserId]) {
                     
                     obj.isEditPhoto=YES;
                     
                 }else{
                     
                     obj.isEditPhoto=NO;
                 }
                 
                 obj.strIfUserLike=objPhoto.isuserhasliked;
                 [self.navigationController pushViewController:obj animated:YES];
                 //[obj release];
             }
         }
     }else{
         
         if ([searchBar isFirstResponder]) {
             
             [searchBar resignFirstResponder];
             
         }else{
         
             UIAlertView *alert=[[UIAlertView alloc] initWithTitle:AppName message:@"Please check your internet connection and try again." delegate:self cancelButtonTitle:@"OK" otherButtonTitles:nil];
             [alert show];
             [alert release];
         }
     }
}

-(void)userNameClicked:(UIButton *)sender
{
    if (self.isNetworkRechable) {
        
        if ([searchBar isFirstResponder]) {
            
            [searchBar resignFirstResponder];
            [self.tblView reloadData];
            
        }else{
            
            int tag=sender.tag;
            NSLog(@"tap tag : %d",tag);
            
            if (self.isSearching)
            {
                NSDictionary *dictionary = [self.arrayCopyListAdv objectAtIndex:tag];
                
                ProfileViewController *obj=[[ProfileViewController alloc]initWithNibName:@"ProfileViewController" bundle:[NSBundle mainBundle]];
                if ([[NSString stringWithFormat:@"%@",[dictionary objectForKey:@"createdUsreid"]] isEqualToString:[AppDelegate setDelegate].strUserId]) {
                    
                    [AppDelegate setDelegate].isLoginUser=YES;
                    
                }else{
                    
                    [AppDelegate setDelegate].isLoginUser=NO;
                    [obj setStringUserId:[NSString stringWithFormat:@"%@",[dictionary objectForKey:@"createdUsreid"]]];
                    [obj setStrUserImage:[NSString stringWithFormat:@"%@",[dictionary objectForKey:@"profileImage"]]];
                }
                
                [AppDelegate setDelegate].isPresentedView=YES;
                
                [self.navigationController pushViewController:obj animated:YES];
                
            }else
            {
                Photo *objPhoto = [self.arrayListAdv objectAtIndex:tag];
                
                ProfileViewController *obj=[[ProfileViewController alloc]initWithNibName:@"ProfileViewController" bundle:[NSBundle mainBundle]];
                
                if ([objPhoto.createduserid isEqualToString:[AppDelegate setDelegate].strUserId]) {
                    
                    [AppDelegate setDelegate].isLoginUser=YES;
                    
                }else{
                    
                    [AppDelegate setDelegate].isLoginUser=NO;
                    [obj setStringUserId:objPhoto.createduserid];
                    [obj setStrUserImage:objPhoto.profileimage];
                }
                
                [AppDelegate setDelegate].isPresentedView=YES;
                
                [self.navigationController pushViewController:obj animated:YES];
            }
        }
        
    }else{
        
        if ([searchBar isFirstResponder]) {
            
            [searchBar resignFirstResponder];
        }else{
            
            UIAlertView *alert=[[UIAlertView alloc] initWithTitle:AppName message:@"Please check your internet connection and try again." delegate:self cancelButtonTitle:@"OK" otherButtonTitles:nil];
            [alert show];
            [alert release];
        }
    }
}

-(void)userProfileClicked:(UITapGestureRecognizer *)recognizer
{
    if (self.isNetworkRechable) {
        
        if ([searchBar isFirstResponder]) {
            
            [searchBar resignFirstResponder];
            [self.tblView reloadData];
            
        }else{
            
            int tag=recognizer.view.tag;
            NSLog(@"tap tag : %d",tag);
            
            if (self.isSearching)
            {
                NSDictionary *dictionary = [self.arrayCopyListAdv objectAtIndex:tag];

                ProfileViewController *obj=[[ProfileViewController alloc]initWithNibName:@"ProfileViewController" bundle:[NSBundle mainBundle]];
                if ([[NSString stringWithFormat:@"%@",[dictionary objectForKey:@"createdUsreid"]] isEqualToString:[AppDelegate setDelegate].strUserId]) {
                    
                    [AppDelegate setDelegate].isLoginUser=YES;
                    
                }else{
                    
                    [AppDelegate setDelegate].isLoginUser=NO;
                    [obj setStringUserId:[NSString stringWithFormat:@"%@",[dictionary objectForKey:@"createdUsreid"]]];
                    [obj setStrUserImage:[NSString stringWithFormat:@"%@",[dictionary objectForKey:@"profileImage"]]];
                }
                
                [AppDelegate setDelegate].isPresentedView=YES;
                
                [self.navigationController pushViewController:obj animated:YES];
                
            }else
            {
                Photo *objPhoto = [self.arrayListAdv objectAtIndex:tag];
                
                ProfileViewController *obj=[[ProfileViewController alloc]initWithNibName:@"ProfileViewController" bundle:[NSBundle mainBundle]];
                
                if ([objPhoto.createduserid isEqualToString:[AppDelegate setDelegate].strUserId]) {
                    
                    [AppDelegate setDelegate].isLoginUser=YES;
                    
                }else{
                    
                    [AppDelegate setDelegate].isLoginUser=NO;
                    [obj setStringUserId:objPhoto.createduserid];
                    [obj setStrUserImage:objPhoto.profileimage];
                }
                
                [AppDelegate setDelegate].isPresentedView=YES;
                
                [self.navigationController pushViewController:obj animated:YES];
            }
        }
        
    }else{
        
        if ([searchBar isFirstResponder]) {
            
            [searchBar resignFirstResponder];
        }else{
            
            UIAlertView *alert=[[UIAlertView alloc] initWithTitle:AppName message:@"Please check your internet connection and try again." delegate:self cancelButtonTitle:@"OK" otherButtonTitles:nil];
            [alert show];
            [alert release];
        }
    }
}

#pragma mark -
#pragma mark Search Bar

- (BOOL)searchBarShouldBeginEditing:(UISearchBar *)theSearchBar
{
    [theSearchBar setShowsCancelButton:YES animated:YES];
    
    return YES;
}

- (void)searchBarTextDidEndEditing:(UISearchBar *)theSearchBar
{
    [theSearchBar setShowsCancelButton:NO animated:YES];
    [theSearchBar resignFirstResponder];
}

- (void) searchBarTextDidBeginEditing:(UISearchBar *)theSearchBar {
    
	if(self.isSearching)
		return;
	
	self.isSearching = NO;
    self.strSearchtext=@"";
}

- (void)searchBar:(UISearchBar *)theSearchBar textDidChange:(NSString *)searchText
{
	//Remove all objects first.
	//[self.arrayCopyListAdv removeAllObjects];
	if([searchText length] > 0) {
		
		self.isSearching = YES;
		//[self searchTableView];
	}
	else {
		
		self.isSearching = NO;
        self.strSearchtext=@"";
	}
}

- (void) searchBarSearchButtonClicked:(UISearchBar *)theSearchBar {
	
    [searchBar resignFirstResponder];
	[self searchTableView];
}

- (void)searchBarCancelButtonClicked:(UISearchBar *) theSearchBar
{
    theSearchBar.text=@"";
    [theSearchBar resignFirstResponder];
    self.isSearching = NO;
    self.strSearchtext=@"";
    
    //[self.expandedPaths removeAllObjects];
    [self.tblView reloadData];
}

- (void) searchTableView {
    
    if (self.isNetworkRechable) {
        
        self.starRecord=0;
        fetchBatch = 0;
        noMoreResultsAvail = NO;
        
        self.strSearchtext=searchBar.text;
        
        [self.arrayCopyListAdv removeAllObjects];
        [self.tblView reloadData];
        
        self.HUD = [MBProgressHUD showHUDAddedTo:self.navigationController.view animated:YES];
        // Configure for text only and offset down
        self.HUD.mode = MBProgressHUDModeText;
        self.HUD.labelText = @"Searching...";
        self.HUD.labelFont=[UIFont fontWithName:@"American Typewriter" size:14];
        self.HUD.margin = 10.f;
        self.HUD.yOffset = 0;
        self.HUD.removeFromSuperViewOnHide = YES;
        
        [self performSelector:@selector(getAllAdvertisements) withObject:nil afterDelay:0.01];
        
    }else{
        
        [searchBar resignFirstResponder];
        searchBar.text=@"";
        self.strSearchtext=@"";
        self.isSearching = NO;
        
        UIAlertView *alert=[[UIAlertView alloc] initWithTitle:AppName message:@"Please check your internet connection and try again." delegate:self cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        [alert release];
    }
}

#pragma mark -
#pragma mark MBProgressHUDDelegate methods

- (void)hudWasHidden:(MBProgressHUD *)hud {
	// Remove HUD from screen when the HUD was hidded
	[HUD removeFromSuperview];
	[HUD release];
	HUD = nil;
}

@end


