<?php


    include_once './EntityClassLib.php';
    include_once './functions/ValidationFunctions.php';
    include_once './functions/DbFunctions.php';
    
    session_start();
    
    if (!isset($_SESSION['user']))
    {
        header("Location: Index.php");
        exit();
    }
    
    $user = $_SESSION['user'];
    $userId = $user->getUserId();
    $friendIdErr = "";
    $friendRequestConfirmMsg = "";
    $friendId = "";
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_POST['btnSubmitFriendRequest'])) {
            $friendId = trim($_POST['friendId']);
            
            $friendIdErr = validateLoginUId($friendId);
            
            // check if user is sending friend request to himself
            if (!$friendIdErr) {
                if (strcmp($friendId, $userId) == 0 ) {
                    $friendIdErr = "You cannot send a friend request to yourself!";
                }    
            }
            
            
            if (!$friendIdErr) {
                // check if entered friend ID exists in the system
                $friend = userIdExists($friendId);
                if ($friend == null) {
                    $friendIdErr = "The ID you entered does not exist.";
                } else {
                    // check if there is a pending friend request from entered ID to user
                    $fId = $friend->getUserId();
                    $friendIdErr = "You sent friend request for ".$friend->getName();
                    $friendRequesters = getFriendRequestersFor($userId);
                    $friendIdErr = $friendIdErr.".<br/>".$friend->getName()." has ".count($friendRequesters)." friend requests.";
                    
                    if ($friendRequesters > 0 && in_array($friendRequesters, $fId)) {
                        acceptFriendRequest($userId, $fId);
                        $friendRequestConfirmMsg = "You and ".$friend->getName()."are now friends.<br />"
                                . "You are now able to view each others shared albums.";
                        $friendId = "";
                    } else {
                        sendFriendRequest($userId, $fId);
                        $friendRequestConfirmMsg = "Your request has been sent to ".$friend->getName()." (ID: ".$fId." ).<br />"
                                . "Once ".$friend->getName()." accepts your request, you and ".$friend->getName()."<br />"
                                . "will be friends and be able to view each others shared albums.";
                        $friendId = "";
                    }
                }    
            }
            
        }
    }
    
?>

<?php include_once './src/Header.php'; ?>

<main class="container m-5">
    <h1 class="text-center">Add Friend</h1>
    <p>Welcome <b><?php echo $user->getName() ?></b>! (not you? Change User <a href='Logout.php'>here</a>)</p>
    <p>Enter the ID of the user you want to be friend with</p>
    <br/>
    <div class="col-sm-8 text-danger">
        <?php echo $friendRequestConfirmMsg?>
    </div>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
        
        <div class="row mt-3 d-flex align-items-center">
            <div class="col-md-1">
                <label class="form-label fw-semibold">ID: </label>
            </div>
            <div class="col-md-3">
                <input type="text" name="friendId" class="form-control" value="<?php print $friendId; ?>" />
            </div>
            <div class="col-md-2">
                <button type="submit" name="btnSubmitFriendRequest" class="btn btn-outline-primary my-2">Send Friend Request</button>
            </div>
            
        </div>  
    </form>
    <div class="col-sm-8 text-danger">
        <?php echo $friendIdErr?>
    </div>
    
</main>

<?php include_once './src/Footer.php'; ?>