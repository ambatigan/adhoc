//
//  EditProfileViewController.m
//  Aviary
//
//  Created by MAC8 on 7/19/13.
//  Copyright (c) 2013 Riddham. All rights reserved.
//

#import "EditProfileViewController.h"

@interface EditProfileViewController ()

@end

@implementation EditProfileViewController

@synthesize txtUserName,txtName,txtHomeTown,imgProfileView;
@synthesize strUserName,strName,strRank,strNoPost,strNoLikes,strHomeTown;

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
   
    // Do any additional setup after loading the view from its nib.
    
//    NSString *strLikes=[NSString stringWithFormat:@"%@",[self.dictUserProfile objectForKey:@"NumberOfLikes"]];
    
    if ([self.strNoLikes isEqualToString:@"<null>"]) {
        
        self.strNoLikes=@"0";
    }
    
    lblLikes.text=[NSString stringWithFormat:@"%@ Likes",self.strNoLikes];
    lblNoPosts.text=[NSString stringWithFormat:@"%@ Posts",self.strNoPost];
    lblRank.text=[NSString stringWithFormat:@"%@",self.strRank];
    
    
    if ([self.strHomeTown isEqualToString:@"<null>"]) {
        
        txtHomeTown.text=@"";
        
    }else{
        
        txtHomeTown.text=self.strHomeTown;
    }
    
    //txtHomeTown.text=self.strHomeTown;
    txtName.text=self.strName;
    
    if (self.strUserName.length>10) {
        
        self.strUserName=[self.strUserName substringToIndex:10];
    }
    
    txtUserName.text=self.strUserName;
    
    [[AppDelegate setDelegate] adjustFontSizeToFit:lblLikes];
    [[AppDelegate setDelegate] adjustFontSizeToFit:lblNoPosts];
    [[AppDelegate setDelegate] adjustFontSizeToFit:lblRank];
        
    NSData* imageData = [[NSUserDefaults standardUserDefaults] objectForKey:@"USER PIC"];
    UIImage *image= [UIImage imageWithData:imageData];
    imgProfileView.image=image;
   // imgProfileView.contentMode = UIViewContentModeScaleAspectFill;
    
    ALAssetsLibrary * assetLibrary = [[ALAssetsLibrary alloc] init];
    [self setAssetLibrary:assetLibrary];
    
    if([AppDelegate setDelegate].isIOS7)
    {
        imgTopbar.frame = CGRectMake(imgTopbar.frame.origin.x, imgTopbar.frame.origin.y, imgTopbar.frame.size.width, imgTopbar.frame.size.height+20);
        lblTitle.frame = CGRectMake(lblTitle.frame.origin.x, lblTitle.frame.origin.y+10, lblTitle.frame.size.width, lblTitle.frame.size.height);
        btnClose.frame = CGRectMake(btnClose.frame.origin.x, btnClose.frame.origin.y+10, btnClose.frame.size.width, btnClose.frame.size.height);
        btnUpdate.frame = CGRectMake(btnUpdate.frame.origin.x, btnUpdate.frame.origin.y+10, btnUpdate.frame.size.width, btnUpdate.frame.size.height);
        viewDetail.frame = CGRectMake(viewDetail.frame.origin.x, viewDetail.frame.origin.y+20, viewDetail.frame.size.width, viewDetail.frame.size.height);
    }
    

}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

#pragma mark - Action Methods

-(IBAction)updateClicked:(id)sender
{
    NSHTTPCookie *cookie;
    NSHTTPCookieStorage *storage = [NSHTTPCookieStorage sharedHTTPCookieStorage];
    for (cookie in [storage cookies]) {
        [storage deleteCookie:cookie];
    }
    [[NSUserDefaults standardUserDefaults] synchronize];
    
    [self performSelector:@selector(updateUserProfile) withObject:nil afterDelay:0.1];
}
-(IBAction)closeClicked:(id)sender
{
    [self dismissViewControllerAnimated:YES completion:nil];
    
}

