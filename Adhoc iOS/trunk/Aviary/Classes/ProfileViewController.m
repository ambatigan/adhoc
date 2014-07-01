//
//  ProfileViewController.m
//  Aviary
//
//  Created by Nidhi on 01/07/13.
//  Copyright (c) 2013 Riddham. All rights reserved.
//

#import "ProfileViewController.h"
#import "HomeViewController.h"
#import "NewEditAdvertismentViewController.h"
#import "EditProfileViewController.h"

#define TAG_CONTENT_WIDTH 142.0f
#define TAG_MARGIN 5.0f
#define RECORD_TO_LOAD 3

@interface ProfileViewController ()

@end

@implementation ProfileViewController
@synthesize tblView;
@synthesize imhUserProfile,arrayUserAdvertisements;
@synthesize starRecord;
@synthesize dictUserProfile,isNetworkAvailable;
@synthesize fetchBatch,loading,noMoreResultsAvail,stringUserId,strUserImage;
//@synthesize navController;
@synthesize objUserDetail,heightsCache;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        // Custom initialization
    }
    return self;
}


- (void)viewDidLoad {
    
    [super viewDidLoad];
    
    //tabBar = [[HBTabBarManager alloc]initWithViewController:self topView:self.view delegate:self selectedIndex:2];
    
//    viewProfile.layer.shadowOffset = CGSizeMake(320,220);
//    viewProfile.layer.shadowRadius = 3.0;
//    viewProfile.layer.shadowColor = [UIColor blackColor].CGColor;
//    viewProfile.layer.shadowOpacity = 1.0;
    
//    self.imhUserProfile=[[MyImageView alloc]initWithFrame:CGRectMake(10,10,110,110)];
//    [viewProfile addSubview:self.imhUserProfile];
    
//    if (IS_IPHONE_5) {
//        
//        self.tblView.frame=CGRectMake(0, 257, 320, 240);
//        
//    }else
//    {
//        self.tblView.frame=CGRectMake(0, 257, 320,152 );
//    }
    
    NSMutableDictionary *dict=[[NSMutableDictionary alloc]init];
    self.heightsCache = dict;
    [dict release];
    
    NSMutableArray *array=[[NSMutableArray alloc]init];
    self.arrayUserAdvertisements=array;
    [array release];
    
    fetchBatch = 0;
    noMoreResultsAvail = NO;
    self.loading=NO;
    
    
}

-(void)viewWillAppear:(BOOL)animated
{
    self.imhUserProfile.clipsToBounds=YES;
    self.imhUserProfile.contentMode = UIViewContentModeScaleAspectFill;
    
    NSLog(@"self.isLoginUser : %d",[AppDelegate setDelegate].isLoginUser);
    
    if ([AppDelegate setDelegate].isLoginUser) {
        
        btnEdit.hidden=NO;
        
        NSData* imageData = [[NSUserDefaults standardUserDefaults] objectForKey:@"USER PIC"];
        UIImage *image= [UIImage imageWithData:imageData]; //[UIImage imageNamed:@"test.png"];
        
        if (image)
        {
            [self.imhUserProfile setImage:image];
            
        }else{
            
            [self.imhUserProfile setImage:[UIImage imageNamed:@"profileImage.png"]];
        }
        
    }else{
        
        NSLog(@"self.stringUserId : %@",self.stringUserId);
        
        NSString *strImage=[USERPHOTO_URL stringByAppendingString:self.strUserImage];
        
        NSLog(@"strUserImage : %@",strUserImage);
        
        [self.imhUserProfile setImageWithURL:[NSURL URLWithString:strImage] placeholderImage:[UIImage imageNamed:@"profileImage.png"] options:SDWebImageProgressiveDownload];
        
        btnEdit.hidden=YES;
    }
    
    if ([AppDelegate setDelegate].isPresentedView) {
        
        btnClose.hidden=NO;
        
    }else{
        
        btnClose.hidden=YES;
    }
    
    self.starRecord=0;
    
    [self getOfflineDataForUser];
   
   
    //call webservice
    if ([[AppDelegate setDelegate] isNetWorkAvailable]) {
        
        
        self.isNetworkAvailable=YES;
        [self.arrayUserAdvertisements removeAllObjects];
        [self performSelector:@selector(getUserProfileDetail) withObject:nil afterDelay:0.01];
        
    }else{

        self.isNetworkAvailable=NO;
    }
    
    [self performSelector:@selector(noInternetConnectionProfile) withObject:nil afterDelay:1];
}

-(void)noInternetConnectionProfile
{
    if (self.isNetworkAvailable) {
        
        [UIView animateWithDuration:0.60 animations:^{
            
            [viewNoInternet setFrame:CGRectMake(0,232,320,25)];
            
        }completion:^(BOOL isFinished){
            
            viewNoInternet.hidden=YES;
            
        }];
        
    }else{
        
        viewNoInternet.hidden=NO;
        
        [UIView animateWithDuration:0.60 animations:^{
            
            [viewNoInternet setFrame:CGRectMake(0,257,320,25)];
        }];
    }
}

