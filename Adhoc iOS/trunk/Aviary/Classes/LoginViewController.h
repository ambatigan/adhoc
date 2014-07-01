//
//  LoginViewController.h
//  Aviary
//
//  Created by Nidhi on 01/07/13.
//  Copyright (c) 2013 Riddham. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <Accounts/ACAccountStore.h>
#import <Accounts/ACAccount.h>
#import <FacebookSDK/FacebookSDK.h>
#import "Base64.h"
#import "JSON.h"

@interface LoginViewController : UIViewController  {

    NSMutableData* responseData;
    IBOutlet UILabel *lblTextLable;
    IBOutlet UIButton *btnFacebook;
    IBOutlet UIImageView *imgTopBar;
    IBOutlet UILabel *lblTitle;


}

@property (strong, nonatomic) ACAccountStore *accountStore;
@property (strong, nonatomic) ACAccount *facebookAccount;
@property (strong, nonatomic) NSMutableData* responseData;

-(IBAction)loginWithFacebook:(id)sender;

@end
