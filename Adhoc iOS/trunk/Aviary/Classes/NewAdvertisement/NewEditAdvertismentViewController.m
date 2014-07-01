//
//  NewEditAdvertismentViewController.m
//  Aviary
//
//  Created by MAC8 on 7/13/13.
//  Copyright (c) 2013 Riddham. All rights reserved.
//

#import "NewEditAdvertismentViewController.h"
#import "Base64.h"

@interface NewEditAdvertismentViewController ()

@end

@implementation NewEditAdvertismentViewController
@synthesize imagePreviewView,txtBrands,lblTitle;
@synthesize responseData;
@synthesize isEditPhoto,imgEditAdvertise,strPhotoId;

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
    
    UITapGestureRecognizer *tapper = [[UITapGestureRecognizer alloc] initWithTarget:self action:@selector(photoTapped:)];
    [tapper setDelegate:self];
    [self.imagePreviewView addGestureRecognizer:tapper];
    [tapper release];

    // Allocate Asset Library
    ALAssetsLibrary * assetLibrary = [[ALAssetsLibrary alloc] init];
    [self setAssetLibrary:assetLibrary];
    
    // Allocate Sessions Array
    NSMutableArray * sessions = [NSMutableArray new];
    [self setSessions:sessions];
    
    // Start the Aviary Editor OpenGL Load
    [AFOpenGLManager beginOpenGLLoad];
    
    btnPost.enabled=TRUE;
    
    if (self.isEditPhoto) {
        
        lblTitle.text=@"Edit Advertisment";
        
        self.txtBrands.text=[AppDelegate setDelegate].strBrands;
        
        self.imagePreviewView.image=self.imgEditAdvertise;
        
        [self performSelector:@selector(openAviaryForEditImage) withObject:nil afterDelay:0.1];
        
    }else{
        
        lblTitle.text=@"New Advertisment";
        self.imagePreviewView.image=nil;
    }
    if([AppDelegate setDelegate].isIOS7)
    {
        imgTopbar.frame = CGRectMake(imgTopbar.frame.origin.x, imgTopbar.frame.origin.y, imgTopbar.frame.size.width, imgTopbar.frame.size.height+20);
        lblTitle.frame = CGRectMake(lblTitle.frame.origin.x, lblTitle.frame.origin.y+10, lblTitle.frame.size.width, lblTitle.frame.size.height);
         btnBack.frame = CGRectMake(btnBack.frame.origin.x, btnBack.frame.origin.y+10, btnBack.frame.size.width, btnBack.frame.size.height);
         btnPost.frame = CGRectMake(btnPost.frame.origin.x, btnPost.frame.origin.y+10, btnPost.frame.size.width, btnPost.frame.size.height);
        advertiseView.frame = CGRectMake(advertiseView.frame.origin.x, advertiseView.frame.origin.y+20, advertiseView.frame.size.width, advertiseView.frame.size.height);
    }

}

