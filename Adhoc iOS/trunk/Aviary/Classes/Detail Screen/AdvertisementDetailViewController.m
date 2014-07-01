//
//  AdvertisementDetailViewController.m
//  Aviary
//
//  Created by MAC8 on 7/18/13.
//  Copyright (c) 2013 Riddham. All rights reserved.
//

#import "AdvertisementDetailViewController.h"
#import "UserCommentsCell.h"
#import "AddCommentCell.h"
#import "NewEditAdvertismentViewController.h"
#import "Photo.h"

#define COMMENT_CONTENT_WIDTH 200.0f
#define COMMENT_MARGIN 5.0f

@interface AdvertisementDetailViewController ()

@end

@implementation AdvertisementDetailViewController
@synthesize arrayComments,tblViewComments;
@synthesize scrollView,txtViewComment;
@synthesize strPhotoId,strIfUserLike;
@synthesize dictPhotoDetail,isEditPhoto;

-(void)dealloc
{
    [arrLikeUsers release];
    [super dealloc];
}
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
    
    NSMutableArray *arr=[[NSMutableArray alloc] init];
    self.arrayComments=arr;
    [arr release];
    
    NSMutableDictionary *dict=[[NSMutableDictionary alloc]init];
    self.dictPhotoDetail=dict;
    [dict release];
    
    self.navigationController.delegate=self;
    
//    if ([AppDelegate setDelegate].isFromNotification) {
//        
//        self.strPhotoId=[AppDelegate setDelegate].strNotificationPhotoId;
//    }
    
    NSLog(@"self.strPhotoId : %@",self.strPhotoId);
    
    if ([self.strIfUserLike isEqualToString:@"1"]) {
        
        btnLike.enabled=NO;
        
    }else{
        
        btnLike.enabled=YES;
    }

    isAddComment=NO;
    
    
    if (self.isEditPhoto) {
        
        btnEdit.hidden=NO;
        btnFlag.hidden=YES;
        
    }else{
        
        btnEdit.hidden=YES;
        btnFlag.hidden=NO;
    }
    
    [btnAddComment setTitleTextAttributes:[NSDictionary dictionaryWithObjectsAndKeys:[UIFont fontWithName:@"American Typewriter" size:15], UITextAttributeFont,nil] forState:UIControlStateNormal];
    
    self.tblViewComments.scrollEnabled=NO;
    
    //    if([AppDelegate setDelegate].isIOS7)
    //    {
    //        scrollView.frame = CGRectMake(scrollView.frame.origin.x, scrollView.frame.origin.y+20, scrollView.frame.size.width, scrollView.frame.size.height);
    //    }
        
    //    
    lblLike.hidden =TRUE;
}

- (void)viewWillAppear:(BOOL)animated
{
    // register for keyboard notifications
    [[NSNotificationCenter defaultCenter] addObserver:self
                                             selector:@selector(keyboardWillShow)
                                                 name:UIKeyboardWillShowNotification
                                               object:nil];
    
    [[NSNotificationCenter defaultCenter] addObserver:self
                                             selector:@selector(keyboardWillHide)
                                                 name:UIKeyboardWillHideNotification
                                               object:nil];
    
    //call webservice
    if ([[AppDelegate setDelegate] isNetWorkAvailable]) {
        
        [self performSelector:@selector(getAdvertisementDetailWebservice) withObject:nil afterDelay:0.01];
        
    }else{
        
        UIAlertView *alert=[[UIAlertView alloc] initWithTitle:AppName message:@"Please check your internet connection and try again." delegate:self cancelButtonTitle:@"OK" otherButtonTitles:nil];
        [alert show];
        [alert release];
    }

}

- (void)viewWillDisappear:(BOOL)animated
{
    // unregister for keyboard notifications while not visible.
    [[NSNotificationCenter defaultCenter] removeObserver:self
                                                    name:UIKeyboardWillShowNotification
                                                  object:nil];
    
    [[NSNotificationCenter defaultCenter] removeObserver:self
                                                    name:UIKeyboardWillHideNotification
                                                  object:nil];
}

#pragma mark - Keyboard notification methods

-(void)keyboardWillShow {
    // Animate the current view out of the way
}

