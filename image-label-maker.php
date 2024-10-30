<?php
/*
Plugin Name: Image Label Maker
Plugin URI: https://github.com/mostafa272/Image-Label-Maker
Description: The Image Label Maker is a simple plugin to merge images together and creates a new image with label or watermark.
Version: 1.0
Author: Mostafa Shahiri<mostafa2134@gmail.com>
Author URI: https://github.com/mostafa272
*/
/*  Copyright 2009  Mostafa Shahiri(email : mostafa2134@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
add_action('admin_menu', 'image_label_maker_setup_menu');
add_action( 'wp_enqueue_scripts', 'image_label_maker_scripts' );
add_action('admin_init', 'image_label_maker_register_settings');
add_action('init', 'image_label_maker_delete_images');
function image_label_maker_setup_menu(){
        add_menu_page( 'Image Label Maker Plugin', 'Image Label Maker', 'manage_options', 'image-label-maker', 'image_label_maker_init' );
}
function image_label_maker_register_settings(){

    register_setting('image-label-maker-settings', 'access_imglbl');
     register_setting('image-label-maker-settings', 'filesize_imglbl');
     register_setting('image-label-maker-settings', 'question_imglbl');
     register_setting('image-label-maker-settings', 'deletetime_imglbl');
}
function image_label_maker_init(){
if(current_user_can('manage_options'))
{ ?>
        <h1>Image Label Maker Setting</h1>
        <form  method="post" action="options.php" >
        <?php settings_fields( 'image-label-maker-settings' ); ?>
    <?php do_settings_sections( 'image-label-maker-settings' ); ?>
    <table class="form-table">
    <tbody>
    <tr><th scope="row"><label>Access level:</label></th>
    <td> <input type="radio" id="access_imglbl" style="margin-left:20px;" name="access_imglbl" value="1" <?php checked( 1, get_option( 'access_imglbl' ) ); ?>/>Authorized Users
 <input type="radio" id="access_imglbl" name="access_imglbl" style="margin-left:20px;" value="0" <?php checked( 0, get_option( 'access_imglbl' ) ); ?>/>All Visitors</td></tr>
 <tr><th scope="row"><label>Show Question:</label></th>
    <td> <input type="radio" id="question_imglbl" style="margin-left:20px;" name="question_imglbl" value="1" <?php checked( 1, get_option( 'question_imglbl' ) ); ?>/>Yes
 <input type="radio" id="question_imglbl" name="question_imglbl" style="margin-left:20px;" value="0" <?php checked( 0, get_option( 'question_imglbl' ) ); ?>/>No</td></tr>
  <tr><th scope="row"><label>Limit File Size:</label></th>
    <td> <input type="text" name="filesize_imglbl" value="<?php echo empty(get_option('filesize_imglbl'))?'1024':sanitize_text_field(intval(get_option('filesize_imglbl')));  ?>"/> KB</td></tr>
      <tr><th scope="row"><label>Delete created files after:</label></th>
    <td> <input type="text" name="deletetime_imglbl" value="<?php echo empty(get_option('deletetime_imglbl'))?'60':sanitize_text_field(intval(get_option('deletetime_imglbl')));  ?>"/> Mins</td></tr>
               <tr><td><?php submit_button('Save') ?>  </td></tr>
                </tbody>
                </table>
        </form>
<?php }
      else {
      echo "You don't have enough permission";
      }
}
/**
 * Add our JS and CSS files
 */