-(void)openAviaryForEditImage
{
    [self launchPhotoEditorWithImage:imgEditAdvertise highResolutionImage:imgEditAdvertise];
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

#pragma mark - GestureRecognizer Methods

-(void)photoTapped:(UITapGestureRecognizer *)recognizer
{
    if ([self.txtBrands isFirstResponder]) {
        
        [self.txtBrands resignFirstResponder];
    }
    
    int tag=recognizer.view.tag;
    
    NSLog(@"tap tag : %d",tag);
    
    if (self.imagePreviewView.image == nil)
    {
        UIActionSheet *actionSheet=[[UIActionSheet alloc]initWithTitle:AppName delegate:self cancelButtonTitle:@"Cancel" destructiveButtonTitle:nil otherButtonTitles:@"Take Photo",@"Select Photo", nil];
      //  actionSheet.actionSheetStyle=UIActionSheetStyleBlackTranslucent;
        [actionSheet showInView:self.view];
        [actionSheet release];
        
    }else
    {
        UIActionSheet *actionSheet=[[UIActionSheet alloc]initWithTitle:AppName delegate:self cancelButtonTitle:@"Cancel" destructiveButtonTitle:nil otherButtonTitles:@"Edit",@"Take Photo",@"Select Photo", nil];
        actionSheet.actionSheetStyle=UIActionSheetStyleBlackTranslucent;
        [actionSheet showInView:self.view];
        [actionSheet release];
    }
}

#pragma mark - ActionSheet Delegate Method

- (void)actionSheet:(UIActionSheet *)actionSheet clickedButtonAtIndex:(NSInteger)buttonIndex
{
    if (self.imagePreviewView.image == nil)
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

    }else
    {
        if (buttonIndex == 0) {
            
            NSLog(@"Edit");
            
            [self launchPhotoEditorWithImage:self.imagePreviewView.image highResolutionImage:self.imagePreviewView.image];
            
        }else if (buttonIndex == 1) {
            
            NSLog(@"Take Photo");
            isSelectCamera=YES;
            [self showImagePicker];
            
        }else if (buttonIndex == 2) {
            
            NSLog(@"Select Photo");
            isSelectCamera=NO;
            [self showImagePicker];
        }
    }
}

