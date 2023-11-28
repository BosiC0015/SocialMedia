<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>AC Social Media</title>
    <meta charset="utf-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary position-sticky" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand p-1" href="http://www.algonquincollege.com">
                <img src="src/AC.png" alt="Algonquin College" />
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item mx-2"><a class="nav-link" href="Index.php">Home</a></li>
                    <li class="nav-item mx-2"><a class="nav-link" href="MyFriends.php">My Friends</a></li>
                    <li class="nav-item mx-2"><a class="nav-link" href="MyAlbums.php">My Albums</a></li>
                    <li class="nav-item mx-2"><a class="nav-link" href="MyPictures.php">My Pictures</a></li>
                    <li class="nav-item mx-2"><a class="nav-link" href="UploadPictures.php">Upload Pictures</a></li>
                    <?php
                        if (isset($_SESSION['user'])) {
                            print '<li class="nav-item mx-2"><a class="nav-link" href="Logout.php">Log out</a></li>';
                        } else {
                            print '<li class="nav-item mx-2"><a class="nav-link" href="Login.php">Log in</a></li>';                    
                        }
                    ?>
                </ul>
            </div>
        </div>  
    </nav>