-(void)getOfflineDataForUser
{
    NSMutableArray *array=[NSMutableArray arrayWithArray:[self getOfflineUserDetail]];
    
    for (User *obj in array) {
        
        BOOL isLogin=NO;
        
        if ([AppDelegate setDelegate].isLoginUser) {
        
            if ([obj.useid integerValue] == [[AppDelegate setDelegate].strUserId intValue]) {
                isLogin = YES;
            }
           // isLogin=[obj.useid isEqualToString:[AppDelegate setDelegate].strUserId];
            
        }else{
            
            if ([obj.useid integerValue] == [self.stringUserId intValue]) {
                isLogin = YES;
            }

           // isLogin=[obj.useid isEqualToString:self.stringUserId];
        }
        
        
        if (isLogin)
        {
            lblCurrentRank.text=[NSString stringWithFormat:@"%@",obj.userrank];
            lblNoLikes.text=[NSString stringWithFormat:@"%@",obj.numberoflikes];
            lblNoPost.text=[NSString stringWithFormat:@"%@",obj.numberofpost];
            
            NSString *str=[NSString stringWithFormat:@"%@",obj.hometown];
            
            if ([str isEqualToString:@"<null>"]) {
                lblHomeTown.text=@"";
                
            }else{
                
                lblHomeTown.text=[NSString stringWithFormat:@"%@",obj.hometown];
            }
            
            lblCurrentRank.text=[NSString stringWithFormat:@"%@",obj.userrank];
            
            NSString *strLikes=[NSString stringWithFormat:@"%@",obj.numberoflikes];
            
            if ([strLikes isEqualToString:@"<null>"]) {
                
                lblNoLikes.text=@"0";
                
            }else
            {
                lblNoLikes.text=strLikes;
            }
            
            lblTitle.text=[NSString stringWithFormat:@"%@",obj.username];
            
            
            if ([str isEqualToString:@"<null>"]) {
                lblHomeTown.text=@"";
                
            }else{
                
                lblHomeTown.text=[NSString stringWithFormat:@"%@",obj.hometown];

            }
            
            if ([AppDelegate setDelegate].isLoginUser) {
                
                lblName.text=[NSString stringWithFormat:@"%@ %@",obj.firstname,obj.lastname];
                
            }else
            {
                lblName.text=[NSString stringWithFormat:@"%@",obj.username];
            }
            
            
            NSSet *tagset = obj.userTags;
            
            if (tagset.count == 0) {
                
                lblBrands.text=@"No tags available";
                
            }else{
                
                NSString *strTags=@"";
                
                int i=0;
//                int count=0;
                
                
                for (UserTag *tag in tagset) {
                    
                    NSString *str1=@"#";
                    
                    if (![tag.name isEqualToString:@""]) {
                        
                        NSString *Tags=[str1 stringByAppendingString:tag.name];
                        
                        if (i < tagset.count - 1) {
                            
                            Tags=[Tags stringByAppendingString:@","];
                        }
                        
                        strTags = [strTags stringByAppendingString:Tags];
                        
//                        if (count > 4) {
//                            
//                            break;
//                        }
//                        count=count+1;
                    }
                    
                    i = i+1;
                }
                
                if (![strTags isEqualToString:@""]) {
                    
                    lblBrands.text=strTags;
                    
                }
                
                NSLog(@"strTags : %@",strTags);
            }
            
            [[AppDelegate setDelegate] adjustFontSizeToFit:lblBrands];
            [[AppDelegate setDelegate] adjustFontSizeToFit:lblCurrentRank];
            [[AppDelegate setDelegate] adjustFontSizeToFit:lblHomeTown];
            [[AppDelegate setDelegate] adjustFontSizeToFit:lblNoLikes];
            [[AppDelegate setDelegate] adjustFontSizeToFit:lblNoPost];
            [[AppDelegate setDelegate] adjustFontSizeToFit:lblName];
            
            break;
        }
    }
}

#pragma mark - Action Methods

