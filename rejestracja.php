<?php
	session_start();
	
	if(isset($_POST['email'])){
		$everything_ok = true;
		$_SESSION['success_registration'] = false;
		
		$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
		
		if((strlen($username) < 3) || (strlen($username) > 20)){
			$everything_ok = false;
			$_SESSION['e_username'] = "Imie musi posiadać od 3 do 20 znaków";
		}
		
		if(ctype_alpha($username) == false){
			$everything_ok = false;
			$_SESSION['e_username'] = "Imie może skladać się tylko z liter (bez poslich znaków)";
		}
		
		$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
		$email = strtolower($email);
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			$everything_ok = false;
			$_SESSION['e_email'] = "Podaj poprawny adres e-mail";
		}
		
		$password1 = $_POST['password1'];
		$password2 = $_POST['password2'];
		
		if(strlen($password1) < 8 || (strlen($password1) > 20)){
			$everything_ok = false;
			$_SESSION['e_password'] = "Hasło musi posiadać od 8 do 20 znaków";
		}
		
		if($password1 != $password2){
			$everything_ok = false;
			$_SESSION['e_password'] = "Podane hasła nie sąidentyczne";
		}
		
		if(!isset($_POST['terms'])){
			$everything_ok = false;
			$_SESSION['e_terms'] = "Zaakceptuj regulamin";
		}
		
		$password_hash = password_hash($password1, PASSWORD_DEFAULT);
		
		
		$secret = "6Ld2BfspAAAAAG0Db_zFJfL-2Vnnfy5mqWPrbkI3";
		$check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST[
		'g-recaptcha-response']);
		$response = json_decode($check);
		
		if($response->success==false){
			$everything_ok = false;
			$_SESSION['e_bot'] = "Potwirdz że nie jesteś botem";
		}
		
		$_SESSION['fr_username'] = $username;
		$_SESSION['fr_email'] = $email;
		$_SESSION['fr_password1'] = $password1;
		$_SESSION['fr_password2'] = $password2;
		if(isset($_POST['terms']))$_SESSION['fr_terms'] = true;
		
		require_once "database.php";
		

		$emailquery = $db->prepare('SELECT id FROM users WHERE email= :email');
		$emailquery->bindValue(':email', $email, PDO::PARAM_STR);
		$emailquery->execute();
		
		$isEmailInBase = $emailquery->rowCount();
		
		if($isEmailInBase > 0){
			$everything_ok = false;
			$_SESSION['e_email'] = "Istnieje już konto przypisane do tego adresu e-mail.";
		}
		
		if($everything_ok){	
			$query = $db->prepare('INSERT INTO users VALUES (NULL, :username, :password, :email)');
			$query->bindValue(':username', $username, PDO::PARAM_STR);
			$query->bindValue(':password', $password_hash, PDO::PARAM_STR);
			$query->bindValue(':email', $email, PDO::PARAM_STR);
			$query->execute();
			
			$idquery = $db->prepare('SELECT id FROM users WHERE email= :email');
			$idquery->bindValue(':email', $email, PDO::PARAM_STR);
			$idquery->execute();
			
			$user = $idquery->fetch(PDO::FETCH_ASSOC);
			$user_id = $user['id']; 
			
			$copyIncomesCategories = $db->prepare('INSERT INTO incomes_category_assigned_to_users (user_id , name) 
												   SELECT :id, name
												   FROM incomes_category_default');
			$copyIncomesCategories->bindValue(':id', $user_id, PDO::PARAM_STR);
			$copyIncomesCategories->execute();
			
			$copyOutcomesCategories = $db->prepare('INSERT INTO expenses_category_assigned_to_users (user_id , name) 
													SELECT :id, name
													FROM expenses_category_default');
			$copyOutcomesCategories->bindValue(':id', $user_id, PDO::PARAM_STR);
			$copyOutcomesCategories->execute();
			
			$copyPaymentMethods = $db->prepare('INSERT INTO payment_methods_assigned_to_users (user_id , name) 
												SELECT :id, name
												FROM payment_methods_default');
			$copyPaymentMethods->bindValue(':id', $user_id, PDO::PARAM_STR);
			$copyPaymentMethods->execute();
			
			unset($_SESSION['e_email']);
			unset($_SESSION['e_password']);
			unset($_SESSION['e_terms']);
			unset($_SESSION['e_bot']);
			$_SESSION['success_registration'] = true;
		}
	}		

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Budżet domowy</title>
  <script src="https://www.google.com/recaptcha/api.js"></script>
  <link rel="icon" type="image/png" sizes="32x32" href="./images/icons/coin.svg">
  <link rel="stylesheet" href="registerStyle.css"> 
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Castoro:ital@0;1&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
      <a href="http://127.0.0.1:3000/ZZZ_budzet domowy/welcomePage.html" class="d-flex align-items-center mb-3 mb-md-0 me-2 link-body-emphasis text-decoration-none">
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
    <div class="container col-xl-10 col-xxl-8 px-4 py-5 d-flex justify-content-center">
        <div class="card mb-4 rounded-3 shadow-sm mainCard">
          <div class="card-header py-3">
            <h4 id="rejestracja" class="my-0 fw-normal text-center">REJESTRACJA</h4>
          </div>
            <form class="p-4 p-md-5 border rounded-3 .bg-light-subtle" method="post">
              <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingInput" placeholder="Imie" value="<?php
					if(isset($_SESSION['fr_username'])){
						echo $_SESSION['fr_username'];
						unset($_SESSION['fr_username']);
					}
				?>" name="username"/>
                <label for="floatingInput"><img src="./images/icons/person.svg" class="me-3" alt="minus icon" width="25" height="20">Imie</label>
              </div>
			  
			  <?php
				if(isset($_SESSION['e_username'])){
					echo '<div class="error">'.$_SESSION['e_username'].'</div>';
					unset($_SESSION['e_username']);
				}
			  ?>
			  
              <div class="form-floating mb-3">
                <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" value="<?php
					if(isset($_SESSION['fr_email'])){
						echo $_SESSION['fr_email'];
						unset($_SESSION['fr_email']);
					}
				?>" name="email"/>
				<label for="floatingPassword"><img src="./images/icons/envelope.svg" class="me-3" alt="envelope" width="25" height="20">E-mail</label>
              </div>
			  
			  <?php
				if(isset($_SESSION['e_email'])){
					echo '<div class="error">'.$_SESSION['e_email'].'</div>';
					unset($_SESSION['e_email']);
				}
			  ?>
			  
              <div class="form-floating mb-3">
                <input type="password" class="form-control" id="floatingPassword" placeholder="password" value="<?php
					if(isset($_SESSION['fr_password1'])){
						echo $_SESSION['fr_password1'];
						unset($_SESSION['fr_password1']);
					} 
				?>" name="password1"/>
                <label for="floatingInput"><img src="./images/icons/lock.svg" class="me-3" alt="locker" width="25" height="20">Hasło</label>
              </div>
			  
			  <?php
				if(isset($_SESSION['e_password'])){
					echo '<div class="error">'.$_SESSION['e_password'].'</div>';
					unset($_SESSION['e_password']);
				}
			  ?>
			  
              <div class="form-floating mb-3">
                <input type="password" class="form-control" id="floatingPassword" placeholder="password" value="<?php
					if(isset($_SESSION['fr_password2'])){
						echo $_SESSION['fr_password2'];
						unset($_SESSION['fr_password2']);
					} 
				?>" name="password2"/>
                <label for="floatingInput"><img src="./images/icons/lock.svg" class="me-3" alt="locker" width="25" height="20">Powtórz hasło</label>
              </div>
			  
			  
			  
              <div class="checkbox mb-3">
                <label>
                  <input type="checkbox" name="terms" <?php
				  if(isset($_SESSION['fr_terms'])){
					echo "checked";
					unset($_SESSION['fr_terms']);
				  }
				  ?>/> Akceptuje regulamin
                </label>
              </div>
			  
			  <?php
				if(isset($_SESSION['e_terms'])){
					echo '<div class="error">'.$_SESSION['e_terms'].'</div>';
					unset($_SESSION['e_terms']);
				}
			  ?>
			  
			  <div class="g-recaptcha" data-sitekey="6Ld2BfspAAAAAA0f9YMisUrB4U4Fob2dcIICtP5y"></div></br>
			  <?php
				if(isset($_SESSION['e_bot'])){
					echo '<div class="error">'.$_SESSION['e_bot'].'</div>';
					unset($_SESSION['e_bot']);
				}
			  ?>
              
              <button type="submit" class="w-100 btn btn-lg btn-primary mt-4">
                Zarejestruj
              </button>
              
			  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-header">
							<h1 class="modal-title fs-5" id="exampleModalLabel">Rejestracja zakończona</h1>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							Zdecyduj czy chcesz zalogować się od razu czy wrócić do strony głównej?
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Anuluj</button>
							<div class="btn btn-primary">
								<a href="logowanie.php" class="nav-link active" aria-current="page">Zaloguj</a>
							</div>
						</div>
					</div>
				</div>
			  </div>

              <hr class="my-4">
              <small class="text-body-secondary">Klikając zarejestruj, zgadzasz się na warunki użytkowania.</small>
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
  <script src="welcomeIndex.js" charset="utf-8"></script>
  <script>
	document.addEventListener('DOMContentLoaded', function() {
		<?php
		if (isset($_SESSION['success_registration']) && $_SESSION['success_registration']) {
			$_SESSION['success_registration'] = false;
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