<?php
error_reporting(0);

	/*DATABASE CONNECTION VARIABLES ARE DEFINED HERE*/
	
	function connectToDB()
	{
		$conn = mysqli_connect(HOST,USER,PASS,DB);
		return $conn;
	}
	
	/*Creates the log in form.*/
	function logInForm()
	{
		echo '<div class="form">';
		echo '<h1>RaceShop Log In</h1>';
		echo '<form method="post">';
			echo '<input type="text" name="user" placeholder="Please enter your username"><br>';
			echo '<input type="password" name="pass" placeholder="Please enter your password"><br>';
			echo '<input type="submit" name="login" value="Log In">';
			echo '<input type="reset">';
		echo '</form>';
		echo '<a href="create-account.php">Create Account</a>';
	}
	
	/*Displays log in form if not logged in. If user tries to log in, checks their credentials.*/
	function homePage()
	{
		if(!isset($_POST['login']))
		{
			logInForm();
			echo '</div>';
		} else
		{
			checkLogIn();
		}
	}
	
	/*Checks user log in credentials. If their credentials are valid, logs them in and displays welcome message.
	Otherwise, displays an error message notifying the user of their false information.*/
	function checkLogIn()
	{
		$user = $_POST['user'];
		$pass = $_POST['pass'];
		$pass = hasher($pass);
		
		$conn = connectToDB();
		$sql = "select * from user where username = '$user' and password = '$pass';";
		$results = mysqli_query($conn, $sql);

		if(mysqli_num_rows($results)) 
		{
			$_SESSION['access'] = true;
			welcomeMessage();
			mysqli_close($conn);
		}
	
		if(!haveAccess()) 
		{
			logInForm();
			echo '<p class="invalid">Sorry, that username and/or password is incorrect! If you do not have an account, click "Create Account" above!</p>';
			echo '</div>';
		}
	}
	
	/*Returns true if user is logged in, returns false otherwise.*/
	function haveAccess()
	{
		if(isset($_SESSION['access'])) return true;
		
		return false;
	}
	
	/*Creates a welcome message for a user that logs in.*/
	function welcomeMessage()
	{
		echo '<div class="welcome">';
			echo '<h1>Welcome to the RaceShop!</h1>';
			echo '<p>We pride ourselves in selling the highest quality racecars you can find on the market!</p>';
			echo '<p>You can have a look at our products by clicking <a href="catalog.php">here.</a></p>';
		echo '</div>';
	}
	
	function hasher($word)
	{
		$salt1 = 'ahbfuyewabfjka';
		$salt2 = 'dhsfabfyuewefn';
		$word = $salt1.$word.$salt2;
		$word = hash('sha1', $word);
		return $word;
	}
	
	/*Creates account creation form.*/
	function createForm()
	{
		if(!isset($_POST['nPass']))
		{
			$nPass = "";
		} else
		{
			$nPass = $_POST['nPass'];
		}
		
		if(!isset($_POST['vPass']))
		{
			$vPass = "";
		} else
		{
			$vPass = $_POST['vPass'];
		}
		
		echo '<div class="form">';
		echo '<h1>RaceShop Account Creation</h1>';
		echo '<form method="post">';
			echo '<input id="nUser" type="text" name="nUser" placeholder="Please enter a username"><br>';
			echo '<input id="nPass" type="password" name="nPass" value="'.$nPass.'" placeholder="Please enter a password"><br>';
			echo '<input id="vPass" type="password" name="vPass" value="'.$vPass.'" placeholder="Please verify your password"><br>';
			echo '<input id="submit" type="submit" name="create-account" value="Create Account" disabled>';
			echo '<input type="reset">';
		echo '</form>';
		echo '<p id="pNum" class="invalid"></p>';
		echo '<p id="pChar" class="invalid"></p>';
		echo '<p id="pMatch" class="invalid"></p>';
	}
	
	/*Displays account creation form. If user tries to create an account, checks their inputs for an acceptable username. 
	The script.js file handles the password.*/
	function createAccPage()
	{
		if(!isset($_POST['create-account']))
		{
			createForm();
			echo '</div>';
		} else
		{
			$nUser = $_POST['nUser'];
			$nPass = $_POST['nPass'];
			
			if(verifyUser($nUser) == false)
			{
				createForm();
				echo "<p class=\"invalid\">Sorry! Either that username has been taken, or you've left the field blank.</p>";
				echo '</div>';
			} else if(verifyUser($nUser) == true)
			{
				insertNewAccount($nUser, $nPass);
				echo '<div class="message">';
					echo '<h1>Your account has been created!</h1>';
					echo '<p>Thank you for signing up with RaceShop, you can now log in <a href="index.php">here!</a></p>';
				echo '</div>';
			}
		}
	}
	
	/*Verifies if a username is taken.*/
	function verifyUser($username)
	{
		$conn = connectToDB();
		$sql = 'select username from user;';
		$results =  mysqli_query($conn, $sql);
		
		while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC))
		{
			if($row['username'] == $username)
			{
				mysqli_close($conn);
				return false;
			}
		}
		
		if ($username == false) {
			return false;
		}
		
		mysqli_close($conn);
		return true;
	}
	
	/*Inserts a new username and password into the database once an account is successfully created.*/
	function insertNewAccount($username, $password)
	{
		$conn = connectToDB();
		$password = hasher($password);
		
		$sql = 'insert into user (username, password) values ("'.$username.'", "'.$password.'");';
		$results =  mysqli_query($conn, $sql);
		mysqli_close($conn);
	}
	
	/*Displays all products in a table for the catalog page.*/
	function catalogPage()
	{
		echo '<div class="catalog">';
			echo '<h1>RaceShop Products</h1>';
			$conn = connectToDB();
			$sql = 'select * from product;';
			$results = mysqli_query($conn, $sql);
			
			$tableOpen = '<table><tr><th class="empty"></th><th>Product Name</th><th>Unit Price</th><th class="empty"></th></tr>';
			$tableGuts = '';
			$tableClose = '</table>';
			
			while ($rows = mysqli_fetch_array($results, MYSQLI_ASSOC))
			{
				$tableGuts .= '<tr><td><img src="img/'.$rows['image'].'"></td>';
				$tableGuts .= '<td>'.$rows['name'].'</td>';
				$tableGuts .= '<td>$'.$rows['price'].'.00</td>';
				$tableGuts .= '<td><a href="product.php?id='.$rows['id'].'">View Product Details</a></td></tr>';
			}
			
			mysqli_close($conn);
			
			$table = $tableOpen.$tableGuts.$tableClose;
			echo $table;
		echo '</div>';
	}
	
	/*Displays a specific product the user clicked on from the catalog page. The user can add a product to their cart from this page.*/
	function productPage()
	{
		if(isset($_GET['id']) && !empty($_GET['id']))
		{
			$conn = connectToDB();
			$sql = 'select * from product where id = '.$_GET['id'].';';
			$results = mysqli_query($conn, $sql);
			
			echo '<div class="product">';
			$product = '';
		
			while ($rows = mysqli_fetch_array($results, MYSQLI_ASSOC))
			{
				$product.= '<h2>'.$rows['name'].'</h2>';
				$product.= '<img class="details" src="img/'.$rows['image'].'">';
				$product.= '<p class="desc">'.$rows['description'].'</p>';
				$product.= '<h3>$'.$rows['price'].'.00</h3>';
			}
			
			echo '<h1>RaceShop Products</h1>';
			echo $product;
			
			echo '<form method="post">';
				echo '<input type="hidden" value="'.$_GET['id'].'" name="id">';
				echo '<input type="number" name="qty" min="0" placeholder="Enter Amount to Purchase">';
				echo '<input type="submit" value="Add to Cart" name="add-to-cart">';
			echo '</form>';
			echo '<a href="catalog.php">Back to Products</a>';
			
			
			mysqli_close($conn);
		}
	}
	
	/*Creates sessions for the cart so their items are saved.*/
	function createCartSessions()
	{
		if(!haveAccess())
		{
			echo '<p class="invalid">You must log in before adding products to your cart. You can log in <a href="index.php">here</a>, or create an account <a href="create-account.php">here.</a> Thank you!</p>';
			echo '</div>';
		} else
		{
			$conn = connectToDB();
			$sql = 'select * from product where id = '.$_POST['id'].';';
			
			$results = mysqli_query($conn, $sql);
			while ($rows = mysqli_fetch_array($results, MYSQLI_ASSOC))
			{
				$price = $rows['price'];
				$name = $rows['name'];
			}
			
			mysqli_close($conn);
			
			if (!isset($_SESSION['id']))
			{
				$_SESSION['id'] = array();
				$_SESSION['qty'] = array();
				$_SESSION['price'] = array();
				$_SESSION['name'] = array();
			}
			
			if(!in_array($_POST['id'], $_SESSION['id']) || $_SESSION['id'] == null)
			{
				array_push($_SESSION['id'], $_POST['id']);
				array_push($_SESSION['qty'], $_POST['qty']);
				array_push($_SESSION['price'], $price);
				array_push($_SESSION['name'], $name);
			} else
			{
				for ($x = 0; $x < sizeof($_SESSION['id']); $x++)
				{
					if($_SESSION['id'][$x] == $_POST['id'])
					{
						$_SESSION['qty'][$x] = $_SESSION['qty'][$x] + $_POST['qty'];
					}
				}
			}
			
			echo '<p>Successfully added to your cart! You can view your cart <a href="cart.php">here.</a></p>';
			echo '</div>';
		}
	}
	
	/*Creates a table containing all of the user's items in their cart. If their cart is empty, displays a message.*/
	function cartTable()
	{
		if (isset($_SESSION['id']))
		{
			echo '<div class="cart">';
			echo '<form method="post">';
			
			$tableOpen = '<table class="cart"><tr><th>Product Name</th><th>Unit Price</th><th>Quantity</th><th>Total Price</th></tr>';
			$tableGuts = '';
			$tableClose = '</table>';
			$total = 0;
			
			for ($x = 0; $x < sizeof($_SESSION['id']); $x++)
			{
				if($_SESSION['qty'][$x] != 0)
				{
					$tableGuts .= '<tr><td class="cart">'.$_SESSION['name'][$x].'</td>';
					$tableGuts .= '<td class="cart">$'.$_SESSION['price'][$x].'.00</td>';
					$tableGuts .= '<td class="qty"><input class="qty" name="qty'.$x.'" type="text" value="'.$_SESSION['qty'][$x].'"></td>';
					$tableGuts .= '<td class="cart">$'.($_SESSION['price'][$x] * $_SESSION['qty'][$x]).'.00</td></tr>';
					$total += ($_SESSION['price'][$x] * $_SESSION['qty'][$x]);
				}
			}
			
			echo'<h1>RaceShop Cart</h1>';
	
			if($total != 0)
			{
				$table = $tableOpen.$tableGuts.$tableClose;
				echo $table;
				echo '<p>Final Price: $'.$total.'.00</p>';
				echo '<input name="update" type="submit" value="Update Cart">';
				echo '<input name="place-order" type="submit" value="Place Order"></form>';
			} else
			{
				echo'<p class="invalid">Your cart is empty! To buy racecars, head to our products page found <a href="catalog.php">here.</a></p>';
				echo '</div>';
			}
		} else
		{
			echo '<div class="cart">';
			echo'<h1>RaceShop Cart</h1>';
			echo'<p class="invalid">Your cart is empty! To buy racecars, head to our products page found <a href="catalog.php">here.</a></p>';
			echo '</div>';
		}
	}
	
	if(isset($_POST['update'])) //NOT A FUNCTION, JUST CODE CHECKING IF UPDATE BUTTON WAS PRESSED
	{
		for ($x = 0; $x < sizeof($_SESSION['qty']); $x++)
		{
			$postID = 'qty'.$x;
			$_SESSION['qty'][$x] = $_POST[$postID];
		}
	}
	
	/*Displays cart table and allows user to 'purchase' their items. Changes page into a list of their purchased items if they clicked the 'purchase' button.*/
	function cartPage()
	{
		if(isset($_POST['place-order']))
		{
			echo '<div class="message">';
				echo '<h2>Thank you for your order! We hope to see you again soon!</h2>';
				echo '<div class="order-list">';
				echo '<p class="ordered">You ordered:</p>';
				$total = 0;
				
				for ($x = 0; $x < sizeof($_SESSION['id']); $x++)
				{
					if($_SESSION['qty'][$x] != 0)
					{
						echo '<p class="ordered">'.$_SESSION['qty'][$x].' of the '.$_SESSION['name'][$x].'</p>';
						$total += ($_SESSION['price'][$x] * $_SESSION['qty'][$x]);
					}
				}
				
				echo '<p class="order-total">Total: $'.$total.'.00</p>';
				echo '</div>';
			echo '</div>';
			unset($_SESSION['id']);
		} else
		{
			cartTable();
		}
	}
?>