-(IBAction)editClicked:(id)sender
{
    if (self.isNetworkAvailable) {
        
        EditProfileViewController *objEdit=[[EditProfileViewController alloc]initWithNibName:@"EditProfileViewController" bundle:[NSBundle mainBundle]];
        
        objEdit.strUserName=[NSString stringWithFormat:@"%@",self.objUserDetail.username]; //[self.dictUserProfile objectForKey:@"userName"]];
        objEdit.strRank=[NSString stringWithFormat:@"%@",self.objUserDetail.userrank]; // [self.dictUserProfile objectForKey:@"UserRank"]];
        objEdit.strNoLikes=[NSString stringWithFormat:@"%@",self.objUserDetail.numberoflikes]; // [self.dictUserProfile objectForKey:@"NumberOfLikes"]];
        objEdit.strNoPost=[NSString stringWithFormat:@"%@",self.objUserDetail.numberofpost]; //[self.dictUserProfile objectForKey:@"NumberOfPost"]];
        objEdit.strHomeTown=[NSString stringWithFormat:@"%@",self.objUserDetail.hometown]; //,[self.dictUserProfile objectForKey:@"hometown"]];
        objEdit.strName=[NSString stringWithFormat:@"%@ %@",self.objUserDetail.firstname,self.objUserDetail.lastname]; //,[self.dictUserProfile objectForKey:@"first_name"],[self.dictUserProfile objectForKey:@"last_name"]];
        [[AppDelegate setDelegate].mainViewController presentViewController:objEdit animated:YES completion:nil];
        [objEdit release];
        
    }else{
        
        UIAlertView *alert=[[UIAlertView alloc] initWithTitle:AppName message:@"Please check your internet connection and try again." delegate:self cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        [alert release];
    }
}

-(IBAction)closeClicked:(id)sender
{
    //[self dismissViewControllerAnimated:YES completion:nil];
    [self.navigationController popViewControllerAnimated:YES];
}

#pragma mark - WebService Methods

- (void)loadData
{
    self.fetchBatch ++;
    self.starRecord=self.starRecord + RECORD_TO_LOAD ;
    [self performSelector:@selector(getUserAdvertisements) withObject:nil afterDelay:0.1];
}

-(void)getUserProfileDetail
{
    NSMutableDictionary * headerDic = [NSMutableDictionary dictionary];
    [headerDic setObject:@"GetUserProfile" forKey:@"name"];
    NSMutableDictionary * bodyDic = [NSMutableDictionary dictionary];

    if ([AppDelegate setDelegate].isLoginUser) {
    
        [bodyDic setValue:[AppDelegate setDelegate].strUserId forKey:@"userid"];
        
    }else{
        
        [bodyDic setValue:self.stringUserId forKey:@"userid"];
    }
    
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
        
        [responseString release];
        NSLog(@"getUserProfileDetail : %@",temp);
        
        if ([[temp objectForKey:@"status"] isEqualToString:@"GET_USER_PROFILE_1"]) {
         
            id object =[[temp objectForKey:@"data"] objectForKey:@"data"];
            
            if ([object isKindOfClass:[NSArray class]] || [object isKindOfClass:[NSMutableArray class]])
            {
                NSMutableArray *array=(NSMutableArray *)object;
                [self performSelector:@selector(getUserProfileDetailSucess:) withObject:[array objectAtIndex:0] afterDelay:0.1];
                
            }else{
                
                NSMutableDictionary *dict=(NSMutableDictionary *)object;
                [self performSelector:@selector(getUserProfileDetailSucess:) withObject:dict afterDelay:0.1];
            }
        }else{
            
//            UIAlertView *alert=[[UIAlertView alloc] initWithTitle:AppName message:@"Something is going wrong. Please try again later." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles: nil];
//            [alert show];
//            [alert release];
            
            NSLog(@"Something is going wrong!");
        }
        
    } errorBlock:^(NSError *error) {
        
        UIAlertView *alert=[[UIAlertView alloc] initWithTitle:AppName message:@"The request timed out. Please try again later." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles: nil];
        [alert show];
        [alert release];
        
        NSLog(@"Error!");
    }];
}

-(void)getUserAdvertisements
{
    NSMutableDictionary * headerDic = [NSMutableDictionary dictionary];
    [headerDic setObject:@"ListOfUserAdvertisement" forKey:@"name"];
    NSMutableDictionary * bodyDic = [NSMutableDictionary dictionary];
    
    NSString *strStart=[NSString stringWithFormat:@"%d",self.starRecord];
    NSString *strTotal=[NSString stringWithFormat:@"%d",RECORD_TO_LOAD];
    
    // Release the dateFormatter
    [bodyDic setValue:strStart forKey:@"start"];
    [bodyDic setValue:strTotal forKey:@"total_record"];
    
    if ([AppDelegate setDelegate].isLoginUser) {
        
        [bodyDic setValue:[AppDelegate setDelegate].strUserId forKey:@"user_id"];
        
    }else{
        
        [bodyDic setValue:self.stringUserId forKey:@"user_id"];
    }
    
    //[bodyDic setValue:[AppDelegate setDelegate].strUserId forKey:@"user_id"];
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
        [responseString release];
        //NSLog(@"temp : %@",temp);
        
        if ([[temp objectForKey:@"status"] isEqualToString:@"ListOfUserAdvertisement_2"]) {
            
            noMoreResultsAvail = YES;
            [self.tblView reloadData];
            
        }else{
            
            NSArray *array=[[temp objectForKey:@"data"] objectForKey:@"data"];
            [self performSelector:@selector(getUserAdvertisementsSucess:) withObject:array afterDelay:0.1];
        }
        
    } errorBlock:^(NSError *error) {
        
        UIAlertView *alert=[[UIAlertView alloc] initWithTitle:AppName message:@"The request timed out. Please try again later." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles: nil];
        [alert show];
        [alert release];
        
        NSLog(@"Error!");
    }];
}


