//
//  AddCommentCell.h
//  Aviary
//
//  Created by MAC8 on 7/18/13.
//  Copyright (c) 2013 Riddham. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface AddCommentCell : UITableViewCell<UITextViewDelegate>
{
    UIImageView *imgViewUserImage;
    UITextView *txtViewComment;
}

@property(nonatomic,retain) IBOutlet UIImageView *imgViewUserImage;
@property(nonatomic,retain) IBOutlet UITextView *txtViewComment;
@end
