//
//  LoginViewController.m
//  Aviary
//
//  Created by Nidhi on 01/07/13.
//  Copyright (c) 2013 Riddham. All rights reserved.
//

#import "LoginViewController.h"
#import "AppDelegate.h"
#import <Accounts/Accounts.h>
#import <Social/Social.h>
#import <Social/Social.h>
#import <Accounts/ACAccountType.h>
#import <Accounts/ACAccountCredential.h>
#import "MainViewController.h"

@interface LoginViewController ()

@end

@implementation LoginViewController

@synthesize accountStore;
@synthesize facebookAccount;
@synthesize responseData;


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
    [lblTextLable sizeToFit];
    btnFacebook.enabled=TRUE;
    if([AppDelegate setDelegate].isIOS7)
        
    {
        imgTopBar.frame = CGRectMake(imgTopBar.frame.origin.x, imgTopBar.frame.origin.y, imgTopBar.frame.size.width, imgTopBar.frame.size.height+20);
        lblTitle.frame = CGRectMake(lblTitle.frame.origin.x, lblTitle.frame.origin.y+20, lblTitle.frame.size.width, lblTitle.frame.size.height);
    }
}

#pragma mark - Action Methods

-(IBAction)loginWithFacebook:(id)sender
{
    //float ver = [[[UIDevice currentDevice] systemVersion] floatValue];
    
    btnFacebook.enabled=FALSE;
    
    if (lblTextLable.textColor == [UIColor redColor]) {
        
        lblTextLable.text=@"We'll never share anything without asking";
        lblTextLable.textColor=[UIColor whiteColor];
    }
    
    accountStore = [[ACAccountStore alloc]init];
    ACAccountType *FBaccountType= [self.accountStore accountTypeWithAccountTypeIdentifier:ACAccountTypeIdentifierFacebook];
    
    //DrinkVoucher 164117317078099
    //Aviary 167439746767463
    
    
    NSString *key =@"167439746767463";
    NSArray *arryPermission=[NSArray arrayWithObjects:@"email",@"user_location",@"user_hometown",@"user_birthday", nil];
    NSDictionary *dictFB = [NSDictionary dictionaryWithObjectsAndKeys:key,ACFacebookAppIdKey,arryPermission,ACFacebookPermissionsKey, nil];
    
//    
//    [FBSession openActiveSessionWithReadPermissions:arryPermission
//                                       allowLoginUI:YES
//                                  completionHandler:^(FBSession *session, FBSessionState status, NSError *error) {
//                                      /* handle success + failure in block */
//                                  }];
    
    [self.accountStore requestAccessToAccountsWithType:FBaccountType options:dictFB completion:
     ^(BOOL granted, NSError *e) {
         if (granted) {
             NSArray *accounts = [self.accountStore accountsWithAccountType:FBaccountType];
             //it will always be the last object with single sign on
             self.facebookAccount = [accounts lastObject];
             //NSLog(@"facebook account =%@",self.facebookAccount);
             [self get];
         } else {
             //Fail gracefully...
             NSLog(@"error getting permission : %@",e);
             
             [self performSelectorOnMainThread:@selector(userDontAllow) withObject:nil waitUntilDone:YES];             
         }
     }];
}

#pragma mark - Fetch data from facebook

-(void)userDontAllow
{
    btnFacebook.enabled=TRUE;
    NSLog(@"user Dont Allow");
    
    lblTextLable.text=@"You need to authorize the AdHoc app to view your Facebook profile in order to use the app";
    [lblTextLable sizeToFit];
    lblTextLable.textColor=[UIColor redColor];
    
//    UIAlertView *alert=[[UIAlertView alloc]initWithTitle:@"Aviary" message:@"User Dont Allow" delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil, nil];
//    [alert show];
}

-(void)get
{
    NSURL *requestURL = [NSURL URLWithString:@"https://graph.facebook.com/me"];
    SLRequest *request = [SLRequest requestForServiceType:SLServiceTypeFacebook
                                            requestMethod:SLRequestMethodGET
                                                      URL:requestURL
                                               parameters:nil];
    request.account = self.facebookAccount;
    
    [request performRequestWithHandler:^(NSData *data,
                                         NSHTTPURLResponse *response,
                                         NSError *error) {
        
        if(!error)
        {
            NSMutableDictionary *accDetail =[NSJSONSerialization JSONObjectWithData:data options:kNilOptions error:&error];
            
            NSLog(@"Dictionary : %@", accDetail );
            
            if([accDetail objectForKey:@"error"]!=nil)
            {
                
                [self attemptRenewCredentials];
                
                btnFacebook.enabled=TRUE;
                lblTextLable.text=@"You need to authorize the AdHoc app to view your Facebook profile in order to use the app";
               [lblTextLable sizeToFit];
                lblTextLable.textColor=[UIColor redColor];
                
            }else{
            
                [self createJsonRequestDictinaryForFBDetail:accDetail];
            }
            //dispatch_async(dispatch_get_main_queue(),^{
                //nameLabel.text = [accDetail objectForKey:@"username"];
            //});
        }
        else{
            //handle error gracefully
            NSLog(@"error from get : %@",error);
            //attempt to revalidate credentials
            
            btnFacebook.enabled=TRUE;
            lblTextLable.text=@"You need to authorize the AdHoc app to view your Facebook profile in order to use the app";
            [lblTextLable sizeToFit];
            lblTextLable.textColor=[UIColor redColor];
        }
    }];
}

