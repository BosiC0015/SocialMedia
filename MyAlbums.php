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
    
    $ac = $picture = '';
    
    
    if(isset($_GET['aid'])) 
    {
        deletePictures($_GET['aid']);
        deleteAlbum($_GET['aid']);
        header("Location: MyAlbums.php");
    }
    
    if(isset($_POST['btnSaveChanges']))
    {   
        $albums = array();
        
        foreach($_POST['selAccessCodes'] as $ai)
        {
            $split = explode(' ', $ai, 2);
            //echo 'DEBUG: ' . $split[0] . $split[1];
            saveMyAlbumsChanges($split[0], $split[1]);
        }
        
        header("Location: MyAlbums.php");
    }
    $allAlbums = getAlbumsById($user->getUserId());
?>

<?php include_once './src/Header.php'; ?>
<main class="container m-5">
    <h1 class="text-center">My Albums</h1>
    <p>Welcome <b><?php echo $user->getName() ?></b>! (not you? Change User <a href='Logout.php'>here</a>)</p>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
        <div class="row col-sm-12">
            <div class="col-sm-2 offset-sm-10">
                <a href='/SocialMedia/AddAlbum.php'>Create a New Album</a>
            </div>
        </div>
        <div class="row col-sm-12 pb-3">
            <table class="table table-sm col-sm-12">
                <thead>
                  <tr>
                    <th scope="col">Title</th>
                    <th scope="col">Number of Pictures</th>
                    <th scope="col">Accessibility</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody>
                    <?php

                        foreach($allAlbums as $album)					
                        {   
                           $accessibility = getDescByAccessCode($album->getAccessibilityCode());
                           $accessCodes = getAllAccessCodes();

                           $ctPictures = (int)getCtPictures($album->getAlbumId());

                           $selAccessCode = $accessibility->getAccessibilityCode();

                           $selectout = "";
                            foreach($accessCodes as $accessCode)
                            {
                                if(!strcmp($selAccessCode, $accessCode['Accessibility_Code']))
                                {
                                    $selectout = $selectout . "<option value=\"{$album->getAlbumId()} {$accessCode['Accessibility_Code']}\" selected>{$accessCode['Description']}</option>";

                                }
                                else {
                                   $selectout = $selectout . "<option value=\"{$album->getAlbumId()} {$accessCode['Accessibility_Code']}\">{$accessCode['Description']}</option>";
                                }
                            }

                           print<<<MyAlbumsTable
                                <tr>
                                    <td scope="row" class="col-sm-3"> <a href="/SocialMedia/MyPictures.php?aid={$album->getAlbumId()}">$album->Title</a> </td>
                                    <td scope="row" class="col-sm-2">$ctPictures</td>
                                    <td scope="row" class="col-sm-3">
                                        <select name="selAccessCodes[]" class="form-control">
                                        $selectout
                                        </select>
                                    </td>
                                    <td scope="row" class="col-sm-2">
                                        <div class="col-sm-2 offset-sm-3">
                                            <a href="/SocialMedia/MyAlbums.php?aid={$album->getAlbumId()}" class="btn btn-link" role="button" onclick="return confirm('The selected album will be deleted!'); return false;">Delete</a>
                                            <!-- <input type="submit" name="SubmitDelete" class="btn btn-link" value="Delete" onclick="return confirm('The selected album will be deleted!'); return false;"></input> -->

                                        </div>
                                    </td>

                               </tr>
                           MyAlbumsTable;
                       }                 
                    ?>
                </tbody>
            </table>
        </div>
        <div class="row col-sm-12 offset-sm-10">
            <div class="col-md-3">
                <button type="submit" name="btnSaveChanges" class="btn btn-outline-primary my-2">Save Changes</button>
            </div> 
        </div>     
    </form>
</main>
<?php include_once './src/Footer.php'; ?>
