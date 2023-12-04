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
                    
                    // get list of pending friend request of user
                    $fId = $friend->getUserId();
                    
                    $friendRequesters = getFriendRequestersFor($userId);
                    
                    
                    // get list of friends of user
                    
                    $friends = array();
                    
                    $friendRequestsReceivedAccepted = getFriendRequestsReceivedAccepted($userId, "accepted");
                    if (!empty($friendRequestsReceivedAccepted)) { array_push($friends, $friendRequestsReceivedAccepted); }
                    
                    $friendRequestsSentAccepted = getFriendRequestsSentAccepted($userId, "accepted");
                    if (!empty($friendRequestsSentAccepted)) { array_push($friends, $friendRequestsSentAccepted); }
                    
                    $statusSet = false;
                    
                    
                    
                    if (count($friendRequesters) > 0) {
                        // check if there is a pending friend request from entered ID to user
                        for($i=0; $i<count($friendRequesters); $i++) {
                            if (strcmp($friendRequesters[$i]->getFriend_RequesterId(), $fId) == 0) {
                                acceptFriendRequest($userId, $fId);
                                $friendRequestConfirmMsg = "You and ".$friend->getName()."are now friends.<br />"
                                        . "You are now able to view each others shared albums.";
                                $friendId = "";
                                $statusSet = true;
                                break;
                            }
                        }
                    }
                    
                    // check if entered ID is already friends with user
                    if (!$statusSet && count($friends) > 0) {
                        $friendIdErr = $friendIdErr."<br/>DEBUG HERE...";
                        if (count($friendRequestsReceivedAccepted) > 0) {
                            foreach($friendRequestsReceivedAccepted as $a) {
                                if (strcmp($a->getFriend_RequesterId(), $fId) == 0) {
                                    $friendRequestConfirmMsg = "You and ".$friend->getName()."(friend requester) are already friends.<br />"
                                            . "You are able to view each others shared albums.";
                                    $friendId = "";
                                    $statusSet = true;
                                    break;
                                }
                            }
                        }
                        
                        if (!$statusSet && count($friendRequestsSentAccepted) > 0) {
                            foreach ($friendRequestsSentAccepted as $b) {
                                if (strcmp($b->getFriend_RequesteeId(), $fId) == 0) {
                                    $friendRequestConfirmMsg = "You (requester) and ".$friend->getName()." are already friends.<br />"
                                            . "You are able to view each others shared albums.";
                                    $friendId = "";
                                    $statusSet = true;
                                    break;
                                }
                            }
                        }
                        
                    }
                    
                    if (!$statusSet) {
                        sendFriendRequest($userId, $fId);
                        $friendRequestConfirmMsg = "Your request has been sent to ".$friend->getName()." (ID: ".$fId." ).<br />"
                                . "Once ".$friend->getName()." accepts your request, you and ".$friend->getName()."<br />"
                                . "will be friends and be able to view each others shared albums.";
                        $friendId = "";
                        $statusSet = true;
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