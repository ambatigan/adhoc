//
//  User.h
//  Aviary
//
//  Created by MAC8 on 8/5/13.
//  Copyright (c) 2013 Riddham. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreData/CoreData.h>


@interface User : NSManagedObject

@property (nonatomic, retain) NSString * useid;
@property (nonatomic, retain) NSString * username;
@property (nonatomic, retain) NSString * firstname;
@property (nonatomic, retain) NSString * lastname;
@property (nonatomic, retain) NSString * profilephoto;
@property (nonatomic, retain) NSString * numberofpost;
@property (nonatomic, retain) NSString * numberoflikes;
@property (nonatomic, retain) NSString * userrank;
@property (nonatomic, retain) NSString * hometown;
@property (nonatomic, retain) NSSet *userTags;
@end

@interface User (CoreDataGeneratedAccessors)

- (void)addUserTagsObject:(NSManagedObject *)value;
- (void)removeUserTagsObject:(NSManagedObject *)value;
- (void)addUserTags:(NSSet *)values;
- (void)removeUserTags:(NSSet *)values;

@end