-(void)accountChanged:(NSNotification *)notif//no user info associated with this notif
{
    [self attemptRenewCredentials];
}
-(void)attemptRenewCredentials{
    [self.accountStore renewCredentialsForAccount:(ACAccount *)self.facebookAccount completion:^(ACAccountCredentialRenewResult renewResult, NSError *error){
        if(!error)
        {
            switch (renewResult) {
                case ACAccountCredentialRenewResultRenewed:
                    NSLog(@"Good to go");
                    // [self get];
                    break;
                case ACAccountCredentialRenewResultRejected:
                    NSLog(@"User declined permission");
                    break;
                case ACAccountCredentialRenewResultFailed:
                    NSLog(@"non-user-initiated cancel, you may attempt to retry");
                    break;
                default:
                    break;
            }
        }
        else{
            //handle error gracefully
            NSLog(@"error from renew credentials%@",error);
        }
    }];
}

#pragma mark - Prepare Data for Request

-(void)createJsonRequestDictinaryForFBDetail:(NSMutableDictionary *)accDetail
{
    [MBProgressHUD showHUDAddedTo:self.view animated:YES];
    
    self.responseData = [NSMutableData data];
    
    NSMutableDictionary * headerDic = [NSMutableDictionary dictionary];
    [headerDic setObject:@"register" forKey:@"name"];
    
    NSMutableDictionary * bodyDic = [NSMutableDictionary dictionary];
    
    // Birth Date
    NSDateFormatter *formater=[NSDateFormatter new];
    [formater setDateFormat:@"mm/dd/YYYY"];
    NSDate *date=[formater dateFromString:[accDetail objectForKey:@"birthday"]];
    [formater setDateFormat:@"dd/mm/YYYY"];
    NSString *birthDate=[formater stringFromDate:date];
    NSLog(@"birthDate : %@",birthDate);
    
    // Profile Image URL
    NSURL *profilePictureURL = [NSURL URLWithString:[NSString stringWithFormat:@"https://graph.facebook.com/%@/picture?type=large", [accDetail objectForKey:@"id"]]];
    NSData *imageData=[NSData dataWithContentsOfURL:profilePictureURL];
    
    
    [[NSUserDefaults standardUserDefaults] setObject:imageData forKey:@"USER PIC"];
    [[NSUserDefaults standardUserDefaults] synchronize];
    
    NSString *strBase64=[Base64 encode:imageData];
    //NSLog(@"strBase64 : %@",strBase64);
    
    // Release the dateFormatter
    [bodyDic setValue:[accDetail objectForKey:@"first_name"] forKey:@"firstname"];
    [bodyDic setValue:[accDetail objectForKey:@"last_name"] forKey:@"lastname"];
    [bodyDic setValue:[accDetail objectForKey:@"email"] forKey:@"emailid"];
    
    if ([accDetail objectForKey:@"hometown"]) {
        
        NSMutableDictionary *dict=[accDetail objectForKey:@"hometown"];
        [bodyDic setValue:[dict objectForKey:@"name"] forKey:@"hometown"];
        
    }else{
    
        [bodyDic setValue:@"" forKey:@"hometown"];
    }
    
    
    [bodyDic setValue:birthDate forKey:@"birthdate"];
    
    NSString *strSex;
    if ([[accDetail objectForKey:@"gender"] isEqualToString:@"male"]) {
        
        strSex=@"M";
    }else{
        
        strSex=@"F";
    }
    
    [bodyDic setValue:strSex forKey:@"sex"]; 
    [bodyDic setValue:strBase64 forKey:@"profileimage"];
    [headerDic setObject:bodyDic forKey:@"body"];
    
    //NSLog(@"headerDic : %@",headerDic);
    
    // [self performSelector:@selector(execMethod:) withObject:dic afterDelay:0.1];
    [self performSelectorOnMainThread:@selector(execMethod:) withObject:headerDic waitUntilDone:YES];
}

#pragma mark - Web Service Methods

