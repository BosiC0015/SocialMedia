<?php

    include_once './EntityClassLib.php';
    include_once './functions/ValidationFunctions.php';
    include_once './functions/DbFunctions.php';
    include_once './functions/ImageFunctions.php';

    
    session_start();
    
    if (!isset($_SESSION['user'])) {
        $_SESSION['page'] = "uploadPictures";
        header("Location: Login.php");
        exit();
    }
    
    $user = $_SESSION['user'];
    $uid = $user->getUserId();
    
    // get albums
    $albumsArr = getAlbumsById($uid);
    
    //define constants for convenience
    define("ORIGINAL_IMAGE_DESTINATION", "./original_pics"); 

    define("IMAGE_DESTINATION", "./images"); 
    define("IMAGE_MAX_WIDTH", 800);
    define("IMAGE_MAX_HEIGHT", 600);

    define("THUMB_DESTINATION", "./thumbnail_imgs");  
    define("THUMB_MAX_WIDTH", 100);
    define("THUMB_MAX_HEIGHT", 100);

    //Use an array to hold supported image types for convenience
    $supportedImageTypes = array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG);

    if (isset($_POST['btnUpload'])) {
        $albumId = $_POST['uploadAlbum'];
        $title = trim($_POST['title']);
        $desc = trim($_POST['desc']);
        
        $uploadCount = count($_FILES['upload']['name']);
        for ($n = 0; $n < $uploadCount; $n++) {
            $imgName = $_FILES['upload']['name'][$n];
            if ($_FILES['upload']['error'][$n] == 0) {
                $filePath = save_uploaded_file(ORIGINAL_IMAGE_DESTINATION, $n);

                $imageDetails = getimagesize($filePath);

                if ($imageDetails && in_array($imageDetails[2], $supportedImageTypes)) {
                    resamplePicture($filePath, IMAGE_DESTINATION, IMAGE_MAX_WIDTH, IMAGE_MAX_HEIGHT);
                    resamplePicture($filePath, THUMB_DESTINATION, THUMB_MAX_WIDTH, THUMB_MAX_HEIGHT);

                    // update database
                    addPictures($albumId, $imgName, $title, $desc);
                }
                else {
                    $error = "Uploaded file is not a supported type"; 
                    unlink($filePath);
                }
            }
            elseif ($_FILES['upload']['error'][$n] == 1) {
                $error = "Upload file is too large"; 
            }
            elseif ($_FILES['upload']['error'][$n] == 4) {
                $error = "No upload file specified"; 
            }
            else {
                $error  = "Error happened while uploading the file. Try again late"; 
            }
        }
    }
    
?>

<?php include_once './src/Header.php'; ?>
<main class="container m-5">
    <h1 class="text-center">Upload Pictures</h1>
    <p>Accepted picture types: JPEG, GIF, and PNG</p>
    <span class="text-danger"><?php print $error;?></span>
    
    <form method="post" action="UploadPictures.php" enctype="multipart/form-data">
        <div class="row form-group my-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Upload to Album:</label>
            </div>
            <div class="col-md-4"> 
                <select name="uploadAlbum" class="form-control">
                    <option value="-1">Select Album...</option>
                    <?php
                        foreach ($albumsArr as $album) {
                            $aid = $album->getAlbumId();
                            $aTitle = $album->getTitle();
                            
                            print "<option value=\"$aid\">$aTitle</option>";
                        }
                    ?>
                </select>
            </div> 
        </div>
        <div class="row form-group my-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold">File to Upload:</label>
            </div>
            <div class="col-md-4"> 
                <input type="file" name="upload[]" id= "upload" class="form-control" multiple />
            </div> 
        </div>
        <div class="row form-group my-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Title:</label>
            </div>
            <div class="col-md-4"> 
              <input type="text" name="title" id= "title" class="form-control"/>
            </div> 
        </div>
        <div class="row form-group my-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Description:</label>
            </div>
            <div class="col-md-4"> 
                <textarea name="desc" id= "desc" class="form-control" rows="4"></textarea>
            </div> 
        </div>
        
        <!--buttons-->
        <div class="row form-group my-3 offset-1">
            <div class="col-md-3"> 
               <input type="submit" name="btnUpload" value="Upload" class="btn btn-primary"/>
            </div>
            <div class="col-md-3">
               <input type="reset" name="btnReset" value="Reset" class="btn btn-secondary"/>
            </div>
        </div>
     </div>
   </form> 
</main>
<?php include_once './src/Footer.php'; ?>
