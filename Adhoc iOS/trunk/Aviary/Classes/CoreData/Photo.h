//
//  Photo.h
//  Aviary
//
//  Created by MAC8 on 8/5/13.
//  Copyright (c) 2013 Riddham. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreData/CoreData.h>				


@interface Photo : NSManagedObject

@property (nonatomic, retain) NSString * photoid;
@property (nonatomic, retain) NSString * photourl;
@property (nonatomic, retain) NSString * createduserid;
@property (nonatomic, retain) NSString * createdusername;
@property (nonatomic, retain) NSString * profileimage;
@property (nonatomic, retain) NSString * numberoflikes;
@property (nonatomic, retain) NSString * numberofcomments;
@property (nonatomic, retain) NSString * createddate;
@property (nonatomic, retain) NSString * createdon;
@property (nonatomic, retain) NSString * isuserhasliked;
@property (nonatomic, retain) NSSet *tags;
@end

@interface Photo (CoreDataGeneratedAccessors)
				
- (void)addTagsObject:(NSManagedObject *)value;
- (void)removeTagsObject:(NSManagedObject *)value;
- (void)addTags:(NSSet *)values;
- (void)removeTags:(NSSet *)values;

@end
