<?php
	session_start();
	
	if((!isset($_SESSION['loggedIn']))){
		header('Location: index.php');
		exit();
	}
	
	require_once "database.php";
	
	$categoryquery = $db->prepare('SELECT id, name FROM incomes_category_assigned_to_users WHERE user_id = :user_id');
	$categoryquery->bindValue(':user_id', $_SESSION['logged_id'], PDO::PARAM_STR); 
	$categoryquery->execute();
	$categories = $categoryquery->fetchAll(PDO::FETCH_ASSOC);
	
	
	if(isset($_POST['amount'])){
		$everything_ok = true;
		
		$amount = trim($_POST['amount']);
		$amount = str_replace(',', '.', $amount);
		
		$pattern = '/^\d+\.\d{2}$/';
		if (!preg_match($pattern, $amount)) {
			$everything_ok = false;
			$_SESSION['e_amount'] = "podaj dokładną kwotę z dwoma miejscami po przecinku";
		}
		
		$date = $_POST['date'];
		if (strpos($date, '.') !== false) {
			list($day, $month, $year) = explode('.', $date);
			$date = sprintf('%04d-%02d-%02d', $year, $month, $day);
		}
		
		if($_POST['category'] == "-1"){
			$everything_ok = false;
			$_SESSION['e_category'] = "wybierz kategoeire";
		} else {
			$chosencategory = $_POST['category'];
		}
		
		$comment = $_POST['comment'];
		
		$_SESSION['fr_amount'] = $amount;
		
		if($everything_ok){	
			$query = $db->prepare('INSERT INTO incomes VALUES (NULL, :user_id, :income_category_assigned_to_user_id, :amount, :date_of_income, :income_comment)');
			$query->bindValue(':user_id', $_SESSION['logged_id'], PDO::PARAM_STR);
			$query->bindValue(':income_category_assigned_to_user_id', $chosencategory, PDO::PARAM_STR);
			$query->bindValue(':amount', $amount, PDO::PARAM_STR);
			$query->bindValue(':date_of_income', $date, PDO::PARAM_STR);
			$query->bindValue(':income_comment', $comment, PDO::PARAM_STR);
			$query->execute();
			
			unset($_SESSION['e_amount']);
			unset($_SESSION['e_date']);
			unset($_SESSION['e_category']);
			$_SESSION['income_added'] = true;
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Budżet domowy</title>

  <link rel="icon" type="image/png" sizes="32x32" href="./images/icons/coin.svg">
  <link rel="stylesheet" href="registerStyle.css"> 
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Castoro:ital@0;1&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
		.error{
			color:red;
			margin-top: 10px;
			margin-bottom: 10px;
		}
  </style>
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
            <ul class="navbar-nav align-items-center" >
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
    <div class="container col-xl-10 col-xxl-8 px-4 py-5 d-flex justify-content-center">
        <div class="card mb-4 rounded-3 shadow-sm mainCard">
          <div class="card-header py-3">
            <h4 id="rejestracja" class="my-0 fw-normal text-center">DODAJ PRZYCHÓD</h4>
          </div>
            <form class="p-4 p-md-5 border rounded-3 .bg-light-subtle" method="post">

              <div class="col-auto">
                <label class="visually-hidden" for="autoSizingInputGroup">Kwota</label>
                <div class="input-group">
                  <div class="input-group-text"><img src="./images/icons/currency-dollar.svg" alt="dollar" width="25" height="20">
                  </div>
                  <span class="input-group-text">0,00</span>
                  <input type="text" class="form-control" id="autoSizingInputGroup" placeholder="Wpisz kwote.." value="<?php
					if(isset($_SESSION['fr_amount'])){
						echo $_SESSION['fr_amount'];
						unset($_SESSION['fr_amount']);
					}
				  ?>" name="amount">
                </div>
				
				<?php
				if(isset($_SESSION['e_amount'])){
					echo '<div class="error">'.$_SESSION['e_amount'].'</div>';
					unset($_SESSION['e_amount']);
				}
			    ?>
				
              </div>

              <div class="col-auto">
                <label for="floatingDate"></label>
                <div class="input-group">
                  <div class="input-group-text"><img src="./images/icons/calendar3.svg" alt="calendar" width="25" height="20">
                  </div>
                  <input type="date" class="form-control" id="floatingDate" placeholder="Data" name="date">
                </div>             
              </div>

              <div class="col-auto">
                <label for="floatingCategory"></label>
                <div class="input-group">
                  <div class="input-group-text"><img src="./images/icons/puzzle.svg" alt="puzzle" width="25" height="20">
                  </div>
                  <select class="form-select" id="floatingCategory" aria-label="Floating label select example" name="category">
                    <option value="-1" selected>Wybierz kategorię...</option>
					<?php
						foreach($categories as $category){
							echo '<option value="'.$category['id'].'">'.$category['name'].'</option>';
						}
					?>
                  </select>
                </div>             
              </div>
			  
			  <?php
				if(isset($_SESSION['e_category'])){
					echo '<div class="error">'.$_SESSION['e_category'].'</div>';
					unset($_SESSION['e_category']);
				}
			  ?>

              <div class="mb-3 mt-4">
                <label for="exampleFormControlTextarea1" class="form-label">Komentarz (opcjonalnie)</label>
                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="comment"></textarea>
              </div>




              <div class="w-40 btn btn-lg btn-success mt-4">
                <button type="submit" class="nav-link active" aria-current="page">Dodaj
                </button>
              </div>
              <div class="w-40 btn btn-lg btn-danger mt-4">
                <a href="incomes.php" class="nav-link active" aria-current="page">Anuluj
                </a>
              </div>
			  
			  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-header">
							<h1 class="modal-title fs-5" id="exampleModalLabel">Przychód dodany</h1>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							Chcesz dodać kolejny czy przejść do menu?
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-success" data-bs-dismiss="modal">Dodaj</button>
							<a href="mainPage.php" class="btn btn-danger" aria-current="page">Anuluj</a>
						</div>
					</div>
				</div>
			  </div>
			  
            </form>
          </div>
        </div>
    </div>

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
  <script src="income.js" charset="utf-8"></script>
  <script>
	document.addEventListener('DOMContentLoaded', function() {
		<?php
		if (isset($_SESSION['income_added']) && $_SESSION['income_added']) {
			$_SESSION['income_added'] = false;
		?>
			showSuccessModal();
		<?php } ?>

		function showSuccessModal() {
			var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
			myModal.show();
		}
	});
	</script>
</body>
</html>