//
//  MyImageView.m
//  Aviary
//
//  Created by MAC8 on 7/26/13.
//  Copyright (c) 2013 Riddham. All rights reserved.
//

#import "MyImageView.h"
#import "UIImageView+WebCache.h"

@implementation MyImageView

@dynamic image;

- (id)initWithFrame:(CGRect)frame
{
    self = [super initWithFrame:frame];
    if (self) {
        self.clipsToBounds = YES;
        _imageView = [[UIImageView alloc] initWithFrame:self.bounds];
        _imageView.contentMode = UIViewContentModeScaleAspectFill;
        [self addSubview:_imageView];
    }
    return self;
}

- (id)initWithImage:(UIImage *)anImage
{
    self = [self initWithFrame:CGRectZero];
    if (self) {
        _imageView.image = anImage;
        [_imageView sizeToFit];
        
        // initialize frame to be same size as imageView
        self.frame = _imageView.bounds;
    }
    return self;
}

- (id)initWithImageUrl:(NSString *)strimgUrlStr
{
    self = [self initWithFrame:CGRectZero];
    if (self) {
        
        [_imageView setImageWithURL:[NSURL URLWithString:strimgUrlStr] placeholderImage:nil options:SDWebImageProgressiveDownload]; // .image = anImage;
        [_imageView sizeToFit];
        
        // initialize frame to be same size as imageView
        self.frame = _imageView.bounds;
    }
    return self;
}

- (void)dealloc
{
    [_imageView release];
    [super dealloc];
}

- (UIImage *)image
{
    return _imageView.image;
}

- (void)setImage:(UIImage *)anImage
{
    _imageView.image = anImage;
    [self setNeedsLayout];
}

- (void)setImageWithUrl:(NSString *)strImageUrl
{
    [_imageView setImageWithURL:[NSURL URLWithString:strImageUrl] placeholderImage:nil options:SDWebImageProgressiveDownload]; // .image = anImage;
    [_imageView sizeToFit];
    
    //[self setNeedsLayout];
}

- (void)layoutSubviews
{
    // compute scale factor for imageView
    CGFloat widthScaleFactor = CGRectGetWidth(self.bounds) / self.image.size.width;
    CGFloat heightScaleFactor = CGRectGetHeight(self.bounds) / self.image.size.height;
    
    CGFloat imageViewXOrigin = 0;
    CGFloat imageViewYOrigin = 0;
    CGFloat imageViewWidth;
    CGFloat imageViewHeight;
    
    
    // if image is narrow and tall, scale to width and align vertically to the top
    if (widthScaleFactor > heightScaleFactor) {
        imageViewWidth = self.image.size.width * widthScaleFactor;
        imageViewHeight = self.image.size.height * widthScaleFactor;
    }
    
    // else if image is wide and short, scale to height and align horizontally centered
    else {
        imageViewWidth = self.image.size.width * heightScaleFactor;
        imageViewHeight = self.image.size.height * heightScaleFactor;
        imageViewXOrigin = - (imageViewWidth - CGRectGetWidth(self.bounds))/2;
    }
    
    _imageView.frame = CGRectMake(imageViewXOrigin,
                                  imageViewYOrigin,
                                  imageViewWidth,
                                  imageViewHeight);
}

- (void)setFrame:(CGRect)frame
{
    [super setFrame:frame];
    [self setNeedsLayout];
}

@end