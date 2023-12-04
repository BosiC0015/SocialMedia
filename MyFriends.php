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
    
    // get list of friends of user
    $friendsList = array();
                    
    $friendRequestsReceivedAccepted = getFriendRequestsReceivedAccepted($user->getUserId(), "accepted");
    $friendRequestsSentAccepted = getFriendRequestsSentAccepted($user->getUserId(), "accepted");
     
    // get list of friend requests
    $friendRequests = getFriendRequestersFor($user->getUserId());
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["btnSubmitDefriend"])) {
            $defriendSelected = $_POST["defriendSelected"];
            if (count($defriendSelected) > 0) {
                foreach($defriendSelected as $friendId) {
                    deleteFriend($userId, $friendId);
                }
                
            }
        }
        
        if (isset($_POST["btnSubmitAccept"])) {
            $requestsSelected = $_POST["requestsSelected"];
            if (count($requestsSelected) > 0) {
                foreach($requestsSelected as $requestFriendId) {
                    acceptFriendRequest($userId, $requestFriendId);
                }
                
            }
        }
        
        if (isset($_POST["btnSubmitDeny"])) {
            $requestsSelected = $_POST["requestsSelected"];
            if (count($requestsSelected) > 0) {
                foreach($requestsSelected as $requestFriendId) {
                    denyFriendRequest($userId, $requestFriendId);
                }
                
            }
        }
        
        // get list of friends of user
        $friendsList = array();

        $friendRequestsReceivedAccepted = getFriendRequestsReceivedAccepted($user->getUserId(), "accepted");
        $friendRequestsSentAccepted = getFriendRequestsSentAccepted($user->getUserId(), "accepted");

        // get list of friend requests
        $friendRequests = getFriendRequestersFor($user->getUserId());
    }
    
    

?>

<?php include_once './src/Header.php'; ?>

<main class="container m-5">
    <h1 class="text-center">My Friends</h1>
    <p>Welcome <b><?php echo $user->getName() ?></b>! (not you? Change User <a href='Logout.php'>here</a>)</p>  
    
    <form name="formFriendsList" id="formFriendsList" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
        <div class="row">
            <div class="col-sm-2 offset-sm-1">
                Friends:
            </div>
            <div class="col-sm-2 offset-sm-5">
                <a href='/SocialMedia/AddFriend.php'>Add Friends</a>
            </div>
        </div>
        
        <div class="row">
            <div class="col-sm-10 offset-sm-1">
                <?php 
                if (count($friendRequestsReceivedAccepted) > 0 || count($friendRequestsSentAccepted) > 0)
                {
                    echo "<table style='width: 100%; margin-top: 10px; margin-bottom: 40px;'>";
                        echo "<tr style='border-top: 1px solid grey; border-bottom: 1px solid grey; height: 30px;'>";
                            echo "<th>Name</th>";
                            echo "<th>Shared Albums</th>";
                            echo "<th>Defriend</th>";
                        echo "</tr>";
                        
                        if (count($friendRequestsReceivedAccepted) > 0) {
                            foreach($friendRequestsReceivedAccepted as $a) {
                                $aId = $a->getFriend_RequesterId();
                                $aFriend = userIdExists($aId);
                                $sharedAlbums = getSharedAlbums($aId);
                                echo "<tr style='border-top: 1px solid grey; border-bottom: 1px solid grey; height: 30px;'>";
                                    echo "<td><a href='FriendPictures.php'>".$aFriend->getName()."</a></th>";
                                    echo "<td>".count($sharedAlbums)."</td>";
                                    echo "<td><input type='checkbox' name='defriendSelected[]' value='$aId'/></td>";
                                echo "</tr>";
                            }
                        }
                        
                        if (count($friendRequestsSentAccepted) > 0) {
                            foreach($friendRequestsSentAccepted as $b) {
                                $bId = $b->getFriend_RequesteeId();
                                $bFriend = userIdExists($bId);
                                $sharedAlbums = getSharedAlbums($bId);
                                echo "<tr style='border-top: 1px solid grey; border-bottom: 1px solid grey; height: 30px;'>";
                                    echo "<td><a href='FriendPictures.php'>".$bFriend->getName()."</a></td>";
                                    echo "<td>".count($sharedAlbums)."</td>";
                                    echo "<td><input type='checkbox' name='defriendSelected[]' value='$bId'/></td>";
                                echo "</tr>";
                            }
                        }
           
                    echo "</table>";
                }
                else
                {
                    echo "<div><i> You do not have any friends yet.</i></div>";
                }
                ?>
            </div>
        </div>
        
        <div class="row">
            <div class="col-sm-2 offset-sm-8">
                <button type="submit" name="btnSubmitDefriend" class="btn btn-outline-primary my-2">Defriend Selected</button>
            </div>
            
            
        </div>
    
    </form>
    
    <br/>
    <br/>
    <form name="formFriendRequests" id="formFriendRequests" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
        <div class="row">
            <div class="col-sm-2 offset-sm-1">
                Friends Requests:
            </div>
            
        </div>
        
        <div class="row">
            <div class="col-sm-10 offset-sm-1">
                <?php 
                if (count($friendRequests) > 0)
                {
                    echo "<table style='width: 100%; margin-top: 10px; margin-bottom: 40px;'>";
                        echo "<tr style='border-top: 1px solid grey; border-bottom: 1px solid grey; height: 30px;'>";
                            echo "<th>Name</th>";
                            echo "<th>Accept or Deny</th>";
                        echo "</tr>";
                        
                        foreach ($friendRequests as $request) {
                            $requester = userIdExists($request->getFriend_RequesterId());
                            $rId = $requester->getUserId();
                            echo "<tr style='border-top: 1px solid grey; border-bottom: 1px solid grey; height: 30px;'>";
                                echo "<td>".$requester->getName()."</td>";
                                echo "<td><input type='checkbox' name='requestsSelected[]' value='$rId'/></td>";
                            echo "</tr>";
                        }
                        
                        
                        
                    echo "</table>";
                }
                else
                {
                    echo "<div><i>You do not have any friend requests.</i></div>";
                }
                ?>
            </div>
        </div>
        
        <div class="row">
            <div class="col-sm-2 offset-sm-6">
                <button type="submit" name="btnSubmitAccept" class="btn btn-outline-primary my-2">Accept Selected</button>
            </div>
            
            <div class="col-sm-2">
                <button type="submit" name="btnSubmitDeny" class="btn btn-outline-primary my-2">Deny Selected</button>
            </div>
        </div>
            
        
    
    </form>
    
    
</main>

<?php include_once './src/Footer.php'; ?>