-(void)keyboardWillHide
{    
    isAddComment=NO;
    btnComment.enabled=YES;
    
    [self performSelector:@selector(textViewdResignFirstResponder) withObject:nil afterDelay:0.3];
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

#pragma mark - Action Methods

-(IBAction)backClicked:(id)sender
{
    [self.navigationController popViewControllerAnimated:YES];
}

-(IBAction)flagClicked:(id)sender
{
    [self performSelector:@selector(addFlagOnPhotoWebservice) withObject:nil afterDelay:0.1];
}

-(IBAction)editClicked:(id)sender
{
    NewEditAdvertismentViewController *profileViewController = [[NewEditAdvertismentViewController alloc]initWithNibName:@"NewEditAdvertismentViewController" bundle:[NSBundle mainBundle]];
    profileViewController.isEditPhoto=YES;
    profileViewController.strPhotoId=self.strPhotoId;
    profileViewController.imgEditAdvertise=imgViewAdvertise.image;
    [self presentViewController:profileViewController animated:NO completion:nil];
    [profileViewController release];
    
}

-(IBAction)addCommentDoneKeyboadClicked:(id)sender
{
    isAddComment=NO;
    btnComment.enabled=YES;
    
    
    [self.tblViewComments setFrame:CGRectMake(self.tblViewComments.frame.origin.x, self.tblViewComments.frame.origin.y, self.tblViewComments.frame.size.width, self.tblViewComments.frame.size.height - 70)];
    scrollView.contentSize=CGSizeMake(320, scrollView.contentSize.height - 70);
    
    NSMutableDictionary *dict=[[NSMutableDictionary alloc]init];
    [dict setObject:@"" forKey:@"CommentId"];
    [dict setObject:self.txtViewComment.text forKey:@"CommentText"];
    [dict setObject:[AppDelegate setDelegate].strUserName forKey:@"Username"];
    [dict setObject:[AppDelegate setDelegate].strUserId forKey:@"Usreid"];
    [dict setObject:@"Few seconds ago" forKey:@"createDate"];
    [dict setObject:@"" forKey:@"photoUrl"];

    [self.arrayComments insertObject:dict atIndex:0];
    
    lblNoComments.text=[NSString stringWithFormat:@"%d",self.arrayComments.count];

    [dict release];
    
    [self.txtViewComment resignFirstResponder];
    [self getAllCommentsSuccessCal];
    
    [self performSelector:@selector(addCommentOnPhotoWebservice) withObject:nil afterDelay:0.1];
}

-(IBAction)cancelKeyboadClicked:(id)sender
{
    isAddComment=NO;
    btnComment.enabled=YES;
    
    [self.tblViewComments setFrame:CGRectMake(self.tblViewComments.frame.origin.x, self.tblViewComments.frame.origin.y, self.tblViewComments.frame.size.width, self.tblViewComments.frame.size.height - 70)];
    scrollView.contentSize=CGSizeMake(320, scrollView.contentSize.height - 70);
    
    [self.txtViewComment resignFirstResponder];
}

-(void)textViewdResignFirstResponder
{
    [self.tblViewComments reloadSections:[NSIndexSet indexSetWithIndex:0] withRowAnimation:UITableViewRowAnimationFade];
}

-(IBAction)likeClicked:(id)sender
{
    lblNoLikes.text=[NSString stringWithFormat:@"%d",[lblNoLikes.text integerValue]+1];
    btnLike.enabled=FALSE;
    
    [self performSelector:@selector(addLikeOnPhotoWebservice) withObject:nil afterDelay:0.1];
    
}

-(IBAction)commentClicked:(id)sender
{
    isAddComment=YES;
    btnComment.enabled=FALSE;
    
    [self.tblViewComments setFrame:CGRectMake(self.tblViewComments.frame.origin.x, self.tblViewComments.frame.origin.y, self.tblViewComments.frame.size.width, self.tblViewComments.frame.size.height + 70)];
    scrollView.contentSize=CGSizeMake(320, self.tblViewComments.frame.origin.y + scrollHeight + 70);
    
    [self.tblViewComments reloadSections:[NSIndexSet indexSetWithIndex:0] withRowAnimation:UITableViewRowAnimationFade];
    [self performSelector:@selector(textViewdBecomeFirstResponder) withObject:nil afterDelay:0.3];
}

-(void)textViewdBecomeFirstResponder
{
    [self.txtViewComment becomeFirstResponder];
}

-(IBAction)facebookClicked:(id)sender
{
    if([SLComposeViewController isAvailableForServiceType:SLServiceTypeFacebook]) {
        
        SLComposeViewController *controller = [SLComposeViewController composeViewControllerForServiceType:SLServiceTypeFacebook];
        
        NSString *strShare=[SHARING_URL stringByAppendingString:self.strPhotoId];
        
        [controller setInitialText:lbltags.text];
        [controller addURL:[NSURL URLWithString:strShare]];
        [controller addImage:imgViewAdvertise.image];
        
        [self presentViewController:controller animated:YES completion:Nil];
    }
}

-(IBAction)twitterClicked:(id)sender
{
    if ([SLComposeViewController isAvailableForServiceType:SLServiceTypeTwitter])
    {
        SLComposeViewController *tweetSheet = [SLComposeViewController composeViewControllerForServiceType:SLServiceTypeTwitter];
        NSString *strShare=[SHARING_URL stringByAppendingString:self.strPhotoId];
        
        [tweetSheet setInitialText:lbltags.text];
        [tweetSheet addURL:[NSURL URLWithString:strShare]];
        [tweetSheet addImage:imgViewAdvertise.image];
        [self presentViewController:tweetSheet animated:YES completion:nil];
    }
}

#pragma mark - WebService Methods

-(void)getAdvertisementLikeDetail
{
    
    NSMutableDictionary * headerDic = [NSMutableDictionary dictionary];
    [headerDic setObject:@"listofuserswholikedads" forKey:@"name"];
    NSMutableDictionary * bodyDic = [NSMutableDictionary dictionary];
    
    // Release the dateFormatter
    [bodyDic setValue:self.strPhotoId forKey:@"photo_id"];
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
        
        NSLog(@"temp : %@",temp);
        
        if ([[temp objectForKey:@"status"] isEqualToString:@"listofuserswholikedads_1"]) {
            
            NSMutableArray *array=(NSMutableArray *)[temp objectForKey:@"data"];
            if(array.count)
            {
                lblLike.hidden =TRUE;

                 NSMutableArray *array=(NSMutableArray *)[[temp objectForKey:@"data"] objectForKey:@"data"];
                [self performSelector:@selector(getAdvertisementLikeDetailSuccessCal:) withObject:array afterDelay:0.1];
            }
            else
            {
                lblLike.hidden =FALSE;
                [self performSelector:@selector(getAllCommentsWebservice) withObject:nil afterDelay:0.1];

            }
           
            
           
        }
       else{
           NSLog(@"No Data Found");
           [self performSelector:@selector(getAllCommentsWebservice) withObject:nil afterDelay:0.1];

           
       }
        
    } errorBlock:^(NSError *error) {
        
        UIAlertView *alert=[[UIAlertView alloc] initWithTitle:AppName message:@"The request timed out. Please try again later." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles: nil];
        [alert show];
        [alert release];
        
        NSLog(@"Error!");
    }];

}