-(void) execMethod:(NSMutableDictionary *)dic{
    
    NSURL * url = [NSURL URLWithString:WEBSERVICE];
    
    //Request
    NSMutableURLRequest *request = [[NSMutableURLRequest alloc] initWithURL:url];
     NSString *postString=[NSString stringWithFormat:@"json=%@",[dic JSONRepresentation]];
    //prepare http body
    [request setHTTPMethod:@"POST"];
    [request setHTTPBody:[postString dataUsingEncoding:NSUTF8StringEncoding]];
    
    
    NSLog("Uploading POST data: %@ to URL: %@",postString, [url absoluteString]);
    //  NSURLConnection *urlConnection = [[NSURLConnection alloc]initWithRequest:request delegate:self startImmediately:YES];
    [NSURLConnection connectionWithRequest:request delegate:self];
}

- (void)connection:(NSURLConnection *)connection didReceiveResponse:(NSURLResponse *)response {
    [self.responseData setLength:0];
}

- (void)connection:(NSURLConnection *)connection didReceiveData:(NSData *)data {    
    [self.responseData appendData:data];
}

- (void)connection:(NSURLConnection *)connection didFailWithError:(NSError *)error {
    
    NSLog(@"Connection failed with error: %@",[error description]);
	//[connection release];
	self.responseData = nil;
    
    btnFacebook.enabled=TRUE;
    
    [MBProgressHUD hideHUDForView:self.view animated:YES];
    
    [[NSUserDefaults standardUserDefaults] setBool:NO forKey:@"LOGIN"];
    
    UIAlertView *alert=[[UIAlertView alloc] initWithTitle:AppName message:@"The request timed out. Please try again later." delegate:self cancelButtonTitle:@"OK" otherButtonTitles: nil];
    [alert show];
    [alert release];
}

- (void)connectionDidFinishLoading:(NSURLConnection *)connection
{
    btnFacebook.enabled=TRUE;
    [MBProgressHUD hideHUDForView:self.view animated:YES];
    
    //NSError *error = nil;
//    NSDictionary *temp = [NSJSONSerialization JSONObjectWithData:self.responseData options:NSJSONReadingMutableContainers error: &error];
    
    NSString *responseString = [[NSString alloc] initWithData:self.responseData encoding:NSUTF8StringEncoding];
    NSLog(@"responseString : %@",responseString);
    NSMutableDictionary *temp=[responseString JSONValue];
    
//    if (error) {
//        
//        [[NSUserDefaults standardUserDefaults] setBool:NO forKey:@"LOGIN"];
//        
//        UIAlertView *alert=[[UIAlertView alloc] initWithTitle:AppName message:[error description] delegate:self cancelButtonTitle:@"OK" otherButtonTitles: nil];
//        [alert show];
//        [alert release];
//        
//    }else{
    
        if ([[temp objectForKey:@"status"] isEqualToString:@"REGGISTERATION_1"])
        {
            NSLog(@"Sucess : %@",temp);
            
            id object =[[temp objectForKey:@"data"] objectForKey:@"data"];
            
            NSMutableDictionary *dict;
            
            if ([object isKindOfClass:[NSArray class]] || [object isKindOfClass:[NSMutableArray class]])
            {
                
                NSMutableArray *array=(NSMutableArray *)object;
                dict=[array objectAtIndex:0];
                
            }else{
                
                dict=(NSMutableDictionary *)object;
                
            }
            //NSMutableDictionary *dict=[[temp objectForKey:@"data"] objectAtIndex:0];
            
            [[NSUserDefaults standardUserDefaults] setBool:YES forKey:@"LOGIN"];
            [[NSUserDefaults standardUserDefaults] setObject:[dict objectForKey:@"user_id"] forKey:@"USER ID"];
            [[NSUserDefaults standardUserDefaults] setObject:[dict objectForKey:@"user_name"] forKey:@"USER NAME"];
            //[[NSUserDefaults standardUserDefaults] setObject:[dict objectForKey:@"user_name"] forKey:@"USER PIC"];
            [[NSUserDefaults standardUserDefaults] synchronize];
            
            [AppDelegate setDelegate].strUserId = [[NSUserDefaults standardUserDefaults] objectForKey:@"USER ID"];
            [AppDelegate setDelegate].strUserName = [[NSUserDefaults standardUserDefaults] objectForKey:@"USER NAME"];
            //[AppDelegate setDelegate].strUserPic = [[NSUserDefaults standardUserDefaults] objectForKey:@"USER PIC"];
            
            
            MainViewController *obj=[[MainViewController alloc]initWithNibName:@"MainViewController" bundle:[NSBundle mainBundle]];
            [self.navigationController pushViewController:obj animated:YES];
            [obj release];
            
        }else
        {
            [[NSUserDefaults standardUserDefaults] setBool:NO forKey:@"LOGIN"];
            [[NSUserDefaults standardUserDefaults] synchronize];
            
            lblTextLable.text=@"We are unable to log you into Adhoc at this time, please try again later.";
            [lblTextLable sizeToFit];
            lblTextLable.textColor=[UIColor redColor];
            
            NSLog(@"Fail : %@",temp);
            
        }
//    }
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
}

@end
