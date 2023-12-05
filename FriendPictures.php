<?php

    include_once './EntityClassLib.php';
    include_once './functions/ValidationFunctions.php';
    include_once './functions/DbFunctions.php';
    
    session_start();
    
    if (!isset($_SESSION['user']))
    {
        $_SESSION['page'] = "friendPictures";
        header("Location: Login.php");
        exit();
    }
    
    $user = $_SESSION['user'];
    $uid = $user->getUserId();
    
    $albumErr = "";
    $commentErr = "";
    
    
    // get friend's id from query string
    if(isset($_GET['fid'])) 
    {
        $fId = $_GET['fid'];  
        
    } else {
        header("Location: MyFriends.php");
        exit();
    }

    // get shared albums from friend
    $albumsArr = getAlbumsSharedFrom($fId);
    $selectedAId = "";
    $selectedFriendName = userIdExists($fId)->getName();
    $displayPic = null;
    
     //select album
    if (isset($_SESSION['aFid'])) {
        $selectedAId = $_SESSION['aFid'];
    }
    
    if (isset($_POST['btnAlbum'])) {
        $_SESSION['displayFriendPic'] = null;
        $selectedAId = $_POST['album'];
        $albumErr = validateAlbum($selectedAId);
        
        if (empty($albumErr)) {
            $_SESSION['aFid'] = $selectedAId;
        }
    } else {
        $selectedAId = $_SESSION['aFid'];
    }
    
    // get pictures in album
    if (!empty($selectedAId)) {
        $picturesArr = getPicturesInAlbum($selectedAId);
        $selectedFriendName = getNameByAId($selectedAId);
    } else {
        $picturesArr = array();
    }
    
    // add comment
    if (isset($_POST['btnAddComment'])) {
        $commentText = trim($_POST['comment']);
        $pictureId = $_SESSION['displayFriendPic']->getPictureId();
        $commentErr = validateCommentText($commentText);
        
        if (empty($commentErr)) {
            addComment($uid, $pictureId, $commentText);
            header("Location: FriendPictures.php?fid=$fId");
            exit();
        }
    }
    
    // get large picture to be displayed
    if (isset($_SESSION['displayFriendPic'])) {
        $displayPic = $_SESSION['displayFriendPic'];
    } else {
        $displayPic = $picturesArr[0];
        $_SESSION['displayFriendPic'] = $picturesArr[0];
    }
    
    if (isset($_POST['btnPic'])) {
        if (isset($_SESSION['displayFriendPic'])) {
        $displayPic = $_SESSION['displayFriendPic'];
    } else {
        $displayPic = $picturesArr[0];
        $_SESSION['displayFriendPic'] = $picturesArr[0];
    }
        if (isset($_POST['selectedPic'])) {
            if ($_SESSION['displayFriendPic'] != null) {
                $pid = $_POST['selectedPic'];
                $displayPic = getPictureById($pid);
                $_SESSION['displayFriendPic'] = $displayPic;
            }
        }
    }
    
?>

<?php include_once './src/Header.php'; ?>
<main class="container m-5">
    <h1 class="text-center"><?php print $selectedFriendName; ?>'s Shared Pictures</h1>
    <form method="post" action="FriendPictures.php?fid=<?php print $fId; ?>" class="">
        <!--select album part-->
        <div class="col-md-10 mb-3">
            <select name="album" id="album-list" class="form-control">
                <option value="-1">Select Album...</option>
                <?php
                    foreach ($albumsArr as $album) {
                        $aFid = $album->getAlbumId();
                        $aTitle = $album->getTitle();

                        if ($aFid == $selectedAId) {
                            print "<option value=\"$aFid\" selected>$aTitle</option>";
                        } else {
                            print "<option value=\"$aFid\">$aTitle</option>";
                        }
                    }
                ?>
            </select>
            <?php print $albumErr; ?>
            <div>
                <button type="submit" name="btnAlbum" id="select-album" class="d-none"></button>
            </div>
        </div>
        <!--display pictures part-->
        <div>
            <!--large picture-->
            <div>
                <?php
                    if ($displayPic == null) {
                        print "<h5 class=\"text-danger\">Your album is empty</h5>";
                    } else {
                        $displayTitle = $displayPic->getTitle();
                        $displayFileName = $displayPic->getFileName();
                        $displayDesc = $displayPic->getDescription();
                        $displayPId = $displayPic->getPictureId();
                        
                        print <<<image
                            <h3 class="text-center">$displayTitle</h3>
                            <div class="d-flex flex-row">
                                <div class="col-md-8">
                                    <img src="./images/$displayFileName" alt="$displayTitle" class="mw-100 mh-100" />
                                    <!--thumbnails-->
                                    <div class="d-flex align-items-center overflow-x-auto" id="thumbnails">
                        image;
                        
                        
                                        foreach ($picturesArr as $pic) {       

                                            $picThumbnail = $pic->getFileName();
                                            $picTitle = $pic->getTitle();
                                            $picId = $pic->getPictureId();                    

                                            if ($displayPic->getPictureId() == $picId) {
                                                print <<<thumbnail
                                                    <label class="border border-3 border-primary">
                                                        <input type="radio" name="selectedPic" value="$picId" class="d-none" checked /> 
                                                        <img src="./thumbnail_imgs/$picThumbnail" alt="$picTitle" id="$picId" />
                                                    </label>
                                                thumbnail;
                                            } else {
                                                print <<<thumbnail
                                                    <label class="border border-3">
                                                        <input type="radio" name="selectedPic" value="$picId" class="d-none" checked /> 
                                                        <img src="./thumbnail_imgs/$picThumbnail" alt="$picTitle" id="$picId" />
                                                    </label>
                                                thumbnail;
                                            }
                                        }
                        print <<<closing
                                    </div>
                                </div>
                                <div class="col-md-4 offset-1">
                                    <h5>Description: </h5>
                                    <p>$displayDesc</p>
                                    <h5>Comments: </h5>
                        closing;
                        
                        // show comments
                        $commentsArr = getCommentsForPic($displayPId);
                        
                        foreach ($commentsArr as $comment) {
                            $author = $comment->getAuthorName();
                            $text = $comment->getCommentText();
                            
                            print <<<comments
                                <div class="d-inline">
                                    <p><span class="text-info">$author: </span>$text</p>
                                </div>
                            comments;
                        }
                        
                        // add comments
                        print $commentErr;
                        print <<<closing
                                <textarea name="comment" class="form-control my-3" rows="4" placeholder="Leave a comment here..."></textarea>
                                <div class="my-3">
                                    <button type="submit" name="btnAddComment" class="btn btn-primary">Add Comment</button>
                                </div>
                        
                                </div>
                            </div>
                        closing;
                    }
                ?>
            </div>
            
            <div>
                <button type="submit" name="btnPic" id="select-picture" class="d-none"></button>
            </div>
        </div>
    </form>
</main>

<script>
    const albumList = document.getElementById('album-list');
    const selectAlbumBtn = document.getElementById('select-album');
    const thumbnails = document.getElementById('thumbnails');
    const selectPicBtn = document.getElementById('select-picture');

    albumList.addEventListener('change', () => selectAlbumBtn.click());
    
    thumbnails.addEventListener('click', () => selectPicBtn.click());
</script>

<?php include_once './src/Footer.php'; ?>