<?php

    include_once './EntityClassLib.php';
    include_once './functions/ValidationFunctions.php';
    include_once './functions/DbFunctions.php';
    
    session_start();
    
    if (!isset($_SESSION['user']))
    {
        $_SESSION['page'] = "addAlbum";
        header("Location: Login.php");
        exit();
    }
    
    $user = $_SESSION['user'];
    
    // Added for Windows
    $txtTitle = $errTxtTitleMsg = $errSelAccessCodeMsg = $txtAreaDescription = $errDescriptionMsg =  $errSubmitMsg = '';

    if(isset($_POST["btnSubmit"]))
    {
        if(($_POST['selAccessCode']!= -1) && !empty($_POST['txtTitle']))
        {
            addAlbum(trim($_POST['txtTitle']), trim($_POST['txtAreaDescription']), $user->getUserId(), $_POST['selAccessCode']);
            header("Location: MyAlbums.php");
            exit();
        }
        else 
        {
            if (empty($_POST['txtTitle']))
            {
                $errTxtTitleMsg = "Title is required";
            }
            
            if($_POST['selAccessCode'] == -1)
            {
                $errSelAccessCodeMsg = "Accessibility is required";
            }
        }
    }
?>

<?php include_once './src/Header.php'; ?>
<main class="container m-5">
    <h1 class="text-center">Create New Album</h1>
    <p>Welcome <b><?php echo $user->getName() ?></b>! (not you? Change User <a href='Logout.php'>here</a>)</p>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
        <div class="form-group row col-sm-12">
            <div class="row mt-3 d-flex align-items-center">
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Title: </label>
                </div>
                <div class="col-md-4">
                    <input type="text" name="txtTitle" class="form-control" value="<?php echo isset($_POST['txtTitle']) ? $_POST['txtTitle'] : '' ?>" />
                </div>
                <div class="col-md-6">
                    <text class="text-danger"><?php print $errTxtTitleMsg; ?></text>
                </div>    
            </div>
            <div class="row mt-3 d-flex align-items-center">
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Accessibility: </label>
                </div>
                <div class="col-md-4">
                    <select name="selAccessCode" class="form-control">
                        <?php
                            $accessCodes = getAllAccessCodes();
                            $selAccessCode = "-1";
                            
                            if (isset($_POST['selAccessCode'])) 
                            {
                                $selAccessCode = $_POST['selAccessCode'];
                            }
                            
                            echo "<option value=\"-1\" selected>Select one</option>";

                            foreach($accessCodes as $accessCode)
                            {
                                if(!strcmp($selAccessCode, $accessCode['Accessibility_Code']))
                                {
                                    echo "<option value=\"{$accessCode['Accessibility_Code']}\" selected>{$accessCode['Description']}</option>";

                                }
                                else {
                                    echo "<option value=\"{$accessCode['Accessibility_Code']}\">{$accessCode['Description']}</option>";
                                }
                            }
                        ?>
                    </select>
                </div>
                    <div class="col-md-6">
                        <text class="text-danger"><?php print $errSelAccessCodeMsg; ?></text>
                    </div>
            </div>
                <div class="row mt-3 d-flex">
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Description: </label>
                    </div>
                    <div class="col-md-4">
                        <textarea id="txtAreaDescription" name="txtAreaDescription" rows="6" cols="50" class="form-control" value="">
                            <?php echo  isset($_POST['txtAreaDescription']) ? $_POST['txtAreaDescription'] : '' ?>
                        </textarea>
                    </div>
                    <div class="col-md-6">
                        <text class="text-danger"><?php print $errDescriptionMsg; ?></text>
                    </div>
                </div>
            </div>
            <div class="row mt-4 col-md-7">
                <div class="col-md-2">
                    <button type="submit" name="btnSubmit" class="btn btn-outline-primary my-2">Submit</button>
                </div>          
                <div class="col-md-2">
                    <button type="reset" name="btnClear" class="btn btn-outline-primary my-2">Clear</button>
                </div> 
                <?php print $errSubmitMsg; ?>
            </div>
        </div>     
    </form>
</main>
<?php include_once './src/Footer.php'; ?>
