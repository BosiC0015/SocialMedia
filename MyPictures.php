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
    
    
?>

<?php include_once './src/Header.php'; ?>
<main class="container m-5">
    <h1 class="text-center">My Pictures</h1>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
        <div class="row col-sm-12">
            <div class="col-sm-3">
                <?php
                    $selectauid = "-1";
                    $allAlbums = getAlbumsById($user->getUserId());
                    $selectout = "";
                    if(isset($_GET['aid'])) 
                    {
                        $selectauid = $_GET['aid'];
                    }
                    
                    foreach($allAlbums as $album)					
                    { 
                        
                        
                        if(!strcmp($selectauid, $album->getAlbumId()))
                        {
                            $selectout = $selectout . "<option value=\"{$album->getAlbumId()}\" selected>$album->Title</option>";

                        }
                        else {
                           $selectout = $selectout . "<option value=\"{$album->getAlbumId()}\">$album->Title</option>";
                        }
                    }
                    print<<<selectedAlbum
                        <select name="selAlbum" class="form-control">
                        $selectout
                        </select>

                    selectedAlbum;
                ?>
            </div>        
        </div>
    </form>
</main>

<?php include_once './src/Footer.php'; ?>