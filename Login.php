<?php

    include_once './EntityClassLib.php';
    include_once './functions/ValidationFunctions.php';
    include_once './functions/DbFunctions.php';
    
    session_start();
    
    // Added for Windows
    $uid = $pw = $uidErr =  $pwErr = $loginErr = '';
   
    if (isset($_SESSION['user'])) {
        header("Location: MyAlbums.php");
        exit();
    }

    if(isset($_POST['btnSubmit'])) {

        $uid = trim($_POST['uid']);
        $pw = trim($_POST['pw']);
        $pwHashed = hash("sha256", $pw);
        $loginErr = "";
        
        $uidErr = validateLoginUId($uid);
        $pwErr = validateLoginPw($pw);
        
          
        if (!($uidErr || $pwErr)) {
            $user = getUserByIdAndPassword($uid, $pwHashed);

            if ($user == null) {
                $loginErr = '<div class="row mt-3 d-flex align-items-center text-danger">Incorrect user ID and / or password</div>';
            } else {
                $_SESSION['user'] = $user;
                
                switch ($_SESSION['page']) {
                    case "addAlbum":
                        header("Location: AddAlbum.php");
                        exit();
                    case "addFriend":
                        header("Location: AddFriend.php");
                        exit();
                    case "friendPictures":
                        header("Location: FriendPictures.php");
                        exit();
                    case "myAlbums":
                        header("Location: MyAlbums.php");
                        exit();
                    case "myFriends":
                        header("Location: MyFriends.php");
                        exit();
                    case "myPictures":
                        header("Location: MyPictures.php");
                        exit();
                    case "uploadPictures":
                        header("Location: UploadPictures.php");
                        exit();
                    default:
                        header("Location: MyAlbums.php");
                        exit();
                }
            }
        }
    }
?>

<?php include_once './src/Header.php'; ?>
<main class="container m-5">
    <h1 class="text-center">Log In</h1>
    <p>You need to <a href="NewUser.php">sign up</a> if you are a new user.</p>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
        <!--user id-->
        <div class="row mt-3 d-flex align-items-center">
            <div class="col-md-3">
                <label class="form-label fw-semibold">User ID: </label>
            </div>
            <div class="col-md-3">
                <input type="text" name="uid" class="form-control" value="<?php print $uid; ?>" />
            </div>
            <?php print $uidErr; ?>
        </div>
        <!--password-->
        <div class="row mt-3 d-flex align-items-center">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Password: </label>
            </div>
            <div class="col-md-3">
                <input type="password" name="pw" class="form-control" value="<?php print $pw; ?>" />
            </div>
            <?php print $pwErr; ?>
        </div>
        <?php print $loginErr; ?>
        <!--buttons-->
        <div class="row mt-4 offset-1">
            <div class="col-md-3">
                <button type="submit" name="btnSubmit" class="btn btn-outline-primary my-2">Submit</button>
            </div>          
            <div class="col-md-3">
                <button type="reset" name="btnClear" class="btn btn-outline-primary my-2">Clear</button>
            </div> 
        </div>
    </form>
</main>
<?php include_once './src/Footer.php'; ?>
