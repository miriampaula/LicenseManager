<?php


session_start();

$db = mysqli_connect("localhost:3306", "root", "");
mysqli_select_db($db, "licente");

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['nume'])) {

    $nume = $_SESSION['nume'];

    header("Location:lista.php");
    exit;
} else {

    $nume = $password =  "";

    if ((empty($_POST["nume"])) && (empty($_POST["password"]))) {
        $nameErr = "Este necesar sa introduceti datele cerute!";
    } else {

        $nume = $_POST["nume"];
        $password = $_POST["password"];

        $numeleExista = numeleExista($nume);
        $verificat = verifyNumeAndPassword($nume, $password);

        if ($numeleExista) {
            if ($verificat) {

                $_SESSION['nume'] = $nume;
                $_SESSION['logged_in'] = true;
                header("Location: lista.php");
                exit;
            } else {
                echo '<script>alert("Incorrect authentication.");</script>';
            }
        } else {

            echo '<script>alert("Name does not exist.");</script>';
        }
    }
}


function verifyNumeAndPassword($nume, $password)
{
    global $db;
    $sanitizedEmail = mysqli_real_escape_string($db, $nume);
    $sanitizedPassword = mysqli_real_escape_string($db, $password);

    $query = "SELECT COUNT(*) AS count FROM user WHERE nume = '$sanitizedEmail' AND parola = '$sanitizedPassword'";
    $result = mysqli_query($db, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $count = $row['count'];
        return ($count > 0);
    } else {
        return false;
    }
}

function numeleExista($nume)
{
    global $db;
    $sanitizedEmail = mysqli_real_escape_string($db, $nume);

    $query = "SELECT COUNT(*) AS count FROM user WHERE nume = '$sanitizedEmail'";
    $result = mysqli_query($db, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $count = $row['count'];
        return ($count > 0);
    } else {
        return false;
    }
}

?>



<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">

    <style>
        #eye {
            margin: 5.9px 0 0 0;
            margin-left: -20px;
            padding: 15px 9px 19px 0px;
            border-radius: 0px 5px 5px 0px;

            float: right;
            position: relative;
            right: 1%;
            top: -.2%;
            z-index: 5;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div id="overlay" style="position:fixed; top:0; left:0; width:100%; height:100%; z-index:9999; background-color:black;"></div>
    <div>
        <div class="relative min-h-screen  grid bg-black ">
            <div class="flex flex-col sm:flex-row items-center md:items-start sm:justify-center md:justify-start flex-auto min-w-0 ">
                <div class="relative sm:w-1/2 xl:w-3/5 bg-green-500 h-full hidden md:flex flex-auto items-center justify-center p-10 overflow-hidden  text-white bg-no-repeat bg-cover relative" style="background-image: url(logo.png);">
                    <div class="absolute bg-black  opacity-25 inset-0 z-0"></div>
                    <div class="w-full  lg:max-w-2xl md:max-w-md z-10 items-center text-center ">
                        <div class=" font-bold leading-tight mb-6 mx-auto w-full content-center items-center ">

                        </div>
                    </div>
                </div>
                <div class="md:flex md:items-center md:justify-left w-full sm:w-auto md:h-full xl:w-1/2 p-8  md:p-10 lg:p-14 sm:rounded-lg md:rounded-none ">
                    <div class="max-w-xl w-full space-y-12">
                        <div class="lg:text-left text-center">

                            <div class="flex items-center justify-center ">
                                <div class="bg-black flex flex-col w-80 rounded-lg px-8 py-10">

                                    <form [formGroup]="createaccount" method="POST" class="flex flex-col space-y-8 mt-10">
                                        <label class="font-bold text-lg text-white ">Username</label>
                                        <input name="nume" type="text" formControlName="accnum" placeholder="Username" class="border rounded-lg py-3 px-3 mt-4 bg-black placeholder-white-500 text-white" style="border-color: #3dc147;">


                                        <label class="font-bold text-lg text-white">Password</label>
                                        <div class="relative">
                                            <input id="id_password" type="password" name="password" placeholder="****" class="border rounded-lg py-3 px-3 bg-black placeholder-white-500 text-white w-full" style="border-color: #3dc147;">
                                            <i class="far fa-eye absolute top-1/2 transform -translate-y-1/2 right-4 text-white cursor-pointer" id="togglePassword"></i>
                                        </div>
                                        <button type="submit" class="border bg-black text-white rounded-lg py-3 font-semibold" style="border-color: #963bc1;">Log In</button>
                                    </form>
                                </div>
                            </div>

                        </div>

                    </div>
    



                    <script>
                        const togglePassword = document.querySelector('#togglePassword');
                        const password = document.querySelector('#id_password');

                        togglePassword.addEventListener('click', function(e) {
                            // toggle the type attribute
                            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                            password.setAttribute('type', type);
                            // toggle the eye slash icon
                            this.classList.toggle('fa-eye-slash');
                        });

                        window.onload = function() {
                            var overlay = document.getElementById('overlay');
                            overlay.style.display = 'none';
                        };
                    </script>
                    
</body>


</html>