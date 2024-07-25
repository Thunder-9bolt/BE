<?php
$invalidUsername = false;
$invalidPassword = false;
$username = '';
$selectedAction = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedAction = isset($_POST['selectedAction']) ? $_POST['selectedAction'] : '';
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $invalidUsername = true;
    }

    if (strlen($password) < 8 || strlen($password) > 16) {
        $invalidPassword = true;
    }

    if (!$invalidUsername && !$invalidPassword && !empty($username) && !empty($password)) {
        if ($selectedAction === 'validate') {
            echo '<script>window.location.href = "newtab.php?selectedAction=' . $selectedAction . '&username=' . $username . '&password=' . $password . '";</script>';
        } elseif ($selectedAction === 'signup') {
            echo '<script>window.location.href = "newtab.php?selectedAction=' . $selectedAction . '&username=' . $username . '&password=' . $password . '";</script>';
        }
    } else {
        if (empty($username) || empty($password)) {
            echo '<script>alert("Username and password cannot be empty.");</script>';
        } elseif ($invalidUsername) {
            echo '<script>alert("Invalid username. Please enter a valid email address.");</script>';
        } elseif ($invalidPassword) {
            echo '<script>alert("Invalid password. Password must be between 8 and 16 characters.");</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap.css">
    <title>WELCOME</title>
    <style>
        body {
            background-color: peru;
            background-size: cover;
            background-position: center;
            text-align: center;
            padding: 50px;
            background-image: url("security.png");
        }

        .form-container {
            border: 5px solid #b2ebf2;
            padding: 70px;
            margin-top: 50px;
            text-align: center;
            background-color: #0277bd;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 20px;
            font-weight: bold;
        }

        input.form-control {
            width: 60%;
           
            padding: 10px;
            margin-bottom: 15px;
        }

        button.btn {
            padding: 10px 30px;
            font-size: 20px;
            background-color: green;
            border-color: green;
            font-weight: bold;
            
        }

        .warning {
            color: #76ff03;
        }

        .success {
            color: green;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 form-container">
                <h2 style="color:#ad1457"><i>WELCOME</i></h2>

                <form action="" method="post" onsubmit="return validateForm()">
                    <label for="inputUsername"><b>Username (Email):</label>
                    <input type="text" id="inputUsername" name="username" class="form-control" required>
                    <br>

                    <label for="inputPassword"><b>Password:</label>
                    <div class="input-group">
                        <input type="password" id="inputPassword" name="password" class="form-control" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <input type="checkbox" onclick="showPassword()"> Show Password
                            </div>
                        </div>
                    </div>
                    <br>

                    <label1 for="signIn">Sign In</label1>
                    <input type="radio" id="signIn" name="selectedAction" value="validate">

                    <label1 for="signUp">Sign Up</label1>
                    <input type="radio" id="signUp" name="selectedAction" value="signup">
                    <br>
                    <br>
                    <button type="submit" class="btn btn-primary">Validate</button>

                    <?php
                    if ($invalidUsername) {
                        //echo '<div class="warning">Invalid username. Please enter a valid email address.</div>';
                    }

                    if ($invalidPassword) {
                        echo '<div class="warning">Invalid password. Password must be between 8 and 16 characters.</div>';
                    }
                    ?>

                    <div class="warning" id="warningMessage"></div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function validateForm() {
            var username = document.getElementById("inputUsername").value;
            var password = document.getElementById("inputPassword").value;
            var warningMessage = document.getElementById("warningMessage");
            var selectedAction = document.querySelector('input[name="selectedAction"]:checked');

            if (selectedAction === null) {
                warningMessage.textContent = "Please select an action (Sign In or Sign Up).";
                return false;
            }

            selectedAction = selectedAction.value;

            if (!filterEmail(username)) {
                warningMessage.textContent = "Invalid username. Please enter a valid email address.";
                return false;
            } else if (username.trim() === "" || password.trim() === "") {
                warningMessage.textContent = "Username and password cannot be empty";
                return false;
            } else if (password.length < 8 || password.length > 16) {
                warningMessage.textContent = "Password must be between 8 and 16 characters";
                return false;
            } else {
                warningMessage.textContent = "";
                return true;
            }
        }

        function filterEmail(email) {
        
            return true;
        }

        function showPassword() {
            var passwordInput = document.getElementById("inputPassword");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
            } else {
                passwordInput.type = "password";
            }
        }
    </script>

    <script src="popper.js"></script>
    <script src="bootstrap.js"></script>
</body>

</html>
