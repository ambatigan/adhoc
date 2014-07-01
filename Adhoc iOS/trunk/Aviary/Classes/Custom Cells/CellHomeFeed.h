//
//  CellHomeFeed.h
//  Aviary
//
//  Created by MAC8 on 7/12/13.
//  Copyright (c) 2013 Riddham. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface CellHomeFeed : UITableViewCell
{
    UIImageView *imgAdvertiseMent,*imgViewBG,*imgViewProfile,*imgViewCommentIcon,*imgViewTagIcon;
    UILabel *lblTimeAgo,*lblLikes,*lblComments,*lblTags;
    UIButton *btnUserName,*btnLike;
    UIView *viewCellBotom;
}

@property(nonatomic,retain) IBOutlet UIView *viewCellBotom;
@property(nonatomic,retain) IBOutlet UIImageView *imgAdvertiseMent,*imgViewBG,*imgViewProfile,*imgViewCommentIcon,*imgViewTagIcon;
@property(nonatomic,retain) IBOutlet UILabel *lblTimeAgo,*lblLikes,*lblComments,*lblTags;
@property(nonatomic,retain) IBOutlet UIButton *btnUserName,*btnLike;

@end
