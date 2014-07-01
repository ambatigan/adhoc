//
//  UserCommentsCell.m
//  Aviary
//
//  Created by MAC8 on 7/18/13.
//  Copyright (c) 2013 Riddham. All rights reserved.
//

#import "UserCommentsCell.h"

@implementation UserCommentsCell

@synthesize imgViewProfile,lblCommentText,lblUserName,lblCommentDate;

- (id)initWithStyle:(UITableViewCellStyle)style reuseIdentifier:(NSString *)reuseIdentifier
{
    self = [super initWithStyle:style reuseIdentifier:reuseIdentifier];
    if (self) {
        // Initialization code
    }
    return self;
}

- (void)setSelected:(BOOL)selected animated:(BOOL)animated
{
    [super setSelected:selected animated:animated];

    // Configure the view for the selected state
}

@end
