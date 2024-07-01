<?php
	session_start();
	
	if((!isset($_SESSION['loggedIn']))){
		header('Location: index.php');
		exit();
	}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Budżet domowy</title>

  <link rel="icon" type="image/png" sizes="32x32" href="./images/icons/coin.svg">
  <link rel="stylesheet" href="mainStyle.css"> 
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Castoro:ital@0;1&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

  <header class="container">
    <div class="d-flex flex-wrap justify-content-between py-3 border-bottom">
      <div>
        <a href="mainPage.php" class="d-flex align-items-center mb-3 mb-md-0 me-2 link-body-emphasis text-decoration-none">
          <img src="./images/icons/swinka-domek-tlo.png" class="bi me-2" width="80" height="80"></img>
          <h1 id="main-name">
            <span>Mój</span>
            <span>Budżet</span>
          </h1>
        </a>
      </div>

      <div class="d-flex flex-row justify-content-between align-items-center py-3 border-bottom">
        <ul class="nav nav-pills align-content-center me-2">
          <li class="nav-item me-1">
            <p class="me-1">Zalogowany jako</p> 
            <a class="ms-1" href="#"  aria-current="page"><?= $_SESSION['username'] ?></a></li>
        </ul>
  
        <div class="flex-shrink-0 dropdown ms-2">
          <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="./images/icons/person-circle.svg" alt="mdo" width="32" height="32" class="rounded-circle">
          </a>
          <ul class="dropdown-menu text-small shadow" style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate3d(0.5px, 34px, 0px);" data-popper-placement="bottom-end">
            <li class="dropdown-flex">
              <img src="./images/icons/person-lines-fill.svg" alt="profile" width="20" height="20">
              <a class="dropdown-item" href="#">Profil</a>
            </li>
            <li class="dropdown-flex">
              <img src="./images/icons/trybik.png" alt="gear" width="20" height="20">
              <a class="dropdown-item" href="#">Ustawienia</a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li class="dropdown-flex">
              <img src="./images/icons/power.svg" alt="house" width="20" height="20">
              <a class="dropdown-item" href="logout.php">Wyloguj</a>
            </li>
          </ul>
        </div>
      </div>
    </div>



    <nav class="navbar navbar-expand-lg bg-body-tertiary rounded" aria-label="menu-navbar">
        <div class="container-fluid justify-content-end">
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample10" aria-controls="navbarsExample10" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
  
          <div class="collapse navbar-collapse justify-content-md-center" id="navbarsExample10">
            <ul class="navbar-nav align-items-center">
              <li class="nav-item">
                <a class="nav-link ps-3" href="mainPage.php"><img class="me-2" src="./images/icons/domek.png" alt="house" width="30" height="25">Strona główna</a>
              </li>
              <li class="nav-item">
                <a class="nav-link ps-3" href="incomes.php"><img class="me-2" src="./images/icons/plus.png" alt="plus icon" width="25" height="24">Dodaj przychód</a>
              </li>
              <li class="nav-item">
                <a class="nav-link ps-3" href="outcomes.php"><img class="me-2" src="./images/icons/minus.png" alt="minus icon" width="26" height="30">Dodaj wydatek</a>
              </li>
              <li class="nav-item">
                <a class="nav-link ps-3" href="balance.php"><img class="me-2" src="./images/icons/wykres.png" alt="bar diagram" width="30" height="30">Przedstaw bilans</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>

    </nav>
  </header>

  <main>

    <section class="container mt-4 powitanie">
      <h2>Cześć <?= $_SESSION['username'] ?></h2>
      <h3>pora rzucic okiem twoje finanse!</h3>
    </section>
      

    <section class="container">

      <div class="row row-cols-1 row-cols-md-3 mb-3  mt-5 pt-4 text-center">
        <div class="col d-flex align-items-stretch justify-content-center">
          <div class="card mb-4 rounded-3 shadow-sm">
            <div class="modal-body p-4">      
              <img src="./images/icons/plus.png" alt="plus icon" width="120" height="120">
              <div class="w-100 btn btn-lg btn-outline-primary">
                <a href="incomes.php" class="nav-link active" aria-current="page">Dodaj przychód
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="col d-flex align-items-stretch justify-content-center">
          <div class="card mb-4 rounded-3 shadow-sm">
            <div class="modal-body p-4">      
              <img src="./images/icons/minus.png" alt="plus icon" width="120" height="120">
              <div class="w-100 btn btn-lg btn-outline-primary">
                <a href="outcomes.php" class="nav-link active" aria-current="page">Dodaj wydatek
                </a>
              </div>
            </div>
          </div>
        </div>  
        <div class="col d-flex align-items-stretch justify-content-center">
          <div class="card mb-4 rounded-3 shadow-sm">
            <div class="modal-body p-4">      
              <img src="./images/icons/wykres.png" alt="plus icon" width="150" height="120">

              <div class="w-100 btn btn-lg btn-outline-primary">
                <a href="balance.php" class="nav-link active" aria-current="page">Pokaż bilans
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <footer>
    <div class="container mt-4 pt-4">
      <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
        <div class="col-md-4 d-flex align-items-center">
          <a href="/" class="mb-3 me-2 mb-md-0 text-body-secondary text-decoration-none lh-1">
            <img  src="./images/icons/coin.svg" class="bi" width="30" height="24"><use></use></img>
          </a>
          <span class="mb-3 mb-md-0 text-body-secondary">© 2024 Mój Budżet, Inc</span>
        </div>
    
        <ul class="nav col-md-4 justify-content-end list-unstyled d-flex">
          <li class="ms-3"><a class="text-body-secondary" href="#"><img src="./images/icons/twitter-x.svg"  class="bi" width="24" height="24"><use xlink:href="#twitter"></use></img></a></li>
          <li class="ms-3"><a class="text-body-secondary" href="#"><img src="./images/icons/instagram.svg"  class="bi" width="24" height="24"><use xlink:href="#instagram"></use></img></a></li>
          <li class="ms-3"><a class="text-body-secondary" href="#"><img src="./images/icons/facebook.svg"  class="bi" width="24" height="24"><use xlink:href="#facebook"></use></img></a></li>
        </ul>
      </footer>
    </div>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>