-(void)deleteAdvertiesmentWithPhotoId:(NSString*)strPhotoid WithUserId:(NSString*)strUSerId
{
    NSMutableDictionary * headerDic = [NSMutableDictionary dictionary];
    [headerDic setObject:@"DeleteUserAdvertisement" forKey:@"name"];
    NSMutableDictionary * bodyDic = [NSMutableDictionary dictionary];
    
    [bodyDic setValue:[AppDelegate setDelegate].strUserId forKey:@"user_id"];
    [bodyDic setValue:strPhotoid forKey:@"photo_id"];


    
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
        [responseString release];
        //NSLog(@"temp : %@",temp);
        
        if ([[temp objectForKey:@"status"] isEqualToString:@"deleteadvertisement_1"])
        {
            [self deletefromLocalDataWithId:strPhotoid];
            UIAlertView *alert=[[UIAlertView alloc] initWithTitle:AppName message:@"Advertisement deleted successfully." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles: nil];
            [alert show];
            [alert release];
            [tblView reloadData];

           
        }
        
    } errorBlock:^(NSError *error) {
        
        UIAlertView *alert=[[UIAlertView alloc] initWithTitle:AppName message:@"The request timed out. Please try again later." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles: nil];
        [alert show];
        [alert release];
        
        NSLog(@"Error!");
    }];

    
}
#pragma mark - Sucess Calls

-(void)getUserProfileDetailSucess:(NSMutableDictionary *)dict
{
//    self.dictUserProfile=dict;
//    NSLog(@"self.dictUserProfile : %@",self.dictUserProfile);
    
    // save data Offline
    [self saveUserDetailOffline:dict];
    
    [self getOfflineDataForUser];

    [self performSelector:@selector(getUserAdvertisements) withObject:nil afterDelay:0.5];
}

-(void)getUserAdvertisementsSucess:(NSArray *)array
{
    self.loading=NO;
    
    [self.arrayUserAdvertisements addObjectsFromArray:array];
    NSLog(@"self.arrayCopyListAdv : %d",self.arrayUserAdvertisements.count);
    [self.tblView reloadData];    
}



#pragma mark - Core Data Operation