-(void)getAdvertisementDetailWebservice
{
    NSMutableDictionary * headerDic = [NSMutableDictionary dictionary];
    [headerDic setObject:@"GetPhotoDetail" forKey:@"name"];
    NSMutableDictionary * bodyDic = [NSMutableDictionary dictionary];
    
    // Release the dateFormatter
    [bodyDic setValue:self.strPhotoId forKey:@"photoId"];
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
        
        NSLog(@"temp : %@",temp);
        
        if ([[temp objectForKey:@"status"] isEqualToString:@"GET_PHOTO_DETAIL_2"]) {
            
            NSLog(@"No Data Found");
            
        }else{
            
            id object =[[temp objectForKey:@"data"] objectForKey:@"data"];
            
            
            if ([object isKindOfClass:[NSArray class]] || [object isKindOfClass:[NSMutableArray class]])
            {
                
                NSMutableArray *array=(NSMutableArray *)object;
                [self performSelector:@selector(getAdvertisementDetailSuccessCal:) withObject:[array objectAtIndex:0] afterDelay:0.1];
                
            }else{
                
                NSMutableDictionary *dict=(NSMutableDictionary *)object;
                [self performSelector:@selector(getAdvertisementDetailSuccessCal:) withObject:dict afterDelay:0.1];
            }
        }
        
    } errorBlock:^(NSError *error) {
        
        UIAlertView *alert=[[UIAlertView alloc] initWithTitle:AppName message:@"The request timed out. Please try again later." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles: nil];
        [alert show];
        [alert release];
        
        NSLog(@"Error!");
    }];
}

-(void)getAllCommentsWebservice
{
    NSMutableDictionary * headerDic = [NSMutableDictionary dictionary];
    [headerDic setObject:@"GetPhotoComments" forKey:@"name"];
    NSMutableDictionary * bodyDic = [NSMutableDictionary dictionary];
    
    // Release the dateFormatter
    [bodyDic setValue:self.strPhotoId forKey:@"Pid"];
    [headerDic setObject:bodyDic forKey:@"body"];
    
    NSURL * url = [NSURL URLWithString:WEBSERVICE];
    
    //Request
    NSMutableURLRequest *request = [[NSMutableURLRequest alloc] initWithURL:url];
    NSString *postString=[NSString stringWithFormat:@"json=%@",[headerDic JSONRepresentation]];
    //prepare http body
    [request setHTTPMethod:@"POST"];
    [request setHTTPBody:[postString dataUsingEncoding:NSUTF8StringEncoding]];
    
    [URLConnection asyncConnectionWithRequest:request completionBlock:^(NSData *data, NSURLResponse *response) {
        
        NSError *e = nil;
        NSDictionary *temp = [NSJSONSerialization JSONObjectWithData:data options:NSJSONReadingMutableContainers error:&e];
        
        NSLog(@"e : %@ ",e);
        NSLog(@"temp : %@",temp);
        
        if ([[temp objectForKey:@"status"] isEqualToString:@"GET_PHOTO_COMMENTS_2"]) {
           
            NSLog(@"No Comments Found");
            
        }else{
            
            id object =[[temp objectForKey:@"data"] objectForKey:@"data"];
            
            if ([object isKindOfClass:[NSArray class]] || [object isKindOfClass:[NSMutableArray class]])
            {
                
                NSArray *array=(NSArray *)object;
                
                [self.arrayComments removeAllObjects];
                self.arrayComments=[NSMutableArray arrayWithArray:array];

                 [self performSelector:@selector(getAllCommentsSuccessCal) withObject:nil afterDelay:0.1];
                
            }
        }
        
    } errorBlock:^(NSError *error) {
        
        UIAlertView *alert=[[UIAlertView alloc] initWithTitle:AppName message:@"The request timed out. Please try again later." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles: nil];
        [alert show];
        [alert release];
        
        NSLog(@"Error!");
    }];
}

