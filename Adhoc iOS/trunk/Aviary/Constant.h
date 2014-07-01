//
//  Constant.h
//  Aviary
//
//  Created by Nidhi on 01/07/13.
//  Copyright (c) 2013 Riddham. All rights reserved.
//

#ifndef Aviary_Constant_h
#define Aviary_Constant_h

#define IS_IPHONE (UI_USER_INTERFACE_IDIOM() == UIUserInterfaceIdiomPhone)
#define IS_IPHONE_5 (IS_IPHONE && [[UIScreen mainScreen] bounds].size.height == 568.0f)

#define NAVIGATIONIOS7  64
#define NAVIGATION  44

//#define WEBSERVICE @"http://115.119.122.98/aviary_app_final/services/webservice.php"
//#define ADVERTISEPHOTO_URL @"http://115.119.122.98/aviary_app_final/uploads/photo_image/"
//#define USERPHOTO_URL @"http://115.119.122.98/aviary_app_final/uploads/user_image/"
//#define SHARING_URL @"http://115.119.122.98/aviary_app_final/front/?photo_id="

//#define WEBSERVICE @"http://216.120.250.188/mobile/aviary_app_final/services/webservice.php"
//#define ADVERTISEPHOTO_URL @"http://216.120.250.188/mobile/aviary_app_final/uploads/photo_image/"
//#define USERPHOTO_URL @"http://216.120.250.188/mobile/aviary_app_final/uploads/user_image/"

//#define WEBSERVICE @"http://ec2-54-221-222-67.compute-1.amazonaws.com/services/webservice.php"
//#define ADVERTISEPHOTO_URL @"http://ec2-54-221-222-67.compute-1.amazonaws.com/uploads/photo_image/"
//#define USERPHOTO_URL @"http://ec2-54-221-222-67.compute-1.amazonaws.com/uploads/user_image/"
//#define SHARING_URL @"http://ec2-54-221-222-67.compute-1.amazonaws.com/front/?photo_id="
//#define SHARING_URL @"http://www.adhoc-theapp.com/front/?photo_id="


#define WEBSERVICE @"http://198.162.18.128:8081/services/webservice.php"
#define ADVERTISEPHOTO_URL @"http://198.162.18.128:8081/uploads/photo_image/"
#define USERPHOTO_URL @"http://198.162.18.128:8081/uploads/user_image/"
#define SHARING_URL @"http://198.162.18.128:8081/front/?photo_id="



//#define WEBSERVICE @"http://192.168.10.16/aviary_app_final/services/webservice.php"
//#define ADVERTISEPHOTO_URL @"http://192.168.10.16/aviary_app_final/uploads/photo_image/"
//#define USERPHOTO_URL @"http://192.168.10.16/aviary_app_final/uploads/user_image/"
//#define SHARING_URL @"http://192.168.10.16/aviary_app_final/front/?photo_id="


#define AppName @"AdHoc"

#endif
