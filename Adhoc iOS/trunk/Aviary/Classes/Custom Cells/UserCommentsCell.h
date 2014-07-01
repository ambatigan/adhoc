//
//  UserCommentsCell.h
//  Aviary
//
//  Created by MAC8 on 7/18/13.
//  Copyright (c) 2013 Riddham. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface UserCommentsCell : UITableViewCell
{
    UIImageView *imgViewProfile;
    UILabel *lblCommentText,*lblUserName,*lblCommentDate;
}

@property(nonatomic,retain) IBOutlet UIImageView *imgViewProfile;
@property(nonatomic,retain) IBOutlet UILabel *lblCommentText,*lblUserName,*lblCommentDate;
@end