-(void)addCommentOnPhotoWebservice
{
    NSMutableDictionary * headerDic = [NSMutableDictionary dictionary];
    [headerDic setObject:@"AddComment" forKey:@"name"];
    NSMutableDictionary * bodyDic = [NSMutableDictionary dictionary];
    
    // Release the dateFormatter
    [bodyDic setValue:self.strPhotoId forKey:@"photoId"];
    [bodyDic setValue:[AppDelegate setDelegate].strUserId forKey:@"userId"];
    [bodyDic setValue:self.txtViewComment.text forKey:@"commentText"];
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
        
        if ([[temp objectForKey:@"status"] isEqualToString:@"ADD_COMMENT_1"]) {
            
            NSManagedObjectContext *context = [[AppDelegate setDelegate] managedObjectContext];
            
            NSArray *array=[[[NSArray alloc]init]autorelease];
            // Construct a fetch request
            NSFetchRequest *fetchRequest = [[NSFetchRequest alloc] init];
            NSEntityDescription *entity = [NSEntityDescription entityForName:@"Photo"
                                                      inManagedObjectContext:context];
            [fetchRequest setEntity:entity];
            
            NSPredicate *predicate = [NSPredicate predicateWithFormat:@"photoid == %@",self.strPhotoId];
            [fetchRequest setPredicate:predicate];
            
            NSError *error = nil;
            array = [context executeFetchRequest:fetchRequest error:&error];
            
            Photo *objPhoto = [array objectAtIndex:0];
            objPhoto.numberofcomments =[NSString stringWithFormat:@"%d",self.arrayComments.count];
            [[AppDelegate setDelegate] saveContext];
            
        }else{
            
            [self.arrayComments removeObjectAtIndex:0];
            lblNoComments.text=[NSString stringWithFormat:@"%d",self.arrayComments.count];
            
            [self.tblViewComments reloadData];
        }
        //NSMutableArray *array=[[temp objectForKey:@"data"] objectForKey:@"data"];
        //[self performSelector:@selector(addCommentSuccessCal:) withObject:array afterDelay:0.1];
        
    } errorBlock:^(NSError *error) {
        
        [self.arrayComments removeLastObject];
        [self.tblViewComments reloadData];
        
        UIAlertView *alert=[[UIAlertView alloc] initWithTitle:AppName message:@"The request timed out. Please try again later." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles: nil];
        [alert show];
        [alert release];
        
        NSLog(@"Error!");
    }];
}

-(void)addLikeOnPhotoWebservice
{
    NSMutableDictionary * headerDic = [NSMutableDictionary dictionary];
    [headerDic setObject:@"Addlike" forKey:@"name"];
    NSMutableDictionary * bodyDic = [NSMutableDictionary dictionary];
    
    // Release the dateFormatter
    [bodyDic setValue:self.strPhotoId forKey:@"Pid"];
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
        
        NSLog(@"temp : %@",temp);
        
        if ([[temp objectForKey:@"status"] isEqualToString:@"ADD_LIKE_1"]) {
            
             NSLog(@"Sucess: Add Like");
            
            
            
            NSManagedObjectContext *context = [[AppDelegate setDelegate] managedObjectContext];
            
            NSArray *array=[[[NSArray alloc]init]autorelease];
            // Construct a fetch request
            NSFetchRequest *fetchRequest = [[NSFetchRequest alloc] init];
            NSEntityDescription *entity = [NSEntityDescription entityForName:@"Photo"
                                                      inManagedObjectContext:context];
            [fetchRequest setEntity:entity];
            
            NSPredicate *predicate = [NSPredicate predicateWithFormat:@"photoid == %@",self.strPhotoId];
            [fetchRequest setPredicate:predicate];
            
            NSError *error = nil;
            array = [context executeFetchRequest:fetchRequest error:&error];
            
            Photo *objPhoto = [array objectAtIndex:0];
            //[objPhoto setNumberoflikes:[NSString stringWithFormat:@"%d",[lblNoLikes.text integerValue]+1]];
            objPhoto.numberoflikes =[NSString stringWithFormat:@"%d",[lblNoLikes.text integerValue]];
            objPhoto.isuserhasliked=@"1";
            
                    // Save everything
                    NSError *error1 = nil;
                    if ([context save:&error]) {
                        NSLog(@"The save was successful!");
                    } else {
                        NSLog(@"The save wasn't successful: %@", [error1 userInfo]);
                    }

            
            [[AppDelegate setDelegate] saveContext];
            
            //[self performSelector:@selector(addLikeSuccessCal) withObject:nil afterDelay:0.1];
            
        }else{
            
            lblNoLikes.text=[NSString stringWithFormat:@"%d",[lblNoLikes.text integerValue] -1];
            btnLike.enabled=TRUE;
            
            NSLog(@"Fail : Add Like");
        }
        
    } errorBlock:^(NSError *error) {
        
        UIAlertView *alert=[[UIAlertView alloc] initWithTitle:AppName message:@"The request timed out. Please try again later." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles: nil];
        [alert show];
        [alert release];
        
        NSLog(@"Error!");
    }];
}

