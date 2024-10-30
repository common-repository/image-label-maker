=== Image Label Maker ===
Contributors: Mostafa Shahiri
Donate link: https://github.com/mostafa272/Image-Label-Maker
Tags: watermark, images, watermark image, label, png images, jpg image, transparent png, transparency, shortcode, photo
Requires at least: 3.6.1
Tested up to: 4.8.x
Stable tag: 3.4.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The Image Label Maker is a simple plugin to merge images together and creates a new image with label or watermark.

== Description ==

The Image Label Maker is a simple plugin to merge images together and creates a new image with label or watermark. It works with PNG and JPG images and you can add an
image as label image to another image (main image). This plugin has very flexible options that enables you to generate images with watermark or label.
In admin panel, you can control:

= 1) access level for authorized users or all visitors =
= 2) uploading images size =
= 3) asking question to prevent bots =
= 4) storing time for created images =

In frontend input form, users can control and customize:

= 1) place of label image =
= 2) distance of label image from corners =
= 3) amounts of transparency for label image =
= 4) type of output image (jpg or png) =

**If your label image is a PNG file with transparent background, you must select the "Label image is a png file with transparent background" option to merge images in a
proper way.**

When the output images are created, they are placed in image-label-maker directory in wordpress uploads directory and after a certain time based on plugin options, they will be removed automatically.

The name of output images are created randomly to prevent any conflict. 


To use this plugin, after activation of the plugin, you should place [image_label_maker_form] shortcode in your posts.

== Installation ==

Upload the Image Label Maker plugin to your blog, Activate it.Then place [image_label_maker_form] shortcode in your posts to load it.


== Screenshots ==

1. frontend of plugin (input form and it's fields)
2. Image Label Maker plugin setting page in admin panel


== Changelog ==

= 1.0 =
First release