-(IBAction)editPhotoClicked:(id)sender
{
    if([txtUserName isFirstResponder] || [txtName isFirstResponder] || [txtHomeTown isFirstResponder])
    {
        [txtUserName resignFirstResponder];
        [txtName resignFirstResponder];
        [txtHomeTown resignFirstResponder];
    }
    
    UIActionSheet *actionSheet=[[UIActionSheet alloc]initWithTitle:AppName delegate:self cancelButtonTitle:@"Cancel" destructiveButtonTitle:nil otherButtonTitles:@"Take Photo",@"Select Photo", nil];
    actionSheet.actionSheetStyle=UIActionSheetStyleBlackTranslucent;
    [actionSheet showInView:self.view];
    [actionSheet release];
}

#pragma mark - WebService Methods

-(void)updateUserProfile
{
    NSMutableDictionary * headerDic = [NSMutableDictionary dictionary];
    [headerDic setObject:@"updateUserProfile" forKey:@"name"];
    NSMutableDictionary * bodyDic = [NSMutableDictionary dictionary];
    
    [bodyDic setValue:[AppDelegate setDelegate].strUserId forKey:@"userid"];
    [bodyDic setValue:txtUserName.text forKey:@"username"];
    
    NSData *imageData=UIImageJPEGRepresentation(imgProfileView.image, 1.0);
    NSString *strBase64=[Base64 encode:imageData];
    
    [bodyDic setValue:strBase64 forKey:@"profile_image"];
    
    NSString *strLastName = nil,*strFirstName = nil;
    
    NSArray *array=[txtName.text componentsSeparatedByString:@" "];
    if (array.count!=0) {
        
        strFirstName=[array objectAtIndex:0];
        
        strLastName=@"";
        for (int i=1; i<array.count; i++) {
            
            strLastName=[strLastName stringByAppendingString:[array objectAtIndex:i]];
        }
    }else{
        
         strLastName=@"";
        strFirstName=@"";
    }
    
    [bodyDic setValue:strFirstName forKey:@"first_name"];
    [bodyDic setValue:strLastName forKey:@"last_name"];
    [bodyDic setValue:txtHomeTown.text forKey:@"hometown"];
    
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
        NSLog(@"temp : %@",temp);
        
        if ([[temp objectForKey:@"status"] isEqualToString:@"UPDATE_USER_PROFILE_1"]) {
            
            NSData *imageData=UIImageJPEGRepresentation(imgProfileView.image, 1.0);
            [[NSUserDefaults standardUserDefaults] setObject:imageData forKey:@"USER PIC"];
            [[NSUserDefaults standardUserDefaults] setObject:self.txtUserName.text forKey:@"USER NAME"];
            
            [[NSUserDefaults standardUserDefaults] synchronize];
            
            [self updateUserDetails];
            
        }else{
            
            UIAlertView *alert=[[UIAlertView alloc] initWithTitle:AppName message:@"Something going wrong please try again later." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles: nil];
            [alert show];
            [alert release];

        }
        
    } errorBlock:^(NSError *error) {
        
        UIAlertView *alert=[[UIAlertView alloc] initWithTitle:AppName message:@"The request timed out. Please try again later." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles: nil];
        [alert show];
        [alert release];
        
        NSLog(@"Error!");
    }];
}

#pragma mark - Save to core data

-(void)updateUserDetails
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
    
    User *userStored =[arrayUser objectAtIndex:0];
    
    if ([userStored.useid isEqualToString:[AppDelegate setDelegate].strUserId]) {
        
        userStored.username = txtUserName.text;
        
        NSString *strLastName = nil,*strFirstName = nil;
        
        NSArray *array=[txtName.text componentsSeparatedByString:@" "];
        if (array.count!=0) {
            
            strFirstName=[array objectAtIndex:0];
            
            strLastName=@"";
            for (int i=1; i<array.count; i++) {
                
                strLastName=[strLastName stringByAppendingString:[array objectAtIndex:i]];
            }
        }else{
            
            strLastName=@"";
            strFirstName=@"";
        }
        
        userStored.firstname = strFirstName;
        userStored.lastname = strLastName;
        userStored.hometown = txtHomeTown.text;

        [[AppDelegate setDelegate] saveContext];
    }
    
    [self dismissModalViewControllerAnimated:YES];
}

#pragma mark - UIImage Picker Method

