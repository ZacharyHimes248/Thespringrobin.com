<?php
if(isset($_POST['signup-submit'])){

	require 'dbh.inc.php';

	$username =$_POST['uid'];
	$email =$_POST['mail'];
	$pwd =$_POST['pwd'];
	$passwordRepeat =$_POST['pwd-repeat'];




	if(empty($username) || empty($email) || empty($pwd) || empty($passwordRepeat))
	{

		header("Location: ../HTMLfolders/signup.php?error=allFieldsEmpty&uid=".$username."&mail=".$mail);
		exit();
	}
	else if(!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/", $username))
			{
			    header("Location: ../HTMLfolders/signup.php?=invalidEmailUid");
	            exit();
			}
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {

    	       header("Location:../HTMLfolders/signup.php?error=invalidemail= ".$username);
    	          exit();
        }

    	else if (!preg_match("/^[a-zA-Z0-9]*$/", $username))
        {

    	       header("Location: ../HTMLfolders/signup.php?error=invaliduid&mail= ".$email);
    	          exit();
        }
    	else if($pwd !== $passwordRepeat)
    	{
    		header("Location: ../HTMLfolders/signup.php?error=passwordcheck&uid= " .$username."&mail=".$email);
    		exit();
    	}

	else
	{
		$sql = "SELECT uidUsers FROM users WHERE uidUsers=? AND pwdUsers=?";
		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt,$sql)){
			header("Location: ../HTMLfolders/signup.php?error=sqlerror");
			exit();
		}
		else
        {
				mysqli_stmt_bind_param($stmt, "ss", $username, $pwdUsers);
				mysqli_stmt_execute($stmt);
				mysqli_stmt_store_result($stmt);
				$resultCheck = mysqli_stmt_num_rows($stmt);
				if($resultCheck > 0){
					header("Location: ../HTMLfolders/signup.php?error=usertaken&mail=".$email);
					exit();
				}
				else
                {
						$sql = "INSERT INTO users (uidUsers, emailUsers, pwdUsers) VALUES (?, ?, ?)";
							$stmt = mysqli_stmt_init($conn);
						if(!mysqli_stmt_prepare($stmt,$sql)){
							header("Location: ../HTMLfolders/signup.php?error=sqlerror");
							exit();
					}
					else
						{

							$hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
							mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashedPwd);
							mysqli_stmt_execute($stmt);
							header("Location: ../HTMLfolders/header.php?signup=success");
							exit();
						}

				}
			}

	}

	mysqli_stmt_close($stmt);
	mysqli_close($conn);
}

else
{
	header("Location: ../HTMLfolders/signup.php?");
							exit();
}
?>