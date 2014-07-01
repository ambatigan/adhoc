//
//  AddCommentCell.m
//  Aviary
//
//  Created by MAC8 on 7/18/13.
//  Copyright (c) 2013 Riddham. All rights reserved.
//

#import "AddCommentCell.h"

@implementation AddCommentCell
@synthesize imgViewUserImage,txtViewComment;

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
