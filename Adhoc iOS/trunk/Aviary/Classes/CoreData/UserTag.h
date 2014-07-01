//
//  UserTag.h
//  Aviary
//
//  Created by MAC8 on 8/5/13.
//  Copyright (c) 2013 Riddham. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreData/CoreData.h>

@class User;

@interface UserTag : NSManagedObject

@property (nonatomic, retain) NSString * tagid;
@property (nonatomic, retain) NSString * name;
@property (nonatomic, retain) User *user;

@end
