<?php
// Retrieve data from POST request
$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$selectedAction = isset($_POST['selectedAction']) ? $_POST['selectedAction'] : '';

// Your hashing logic here, for example:
$hashedPassword = hash('sha256', $password);
$d=$hashedPassword;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Add Bootstrap CSS link here (if not already included) -->
    <link rel="stylesheet" href="bootstrap.css">
    <title>Encrypt Hashed Password</title>

    <style>
        body {
            background-color: #0277bd;
            padding: 100px;
            text-align: center;

            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #0277bd;
            padding:150px;
            margin-bottom: 150px;
            border: 5px solid #b2ebf2;
        }

        p {
            color:black;
            margin: 0;
            padding: 5px;
        }

        button.btn {
            padding: 5px 30px;
            font-size: 15px;
            background-color: green;
            border-color: green;
            font-weight: bold;
            
        }
    </style>
</head>

<body>

    <form action="rsa.php" method="post">
        <p><b>User_entered_Password: <span id="originalPassword"style="color:#CAE00D	"><?php echo $password; ?></span></p>
        <br>
        <p><b>Hashed Password:</b> <span id="hashedPassword"style="color:#CAE00D	"><?php echo $d; ?></span></p>
        

        <input type="hidden" name="username" value="<?php echo $username; ?>">
        <input type="hidden" name="password" value="<?php echo $password; ?>">
        <input type="hidden" name="hashedPassword" value="<?php echo $hashedPassword; ?>">
        <input type="hidden" name="selectedAction" value="<?php echo $selectedAction; ?>">
        <br>
        <button type="submit" class="btn btn-primary" style="background-color: green; border-color: green;">Encrypt Hashed Password</button>
    </form>

    <script>
        // Use JavaScript to dynamically update content
        document.getElementById('hashedPassword').innerText = '<?php echo $hashedPassword; ?>';
        document.getElementById('originalPassword').innerText = '<?php echo $password; ?>';
    </script>

    <!-- Add Bootstrap JS and Popper.js scripts here (if needed) -->
    <script src="popper.js"></script>
    <script src="bootstrap.js"></script>
</body>

</html>