-(void)showImagePicker
{
    UIImagePickerController * imagePicker = [UIImagePickerController new];
    
    if (isSelectCamera) {
        
        [imagePicker setSourceType:UIImagePickerControllerSourceTypeCamera];
        
    }else{
        
        [imagePicker setSourceType:UIImagePickerControllerSourceTypeSavedPhotosAlbum];
    }
    
    [imagePicker setDelegate:self];
    [self presentViewController:imagePicker animated:YES completion:nil];
}

#pragma mark - UIImagePicker Delegate

- (void) imagePickerController:(UIImagePickerController *)picker didFinishPickingMediaWithInfo:(NSDictionary *)info
{
    if (isSelectCamera) {
        
        ALAssetsLibrary *library = [[ALAssetsLibrary alloc] init];
        [library writeImageToSavedPhotosAlbum:((UIImage *)[info objectForKey:UIImagePickerControllerOriginalImage]).CGImage
                                     metadata:[info objectForKey:UIImagePickerControllerMediaMetadata]
                              completionBlock:^(NSURL *assetURL, NSError *error) {
                                  
                                  //assetURL=assetURL;
                                  NSLog(@"assetURL %@", assetURL);
                                  [self cameraPictureassetUrl:assetURL];
                              }];
        
    }else{
        
        NSURL *assetURL = [info objectForKey:UIImagePickerControllerReferenceURL];
        
        void(^completion)(void)  = ^(void){
            
            [[self assetLibrary] assetForURL:assetURL resultBlock:^(ALAsset *asset) {
                if (asset){
                    [self launchEditorWithAsset:asset];
                }
            } failureBlock:^(NSError *error) {
                [[[UIAlertView alloc] initWithTitle:@"Error" message:@"Please enable access to your device's photos." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil] show];
            }];
        };
        
        [self dismissViewControllerAnimated:YES completion:completion];
    }
}

-(void)cameraPictureassetUrl:(NSURL *)assetURL
{
    void(^completion)(void)  = ^(void){
        
        [[self assetLibrary] assetForURL:assetURL resultBlock:^(ALAsset *asset) {
            if (asset){
                [self launchEditorWithAsset:asset];
            }
        } failureBlock:^(NSError *error) {
            [[[UIAlertView alloc] initWithTitle:@"Error" message:@"Please enable access to your device's photos." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil] show];
        }];
    };
    
    [self dismissViewControllerAnimated:YES completion:completion];
    
}

- (void) launchEditorWithAsset:(ALAsset *)asset
{
    ALAssetRepresentation * representation = [asset defaultRepresentation];
    CGImageRef image = [representation fullResolutionImage];
    self.imgProfileView.image=[UIImage imageWithCGImage:image];
}

- (void)imagePickerControllerDidCancel:(UIImagePickerController *)picker
{
    [picker dismissModalViewControllerAnimated:YES];
}

#pragma mark - ActionSheet Delegate Method

- (void)actionSheet:(UIActionSheet *)actionSheet clickedButtonAtIndex:(NSInteger)buttonIndex
{
    if (buttonIndex == 0) {
        
        NSLog(@"Take Photo");
        isSelectCamera=YES;
        [self showImagePicker];
        
    }else if (buttonIndex == 1) {
        
        NSLog(@"Select Photo");
        isSelectCamera=NO;
        [self showImagePicker];
    }
}

- (void)willPresentActionSheet:(UIActionSheet *)actionSheet
{
    for (UIView *object in [actionSheet subviews]) {
        
        NSString *className = [NSString stringWithFormat:@"%@", [object class]];
        
        if ([className isEqualToString:@"UILabel"]) {
            
            UILabel *lable=(UILabel*)object;
            lable.numberOfLines=0;
            lable.lineBreakMode=UILineBreakModeWordWrap;
            lable.font=[UIFont fontWithName:@"American Typewriter" size:17];
            
        }
        if ([className isEqualToString:@"UIAlertButton"]) {
            
            UIButton *button=(UIButton *)object;
            [button.titleLabel setFont:[UIFont fontWithName:@"American Typewriter" size:15]];
        }
    }
}

#pragma mark - UITextFiled Methods

- (void)textFieldDidBeginEditing:(UITextField *)textField
{
    
}
- (void)textFieldDidEndEditing:(UITextField *)textField
{
    
}
- (BOOL)textFieldShouldReturn:(UITextField *)textField
{
    [textField resignFirstResponder];
    return YES;
}



@end
