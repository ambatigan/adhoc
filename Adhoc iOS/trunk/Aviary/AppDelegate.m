//
//  AppDelegate.m
//  Aviary
//
//  Created by Nidhi on 01/07/13.
//  Copyright (c) 2013 Riddham. All rights reserved.
//

#import "AppDelegate.h"
#import "Reachability.h"
#import "LoginViewController.h"
#import "HomeViewController.h"
#import "MainViewController.h"
#import <Crashlytics/Crashlytics.h>
#import "TestFlight.h"

static AppDelegate * appDelegate = nil;

@implementation AppDelegate
@synthesize strUserId,strUserName,strUserPic,strBrands;

@synthesize managedObjectContext = _managedObjectContext;
@synthesize managedObjectModel = _managedObjectModel;
@synthesize persistentStoreCoordinator = _persistentStoreCoordinator;
@synthesize arrExpandedPaths,intTotalRecord;
@synthesize isFromNotification,strNotificationPhotoId,strNotificationUserId;
@synthesize delegateNotification;
@synthesize navigationController;
@synthesize isLoginUser,isPresentedView,isIOS7;
@synthesize newAddedPhotoAppdel;


-(void)applicationDidFinishLaunching:(UIApplication *)application
//- (BOOL)application:(UIApplication *)application didFinishLaunchingWithOptions:(NSDictionary *)launchOptions
{
    
    self.window = [[UIWindow alloc] initWithFrame:[[UIScreen mainScreen] bounds]];
    float ver = [[[UIDevice currentDevice] systemVersion] floatValue];
    isIOS7 =FALSE;
    if(ver >=7)
    {
        isIOS7 = TRUE;
    }

//    self.strUserId=[NSString stringWithFormat:@"",[[NSUserDefaults standardUserDefaults] boolForKey:@"Login"]];
    
    [[UIApplication sharedApplication] setStatusBarStyle:UIStatusBarStyleLightContent];
    [[UIApplication sharedApplication] registerForRemoteNotificationTypes:(UIRemoteNotificationTypeAlert | UIRemoteNotificationTypeBadge | UIRemoteNotificationTypeSound)];
    
    self.isFromNotification=NO;
    
    [self performSelector:@selector(SetBadgeToServer)];
    
    NSMutableArray *arr=[[NSMutableArray alloc]init];
    self.arrExpandedPaths=arr;
    [arr release];
    
    // Initialize Flurry
    [Flurry startSession:@"7T57H2SS25CDJTGT4Z65"];
    
    [Flurry logEvent:@"APP LAUNCH" timed:YES];
    
    [TestFlight takeOff:@"6e72eabb-bbb5-4325-80cc-455729a615a2"];
    
    [Crashlytics startWithAPIKey:@"b53fcf08df9b183b382153735d57a10862fc5348"];
    
    if (![[NSUserDefaults standardUserDefaults] boolForKey:@"LOGIN"]) {
    
        self.loginViewController = [[LoginViewController alloc] initWithNibName:@"LoginViewController" bundle:nil];
        self.navigationController = [[UINavigationController alloc] initWithRootViewController:self.loginViewController];
        self.navigationController.navigationBarHidden=YES;
        
        self.window.rootViewController = self.navigationController;
        
    }else{
        
        [AppDelegate setDelegate].strUserId=[NSString stringWithFormat:@"%@",[[NSUserDefaults standardUserDefaults] objectForKey:@"USER ID"]];
        [AppDelegate setDelegate].strUserName=[NSString stringWithFormat:@"%@",[[NSUserDefaults standardUserDefaults] objectForKey:@"USER NAME"]];
        //[AppDelegate setDelegate].strUserPic=[NSString stringWithFormat:@"%@",[[NSUserDefaults standardUserDefaults] objectForKey:@"USER PIC"]];
        
        //self.homeViewController = [[HomeViewController alloc] initWithNibName:@"HomeViewController" bundle:nil];
        self.mainViewController = [[MainViewController alloc] initWithNibName:@"MainViewController" bundle:[NSBundle mainBundle]];
        self.navigationController = [[UINavigationController alloc] initWithRootViewController:self.mainViewController];
        self.navigationController.navigationBarHidden=YES;
        
        self.window.rootViewController = self.navigationController;
    }
    
    [self.window makeKeyAndVisible];
    
    //return YES;
}

