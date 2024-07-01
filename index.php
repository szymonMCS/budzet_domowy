<?php
	session_start();
	if((isset($_SESSION['loggedIn'])) && ($_SESSION['loggedIn'] == true)){
		header('Location: mainPage.php');
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
  <link rel="stylesheet" href="welcomeStyle.css"> 
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Castoro:ital@0;1&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

  <header class="container">
    <div class="d-flex flex-wrap justify-content-between py-3 border-bottom">
      <a href="index.php" class="d-flex align-items-center mb-3 mb-md-0 me-2 link-body-emphasis text-decoration-none">
        <img src="./images/icons/swinka-domek-tlo.png" class="bi me-2" width="80" height="80"></img>
        <h2 id="main-name">
          <span>Mój</span>
          <span>Budżet</span>
        </h2>
      </a>

      <ul class="nav nav-pills align-content-center ms-2">
        <li class="nav-item me-1"><a href="logowanie.php" class="nav-link active" aria-current="page">Logowanie</a></li>
        <li class="nav-item ms-1"><a href="rejestracja.php" class="nav-link">Rejestracja</a></li>
      </ul>
    </div>
  </header>

  <main>
    <div class="my-5">
      <div class="p-5 text-center">
        <div class="container py-5 bg-body-tertiary">
          <h1 class="text-body-emphasis">Witaj w serwisie mój budżet</h1>
          <p class="col-lg-8 mx-auto lead">
            Czujesz że chcesz mieć dużo większą kontrolę nad swoimi wydatkami? Chcesz efektywniej gromadzić pieniądze na wymarzony cel? Z nami w bardzo prosty sposób możesz dodawać swoje wydatki oraz przychody, wyświetlać wyniki w przejżysty sposób w postaci raportów i wykresów.
          </p>
        </div>
      </div>
    </div>
  </main>

  <footer class="footer">
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
<?php
	if(isset($_SESSION['error']))
	echo $_SESSION['error'];
?>
</body>
</html>