function image_label_maker_scripts() {
        wp_enqueue_style( 'imagelabelmaker-style', plugins_url( 'css/style.css', __FILE__ ) );
}
//frontend form for users to use this plugin
function imglbl_maker_html_form_code($show_res='') {
  $access=1;
  if(get_option('access_imglbl')==1)
  {
  $access=0;
  }
  if(current_user_can('read',get_the_ID))
  {
  $access=1;
  }

  if($access==1)
  {
    if(empty($show_res))
    {
	echo '<form class="imagelabelmaker" action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post" enctype="multipart/form-data">';
	echo '<p>';
	echo '<label>Main Image</label> <small>(jpg,jpeg,png|Max size:'.intval(get_option('filesize_imglbl')).' KB|required)</small><br/>';
	echo '<input type="file" name="img-main" accept=".jpg,.jpeg,.png"  required/>';
	echo '</p>';
	echo '<p>';
	echo '<label>Label Image</label> <small>(jpg,jpeg,png|Max size:'.intval(get_option('filesize_imglbl')).' KB|required)</small> <br/>';
   	echo '<input type="file" name="img-label" accept=".jpg,.jpeg,.png"  required/>';
	echo '</p>';
    echo '<p><input type="checkbox" name="trans-png" value="1">  Label Image is a png file with transparent background</p>';
	echo '<p>';
	echo '<label>Place label at</label><br/>';
	echo '<select name="img-position" />';
    echo '<option value="0" selected>Center</option>';
    echo '<option value="1">Top-left corner</option>';
    echo '<option value="2">Bottom-left corner</option>';
    echo '<option value="3">Top-right corner</option>';
    echo '<option value="4">Bottom-right corner</option>';
    echo '</select>';
	echo '</p>';
    echo '<p>';
	echo '<label>Distance of label image from corners</label><br/>';
	echo '<input type="number" value="10" min="0" step="1" name="img-margin" required/>';
	echo '</p>';
    echo '<p>';
	echo '<label>Label image transparency</label><br/>';
	echo '<input type="number" value="50" min="0" step="1" max="100" name="img-trans" required/>';
	echo '</p>';
	echo '<p>';
	echo '<label>Output Type</label><br/>';
	echo '<input type="radio" value="0" name="img-type" checked/>jpg';
    echo '<input type="radio" value="1" name="img-type" />png';
	echo '</p>';
    if(get_option('question_imglbl')==1)
    { //asking a question to prevent bots access
    $num1=rand(21,80);
    $num2=rand(1,20);
    $sign=rand(0,1);
    if($sign==0){
    $result=$num1+$num2;
    $sign_string='+';
    }
    else{
    $result=$num1-$num2;
    $sign_string='-';
     }
    echo '<label>Answer:</label>  '.$num1.' '.$sign_string.' '.$num2.' ? <br/>';
    echo '<input type="text" name="img-answer" pattern="[0-9]{1,3}" title="invalid value" required/>';
	echo '</p>';
    echo '<input type="hidden" name="img-human" value="'.$result.'"/>';
    }
     wp_nonce_field( 'image_label_maker_upload', 'image_label_maker_upload_nonce' );
	echo '<p><input type="submit" name="label-maker-submitted" value="Generate"></p>';
	echo '</form>';
    }
      else
      {
        echo $show_res;
        echo '<br/>';
        echo '<form action="'.esc_url($_SERVER['REQUEST_URI']).'" method="post"><input type="submit" class="backbtn" name="backbutton" value="Back" /></form>';
      }
    }
    else
    {
    echo '<p class="imglblerror">You do not have enough permission to access this item</p>';
    }
}