- (void)applicationWillResignActive:(UIApplication *)application
{
    // Sent when the application is about to move from active to inactive state. This can occur for certain types of temporary interruptions (such as an incoming phone call or SMS message) or when the user quits the application and it begins the transition to the background state.
    // Use this method to pause ongoing tasks, disable timers, and throttle down OpenGL ES frame rates. Games should use this method to pause the game.
}

- (void)applicationDidEnterBackground:(UIApplication *)application
{
    // Use this method to release shared resources, save user data, invalidate timers, and store enough application state information to restore your application to its current state in case it is terminated later.
    // If your application supports background execution, this method is called instead of applicationWillTerminate: when the user quits.
}

- (void)applicationWillEnterForeground:(UIApplication *)application
{
    // Called as part of the transition from the background to the inactive state; here you can undo many of the changes made on entering the background.
}

- (void)applicationDidBecomeActive:(UIApplication *)application
{
    [UIApplication sharedApplication].applicationIconBadgeNumber = 0;
    
    [self performSelector:@selector(SetBadgeToServer)];
    
    // Restart any tasks that were paused (or not yet started) while the application was inactive. If the application was previously in the background, optionally refresh the user interface.
}

- (void)applicationWillTerminate:(UIApplication *)application
{
    [self saveContext];
    
    // Called when the application is about to terminate. Save data if appropriate. See also applicationDidEnterBackground:.
}


#pragma mark - Network Reachability method
- (BOOL)isNetWorkAvailable
{
	Reachability *reach = [Reachability reachabilityForInternetConnection];
    
    if ([reach currentReachabilityStatus] == NotReachable)
        return NO;
    else
        return YES;
    
}

#pragma mark - Class Instance

+(AppDelegate *)setDelegate
{
    if (nil == appDelegate)
    {
        appDelegate =(AppDelegate *) [[UIApplication sharedApplication]delegate];
    }
    
    return appDelegate;
}

#pragma mark - update device token to server

-(void)SetBadgeToServer
{
    NSMutableDictionary *dictHeader=[[NSMutableDictionary alloc]init];
    [dictHeader setValue:@"UpdateAccessToken" forKey:@"name"];
    
    NSMutableDictionary *dictBody=[[NSMutableDictionary alloc]init];
    
    NSString *strToken=[[NSUserDefaults standardUserDefaults] objectForKey:@"deviceToken"];
    [dictBody setValue:strToken forKey:@"token"];
    [dictBody setValue:@"" forKey:@"badge"];
    [dictHeader setObject:dictBody forKey:@"body"];
    [dictBody release];
    
    NSMutableURLRequest *request = [NSMutableURLRequest requestWithURL:[NSURL URLWithString:WEBSERVICE] cachePolicy:NSURLRequestReloadIgnoringCacheData timeoutInterval:60.0];
    [request setHTTPMethod:@"POST"];
    
    NSString *jsonString = [dictHeader JSONRepresentation];
    [dictHeader release];
    NSString *postString=[NSString stringWithFormat:@"json=%@",jsonString];
    [request setHTTPBody:[postString dataUsingEncoding:NSUTF8StringEncoding]];
    
    [URLConnection asyncConnectionWithRequest:request completionBlock:^(NSData *data, NSURLResponse *response) {
        
        NSString *responseString = [[NSString alloc] initWithData:data encoding:NSUTF8StringEncoding];
        NSDictionary *temp=[responseString JSONValue];
        [responseString release];
        
        NSLog(@"sucess update base count : %@",temp);
        
    } errorBlock:^(NSError *error) {
        
        NSLog(@"Error - update base count");
    }];
}

