//
//  MainViewController.m
//  Aviary
//
//  Created by MAC8 on 8/26/13.
//  Copyright (c) 2013 Riddham. All rights reserved.
//

#import "MainViewController.h"

@interface MainViewController ()

@end

@implementation MainViewController

@synthesize baseView;
@synthesize btnFeed,btnCreateAdv,btnProfile;
//@synthesize navControllerFeed,navControllerProfile,navControllerNewAdv;
//@synthesize objHome,objProfile;
@synthesize navControllerHome,navControllerProfile;

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
    
//    if (IS_IPHONE_5) {
//        
//        self.baseView.frame=CGRectMake(0, 0, 320, 498);
//        
//    }else{
//        
//        self.baseView.frame=CGRectMake(0, 0, 320, 410);
//    }
    
    //self.navigationController.delegate=self;
    
    
    
    HomeViewController *objHome=[[HomeViewController alloc]initWithNibName:@"HomeViewController" bundle:[NSBundle mainBundle]];
    self.navControllerHome=[[UINavigationController alloc]initWithRootViewController:objHome];
    self.navControllerHome.navigationBarHidden=YES;
    
   
    //self.navControllerHome.view.frame = CGRectMake(0, self.baseView.frame.origin.y,self.baseView.frame.size.width, self.baseView.frame.size.height);
    //[objHome release];
    //self.objHome.navController=self.navigationController;
    //self.objHome.navController.delegate=self;
    
    ProfileViewController *objProfile=[[ProfileViewController alloc]initWithNibName:@"ProfileViewController" bundle:[NSBundle mainBundle]];
    self.navControllerProfile=[[UINavigationController alloc]initWithRootViewController:objProfile];
    self.navControllerProfile.navigationBarHidden=YES;
    //self.navControllerProfile.view.frame = CGRectMake(0, self.baseView.frame.origin.y,self.baseView.frame.size.width, self.baseView.frame.size.height);

    //[objProfile release];
    //self.objProfile.navController=self.navigationController;
    //self.objProfile.navController.delegate=self;
    
    if([AppDelegate setDelegate].isIOS7)
        
    {
        UIImageView *img = [[UIImageView alloc]initWithFrame:CGRectMake(0, 0, 320, 20)];
        img.image = [UIImage imageNamed:@"topbar_staus.png"];
        [self.view addSubview:img];
        [img release];
        
        if (IS_IPHONE_5)
        {
            self.navControllerProfile.view.frame  = CGRectMake(0, 10, self.navControllerProfile.view.frame.size.width, self.navControllerProfile.view.frame.size.height-50);
            self.navControllerHome.view.frame  = CGRectMake(0, 20, self.navControllerHome.view.frame.size.width, self.navControllerHome.view.frame.size.height-50);
        }
        else
        {
            self.navControllerProfile.view.frame  = CGRectMake(0, 10, self.navControllerProfile.view.frame.size.width, self.navControllerProfile.view.frame.size.height-30);
            self.navControllerHome.view.frame  = CGRectMake(0, 20, self.navControllerHome.view.frame.size.width, self.navControllerHome.view.frame.size.height+30);
        }

    }
    else
    {
        if (IS_IPHONE_5)
        {
            self.navControllerProfile.view.frame  = CGRectMake(0, -20, self.navControllerProfile.view.frame.size.width, self.navControllerProfile.view.frame.size.height);
            self.navControllerHome.view.frame  = CGRectMake(0, -20, self.navControllerHome.view.frame.size.width, self.navControllerHome.view.frame.size.height);
        }
        else
        {
            self.navControllerProfile.view.frame  = CGRectMake(0, -20, self.navControllerProfile.view.frame.size.width, self.navControllerProfile.view.frame.size.height);
            self.navControllerHome.view.frame  = CGRectMake(0, -20, self.navControllerHome.view.frame.size.width, self.navControllerHome.view.frame.size.height);
        }

    }
    [self.baseView addSubview:self.navControllerHome.view];
    if([AppDelegate setDelegate].mainViewController == nil)
    {
        [AppDelegate setDelegate].mainViewController = self;
    }
    //self.navControllerHome.view.frame = CGRectOffset(self.navControllerHome.view.frame, 0.0, 0.0);
    
    
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

-(IBAction)btnFeedClicked:(id)sender
{
    [AppDelegate setDelegate].isPresentedView=YES;
    [self.navControllerProfile.view removeFromSuperview];
    
    //self.navControllerHome.view.frame = CGRectMake(0, 0,self.baseView.frame.size.width, self.baseView.frame.size.height);
    
    [self.baseView addSubview:self.navControllerHome.view];
    //[self.objHome viewWillAppear:YES];
    
    self.btnFeed.selected=YES;
    self.btnProfile.selected=NO;
}

-(IBAction)btnCreateAdvClicked:(id)sender
{
    NewEditAdvertismentViewController *obj=[[NewEditAdvertismentViewController alloc]initWithNibName:@"NewEditAdvertismentViewController" bundle:[NSBundle mainBundle]];
    obj.isEditPhoto=NO;
    UINavigationController *navController=[[UINavigationController alloc]initWithRootViewController:obj];
    navController.navigationBarHidden=YES;
    //[obj release];
    [self presentViewController:navController animated:YES completion:nil];
    
}

-(IBAction)btnProfileClicked:(id)sender
{
    
    [AppDelegate setDelegate].isLoginUser=YES;
    [AppDelegate setDelegate].isPresentedView=NO;
    
    [self.navControllerHome.view removeFromSuperview];
    [self.baseView addSubview:self.navControllerProfile.view];
    [self.view bringSubviewToFront:self.navControllerProfile.view];
    self.btnFeed.selected=NO;
    self.btnProfile.selected=YES;
}



@end