-(void)saveUserDetailOffline:(NSMutableDictionary *)dict
{
    //store locally Core Data
    NSManagedObjectContext *context = [[AppDelegate setDelegate] managedObjectContext];
    
    // Construct a fetch request
    NSFetchRequest *fetchRequest = [[NSFetchRequest alloc] init];
    NSEntityDescription *entity = [NSEntityDescription entityForName:@"User"
                                              inManagedObjectContext:context];
    
    [fetchRequest setEntity:entity];
    NSError *error = nil;
    
    NSMutableArray *arrayUser =(NSMutableArray *) [context executeFetchRequest:fetchRequest error:&error];
    //NSLog(@"arrayUser : %@",arrayUser);
    
    if (arrayUser.count == 0) {
        
        NSLog(@"Add User");
        
        User *userObj  = [NSEntityDescription insertNewObjectForEntityForName:@"User" inManagedObjectContext:context];
        userObj.useid =[NSString stringWithFormat:@"%@",[dict objectForKey:@"userid"]];
        userObj.username = [NSString stringWithFormat:@"%@",[dict objectForKey:@"userName"]];
        userObj.firstname = [NSString stringWithFormat:@"%@",[dict objectForKey:@"first_name"]];
        userObj.lastname = [NSString stringWithFormat:@"%@",[dict objectForKey:@"last_name"]];
        userObj.profilephoto = [NSString stringWithFormat:@"%@",[dict objectForKey:@"profilePhoto"]];
        userObj.numberoflikes = [NSString stringWithFormat:@"%@",[dict objectForKey:@"NumberOfLikes"]];
        userObj.numberofpost = [NSString stringWithFormat:@"%@",[dict objectForKey:@"NumberOfPost"]];
        userObj.userrank = [NSString stringWithFormat:@"%@",[dict objectForKey:@"UserRank"]];
        userObj.hometown = [NSString stringWithFormat:@"%@",[dict objectForKey:@"hometown"]];
        
        //NSMutableArray *arrayTags=[dict objectForKey:@"tags"];
        
        if ([[dict objectForKey:@"tags"] isKindOfClass:[NSNull class]]) {
            
        }else{
            
            NSMutableArray *arrayTags=[dict objectForKey:@"tags"];
            
            int i=0;
            
            for (NSMutableDictionary *dicTag in arrayTags) {
                
                if (![[dicTag objectForKey:@"name"] isEqualToString:@""]) {
                 
                    // Insert the Tag entity
                    UserTag *usertag = [NSEntityDescription insertNewObjectForEntityForName:@"UserTag" inManagedObjectContext:context];
                    
                    // Set the Tag attributes
                    usertag.tagid = [dicTag objectForKey:@"id"];
                    usertag.name = [dicTag objectForKey:@"name"];
                    
                    // Set relationships
                    [userObj addUserTagsObject:usertag];
                    [usertag setUser:userObj];
                    
                    i=i+1;
                }
                
                if (i > 4) {
                    
                    break;
                }
            }
        }
        
        self.objUserDetail=userObj;
    
        [[AppDelegate setDelegate] saveContext];
                
    }else{
     
        User *userStored =[arrayUser objectAtIndex:0];
        
        if ([userStored.useid isEqualToString:[dict objectForKey:@"userid"]]) {
            
            NSLog(@"Update User");
            
            userStored.useid =[NSString stringWithFormat:@"%@",[dict objectForKey:@"userid"]];
            userStored.username = [NSString stringWithFormat:@"%@",[dict objectForKey:@"userName"]];
            userStored.firstname = [NSString stringWithFormat:@"%@",[dict objectForKey:@"first_name"]];
            userStored.lastname = [NSString stringWithFormat:@"%@",[dict objectForKey:@"last_name"]];
            userStored.profilephoto = [NSString stringWithFormat:@"%@",[dict objectForKey:@"profilePhoto"]];
            userStored.numberoflikes = [NSString stringWithFormat:@"%@",[dict objectForKey:@"NumberOfLikes"]];
            userStored.numberofpost = [NSString stringWithFormat:@"%@",[dict objectForKey:@"NumberOfPost"]];
            userStored.userrank = [NSString stringWithFormat:@"%@",[dict objectForKey:@"UserRank"]];
            userStored.hometown = [NSString stringWithFormat:@"%@",[dict objectForKey:@"hometown"]];
            
            
            if ([[dict objectForKey:@"tags"] isKindOfClass:[NSNull class]] ) {
                
            }else{
                
                NSMutableArray *arrayTags=[dict objectForKey:@"tags"];
                [userStored removeUserTags:userStored.userTags];
                
                int i=0;
                for (NSMutableDictionary *dicTag in arrayTags) {
                    
                    if (![[dicTag objectForKey:@"name"] isEqualToString:@""]) {
                     
                        // Insert the Tag entity
                        UserTag *usertag = [NSEntityDescription insertNewObjectForEntityForName:@"UserTag" inManagedObjectContext:context];
                        
                        // Set the Tag attributes
                        usertag.tagid = [dicTag objectForKey:@"id"];
                        usertag.name = [dicTag objectForKey:@"name"];
                        
                        // Set relationships
                        [userStored addUserTagsObject:usertag];
                        [usertag setUser:userStored];
                        
                        i=i+1;
                    }
                    
                    if (i > 4) {
                        
                        break;
                    }
                }
            }

            self.objUserDetail=userStored;
            [[AppDelegate setDelegate] saveContext];
            
        }else{
            
            NSLog(@"Add User");
            
            User *userObj  = [NSEntityDescription insertNewObjectForEntityForName:@"User" inManagedObjectContext:context];
            userObj.useid =[NSString stringWithFormat:@"%@",[dict objectForKey:@"userid"]];
            userObj.username = [NSString stringWithFormat:@"%@",[dict objectForKey:@"userName"]];
            userObj.firstname = [NSString stringWithFormat:@"%@",[dict objectForKey:@"first_name"]];
            userObj.lastname = [NSString stringWithFormat:@"%@",[dict objectForKey:@"last_name"]];
            userObj.profilephoto = [NSString stringWithFormat:@"%@",[dict objectForKey:@"profilePhoto"]];
            userObj.numberoflikes = [NSString stringWithFormat:@"%@",[dict objectForKey:@"NumberOfLikes"]];
            userObj.numberofpost = [NSString stringWithFormat:@"%@",[dict objectForKey:@"NumberOfPost"]];
            userObj.userrank = [NSString stringWithFormat:@"%@",[dict objectForKey:@"UserRank"]];
            userObj.hometown = [NSString stringWithFormat:@"%@",[dict objectForKey:@"hometown"]];
            
            if ([[dict objectForKey:@"tags"] isKindOfClass:[NSNull class]]) {
                
            }else{
                
                NSMutableArray *arrayTags=[dict objectForKey:@"tags"];
                
                int i=0;
                
                for (NSMutableDictionary *dicTag in arrayTags) {
                    
                    if (![[dicTag objectForKey:@"name"] isEqualToString:@""]) {
                        
                        // Insert the Tag entity
                        UserTag *usertag = [NSEntityDescription insertNewObjectForEntityForName:@"UserTag" inManagedObjectContext:context];
                        
                        // Set the Tag attributes
                        usertag.tagid = [dicTag objectForKey:@"id"];
                        usertag.name = [dicTag objectForKey:@"name"];
                        
                        // Set relationships
                        [userObj addUserTagsObject:usertag];
                        [usertag setUser:userObj];
                        
                        i=i+1;
                    }
                    
                    if (i > 4) {
                        
                        break;
                    }
                }
            }
            
            self.objUserDetail=userObj;
            [[AppDelegate setDelegate] saveContext];
        }
    }
}

