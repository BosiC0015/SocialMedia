<?php

    session_start();
    
?>

<?php include_once './src/Header.php'; ?>
<main class="container m-5">
    <h1 class="text-center">Sign Up</h1>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" class="">
        <p class="text-danger">All fields are required.</p>
        <!--user id-->
        <div class="row mt-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold">User ID: </label>
            </div>
            <div class="col-md-3">
                <input type="text" name="uid" class="form-control" value="<?php print $uid; ?>" />
            </div>
        </div>
        <?php print $uidErr; ?>
        <!--name-->
        <div class="row mt-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Name: </label>
            </div>
            <div class="col-md-3">
                <input type="text" name="name" class="form-control" value="<?php print $name ?>" />
            </div>
        </div>
        <?php print $nameErr; ?>
        <!--phone number-->
        <div class="row mt-3 d-flex align-items-center">
            <div class="col-md-3 d-flex flex-column">
                <label class="form-label fw-semibold">Phone Number:</label>
                <span class="text-secondary">(nnn-nnn-nnnn)</span>
            </div>
            <div class="col-md-3">
                <input type="tel" name="phone" class="form-control" value="<?php print $phone; ?>" />
            </div>
        </div>
        <?php print $phoneErr; ?>
        <!--password-->
        <div class="row mt-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Password: </label>
            </div>
            <div class="col-md-3">
                <input type="password" name="pw" class="form-control" value="<?php print $pw; ?>" />
            </div>
        </div>
        <!--password repeat-->
        <div class="row mt-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Password Again: </label>
            </div>
            <div class="col-md-3">
                <input type="password" name="pw2" class="form-control" value="<?php print $pw2; ?>" />
            </div>
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