-(void)addFlagOnPhotoWebservice
{
    NSMutableDictionary * headerDic = [NSMutableDictionary dictionary];
    [headerDic setObject:@"FlagPhoto" forKey:@"name"];
    NSMutableDictionary * bodyDic = [NSMutableDictionary dictionary];
    
    // Release the dateFormatter
    [bodyDic setValue:self.strPhotoId forKey:@"photoId"];
    [bodyDic setValue:[self.dictPhotoDetail objectForKey:@"createdUserid"] forKey:@"createduserId"];
    [bodyDic setValue:[AppDelegate setDelegate].strUserId forKey:@"loginUserId"];
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
        
        NSLog(@"temp : %@",temp);
        
        
        id object =[[temp objectForKey:@"data"] objectForKey:@"data"];
        [responseString release];
        
        if ([object isKindOfClass:[NSArray class]] || [object isKindOfClass:[NSMutableArray class]])
        {
        
            NSMutableArray *array=(NSMutableArray *)object;
            [self performSelector:@selector(addFlagSuccessCal:) withObject:[array objectAtIndex:0] afterDelay:0.1];
            
        }else{
            
            NSMutableDictionary *dict=(NSMutableDictionary *)object;
            [self performSelector:@selector(addFlagSuccessCal:) withObject:dict afterDelay:0.1];
        }
        
    } errorBlock:^(NSError *error) {
        
        UIAlertView *alert=[[UIAlertView alloc] initWithTitle:AppName message:@"The request timed out. Please try again later." delegate:nil cancelButtonTitle:@"OK" otherButtonTitles: nil];
        [alert show];
        [alert release];
        
        NSLog(@"Error!");
    }];
}

#pragma mark - Webservice success calls


-(void)getAdvertisementDetailSuccessCal:(NSMutableDictionary *)dict
{
    NSLog(@"self.dictPhotoDetail : %@",dict);
    self.dictPhotoDetail=dict;
    
    lblNoLikes.text=[NSString stringWithFormat:@"%@",[dict objectForKey:@"numberOfLikes"]];
    lblNoComments.text=[NSString stringWithFormat:@"%@",[dict objectForKey:@"NumberOfComments"]];
    
    NSMutableArray *arraTag=[[dict objectForKey:@"tags"] valueForKey:@"name"];
    
    NSString *strTags=@"";
    if (arraTag.count != 0) {
        
        for (int i=0; i<arraTag.count; i++) {
            
            NSString *strVal=[arraTag objectAtIndex:i];
            NSString *str1=@"#";
            
            if ([strVal length]) {
                
                NSString *Tags=[str1 stringByAppendingString:strVal];
                
                if (i < arraTag.count - 1) {
                    
                    Tags=[Tags stringByAppendingString:@","];
                }
                
                strTags = [strTags stringByAppendingString:Tags];
            }
        }
    }
    
    lbltags.text = [NSString stringWithFormat:@"%@",strTags];
    
    lbltags.layer.shadowColor = [[UIColor blackColor] CGColor];
    lbltags.layer.shadowOffset = CGSizeMake(0.0, 0.0);
    lbltags.layer.shadowRadius = 3.0;
    lbltags.layer.shadowOpacity = 0.8;

    strTags = [strTags stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceCharacterSet]];
    CGSize constraint = CGSizeMake(306 , 20000.0f);
    CGSize size = [strTags sizeWithFont:[UIFont fontWithName:@"American Typewriter" size:13] constrainedToSize:constraint lineBreakMode:NSLineBreakByWordWrapping];
    CGFloat height = MAX(size.height + 5, 25.0f);

   
    
    NSString *strImage=[NSString stringWithFormat:@"%@",[dict objectForKey:@"PhotoUrl"]];
    strImage=[ADVERTISEPHOTO_URL stringByAppendingString:strImage];
    
    //NSLog(@"strImage : %@",strImage);
    [imgViewAdvertise setImageWithURL:[NSURL URLWithString:strImage] placeholderImage:nil options:SDWebImageProgressiveDownload];
     lbltags.frame =CGRectMake( lbltags.frame.origin.x,  (imgViewAdvertise.frame.origin.y+imgViewAdvertise.frame.size.height)-height,  lbltags.frame.size.width,  height);
    
    [self performSelector:@selector(getAdvertisementLikeDetail) withObject:nil afterDelay:0.1];

}

