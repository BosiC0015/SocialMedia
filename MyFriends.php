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
    
    $friendsList = getFriendsList($user->getUserId());
    $friendRequests = getFriendRequestersFor($user->getUserId());
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
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
                if (count($friendsList) > 0)
                {
                    echo "<table style='width: 100%; margin-top: 40px; margin-bottom: 40px;'>";
                        echo "<tr style='border-top: 1px solid grey; border-bottom: 1px solid grey; height: 30px;'>";
                            echo "<th>Name</th>";
                            echo "<th>Shared Albums</th>";
                            echo "<th>Defriend</th>";
                        echo "</tr>";
                        
                        
                    echo "</table>";
                }
                else
                {
                    echo "<div><i> You do not have any friends yet.</i></div>";
                }
                ?>
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
                            $requester = userIdExists($request->getFriendsId());
                            echo "<tr style='border-top: 1px solid grey; border-bottom: 1px solid grey; height: 30px;'>";
                                echo "<td>".$requester->getName()."</th>";
                                echo "<td></th>";
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
    
    </form>
    
    
</main>

<?php include_once './src/Footer.php'; ?>