function image_label_maker_apply() {
         if ( ! function_exists( 'wp_handle_upload' ) ) {
           require_once( ABSPATH . 'wp-admin/includes/file.php' );
        }
     $show_res='';
	// if the submit button is clicked, send the email
	if ( isset($_POST['label-maker-submitted']) && isset($_POST['image_label_maker_upload_nonce']) && wp_verify_nonce($_POST['image_label_maker_upload_nonce'], 'image_label_maker_upload')){

    if(get_option('question_imglbl')==1) //valid user(human) check is set
    { $answer=intval($_POST['img-answer']);
      $result=intval($_POST['img-human']);
    if(isset($_POST['img-answer']) && ($answer==$result))//check true result and answer
      $pass=1;
      else
      $pass=0;
    }
    else{
    $pass=1;
    }
     if($pass==1)
     {
     //set values
        $filesize=intval(get_option('filesize_imglbl'));
        $filesize=$filesize*1024;
        $output_type='jpg';
        $transpng=(isset($_POST['trans-png']) && $_POST['trans-png']=='1')?1:0;
        $margin=(empty($_POST['img-margin']))?0:intval($_POST['img-margin']);
        $trans=(empty($_POST['img-trans']))?100:intval($_POST['img-trans']);
        $position=(empty($_POST['img-position']))?0:intval($_POST['img-position']);
        //checking images size
         if(isset($_FILES['img-main']) && ($_FILES['img-main']['size']<$filesize)&& isset($_FILES['img-label']) && ($_FILES['img-label']['size']<$filesize)) {
            //set output type
           if(isset($_POST['img-type']))
            {
             if(intval($_POST['img-type'])==0)
             $output_type='jpg';
             elseif(intval($_POST['img-type'])==1)
             $output_type='png';
            }
          // Get the type of the uploaded file. This is returned as "type/extension"
         $main_file_type = wp_check_filetype(basename($_FILES['img-main']['name']));
         $label_file_type = wp_check_filetype(basename($_FILES['img-label']['name']));
         $main_type = $main_file_type['type'];
         $label_type = $label_file_type['type'];
          // Set an array containing a list of acceptable formats
         $allowed_file_types = array('image/jpg','image/jpeg','image/png');
                    // If the uploaded file is the right format
         if(in_array($main_type, $allowed_file_types) && in_array($label_type, $allowed_file_types)) {
         $mainfile=$_FILES['img-main'];
         $labelfile=$_FILES['img-label'];

         // Options array for the wp_handle_upload function. 'test_upload' => false
         $upload_overrides = array( 'test_form' => false,'unique_filename_callback' => 'imglbl_maker_filename' );
         // Handle the upload using WP's wp_handle_upload function. Takes the posted file and an options array
         $up_main_file = wp_handle_upload($mainfile, $upload_overrides);
         $up_label_file = wp_handle_upload($labelfile, $upload_overrides);
                        // If the wp_handle_upload call returned a local path for the image
         if(isset($up_main_file['file']) && isset($up_label_file['file'])) {
          //path to files
         $main_image = $up_main_file['file'];
         $label_image = $up_label_file['file'];
         //create images in memory based on type
         if($main_type=='image/jpg' or $main_type=='image/jpeg')
         {
         $dest = imagecreatefromjpeg($main_image);
         }
         if($main_type=='image/png')
         {
         $dest = imagecreatefrompng($main_image);
         }
         if($label_type=='image/jpg' or $label_type=='image/jpeg')
         {
         $src = imagecreatefromjpeg($label_image);
         }
         if($label_type=='image/png')
         {
         $src = imagecreatefrompng($label_image);
         }
         //height and width of label and main images
          $w1 = imagesx ($src);
          $h1 = imagesy ($src);
          $w2 = imagesx ($dest);
           $h2 = imagesy ($dest);
         if($position==0)  //center
            {
               $dest_x = ( $w2 / 2 ) - ( $w1 / 2 );
               $dest_y = ( $h2 / 2 ) - ( $h1 / 2 );
            }
            elseif($position==1) //top-left corner
            { $dest_x=$margin;
             $dest_y=$margin;
            }
            elseif($position==2) //top-bottom corner
            { $dest_x=$margin;
             $dest_y=$h2-($margin+$h1);
            }
            elseif($position==3)  //top-right corner
            { $dest_x=$w2-($margin+$w1);
                $dest_y=$margin;
            }
            elseif($position==4) //bottom-right corner
            { $dest_x=$w2-($margin+$w1);
              $dest_y=$h2-($margin+$h1);
            }
        //using a different way for transparent PNG images- using imagecopy instead of imagecopymerge
        if($transpng==1)
        {
        imagealphablending($dest,true);
        imagesavealpha($dest,true);
        imagealphablending($src,false);
        imagesavealpha($src,true);
        $opacity=1-($trans/100);
        imagefilter($src, IMG_FILTER_COLORIZE, 0,0,0,127*$opacity);
        imagecopy($dest, $src,$dest_x, $dest_y, 0, 0, $w1, $h1);
        }
        else
        {
        imagealphablending($dest,false);
        imagesavealpha($dest,true);
        imagecopymerge($dest, $src,$dest_x, $dest_y, 0, 0, $w1, $h1,$trans);
        }
         //create a directory to store created images
         $up_dir=wp_upload_dir();
         wp_mkdir_p(trailingslashit($up_dir['basedir'].'/image-label-maker'));
         //using random numbers for images names
         $r1=rand(1,1000);
         $r2=rand(1,1000);
           if ( is_user_logged_in() ) {
         $user = wp_get_current_user();
         $fname=$user->user_login.'_'.$r1.'_'.$r2;
         }
         else
         { $fname='public_'.$r1.'_'.$r2;
         }
          //create images based on types
         if($output_type=='jpg')
         {
         imagejpeg($dest,trailingslashit($up_dir['basedir'].'/image-label-maker').$fname.'.jpg');
           $show_res='<img  src="'.trailingslashit($up_dir['baseurl'].'/image-label-maker').$fname.'.jpg"/>';

         }
         elseif($output_type=='png')
         {
          imagepng($dest,trailingslashit($up_dir['basedir']).$fname.'.png');
          $show_res='<img src="'.trailingslashit($up_dir['baseurl']).$fname.'.png"/><br/>';

         }
         //removing images from memory and host space
         imagedestroy($dest);
        imagedestroy($src);
       wp_delete_file($main_image);
        wp_delete_file($label_image);

        }
        else
        {
          echo '<p class="imglblerror">The files were not uploaded correctly</p>';
        }
      }
      else
      {
      echo '<p class="imglblerror">Sorry, This type of files are not supported</p>';
      }
	}
    else
    {
     echo '<p class="imglblerror">Error: Images size is large</p>';
    }
   }
   else
   echo '<p class="imglblerror">Please enter the correct answer!</p>';
   }
 imglbl_maker_html_form_code($show_res);
}
//rename existing filenames with random numbers
function imglbl_maker_filename($dir, $name, $ext){
    return $name.'_'.rand(1,100).$ext;
}
//delete created files after certain time to save host space
function image_label_maker_delete_images(){
$del_time=(empty(get_option('deletetime_imglbl')))?60:intval(get_option('deletetime_imglbl'));
$del_time=$del_time*60;
  $up_dir=wp_upload_dir();
  $maindir=trailingslashit($up_dir['basedir'].'/image-label-maker');
  if(is_dir($maindir))
  {
   $files=glob($maindir."*.{jpg,png}", GLOB_BRACE);
   foreach($files as $file)
   if ((time()-filectime($file)) > $del_time)
    {
     wp_delete_file($file);
     }
  }
}
//load functions that are used in shortcode
function image_label_maker_makeshortcode() {

  ob_start();
  image_label_maker_apply();
  return ob_get_clean();

}
add_shortcode('image_label_maker_form','image_label_maker_makeshortcode');
