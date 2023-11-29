<?php
    include_once './EntityClassLib.php';
    include_once './functions/ValidationFunctions.php';
    include_once './functions/DbFunctions.php';
    
    session_start();
    
    if (isset($_SESSION['user'])) {
        header("Location: MyAlbums.php");
        exit();
    }
    
    if(isset($_POST['btnSubmit'])) {
        $uid = trim($_POST['uid']);
        $name = trim($_POST['name']);
        $phone = trim($_POST['phone']);
        $pw = $_POST['pw'];
        $pw2 = $_POST['pw2'];
        $pwHashed = hash("sha256", $pw);
        $valid = true;
        
        $uidErr = validateUid($uid);
        $nameErr = validateName($name);
        $phoneErr = validatePhone($phone);
        $pwErr = validatePw($pw);
        $pw2Err = validatePwAgain($pw, $pw2);

        if (!($uidErr || $nameErr || $phoneErr || $pwErr || $pw2Err)) {
            try{
                addUser($uid, $name, $phone, $pwHashed);
                $_SESSION['user'] = new User($uid, $name, $phone);

                header("Location: Index.php");
                exit();
            } catch (Exception $ex) {
                die("ERROR!");
            }
        }
        
    }
    
?>

<?php include_once './src/Header.php'; ?>
<main class="container m-5">
    <h1 class="text-center">Sign Up</h1>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" class="">
        <p class="text-danger">All fields are required.</p>
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
        <!--name-->
        <div class="row mt-3 d-flex align-items-center">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Name: </label>
            </div>
            <div class="col-md-3">
                <input type="text" name="name" class="form-control" value="<?php print $name; ?>" />
            </div>
            <?php print $nameErr; ?>
        </div>
        <!--phone number-->
        <div class="row mt-3 d-flex align-items-center">
            <div class="col-md-3 d-flex flex-column">
                <label class="form-label fw-semibold">Phone Number:</label>
                <label class="text-secondary">(nnn-nnn-nnnn)</label>
            </div>
            <div class="col-md-3">
                <input type="tel" name="phone" class="form-control" value="<?php print $phone; ?>" />
            </div>
            <?php print $phoneErr; ?>
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
        <!--password repeat-->
        <div class="row mt-3 d-flex align-items-center">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Password Again: </label>
            </div>
            <div class="col-md-3">
                <input type="password" name="pw2" class="form-control" value="<?php print $pw2; ?>" />
            </div>
            <?php print $pw2Err; ?>
        </div>
        <!--buttons-->
        <div class="row mt-4 offset-2">
            <div class="col-md-2">
                <button type="submit" name="btnSubmit" class="btn btn-outline-primary my-2">Submit</button>
            </div>          
            <div class="col-md-2">
                <button type="reset" name="btnClear" class="btn btn-outline-primary my-2">Clear</button>
            </div> 
        </div>
    </form>
</main>
<?php include_once './src/Footer.php'; ?>