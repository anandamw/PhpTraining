 <?php
    session_start();
    require 'functions.php';
    // cek cookie
    //  if(isset($_COOKIE['login'])){
    //     if( $_COOKIE['login'] == 'true'){
    //         $_SESSION['login'] == true;
    //     }
    //  }

    if (isset($_COOKIE['id']) && isset($_COOKIE['key'])) {
        $id = $_COOKIE['id'];
        $key = $_COOKIE['key'];

        // ambil username berdasarkan id
        $result = mysqli_query($conn, "SELECT username FROM user WHERE 
        id = $id");
        $row = mysqli_fetch_assoc($result);

        // cek cookie dan username 
        if ($key == hash('sha256', $row['username'])) {
            $_SESSION['login'] = true;
        }
    }


    if (isset($_SESSION["login"])) {
        header("location: index.php");
        exit;
    }


    if (isset($_POST["login"])) {

        $username = $_POST["username"];
        $password = $_POST["password"];

        $result = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username'");

        //cek username
        if (mysqli_num_rows($result) === 1) {

            // cek password 
            $row = mysqli_fetch_assoc($result);
            if (password_verify($password, $row["password"])) {
                //set session
                $_SESSION["login"] = true;

                //cek remember me
                if (isset($_POST['remember'])) {
                    //  buat cookie
                    setcookie('id', $row['id'], time() + 60);
                    setcookie(
                        'key',
                        hash('sha256', $row['username']),
                        time() + 60
                    );
                }

                header("location: index.php");
                exit;
            }
        }
        $error = true;
    }

    ?>
 <!DOCTYPE html>
 <html lang="en">

 <head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Halaman Login</title>
     <link rel="stylesheet" href="src/login.css">
 </head>

 <body>


     <?php if (isset($error)) : ?>
         <p style="color: red; font-style: italic; ">username / password salah </p>
     <?php endif; ?>
     <div class="container">
         <img src="img/register.jpg" width="500">
         <form method="post" action="">
             <h1>Halaman Login</h1>
             <ul>
                 <li>
                     <label class="rull1" for="username">Username :</label>
                     <input class="input1" type="text" name="username" id="username">
                 </li>
                 <li>
                     <label class="rull1" for="password">Password :</label>
                     <input class="input1" type="password" name="password" id="password">
                 </li>
                 <div class="rull2">
                     <li>
                         <input type="checkbox" name="remember" id="remember">
                         <label for="remember">Remember me </label>
                     </li>
                     <li>
                         <button type="submit" name="login" id="submit">login</button>
                         <a href="registrasi.php">Register</a>
                     </li>
                 </div>
             </ul>
         </form>
     </div>
 </body>

 </html>