-(void)getAdvertisementLikeDetailSuccessCal:(NSMutableArray *)arr
{
    
    arrLikeUsers = [[NSMutableArray alloc]init];
    for (int i = 0;i<[arr count];i++)
        [arrLikeUsers addObject:[[arr objectAtIndex:i]valueForKey:@"user_name"]];
    int paddingval = 16;
    int paddingvalLabel = paddingval+18;
    valxorigin = 2;
    
    for (int i = 0;i<[arrLikeUsers count];i++)
    {
        
        UIImageView *img = [[UIImageView alloc]initWithImage:[UIImage imageNamed:@"like_profile_icon.png"]];
        img.frame = CGRectMake(valxorigin+paddingval, 8, 14, 12);
        if(i == 0)
            img.frame = CGRectMake(0, 8, 14, 12);

        
        NSString *str = [arrLikeUsers objectAtIndex:i];
        CGSize textSize = [str sizeWithFont:[UIFont fontWithName:@"American Typewriter" size:13] constrainedToSize:CGSizeMake(9000, 20)
                           lineBreakMode:NSLineBreakByWordWrapping];
        UILabel *lbl = [[UILabel alloc]initWithFrame:CGRectMake(valxorigin+paddingvalLabel, 2, textSize.width, 20)];
          if(i == 0)
              lbl.frame = CGRectMake(18, 2, textSize.width, 20);
        lbl.font = [UIFont fontWithName:@"American Typewriter" size:13];
        lbl.textColor = [UIColor darkGrayColor];
        lbl.text  = [arrLikeUsers objectAtIndex:i];
        valxorigin =lbl.frame.origin.x+lbl.frame.size.width;
        [scrollLikeUsers addSubview:img];
        [scrollLikeUsers addSubview:lbl];
        [img release];
        [lbl release];
    }
    arrIndexCount = [arrLikeUsers count];
    scrollLikeUsers.contentSize = CGSizeMake(valxorigin, 24);
    if(scrollLikeUsers.contentSize.width > scrollLikeUsers.frame.size.width)
        scrollTimer = [NSTimer scheduledTimerWithTimeInterval:0.1 target:self selector:@selector(rotateScroleView) userInfo:nil repeats:YES];

    [self performSelector:@selector(getAllCommentsWebservice) withObject:nil afterDelay:0.1];

}
-(void)addFlagSuccessCal:(NSMutableArray *)array
{
    
}

-(void)addLikeSuccessCal
{
//    lblNoLikes.text=[NSString stringWithFormat:@"%d",[lblNoLikes.text integerValue]+1];
//    btnLike.enabled=FALSE;
}

-(void)addCommentSuccessCal:(NSMutableArray *)array
{
    [self performSelector:@selector(getAllCommentsWebservice) withObject:nil afterDelay:0.1];
}

-(void)getAllCommentsSuccessCal //:(NSMutableArray *)array
{
//    [self.arrayComments removeAllObjects];
//    self.arrayComments=[NSMutableArray arrayWithArray:array];
    
    scrollHeight=0;
    
    for (int i=0; i < self.arrayComments.count; i++) {
        
        NSMutableDictionary *temp=[self.arrayComments objectAtIndex:i];
        NSString *text =[temp objectForKey:@"CommentText"];
        text = [text stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceCharacterSet]];
        
        //                NSString *str=@"  ";
        //                text = [str stringByAppendingString:text];
        
        CGSize constraint = CGSizeMake(COMMENT_CONTENT_WIDTH - (COMMENT_MARGIN * 2), 20000.0f);
        CGSize size = [text sizeWithFont:[UIFont fontWithName:@"American Typewriter" size:13] constrainedToSize:constraint lineBreakMode:NSLineBreakByWordWrapping];
        CGFloat height = MAX(size.height + 40, 70.0f);
        scrollHeight=scrollHeight+height;
    }
    
    NSLog(@"scrollHeight : %f",scrollHeight);
    [self.tblViewComments setFrame:CGRectMake(self.tblViewComments.frame.origin.x, self.tblViewComments.frame.origin.y, self.tblViewComments.frame.size.width, scrollHeight)];
    
    scrollView.contentSize=CGSizeMake(320, self.tblViewComments.frame.origin.y + scrollHeight);
    
    
    NSLog(@"self.arrayComments : %@",self.arrayComments);
    [self.tblViewComments reloadData];
}

#pragma mark - UITableView delegate methods

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    //return [self.arrayComments count];
    
    if (isAddComment) {
    
        return [self.arrayComments count] +1 ;
        
    }else{
        
        return [self.arrayComments count];
    }
}

- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath
{
    if (isAddComment) {
        
        if (indexPath.row == self.arrayComments.count) {
        
            return 60;
        }
    }
   
    NSMutableDictionary *temp=[self.arrayComments objectAtIndex:indexPath.row];
    NSString *text =[temp objectForKey:@"CommentText"];
    text = [text stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceCharacterSet]];
    CGSize constraint = CGSizeMake(COMMENT_CONTENT_WIDTH - (COMMENT_MARGIN * 2), 20000.0f);
    CGSize size = [text sizeWithFont:[UIFont fontWithName:@"American Typewriter" size:13] constrainedToSize:constraint lineBreakMode:UILineBreakModeWordWrap];
    CGFloat height = MAX(size.height + 40, 70.0f);
    
    return height;
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    if (isAddComment) {
        
        NSLog(@"indexPath.row : %d",indexPath.row);
        
        if (indexPath.row == self.arrayComments.count) {
            
            static NSString *CellIdentifier =@"cellIdentifire";
            
            AddCommentCell *cell = [tableView dequeueReusableCellWithIdentifier:CellIdentifier];
            
            if (cell == nil)
            {
                
                NSArray *Objects = [[NSBundle mainBundle] loadNibNamed:@"AddCommentCell" owner:nil options:nil];
                
                for(id currentObject in Objects)
                {
                    if([currentObject isKindOfClass:[AddCommentCell class]])
                    {
                        cell = (AddCommentCell *)currentObject;
                        break;
                    }
                }
                
                cell.selectionStyle=UITableViewCellSelectionStyleNone;
            }
            
            self.txtViewComment=cell.txtViewComment;
            cell.txtViewComment.delegate=self;
            cell.txtViewComment.inputAccessoryView=toolbar;
            ((SSTextView *)cell.txtViewComment).placeholder=@"Write a comment...";
            
            NSData* imageData = [[NSUserDefaults standardUserDefaults] objectForKey:@"USER PIC"];
            UIImage *image= [UIImage imageWithData:imageData];
            cell.imgViewUserImage.image=image;
            
            return cell;
            
        }else{
            
            static NSString *CellIdentifier =@"cellIdentifire";
            
            UserCommentsCell *cell = [tableView dequeueReusableCellWithIdentifier:CellIdentifier];
            
            if (cell == nil)
            {
                
                NSArray *Objects = [[NSBundle mainBundle] loadNibNamed:@"UserCommentsCell" owner:nil options:nil];
                
                for(id currentObject in Objects)
                {
                    if([currentObject isKindOfClass:[UserCommentsCell class]])
                    {
                        cell = (UserCommentsCell *)currentObject;
                        break;
                    }
                }
                
                cell.selectionStyle=UITableViewCellSelectionStyleNone;
            }
            
            NSDictionary *dictionary = [self.arrayComments objectAtIndex:indexPath.row];
            
            NSString *strUserImage=[NSString stringWithFormat:@"%@",[dictionary objectForKey:@"photoUrl"]];
            strUserImage=[USERPHOTO_URL stringByAppendingString:strUserImage];
            //NSLog(@"strUserImage : %@",strUserImage);
            
            if ([[AppDelegate setDelegate].strUserId isEqualToString:[NSString stringWithFormat:@"%@",[dictionary objectForKey:@"Usreid"]]]) {
                
                NSData* imageData = [[NSUserDefaults standardUserDefaults] objectForKey:@"USER PIC"];
                UIImage *image= [UIImage imageWithData:imageData];
                cell.imgViewProfile.image=image;
                
            }else{
            
                [cell.imgViewProfile setImageWithURL:[NSURL URLWithString:strUserImage] placeholderImage:nil options:SDWebImageProgressiveDownload];
            }
            
            NSString *text =[NSString stringWithFormat:@"%@",[dictionary objectForKey:@"CommentText"]];
            text = [text stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceCharacterSet]];
            CGSize constraint = CGSizeMake(COMMENT_CONTENT_WIDTH - (COMMENT_MARGIN * 2), 20000.0f);
            CGSize size = [text sizeWithFont:[UIFont fontWithName:@"American Typewriter" size:13] constrainedToSize:constraint lineBreakMode:UILineBreakModeWordWrap];

            [cell.lblCommentText setFrame:CGRectMake(88, 11, 200, MAX(size.height+10, 21.0f))];
            cell.lblCommentText.text=text;
            [cell.lblCommentText sizeToFit];
            
            cell.lblCommentDate.text=[NSString stringWithFormat:@"%@",[dictionary objectForKey:@"createDate"]];
            cell.lblUserName.text=[NSString stringWithFormat:@"%@",[dictionary objectForKey:@"Username"]];
            
            return cell;
            
        }
        
    }else{
        
        static NSString *CellIdentifier =@"cellIdentifire";
        
        UserCommentsCell *cell = [tableView dequeueReusableCellWithIdentifier:CellIdentifier];
        
        if (cell == nil)
        {
            
            NSArray *Objects = [[NSBundle mainBundle] loadNibNamed:@"UserCommentsCell" owner:nil options:nil];
            
            for(id currentObject in Objects)
            {
                if([currentObject isKindOfClass:[UserCommentsCell class]])
                {
                    cell = (UserCommentsCell *)currentObject;
                    break;
                }
            }
            
            cell.selectionStyle=UITableViewCellSelectionStyleNone;
        }
        
        NSDictionary *dictionary = [self.arrayComments objectAtIndex:indexPath.row];
        
        NSString *strUserImage=[NSString stringWithFormat:@"%@",[dictionary objectForKey:@"photoUrl"]];
        strUserImage=[USERPHOTO_URL stringByAppendingString:strUserImage];
        //NSLog(@"strUserImage : %@",strUserImage);
        
        if ([[AppDelegate setDelegate].strUserId isEqualToString:[NSString stringWithFormat:@"%@",[dictionary objectForKey:@"Usreid"]]]) {
            
            NSData* imageData = [[NSUserDefaults standardUserDefaults] objectForKey:@"USER PIC"];
            UIImage *image= [UIImage imageWithData:imageData];
            cell.imgViewProfile.image=image;
            
        }else{
            
            [cell.imgViewProfile setImageWithURL:[NSURL URLWithString:strUserImage] placeholderImage:nil options:SDWebImageProgressiveDownload];
        }
        
        //[cell.imgViewProfile setImageWithURL:[NSURL URLWithString:strUserImage] placeholderImage:nil options:SDWebImageProgressiveDownload];
        
        NSString *text =[NSString stringWithFormat:@"%@",[dictionary objectForKey:@"CommentText"]];
        text = [text stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceCharacterSet]];
        CGSize constraint = CGSizeMake(COMMENT_CONTENT_WIDTH - (COMMENT_MARGIN * 2), 20000.0f);
        CGSize size = [text sizeWithFont:[UIFont fontWithName:@"American Typewriter" size:13] constrainedToSize:constraint lineBreakMode:UILineBreakModeWordWrap];
        
        [cell.lblCommentText setFrame:CGRectMake(88, 11, 200, MAX(size.height+10, 21.0f))];
        cell.lblCommentText.text=text;
        [cell.lblCommentText sizeToFit];
        
        cell.lblCommentDate.text=[NSString stringWithFormat:@"%@",[dictionary objectForKey:@"createDate"]];
        cell.lblUserName.text=[NSString stringWithFormat:@"%@",[dictionary objectForKey:@"Username"]];

        return cell;
    }
}

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
    
}

