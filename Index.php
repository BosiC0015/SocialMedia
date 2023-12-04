<?php

    session_start();
    
    if (isset($_SESSION['user'])) {
        header("Location: MyAlbums.php");
        exit();
    }
?>
<?php include_once './src/Header.php'; ?>

<main class="container p-5">
    <h1 class="text-center">Welcome to Algonquin Social Media Website</h1>
    <div class="my-3">
        <p>If you have never used this before, you have to <a href="NewUser.php">sign up</a> first.</p>
        <p>If you have already signed up, you can <a href="Login.php">log in</a> now</p>
    </div>
</main>

<?php include_once './src/Footer.php'; ?>