- (void)willPresentActionSheet:(UIActionSheet *)actionSheet
{
    for (UIView *object in [actionSheet subviews]) {
        
        NSString *className = [NSString stringWithFormat:@"%@", [object class]];
        
        if ([className isEqualToString:@"UILabel"]) {
            
            UILabel *lable=(UILabel*)object;
            if([AppDelegate setDelegate].isIOS7)
                lable.frame = CGRectMake(lable.frame.origin.x, lable.frame.origin.y , lable.frame.size.width, lable.frame.size.height+15);
            lable.textAlignment  = NSTextAlignmentCenter;
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

-(void)showImagePicker
{
    UIImagePickerController * imagePicker = [UIImagePickerController new];
    
    if (isSelectCamera) {
    
        [imagePicker setSourceType:UIImagePickerControllerSourceTypeCamera];
        
    }else{
        
        [imagePicker setSourceType:UIImagePickerControllerSourceTypePhotoLibrary];
    }
    
    [imagePicker setDelegate:self];
    [self presentViewController:imagePicker animated:YES completion:nil];
}

#pragma mark - UItextField Delegate Methods

- (BOOL)textFieldShouldReturn:(UITextField *)textField
{
    [textField resignFirstResponder];
    return YES;
}

- (void)textFieldDidBeginEditing:(UITextField *)textField
{
    [self animateTextField:textField up:YES];
}

- (void)textFieldDidEndEditing:(UITextField *)textField
{
    textField.text=[textField.text stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceCharacterSet]];
    
    unichar testChar = [textField.text characterAtIndex:textField.text.length-1];
    
    if (testChar == ',') {
    
        textField.text = [textField.text substringToIndex:textField.text.length-1];
    }
    
    NSString *txtValue=@"";
    NSArray *array=[textField.text componentsSeparatedByString:@","];
    
    for (int i=0; i<array.count; i++)
    {
        NSString *str=[array objectAtIndex:i];
        
        unichar testChar = [str characterAtIndex:0];
        
        if (testChar == '#') {
            
        }else{
            NSString *has=@"#";
            str =[has stringByAppendingString:str];
        }
        
        txtValue = [txtValue stringByAppendingString:str];
        
        if (i < array.count -1) {
            
            txtValue = [txtValue stringByAppendingString:@","];
        }
    }
    
    NSLog(@"array : %@",array);
    
    textField.text=txtValue;
    
    if (array.count == 1) {
    
        NSString *string = textField.text;
        
        if (![string isEqualToString:@""]) {
         
            if ([string rangeOfString:@","].location == NSNotFound) {
                
                textField.text=[textField.text stringByReplacingOccurrencesOfString:@"," withString:@""];
            }
            
            unichar testChar = [string characterAtIndex:0];
            
            if (testChar == '#') {
                
            }else{
                NSString *has=@"#";
                textField.text =[has stringByAppendingString:textField.text];
            }
            
//            NSString *strHas=@"#";
//            textField.text=[strHas stringByAppendingString:textField.text];
        }
    }else{
        
        
    }
    
    [self animateTextField:textField up:NO];
}

- (BOOL)textField:(UITextField *)textField shouldChangeCharactersInRange:(NSRange)range replacementString:(NSString *)string
{    
    if ([string isEqualToString:@","]) {
        
        NSString *strText=@"";
        
        if (string.length == 0) {
            
            strText =   [self.txtBrands.text substringToIndex:self.txtBrands.text.length - 1];
            
            //strText = [myString substringFromIndex:1];
            
        }else{
            
            strText =   [self.txtBrands.text stringByAppendingString:string]; // substringFromIndex:self.txtBrands.text.length - 1];
        }
        
        NSLog(@"strText : %@",strText);
        
        NSString *txtValue=@"";
        
        NSArray *array=[strText componentsSeparatedByString:@","];
        
        
        for (int i=0; i<array.count - 1; i++)
        {
            NSString *str=[array objectAtIndex:i];
            
            str=[str stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceCharacterSet]];
            
            unichar testChar = [str characterAtIndex:0];
            
            if (testChar == '#') {
                
            }else{
                NSString *has=@"#";
                str =[has stringByAppendingString:str];
            }
            
            txtValue = [txtValue stringByAppendingString:str];
            
            if (i < array.count -1) {
                
                txtValue = [txtValue stringByAppendingString:@","];
            }
        }
        
        NSLog(@"array : %@",array);

        textField.text=txtValue;
        
        return NO;
    }
    
    return YES;
}

- (void)animateTextField:(UITextField*)textField up:(BOOL)up
{
    int movementDistance = 0;
    
    if (IS_IPHONE_5) {
    
        movementDistance = 100; // tweak as needed
        
    }else{
        
        movementDistance = 185; // tweak as needed
    }
    
    
    const float movementDuration = 0.3f; // tweak as needed
    
    int movement = (up ? -movementDistance : movementDistance);
    
    [UIView beginAnimations: @"anim" context: nil];
    [UIView setAnimationBeginsFromCurrentState: YES];
    [UIView setAnimationDuration: movementDuration];
    advertiseView.frame = CGRectOffset(advertiseView.frame, 0, movement);
    [UIView commitAnimations];
}

#pragma mark - Action Methods

-(IBAction)backClicked:(id)sender
{
    if ([self.txtBrands isFirstResponder]) {
        
        [self.txtBrands resignFirstResponder];
    }
    
    [self dismissModalViewControllerAnimated:YES];
}

-(IBAction)postClicked:(id)sender
{
    if (!self.imagePreviewView.image) {
        
        UIAlertView *alert=[[UIAlertView alloc] initWithTitle:AppName message:@"Please select image to upload" delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil, nil];
        [alert show];
        [alert release];
        
        return;
        
    }else if ([txtBrands.text isEqualToString:@""] || [txtBrands.text isEqualToString:@"#"]) {
        
        UIAlertView *alert=[[UIAlertView alloc] initWithTitle:AppName message:@"Please add tags" delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil, nil];
        [alert show];
        [alert release];
        
        return;
    }
    
    //call webservice
    if ([[AppDelegate setDelegate] isNetWorkAvailable]) {
        
        btnPost.enabled=FALSE;
        
        if ([self.txtBrands isFirstResponder]) {
            
            [self.txtBrands resignFirstResponder];
        }
        
        HUD = [[MBProgressHUD alloc] initWithView:self.view];
        [self.view addSubview:HUD];
        
        HUD.delegate = self;
        HUD.labelText = @"Uploading...";
        HUD.labelFont=[UIFont fontWithName:@"American Typewriter" size:14];
        //HUD.margin = 10.f;
        //HUD.yOffset = 0;
        HUD.removeFromSuperViewOnHide = YES;
        [HUD show:YES];
        
        if (self.isEditPhoto) {
            
            [self performSelector:@selector(editNewPhotoWebservice) withObject:nil afterDelay:0.1];
            
        }else{
            
            [self performSelector:@selector(addNewPhotoWebservice) withObject:nil afterDelay:0.1];
        }
        
    }else{
    
        UIAlertView *alert=[[UIAlertView alloc] initWithTitle:AppName message:@"Please check your internet connection and try again." delegate:self cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        [alert release];
    }
}

#pragma mark - WebService Methods

-(void)addNewPhotoWebservice
{
    self.responseData = [NSMutableData data];
    
    NSMutableDictionary * headerDic = [NSMutableDictionary dictionary];
    [headerDic setObject:@"AddPhoto" forKey:@"name"];
    NSMutableDictionary * bodyDic = [NSMutableDictionary dictionary];
    
    NSData *imgData=UIImagePNGRepresentation(self.imagePreviewView.image);
    [Base64 initialize];
    NSString *base64=[Base64 encode:imgData];
    
    NSString *strTags=[self.txtBrands.text stringByReplacingOccurrencesOfString:@"#" withString:@""];
    
    // Release the dateFormatter
    [bodyDic setValue:[AppDelegate setDelegate].strUserId forKey:@"userId"];
    [bodyDic setValue:@"" forKey:@"captionText"];
    [bodyDic setValue:base64 forKey:@"photoBase64String"];
    [bodyDic setValue:strTags forKey:@"tags"];
    [headerDic setObject:bodyDic forKey:@"body"];
    
    NSURL * url = [NSURL URLWithString:WEBSERVICE];
    
    //Request
    NSMutableURLRequest *request = [[NSMutableURLRequest alloc] initWithURL:url];
    NSString *postString=[NSString stringWithFormat:@"json=%@",[headerDic JSONRepresentation]];
    //prepare http body
    [request setHTTPMethod:@"POST"];
    [request setHTTPBody:[postString dataUsingEncoding:NSUTF8StringEncoding]];
    
    [NSURLConnection connectionWithRequest:request delegate:self];

}

-(void)editNewPhotoWebservice
{
	// myProgressTask uses the HUD instance to update progress
	//[HUD showWhileExecuting:@selector(myProgressTask) onTarget:self withObject:nil animated:YES];
    
    self.responseData = [NSMutableData data];
    
    NSMutableDictionary * headerDic = [NSMutableDictionary dictionary];
    [headerDic setObject:@"updatePhoto" forKey:@"name"];
    NSMutableDictionary * bodyDic = [NSMutableDictionary dictionary];
    
    NSData *imgData=UIImagePNGRepresentation(self.imagePreviewView.image);
    [Base64 initialize];
    NSString *base64=[Base64 encode:imgData];
    
    NSString *strTags=[self.txtBrands.text stringByReplacingOccurrencesOfString:@"#" withString:@""];
    
    // Release the dateFormatter
    [bodyDic setValue:self.strPhotoId forKey:@"Pid"];
    [bodyDic setValue:@"" forKey:@"Caption"];
    [bodyDic setValue:base64 forKey:@"ImageData"];
    [bodyDic setValue:strTags forKey:@"tags"];
    [headerDic setObject:bodyDic forKey:@"body"];
    
    NSURL * url = [NSURL URLWithString:WEBSERVICE];
    
    //Request
    NSMutableURLRequest *request = [[NSMutableURLRequest alloc] initWithURL:url];
    NSString *postString=[NSString stringWithFormat:@"json=%@",[headerDic JSONRepresentation]];
    //prepare http body
    [request setHTTPMethod:@"POST"];
    [request setHTTPBody:[postString dataUsingEncoding:NSUTF8StringEncoding]];
    
    [NSURLConnection connectionWithRequest:request delegate:self];
    
}

#pragma mark -
#pragma mark NSURLConnectionDelegete

- (void)connection:(NSURLConnection *)connection didReceiveResponse:(NSURLResponse *)response
{
	//expectedLength = [response expectedContentLength];
	//currentLength = 0;
	//HUD.mode = MBProgressHUDModeDeterminate;
    
    [responseData setLength:0];
}

- (void)connection:(NSURLConnection *)connection didReceiveData:(NSData *)data {
	//currentLength += [data length];
	//HUD.progress = currentLength / (float)expectedLength;
    
    [responseData appendData:data];
}

- (void)connection:(NSURLConnection *)connection didFailWithError:(NSError *)error {
    
	[HUD hide:YES];
    responseData = nil;
    
    btnPost.enabled=TRUE;
    
    UIAlertView *alert=[[UIAlertView alloc] initWithTitle:AppName message:@"The request timed out. Please try again later." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
    [alert show];
    [alert release];
    
    NSLog(@"Error!");
}

- (void)connectionDidFinishLoading:(NSURLConnection *)connection {
    
    NSString *responseString = [[NSString alloc] initWithData:self.responseData encoding:NSUTF8StringEncoding];
    NSDictionary *temp=[responseString JSONValue];
    [responseString release];
    
    NSLog(@"temp : %@",temp);
    
    [HUD hide:YES];
    
    btnPost.enabled=TRUE;
    
    if (self.isEditPhoto) {
        
        if ([[temp objectForKey:@"status"] isEqualToString:@"UPDATE_PHOTO_1"])
        {
            if ([self.txtBrands isFirstResponder]) {
                
                [self.txtBrands resignFirstResponder];
            }
            
            id object=[[temp objectForKey:@"data"] objectForKey:@"data"];
            
            NSMutableDictionary *dictionary=nil;
            if ([object isKindOfClass:[NSDictionary class]] || [object isKindOfClass:[NSMutableDictionary class]]) {
                
                dictionary=(NSMutableDictionary *)object;
            }else if ([object isKindOfClass:[NSDictionary class]] || [object isKindOfClass:[NSMutableDictionary class]])
            {
                NSMutableArray *array=(NSMutableArray *)object;
                dictionary=[array objectAtIndex:0];
            }
            
            [self updatePhotoToLocalData:dictionary];
            
        }else{
            
            UIAlertView *alert=[[UIAlertView alloc]initWithTitle:AppName message:@"Something going wrong, please try again later." delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil, nil];
            [alert show];
            [alert release];
        }
        
    }else{
        
        if ([[temp objectForKey:@"status"] isEqualToString:@"PHOTO_ADD_1"])
        {
            if ([self.txtBrands isFirstResponder]) {
                
                [self.txtBrands resignFirstResponder];
            }
            
            id object=[[temp objectForKey:@"data"] objectForKey:@"data"];
            
            NSMutableDictionary *dictionary=nil;
            if ([object isKindOfClass:[NSDictionary class]] || [object isKindOfClass:[NSMutableDictionary class]]) {
                
                dictionary=(NSMutableDictionary *)object;
            }else if ([object isKindOfClass:[NSDictionary class]] || [object isKindOfClass:[NSMutableDictionary class]])
            {
                NSMutableArray *array=(NSMutableArray *)object;
                dictionary=[array objectAtIndex:0];
            }
            
            [self addPhotoToLocalData:dictionary];
            
        }else{
            
            UIAlertView *alert=[[UIAlertView alloc]initWithTitle:AppName message:@"Something going wrong, please try again later." delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil, nil];
            [alert show];
            [alert release];
        }
    }
}


-(void)addPhotoToLocalData:(NSMutableDictionary *)dict
{
    //2013-09-13 07:37:43
    
    NSDate *date =[NSDate date];
    NSDateFormatter *df = [[NSDateFormatter alloc] init];
    df.dateStyle = NSDateFormatterMediumStyle;
    [df setDateFormat:@"yyyy-MM-dd HH:mm:ss"];
    NSString *strDate = [NSString stringWithFormat:@"%@",[df stringFromDate:date]];
    [df release];
   

    
    NSManagedObjectContext *context = [[AppDelegate setDelegate] managedObjectContext];
    
    Photo *photoDetail  = [NSEntityDescription insertNewObjectForEntityForName:@"Photo" inManagedObjectContext:context];
    photoDetail.photoid =[NSString stringWithFormat:@"%@",[dict objectForKey:@"photo_id"]];
    photoDetail.photourl = [NSString stringWithFormat:@"%@",[dict objectForKey:@"photourl"]];
    photoDetail.createduserid = [NSString stringWithFormat:@"%@",[AppDelegate setDelegate].strUserId];
    photoDetail.createdusername = [NSString stringWithFormat:@"%@",[AppDelegate setDelegate].strUserName];
    photoDetail.profileimage = @"";
    photoDetail.numberoflikes = @"0";
    photoDetail.createddate = @"a moment ago";
    photoDetail.createdon =strDate;//[NSString stringWithFormat:@"%@",[dict objectForKey:@"created_on"]];
    photoDetail.numberofcomments = @"0";
    photoDetail.isuserhasliked =@"0";
    
    
    NSString *str=[self.txtBrands.text stringByReplacingOccurrencesOfString:@"#" withString:@""];
    NSArray *array=[str componentsSeparatedByString:@","];
    
    for (int i=0; i<array.count; i++)
    {
        Tag *tag = [NSEntityDescription insertNewObjectForEntityForName:@"Tag" inManagedObjectContext:context];
        
        // Set the Tag attributes
        tag.tagid = @"";
        tag.name = [array objectAtIndex:i];
        
        // Set relationships
        [photoDetail addTagsObject:tag];
        [tag setPhoto:photoDetail];
    }
    [AppDelegate setDelegate].newAddedPhotoAppdel = photoDetail;


    //        // Save everything
    //        NSError *error = nil;
    //        if ([context save:&error]) {
    //            NSLog(@"The save was successful!");
    //        } else {
    //            NSLog(@"The save wasn't successful: %@", [error userInfo]);
    //        }
    
    [[AppDelegate setDelegate] saveContext];

    [self dismissModalViewControllerAnimated:YES];
}

-(void)updatePhotoToLocalData:(NSMutableDictionary *)dict
{
    NSManagedObjectContext *context = [[AppDelegate setDelegate] managedObjectContext];
    
    // Construct a fetch request
    NSFetchRequest *fetchRequest = [[NSFetchRequest alloc] init];
    NSEntityDescription *entity = [NSEntityDescription entityForName:@"Photo"
                                              inManagedObjectContext:context];
    
    [fetchRequest setEntity:entity];
    NSError *error = nil;
    NSMutableArray *storedArray =(NSMutableArray *) [context executeFetchRequest:fetchRequest error:&error];
    
    if (storedArray.count != 0) {
        
        Photo *updatePhoto=nil;
        
        for (Photo *objPhoto in storedArray) {
            
            NSLog(@"objPhoto.photoid : %@ photoId : %@",objPhoto.photoid,[dict objectForKey:@"photo_id"]);
            
            if ([objPhoto.photoid isEqualToString:[dict objectForKey:@"photo_id"]]) {
                
                updatePhoto= (Photo *)objPhoto;
                break;
            }
        }
        
        NSLog(@"Update");
        
        updatePhoto.photoid =updatePhoto.photoid;
        updatePhoto.photourl =[NSString stringWithFormat:@"%@", [dict objectForKey:@"photourl"]];
//        updatePhoto.createduserid = updatePhoto.createduserid;
//        updatePhoto.createdusername = updatePhoto.createdusername;
//        updatePhoto.profileimage =updatePhoto.profileimage;
//        updatePhoto.numberoflikes =updatePhoto.numberoflikes;
//        updatePhoto.createddate =updatePhoto.createddate;
//        updatePhoto.numberofcomments =updatePhoto.numberofcomments;
//        updatePhoto.isuserhasliked =updatePhoto.isuserhasliked;
        
        
        NSString *str=[self.txtBrands.text stringByReplacingOccurrencesOfString:@"#" withString:@""];
        NSArray *array=[str componentsSeparatedByString:@","];
        
        for (int i=0; i<array.count; i++)
        {
            Tag *tag = [NSEntityDescription insertNewObjectForEntityForName:@"Tag" inManagedObjectContext:context];
            
            // Set the Tag attributes
            tag.tagid = @"";
            tag.name = [array objectAtIndex:i];
            
            // Set relationships
            
            [updatePhoto addTagsObject:tag];
            [tag setPhoto:updatePhoto];
        }
        
        [AppDelegate setDelegate].newAddedPhotoAppdel = updatePhoto;
        [[AppDelegate setDelegate] saveContext];
        
    }
    
    [self dismissModalViewControllerAnimated:YES];
}

#pragma mark - Private Helper Methods

- (BOOL)hasValidAPIKey
{
    NSString * key = [[NSBundle mainBundle] objectForInfoDictionaryKey:@"Aviary-API-Key"];
    if ([key isEqualToString:@"<YOUR_API_KEY>"]) {
        [[[UIAlertView alloc] initWithTitle:@"Oops!" message:@"You forgot to add your API key!" delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil] show];
        return NO;
    }
    return YES;
}

- (void)layoutImageViews
{
    CGRect borderBounds = [[self borderView] bounds];
    [[self imagePreviewView] setFrame:CGRectInset(borderBounds, 10.0f, 10.0f)];
}

#pragma mark - Photo Editor Launch Methods

- (void)launchEditorWithAsset:(ALAsset *)asset
{
    UIImage * editingResImage = [self editingResImageForAsset:asset];
    UIImage * highResImage = [self highResImageForAsset:asset];
    
    [self launchPhotoEditorWithImage:editingResImage highResolutionImage:highResImage];
}

#pragma mark - Photo Editor Creation and Presentation
- (void)launchPhotoEditorWithImage:(UIImage *)editingResImage highResolutionImage:(UIImage *)highResImage
{
    // Initialize the photo editor and set its delegate
    AFPhotoEditorController * photoEditor = [[AFPhotoEditorController alloc] initWithImage:editingResImage];
    [photoEditor setDelegate:self];
    
    // Customize the editor's apperance. The customization options really only need to be set once in this case since they are never changing, so we used dispatch once here.
    static dispatch_once_t onceToken;
    dispatch_once(&onceToken, ^{
        [self setPhotoEditorCustomizationOptions];
    });
    
    // If a high res image is passed, create the high res context with the image and the photo editor.
    if (highResImage) {
        [self setupHighResContextForPhotoEditor:photoEditor withImage:highResImage];
    }
//    
//    UINavigationController *nav = [[UINavigationController alloc]
//                                   initWithRootViewController:photoEditor];
//    [self presentViewController:nav animated:YES completion:NULL];
    
    // Present the photo editor.
//    photoEditor.view.frame = CGRectMake( photoEditor.view.frame.origin.x,  photoEditor.view.frame.origin.y +20,  photoEditor.view.frame.size.width,  photoEditor.view.frame.size.height);
    [self presentViewController:photoEditor animated:YES completion:nil];
}

- (void)setupHighResContextForPhotoEditor:(AFPhotoEditorController *)photoEditor withImage:(UIImage *)highResImage
{
    // Capture a reference to the editor's session, which internally tracks user actions on a photo.
    __block AFPhotoEditorSession *session = [photoEditor session];
    
    // Add the session to our sessions array. We need to retain the session until all contexts we create from it are finished rendering.
    [[self sessions] addObject:session];
    
    // Create a context from the session with the high res image.
    AFPhotoEditorContext *context = [session createContextWithImage:highResImage];
    
    __block NewEditAdvertismentViewController * blockSelf = self;
    
    // Call render on the context. The render will asynchronously apply all changes made in the session (and therefore editor)
    // to the context's image. It will not complete until some point after the session closes (i.e. the editor hits done or
    // cancel in the editor). When rendering does complete, the completion block will be called with the result image if changes
    // were made to it, or `nil` if no changes were made. In this case, we write the image to the user's photo album, and release
    // our reference to the session.
    [context render:^(UIImage *result) {
        if (result) {
            UIImageWriteToSavedPhotosAlbum(result, nil, nil, NULL);
        }
        
        [[blockSelf sessions] removeObject:session];
        
        blockSelf = nil;
        session = nil;
        
    }];
}

#pragma Photo Editor Delegate Methods

// This is called when the user taps "Done" in the photo editor.
- (void)photoEditor:(AFPhotoEditorController *)editor finishedWithImage:(UIImage *)image
{
    [[self imagePreviewView] setImage:image];
    [[self imagePreviewView] setContentMode:UIViewContentModeScaleAspectFit];
    
    [self dismissViewControllerAnimated:YES completion:nil];
}

// This is called when the user taps "Cancel" in the photo editor.
- (void)photoEditorCanceled:(AFPhotoEditorController *)editor
{
    [self dismissViewControllerAnimated:YES completion:nil];
}

#pragma mark - Photo Editor Customization

- (void)setPhotoEditorCustomizationOptions
{
    // Set Tool Order
    NSArray * toolOrder = @[kAFEffects, kAFFocus, kAFFrames, kAFStickers, kAFEnhance, kAFOrientation, kAFCrop, kAFAdjustments, kAFSplash, kAFDraw, kAFText, kAFRedeye, kAFWhiten, kAFBlemish, kAFMeme];
    [AFPhotoEditorCustomization setToolOrder:toolOrder];
    
    // Set Custom Crop Sizes
    [AFPhotoEditorCustomization setCropToolOriginalEnabled:NO];
    [AFPhotoEditorCustomization setCropToolCustomEnabled:YES];
    NSDictionary * fourBySix = @{kAFCropPresetHeight : @(4.0f), kAFCropPresetWidth : @(6.0f)};
    NSDictionary * fiveBySeven = @{kAFCropPresetHeight : @(5.0f), kAFCropPresetWidth : @(7.0f)};
    NSDictionary * square = @{kAFCropPresetName: @"Square", kAFCropPresetHeight : @(1.0f), kAFCropPresetWidth : @(1.0f)};
    [AFPhotoEditorCustomization setCropToolPresets:@[fourBySix, fiveBySeven, square]];
    
    // Set Supported Orientations
    if (UI_USER_INTERFACE_IDIOM() == UIUserInterfaceIdiomPad) {
        NSArray * supportedOrientations = @[@(UIInterfaceOrientationPortrait), @(UIInterfaceOrientationPortraitUpsideDown), @(UIInterfaceOrientationLandscapeLeft), @(UIInterfaceOrientationLandscapeRight)];
        [AFPhotoEditorCustomization setSupportedIpadOrientations:supportedOrientations];
    }
}

#pragma mark - UIImagePicker Delegate

- (void)imagePickerController:(UIImagePickerController *)picker didFinishPickingMediaWithInfo:(NSDictionary *)info
{
    //NSURL * assetURL=nil;
    
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

- (void) imagePickerControllerDidCancel:(UIImagePickerController *)picker
{
    [self dismissViewControllerAnimated:YES completion:nil];
}

#pragma mark - ALAssets Helper Methods

- (UIImage *)editingResImageForAsset:(ALAsset*)asset
{
    CGImageRef image = [[asset defaultRepresentation] fullScreenImage];
    return [UIImage imageWithCGImage:image scale:1.0 orientation:UIImageOrientationUp];
}

- (UIImage *)highResImageForAsset:(ALAsset*)asset
{
    ALAssetRepresentation * representation = [asset defaultRepresentation];
    CGImageRef image = [representation fullResolutionImage];
    UIImageOrientation orientation = [representation orientation];
    CGFloat scale = [representation scale];
    
    return [UIImage imageWithCGImage:image scale:scale orientation:orientation];
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