#pragma mark - Push Notification Methods

- (void)application:(UIApplication *)app didRegisterForRemoteNotificationsWithDeviceToken:(NSData *)deviceToken
{
    NSLog (@"token %@",deviceToken);
    
    NSString *strDeviceToken = [NSString stringWithFormat:@"%@",deviceToken];
    
    if ( [strDeviceToken length] > 0){
        
        strDeviceToken = [strDeviceToken substringFromIndex:1];
        strDeviceToken = [strDeviceToken substringToIndex:[strDeviceToken length] - 1];
        
        [[NSUserDefaults standardUserDefaults] setObject:strDeviceToken forKey:@"DEVICE TOKEN"];
        [[NSUserDefaults standardUserDefaults] synchronize];
    }    
}

- (void)application:(UIApplication *)app didFailToRegisterForRemoteNotificationsWithError:(NSError *)err {
    
    NSString *str = [NSString stringWithFormat: @"Error: %@", [err description] ];
    NSLog (@"token error:%@",str);
    
}

- (void)application:(UIApplication *)application didReceiveRemoteNotification:(NSDictionary *)userInfo {
    
    NSLog (@"userInfo : %@",userInfo);
    
    UIApplicationState state = [application applicationState];
    
    if (state == UIApplicationStateActive) {
        //the app is in the foreground, so here you do your stuff since the OS does not do it for you
        //navigate the "aps" dictionary looking for "loc-args" and "loc-key", for example, or your personal payload)
        
        NSLog (@"Active");
    }else
    {
        NSLog (@"InActive");
    }
    
    [AppDelegate setDelegate].isFromNotification=YES;
    
    
    [AppDelegate setDelegate].strNotificationPhotoId=[userInfo objectForKey:@"photo_id"];
    
    HomeViewController *obj=[[[HomeViewController alloc]init] autorelease];
    //obj.navController=[AppDelegate setDelegate].navigationController;
    [AppDelegate setDelegate].delegateNotification=obj;
    
    [[AppDelegate setDelegate].delegateNotification goToNextPageForNotification];
    
}

#pragma mark - Adjust Font size

- (void)adjustFontSizeToFit:(UILabel *)lable
{
    UIFont *font = lable.font;
    CGSize size = lable.frame.size;
    
    for (CGFloat maxSize = lable.font.pointSize; maxSize >= lable.minimumFontSize; maxSize -= 0.5f)
    {
        font = [font fontWithSize:maxSize];
        CGSize constraintSize = CGSizeMake(size.width, MAXFLOAT);
        CGSize labelSize = [lable.text sizeWithFont:font constrainedToSize:constraintSize lineBreakMode:UILineBreakModeWordWrap];
        if(labelSize.height <= size.height)
        {
            lable.font = font;
            [lable setNeedsLayout];
            break;
        }
    }
    // set the font to the minimum size anyway
    lable.font = font;
    [lable setNeedsLayout];
}

- (void)adjustFontSizeToFitForButton:(UIButton *)button
{
    UIFont *font = button.titleLabel.font;
    CGSize size = button.frame.size;
    
    for (CGFloat maxSize = button.titleLabel.font.pointSize; maxSize >= button.titleLabel.minimumFontSize; maxSize -= 1.f)
    {
        font = [font fontWithSize:maxSize];
        CGSize constraintSize = CGSizeMake(size.width, MAXFLOAT);
        CGSize labelSize = [button.titleLabel.text sizeWithFont:font constrainedToSize:constraintSize lineBreakMode:UILineBreakModeWordWrap];
        if(labelSize.height <= size.height)
        {
            button.titleLabel.font = font;
            [button setNeedsLayout];
            break;
        }
    }
    // set the font to the minimum size anyway
    button.titleLabel.font = font;
    [button setNeedsLayout];
}

#pragma mark - Save Context

