//
//  MyImageView.h
//  Aviary
//
//  Created by MAC8 on 7/26/13.
//  Copyright (c) 2013 Riddham. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface MyImageView : UIView
{
    UIImageView *_imageView;
}

@property (nonatomic, assign) UIImage *image;

- (void)setImageWithUrl:(NSString *)strImageUrl;

@end