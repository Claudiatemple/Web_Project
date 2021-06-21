<?php

include("connection.php");

//This is login form
if (isset($_POST["login_Submit"])) {
    if (isset($_POST['email']) && isset($_POST['password'])) {

        $__email = trim($_POST['email']);
        $__password = trim($_POST['password']);

        $error_exists = false;
              
        if (empty($__email)) {
            echo '<h4 class="text-danger text-center">Email is required</h4>';
            $error_exists = true;
        } elseif (strlen($__email) > 300) {
            echo '<h4 class="text-danger text-center">Email is too long</h4>';
            $error_exists = true;
        }elseif (!filter_var($__email, FILTER_DEFAULT)) {
            echo '<h4 class="text-danger text-center">The Email is Invalid, please try again</h4>';
            $error_exists = true;
        } 
        if (empty($__password)) {
            echo '<h4 class="text-danger text-center">Password is required</h4>';
            $error_exists = true;
        }elseif (strlen($__password) < 10 || strlen($__password) > 30) {
            echo '<h4 class="text-danger text-center">Password must be between 10 to 30 characters</h4>';
            $error_exists = true;
        }


        if (!$error_exists) {
            
            $sql = "SELECT User_ID,Full_Name,Email,User_Password FROM user_register WHERE Email = ?";
            if ($stmt = $con->prepare($sql)) {
                $stmt->bind_param("s", $__email);

                if ($stmt->execute()) {
                  
                    $stmt->store_result();

                    // Check the email if its correct,then verify your password
                    if ($stmt->num_rows == 1) {

                        //Bind in the result using the variables
                        $stmt->bind_result($id, $full_name, $email, $password);
                        if ($stmt->fetch()) {

                            if (password_verify($__password, $password)) {
                              //Now check if the Password is correct,then start a new session
                                session_start();


                                $_SESSION["logged_In"] = true;
                                $_SESSION["user_id"] = $id;
                                $_SESSION["full_name"] = $fullname;
                                $_SESSION["email"] = $email;

                    
                                header("location: index.php");
                            } else {
                                echo '<h4 class="text-danger text-center">Email or Password is incorrect</h4>';
                            }
                        }
                    }else{
                        echo '<h4 class="text-danger text-center">Email or Password is incorrect</h4>';
                    }
                }
            }
        }
    } else {
        header("location: login.php");
    }
}

//handles the register form 
if (isset($_POST["register_Submit"])) {

    //try catch handle all the errors
    try {
        if (isset($_POST["full_name"]) && isset($_POST["email"]) && isset($_POST["password"])) {

            //variables for storing the values
            $_full_name = trim($_POST["full_name"]);
            $_email = trim($_POST["email"]);
            $_password = trim($_POST["password"]);
            $error_exists = false;

            if (empty($_full_name)) {
                echo '<h4 class="text-danger text-center">Fullname is required</h4>';
                $error_exists = true;
            } elseif (strlen($_full_name) < 3 || strlen($_full_name) > 300) {
                echo '<h4 class="text-danger text-center">Fullname must be between 3 to 200 character</h4>';
                $error_exists = true;
            }
            if (empty($_email)) {
                echo '<h4 class="text-danger text-center">Email is required</h4>';
                $error_exists = true;
            } elseif (strlen($_email) > 300) {
                echo '<h4 class="text-danger text-center">Email is too long</h4>';
                $error_exists = true;
            }
            if (empty($_password)) {
                echo '<h4 class="text-danger text-center">Password is required</h4>';
                $error_exists = true;
            }

            if (strlen($_password) < 10 || strlen($_password) > 30) {
                echo '<h4 class="text-danger text-center">Password must be between 10 to 50 characters</h4>';
                $error_exists = true;
            }

            if (!$error_exists) {
                $query = "SELECT * FROM userregister WHERE Email = ?";

                if ($stmt = $con->prepare($query)) {

                    $stmt->bind_param('s', $_email);

                    if ($stmt->execute()) {
                        $stmt->store_result();

                        if ($stmt->num_rows > 0) {
                            //If the email already exists in the system
                            echo '<h4 class="text-danger text-center">That email is already registered, please try another email</h4>';
                        } else {

                            //Now check the users password
                            $_hashedPassword = password_hash($_password, PASSWORD_DEFAULT);
                            $_query = 'INSERT INTO user_register (Full_Name,Email,User_Password) values (?,?,?)';
                            $statement = $con->prepare($_query);
                            $statement->bind_param('sss', $_full_name, $_email, $_hashedPassword);

                            if ($statement->execute()) {

                                echo '<h4 class="text-success text-center">Registeration Successful</h4>';
                            }
                        }
                    } else {

                        echo '<h4 class="text-danger text-center">Check! Something went wrong</h4>';
                    }
                } else {
                    echo '<h4 class="text-danger text-center">Check! Something went wrong</h4>';
                }
            }
        }
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }
}



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title> 

    //The link between the bootstrap and the pages
    <link rel="stylesheet" href="./css/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="./css/main.css" type="text/css">
</head>

<body>
    <div class="container">
        <div class="row mt-5">

            <div class="col-md-12 text-center">
                <h2 class="display-3">Drugs Management System</h2>
            </div>
            <div class="col-md-5 p-5">
                <form action="login.php" method="post" autocomplete="off">
                    <h4 class="text-uppercase">Login</h4>
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                    </div>

                    <div class="form-group">
                        <label for="">Password</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Enter Password">
                    </div>

                    <input type="submit" value="Login" class="form-control btn btn-info my-3" name="loginSubmit">
                </form>
            </div>
            <div class="col-md-7 p-5">
                <form action="login.php" method="POST" autocomplete="off">
                    <h4 class="text-uppercase">Register Here</h4>
                    <div class="form-group mb-2">
                        <label for="">Fullname</label>
                        <input type="text" class="form-control" name="fullname" id="fullname" placeholder="Fullname">
                    </div>

                    <div class="form-group mb-2">
                        <label for="">Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email">
                    </div>


                    <div class="form-group mb-2">
                        <label for="">Password</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Enter Password">
                    </div>

                    <input type="submit" value="New Account" class="form-control btn btn-primary my-3" name="registerSubmit">

                </form>
            </div>
        </div>
    </div>

    <?php set_footer() ?>