//#pragma mark - UINavigation Controller delegate
//
//- (void)navigationController:(UINavigationController *)navigationController willShowViewController:(UIViewController *)viewController animated:(BOOL)animated
//{
//    
//    [viewController viewWillAppear:animated];
//    //[viewController viewWillAppear:animated];
//}

#pragma mark - UIScrollView delegate methods


-(void)rotateScroleView
{
    scrollLikeUsers.contentOffset = CGPointMake(scrollLikeUsers.contentOffset.x+0.5,scrollLikeUsers.contentOffset.y);
    // NSLog(@"%f %f",scrView.contentOffset.x,scrView.contentSize.width);
    if (scrollLikeUsers.contentOffset.x+scrollLikeUsers.frame.size.width > scrollLikeUsers.contentSize.width)
    {
        UILabel *lbl = [[UILabel alloc]initWithFrame:CGRectMake(scrollLikeUsers.contentSize.width, 0, 60, 30)];
        CGFloat i =   fmod(scrollLikeUsers.contentSize.width/60, [arrLikeUsers count] );
        //  NSLog(@"%f ",i);
        
        lbl.text = [arrLikeUsers objectAtIndex:i];
        [scrollLikeUsers addSubview:lbl];
        scrollLikeUsers.contentSize = CGSizeMake(60+scrollLikeUsers.contentSize.width,20);
        
        
    }
    
}


-(void)scrollViewDidScroll:(UIScrollView *)scrollView
{
    
    int paddingval = 16;
    int paddingvalLabel = paddingval+18;
   // NSLog(@"scrView.contentOffset.x %f ",scrollLikeUsers.contentOffset.x);
    if (scrollLikeUsers.contentOffset.x+scrollLikeUsers.frame.size.width > scrollLikeUsers.contentSize.width)
    {
        
        UIImageView *img = [[UIImageView alloc]initWithImage:[UIImage imageNamed:@"like_profile_icon.png"]];
        img.frame = CGRectMake(valxorigin+paddingval, 8, 14, 12);
      
        arrIndexCount = arrIndexCount %[arrLikeUsers count];
        //CGFloat i =   fmod(scrollLikeUsers.contentSize.width/60, [arrLikeUsers count] );
        NSString *str = [arrLikeUsers objectAtIndex:arrIndexCount];
        CGSize textSize = [str sizeWithFont:[UIFont fontWithName:@"American Typewriter" size:13] constrainedToSize:CGSizeMake(9000, 20)
                              lineBreakMode:NSLineBreakByWordWrapping];
        UILabel *lbl = [[UILabel alloc]initWithFrame:CGRectMake(valxorigin+paddingvalLabel, 2, textSize.width, 20)];
       
        lbl.font = [UIFont fontWithName:@"American Typewriter" size:13];
        lbl.textColor = [UIColor darkGrayColor];
        lbl.text  = [arrLikeUsers objectAtIndex:arrIndexCount];
        valxorigin =lbl.frame.origin.x+lbl.frame.size.width;
        [scrollLikeUsers addSubview:img];
        [scrollLikeUsers addSubview:lbl];
        [img release];
        [lbl release];

        arrIndexCount++;

        scrollLikeUsers.contentSize = CGSizeMake(lbl.frame.size.width+scrollLikeUsers.contentSize.width,20);
    }
    if (scrollLikeUsers.contentOffset.x <= 320 && isTapped)
    {
        scrollLikeUsers.contentOffset = CGPointMake(scrollLikeUsers.contentOffset.x+originalScrollContent,scrollLikeUsers.contentOffset.y);
    }
}

-(void)scrollViewDidEndDecelerating:(UIScrollView *)scrollView
{
    if(scrollLikeUsers.contentSize.width > scrollLikeUsers.frame.size.width)
        scrollTimer = [NSTimer scheduledTimerWithTimeInterval:0.1 target:self selector:@selector(rotateScroleView) userInfo:nil repeats:YES];
    isTapped =FALSE;
    
}


#pragma mark - UITextView delegate methods

- (void)textViewDidBeginEditing:(UITextView *)textView
{
}
- (void)textViewDidEndEditing:(UITextView *)textView
{
}

- (BOOL)textView:(UITextView *)textView shouldChangeTextInRange:(NSRange)range replacementText:(NSString *)text
{
    return YES;
}

@end