-(NSArray *)getOfflineUserDetail
{
    NSManagedObjectContext *context = [[AppDelegate setDelegate] managedObjectContext];
    
    NSArray *array=[[[NSArray alloc]init]autorelease];
    // Construct a fetch request
    NSFetchRequest *fetchRequest = [[NSFetchRequest alloc] init];
    NSEntityDescription *entity = [NSEntityDescription entityForName:@"User"
                                              inManagedObjectContext:context];
    [fetchRequest setEntity:entity];
    
    NSError *error = nil;
    array = [context executeFetchRequest:fetchRequest error:&error];
    
    return array;
}

-(void)deletefromLocalDataWithId:(NSString*)strPhotoId
{
    NSManagedObjectContext *context = [[AppDelegate setDelegate] managedObjectContext];
    NSEntityDescription *productEntity=[NSEntityDescription entityForName:@"Photo" inManagedObjectContext:context];
    NSFetchRequest *fetch=[[NSFetchRequest alloc] init];
    [fetch setEntity:productEntity];
    NSPredicate *p=[NSPredicate predicateWithFormat:@"photoid == %@ And createduserid == %@", strPhotoId,[AppDelegate setDelegate].strUserId];
    [fetch setPredicate:p];
    //... add sorts if you want them
    NSError *fetchError;
    NSArray *fetchedProducts=[context executeFetchRequest:fetch error:&fetchError];
    if(fetchedProducts.count)
    [context deleteObject:[fetchedProducts objectAtIndex:0]];
    [[AppDelegate setDelegate]saveContext];
}

#pragma mark - UITableView delegate methods

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    if (self.arrayUserAdvertisements.count < 3) {
        
        return [self.arrayUserAdvertisements count];
        
    }else{
        
        return [self.arrayUserAdvertisements count] + 1;
    }
}

- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath
{
   if (indexPath.row >= self.arrayUserAdvertisements.count)
    {
        return 25;
    }
    
    NSMutableDictionary *tempDict=[self.arrayUserAdvertisements objectAtIndex:indexPath.row];
//    NSMutableArray *arraTag =[tempDict objectForKey:@"tags"];
//    
//    NSString *strTags=@"";
//    if (arraTag.count != 0) {
//        
//        for (int i=0; i<arraTag.count; i++) {
//            
//            NSMutableDictionary *dict=[arraTag objectAtIndex:i];
//            NSString *str1=@"#";
//            
//            if (![[dict objectForKey:@"name"] isEqualToString:@""]) {
//                
//                NSString *Tags=[str1 stringByAppendingString:[dict objectForKey:@"name"]];
//                
//                if (i < arraTag.count - 1) {
//                    
//                    Tags=[Tags stringByAppendingString:@","];
//                }
//                
//                strTags = [strTags stringByAppendingString:Tags];
//            }
//        }
//    }
//    
//    strTags = [strTags stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceCharacterSet]];
//    CGSize constraint = CGSizeMake(TAG_CONTENT_WIDTH , 20000.0f);
//    CGSize size = [strTags sizeWithFont:[UIFont fontWithName:@"American Typewriter" size:13] constrainedToSize:constraint lineBreakMode:UILineBreakModeWordWrap];
//    CGFloat height = MAX(size.height + 5, 25.0f);
    
    CGFloat height = 25;
    NSString *strImage=[NSString stringWithFormat:@"%@",[tempDict objectForKey:@"photoUrl"]];
    strImage=[ADVERTISEPHOTO_URL stringByAppendingString:strImage];
    
    CGFloat catchHeight = [[self.heightsCache objectForKey:strImage] floatValue];
    
    if (catchHeight) {
        
        return 75 + height + catchHeight;
        
    }else{
        
        return 350 + height;
    }
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    static NSString *CellIdentifier_profile =@"cellIdentifire_profile";
    static NSString *CellIdentifier1_profile =@"cellIdentifire1_profile";
    
    CellHomeFeed *cell = [tableView dequeueReusableCellWithIdentifier:CellIdentifier_profile];

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
    }
    
    NSMutableDictionary *dictionary = nil;
    
    /// If scrolled beyond two thirds of the table, load next batch of data.
    if (indexPath.row >= self.arrayUserAdvertisements.count - 2) {
        if (!self.loading) {
            self.loading = YES;
            // loadRequest is the method that loads the next batch of data.
            //[self loadData];
            [self performSelector:@selector(loadData) withObject:nil afterDelay:1];
        }
    }
    
    // Only starts populating the table if data source is not empty.
    if (self.arrayUserAdvertisements.count != 0) {
        // If the currently requested cell is not the last one, display normal data.
        // Else dispay @"Loading More..." or @"(No More Results Available)"
        if (indexPath.row < self.arrayUserAdvertisements.count) {
            
            dictionary = [self.arrayUserAdvertisements objectAtIndex:indexPath.row];
            
        } else {
            // The currently requested cell is the last cell.
            if (!noMoreResultsAvail) {
                // If there are results available, display @"Loading More..." in the last cell
                UITableViewCell *cell = [[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault
                                                               reuseIdentifier:CellIdentifier1_profile];
                
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
                                                               reuseIdentifier:CellIdentifier1_profile];
                cell.textLabel.font = [UIFont fontWithName:@"American Typewriter" size:13.0f];
                cell.textLabel.textColor=[UIColor blackColor];
                cell.textLabel.text = @"No More Results Available";
                cell.textLabel.textAlignment = UITextAlignmentCenter;
                return cell;
            }
        }
    } else {
        
        //[self.expandedPaths removeAllObjects];
        UITableViewCell *cell = [[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault
                                                       reuseIdentifier:CellIdentifier1_profile];
        cell.textLabel.font = [UIFont fontWithName:@"American Typewriter" size:13.0f];
        cell.textLabel.textColor=[UIColor blackColor];
        
//        if (isFirstLoad) {
//            
//            isFirstLoad=NO;
//            cell.textLabel.text = @"";
//        }else{
        
            cell.textLabel.text = @"No Results Available";
//        }
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
    CGSize size = [strTags sizeWithFont:[UIFont fontWithName:@"American Typewriter" size:13] constrainedToSize:constraint lineBreakMode:UILineBreakModeWordWrap];
    CGFloat height = MAX(size.height + 8, 25.0f);
    
    
    if (height > 25)
    {
        [cell.viewCellBotom setFrame:CGRectMake(cell.viewCellBotom.frame.origin.x , cell.frame.size.height - height, cell.viewCellBotom.frame.size.width,height + 15)];
    }
    
    
    //[cell.lblTags sizeToFit];
    
    cell.lblComments.text=[NSString stringWithFormat:@"%@",[dictionary objectForKey:@"numberOfComments"]];
    cell.lblLikes.text=[NSString stringWithFormat:@"%@",[dictionary objectForKey:@"numberOfLikes"]];
    
    NSDateFormatter *formater=[[NSDateFormatter alloc]init];
    [formater setDateFormat:@"yyyy-mm-dd hh:mm:ss"];
    NSDate *date=[formater dateFromString:[dictionary objectForKey:@"created_on"]];
    
    if (date) {
        
        [formater setDateFormat:@"MM/dd/yyyy hh:mm a"];
        NSString *strDate=[formater stringFromDate:date];
        
        cell.lblTimeAgo.text=strDate;
        
    }else{
        
        cell.lblTimeAgo.text=[NSString stringWithFormat:@"%@",[dictionary objectForKey:@"createdDate"]];
    }
    
    if ([[dictionary objectForKey:@"isUserHasLiked"] isEqualToString:@"1"]) {
        
        cell.btnLike.enabled=NO;
    }else{
        
        cell.btnLike.enabled=NO;
        //[cell.btnLike addTarget:self action:@selector(likedAdvertisementClicked:) forControlEvents:UIControlEventTouchUpInside];
    }
    
    [[AppDelegate setDelegate] adjustFontSizeToFit:cell.lblTags];
    [[AppDelegate setDelegate] adjustFontSizeToFit:cell.lblComments];
    [[AppDelegate setDelegate] adjustFontSizeToFit:cell.lblLikes];
    [[AppDelegate setDelegate] adjustFontSizeToFit:cell.lblTimeAgo];
    
    
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
            
            NSString *strUserImg=[NSString stringWithFormat:@"%@",[dictionary objectForKey:@"profileImage"]];
            strUserImg=[USERPHOTO_URL stringByAppendingString:strUserImg];
            //NSLog(@"strUserImage : %@",strUserImage);
            
            [manager downloadWithURL:[NSURL URLWithString:strUserImg] delegate:self options:0 success:^(UIImage *image)
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
         
         CGSize size = CGSizeAspectFit_Profile(image.size, cell.imgAdvertiseMent.frame.size);
         //NSLog(@"size : %@",NSStringFromCGSize(size));
         
         [cell.imgAdvertiseMent setFrame:CGRectMake(cell.imgAdvertiseMent.frame.origin.x + (cell.imgAdvertiseMent.frame.size.width-size.width)/2, cell.imgAdvertiseMent.frame.origin.y, size.width, MIN(size.height, 280) )];
         
         cell.imgAdvertiseMent.image = image;
         cell.lblTags.frame =CGRectMake(cell.imgAdvertiseMent.frame.origin.x,  (cell.imgAdvertiseMent.frame.origin.y+cell.imgAdvertiseMent.frame.size.height)-cell.lblTags.frame.size.height,  cell.imgAdvertiseMent.frame.size.width,  cell.lblTags.frame.size.height);
         cell.viewCellBotom.frame = CGRectMake( cell.viewCellBotom.frame.origin.x,  (cell.imgAdvertiseMent.frame.origin.y+cell.imgAdvertiseMent.frame.size.height)+8,  cell.viewCellBotom.frame.size.width,  cell.viewCellBotom.frame.size.height);
         
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
             
             [tableView reloadData];
//             [tableView beginUpdates];
//             [tableView reloadRowsAtIndexPaths:@[indexPath] withRowAnimation:UITableViewRowAnimationNone];
//             [tableView endUpdates];
         }
         
     } failure:^(NSError *error) {
         
         //NSLog(@"Error While getting image : %@",strImage);
     }];

    
    UITapGestureRecognizer *tapper = [[UITapGestureRecognizer alloc] initWithTarget:self action:@selector(advertisementTapped:)];
    [tapper setDelegate:self];
    [cell.imgAdvertiseMent addGestureRecognizer:tapper];
    [tapper release];
    
    return cell;
}

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
    
}

