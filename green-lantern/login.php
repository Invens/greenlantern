<?php
session_start();
include "config.php";
include "./assets/components/login-arc.php";

if(isset($_COOKIE['logindata']) && $_COOKIE['logindata'] == $key['token'] && $key['expired'] == "no"){
    $_SESSION['IAm-logined'] = 'yes';
    header("location: panel.php");
}

elseif(isset($_SESSION['IAm-logined'])){
    header('location: panel.php');
    exit;
}

else{ 
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - AbhiSecurity</title>
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #0d0d0d;
            font-family: 'Courier New', monospace;
            overflow: hidden;
        }
        
        .wrapper {
            margin-top: 10%;
            padding: 20px;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        .form-signin {
            background: #1a1a1a;
            padding: 30px;
            border-radius: 5px;
            border: 1px solid #00ff00;
            box-shadow: 0 0 10px #00ff00;
        }

        .form-signin-heading {
            color: #00ff00;
            text-align: center;
            text-transform: uppercase;
            margin-bottom: 30px;
            text-shadow: 0 0 5px #00ff00;
        }

        .form-control {
            background: #0d0d0d;
            border: 1px solid #00ff00;
            color: #00ff00;
            margin-bottom: 15px;
            font-family: 'Courier New', monospace;
        }

        .form-control:focus {
            background: #0d0d0d;
            color: #00ff00;
            box-shadow: 0 0 5px #00ff00;
            border-color: #00ff00;
        }

        .btn-primary {
            background: #00ff00;
            border: none;
            color: #0d0d0d;
            font-weight: bold;
            text-transform: uppercase;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background: #00cc00;
            box-shadow: 0 0 10px #00ff00;
        }

        .footer-text {
            color: #00ff00;
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            text-shadow: 0 0 3px #00ff00;
        }

        /* Matrix-like background effect */
        canvas {
            position: fixed;
            top: 0;
            left: 0;
            z-index: -1;
            opacity: 0.1;
        }
    </style>
</head>

<body>
    <canvas id="matrix"></canvas>
    
    <div class="wrapper">
        <form action="" class="form-signin" method="POST">       
            <h2 class="form-signin-heading">System Access</h2>
            <input type="text" class="form-control" name="username" placeholder="Username" required="" autofocus="" /><br>
            <input type="password" class="form-control" name="password" placeholder="Password" required=""/>     
            <button class="btn btn-lg btn-primary btn-block" type="submit">Initiate Login</button> 

            <?php
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                if(isset($_POST['username']) && isset($_POST['password'])){
                    $username = $_POST['username'];
                    $password = $_POST['password'];

                    if(isset($CONFIG[$username]) && $CONFIG[$username]['password'] == $password){
                        $_SESSION['IAm-logined'] = $username;
                        echo '<script>location.href="panel.php"</script>';
                    } else {
                        echo '<p style="color:#ff4444"><br>Access Denied: Invalid Credentials!</p>';
                    }
                }
            }
            ?>
        </form>
        <div class="footer-text">Powered by AbhiSecurity Group</div>
    </div>

    <script>
        // Matrix effect
        const canvas = document.getElementById('matrix');
        const ctx = canvas.getContext('2d');

        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        const fontSize = 16;
        const columns = canvas.width/fontSize;
        const drops = [];

        for(let x = 0; x < columns; x++) drops[x] = 1;

        function draw() {
            ctx.fillStyle = 'rgba(0, 0, 0, 0.05)';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            
            ctx.fillStyle = '#00ff00';
            ctx.font = fontSize + 'px monospace';

            for(let i = 0; i < drops.length; i++) {
                const text = letters.charAt(Math.floor(Math.random() * letters.length));
                ctx.fillText(text, i * fontSize, drops[i] * fontSize);
                
                if(drops[i] * fontSize > canvas.height && Math.random() > 0.975)
                    drops[i] = 0;
                
                drops[i]++;
            }
        }

        setInterval(draw, 33);
    </script>
</body>
</html>

<?php
}
?>