- (void)saveContext
{
    NSError *error = nil;
    NSManagedObjectContext *managedObjectContext = self.managedObjectContext;
    if (managedObjectContext != nil) {
        if ([managedObjectContext hasChanges] && ![managedObjectContext save:&error]) {
            // Replace this implementation with code to handle the error appropriately.
            // abort() causes the application to generate a crash log and terminate. You should not use this function in a shipping application, although it may be useful during development.
            NSLog(@"Unresolved error %@, %@", error, [error userInfo]);
            abort();
        }
    }
}

#pragma mark - Core Data stack

// Returns the managed object context for the application.
// If the context doesn't already exist, it is created and bound to the persistent store coordinator for the application.
- (NSManagedObjectContext *)managedObjectContext
{
    if (_managedObjectContext != nil) {
        return _managedObjectContext;
    }
    
    NSPersistentStoreCoordinator *coordinator = [self persistentStoreCoordinator];
    if (coordinator != nil) {
        _managedObjectContext = [[NSManagedObjectContext alloc] init];
        [_managedObjectContext setPersistentStoreCoordinator:coordinator];
    }
    return _managedObjectContext;
}

// Returns the managed object model for the application.
// If the model doesn't already exist, it is created from the application's model.
- (NSManagedObjectModel *)managedObjectModel
{
    if (_managedObjectModel != nil) {
        return _managedObjectModel;
    }
    NSURL *modelURL = [[NSBundle mainBundle] URLForResource:@"AdHocCoreModel" withExtension:@"momd"];
    _managedObjectModel = [[NSManagedObjectModel alloc] initWithContentsOfURL:modelURL];
    return _managedObjectModel;
}

// Returns the persistent store coordinator for the application.
// If the coordinator doesn't already exist, it is created and the application's store added to it.
- (NSPersistentStoreCoordinator *)persistentStoreCoordinator
{
    if (_persistentStoreCoordinator != nil) {
        return _persistentStoreCoordinator;
    }
    
    NSURL *storeURL = [[self applicationDocumentsDirectory] URLByAppendingPathComponent:@"AdHocCoreModel.sqlite"];
    
    NSError *error = nil;
    _persistentStoreCoordinator = [[NSPersistentStoreCoordinator alloc] initWithManagedObjectModel:[self managedObjectModel]];
    if (![_persistentStoreCoordinator addPersistentStoreWithType:NSSQLiteStoreType configuration:nil URL:storeURL options:nil error:&error]) {
        /*
         Replace this implementation with code to handle the error appropriately.
         
         abort() causes the application to generate a crash log and terminate. You should not use this function in a shipping application, although it may be useful during development.
         
         Typical reasons for an error here include:
         * The persistent store is not accessible;
         * The schema for the persistent store is incompatible with current managed object model.
         Check the error message to determine what the actual problem was.
         
         
         If the persistent store is not accessible, there is typically something wrong with the file path. Often, a file URL is pointing into the application's resources directory instead of a writeable directory.
         
         If you encounter schema incompatibility errors during development, you can reduce their frequency by:
         * Simply deleting the existing store:
         [[NSFileManager defaultManager] removeItemAtURL:storeURL error:nil]
         
         * Performing automatic lightweight migration by passing the following dictionary as the options parameter:
         @{NSMigratePersistentStoresAutomaticallyOption:@YES, NSInferMappingModelAutomaticallyOption:@YES}
         
         Lightweight migration will only work for a limited set of schema changes; consult "Core Data Model Versioning and Data Migration Programming Guide" for details.
         
         */
        NSLog(@"Unresolved error %@, %@", error, [error userInfo]);
        abort();
    }
    
    return _persistentStoreCoordinator;
}

#pragma mark - Application's Documents directory

// Returns the URL to the application's Documents directory.
- (NSURL *)applicationDocumentsDirectory
{
    return [[[NSFileManager defaultManager] URLsForDirectory:NSDocumentDirectory inDomains:NSUserDomainMask] lastObject];
}



@end
