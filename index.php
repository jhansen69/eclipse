<?php
error_reporting(E_ALL && E_ERROR);
include("includes/functions_page.php");
page_header();
page();
page_footer();


function page()
{
    
    $myForm=new jhForm();
    $myForm->debug=false;
    $myForm->addText('First Name','first_name','Enter your first name','',array('required'=>'true'));
    $myForm->addText('Last Name','last_name','Enter your last name','',array('required'=>'true'));
    $myForm->addText('Email','email','Email address','This is some sample helper text',array('fieldtype'=>'email','required'=>'true'));
    $myForm->dbTable='users';
    $myForm->listFields=array('first_name','last_name','email');
    echo $myForm->render();   
}

function imageTest()
{
    require 'includes/Zebra_Image.php';

    // create a new instance of the class
    $image = new Zebra_Image();

    // indicate a source image (a GIF, PNG or JPEG file)
    $image->source_path = 'artwork/avatars/test1.png';

    // indicate a target image
    // note that there's no extra property to set in order to specify the target 
    // image's type -simply by writing '.jpg' as extension will instruct the script 
    // to create a 'jpg' file
    $image->target_path = 'artwork/avatars/image.jpg';

    // since in this example we're going to have a jpeg file, let's set the output 
    // image's quality
    $image->jpeg_quality = 100;

    // some additional properties that can be set
    // read about them in the documentation
    $image->preserve_aspect_ratio = true;
    $image->enlarge_smaller_images = true;
    $image->preserve_time = true;

    // resize the image to exactly 100x100 pixels by using the "crop from center" method
    // (read more in the overview section or in the documentation)
    //  and if there is an error, check what the error is about
    if (!$image->resize(100, 100, ZEBRA_IMAGE_CROP_CENTER)) {

        // if there was an error, let's see what the error is about
        switch ($image->error) {

            case 1:
                echo 'Source file could not be found!';
                break;
            case 2:
                echo 'Source file is not readable!';
                break;
            case 3:
                echo 'Could not write target file!';
                break;
            case 4:
                echo 'Unsupported source file format!';
                break;
            case 5:
                echo 'Unsupported target file format!';
                break;
            case 6:
                echo 'GD library version does not support target file format!';
                break;
            case 7:
                echo 'GD library is not installed!';
                break;

        }

    // if no errors
    } else {

        echo 'Success!';

    }
}
?>