// Override to support conditional editing of the table view.
// This only needs to be implemented if you are going to be returning NO
// for some items. By default, all items are editable.
- (BOOL)tableView:(UITableView *)tableView canEditRowAtIndexPath:(NSIndexPath *)indexPath {
    // Return YES if you want the specified item to be editable.
    if(![self.stringUserId length])
        return YES;
    else
        return NO;
}

// Override to support editing the table view.
- (void)tableView:(UITableView *)tableView commitEditingStyle:(UITableViewCellEditingStyle)editingStyle forRowAtIndexPath:(NSIndexPath *)indexPath {
    if (editingStyle == UITableViewCellEditingStyleDelete) {
        
        UIAlertView *alert=[[UIAlertView alloc] initWithTitle:AppName message:@"Are you sure you want to delete this advertisement?" delegate:self cancelButtonTitle:@"No" otherButtonTitles: @"Yes",nil];
        alert.tag  = indexPath.row;
        [alert show];
        [alert release];


    }
}


#pragma mark - RUIAlertView delegate
- (void)alertView:(UIAlertView *)alertView clickedButtonAtIndex:(NSInteger)buttonIndex
{
    if(buttonIndex == 1)
    {
        NSMutableDictionary *dictionary = [self.arrayUserAdvertisements objectAtIndex:alertView.tag];

        [self deleteAdvertiesmentWithPhotoId:[dictionary valueForKey:@"photoId"] WithUserId:[dictionary valueForKey:@"createdUsreid"]];
        [self.arrayUserAdvertisements removeObjectAtIndex:alertView.tag];

    }
    else
        [tblView reloadData];
    
}


#pragma mark - Resize Image with Aspect Ratio

CGSize CGSizeAspectFit_Profile(CGSize aspectRatio, CGSize boundingSize)
{
    float mW = boundingSize.width / aspectRatio.width;
    float mH = boundingSize.height / aspectRatio.height;
    if( mH < mW )
        boundingSize.width = boundingSize.height / aspectRatio.height * aspectRatio.width;
    else if( mW < mH )
        boundingSize.height = boundingSize.width / aspectRatio.width * aspectRatio.height;
    return boundingSize;
}

CGSize CGSizeAspectFill_Profile(CGSize aspectRatio, CGSize minimumSize)
{
    float mW = minimumSize.width / aspectRatio.width;
    float mH = minimumSize.height / aspectRatio.height;
    if( mH > mW )
        minimumSize.width = minimumSize.height / aspectRatio.height * aspectRatio.width;
    else if( mW > mH )
        minimumSize.height = minimumSize.width / aspectRatio.width * aspectRatio.height;
    return minimumSize;
}

#pragma mark -
#pragma mark GestureRecognizer Methods

-(void)advertisementTapped:(UITapGestureRecognizer *)recognizer {
    
    int tag=recognizer.view.tag;
    NSLog(@"tap tag : %d",tag);
    
    NSDictionary *dictionary = [self.arrayUserAdvertisements objectAtIndex:tag];
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


- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

@end
