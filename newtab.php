<?php
// Retrieve data from the query parameters
$selectedAction = isset($_GET['selectedAction']) ? $_GET['selectedAction'] : '';
$username = isset($_GET['username']) ? $_GET['username'] : '';
$password = isset($_GET['password']) ? $_GET['password'] : '';

// Database connection parameters
$servername = "localhost";
$usernameDB = "root";
$passwordDB = "";
$dbname = "shubham";

// Create connection
$conn = new mysqli($servername, $usernameDB, $passwordDB, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Add Bootstrap CSS link here (if not already included) -->
    <link rel="stylesheet" href="bootstrap.css">
    <script src="js-sha256.js"></script> <!-- Include js-sha256 library -->
    <title>Obtain Hash</title>
    <style>
        body {
            background-image: url("newtab.png");
        }

        .form-container {
            border: 5px solid #b2ebf2;
            padding: 150px;
            margin-top: 100px;
            text-align: center;
            background-color:#0277bd;
            font-weight: bold;

            
        }

        button.btn {
            padding: 10px 30px;
            font-size: 20px;
            background-color: green;
            border-color: green;
            font-weight: bold;
            
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 form-container">
            <?php
            if ($selectedAction === 'validate' || $selectedAction === 'signup') {
                echo '<h2 style="color:#ad1457"><i>' . ($selectedAction === 'validate' ? 'AUTHENTICATION' : 'REGISTRATION') . ' PHASE</i></h2>';

                // Check if the username exists in the database for the selected action
                $checkUsernameQuery = "SELECT * FROM madhi WHERE username = '$username'";
                $result = $conn->query($checkUsernameQuery);

                if ($selectedAction === 'validate') {
                    if ($result->num_rows > 0) {
                        echo '<button onclick="obtainHash(\'' . $username . '\', \'' . $password . '\', \'' . $selectedAction . '\')" class="btn btn-primary" style="background-color: green; border-color: green;"><strong>Obtain Hash</strong></button>';
                    } else {
                        echo '<script>alert("User not registered."); window.location.href = "2.php";</script>';
                        exit(); // Stop further execution to prevent displaying "Invalid Action" message
                    }
                } elseif ($selectedAction === 'signup') {
                    if ($result->num_rows > 0) {
                        echo '<script>alert("User already registered. Please choose another username."); window.location.href = "2.php";</script>';
                        exit(); // Stop further execution to prevent displaying "Invalid Action" message
                    } else {
                        echo '<button onclick="obtainHash(\'' . $username . '\', \'' . $password . '\', \'' . $selectedAction . '\')" class="btn btn-primary "style="background-color: green; border-color: green;"><strong>Obtain Hash</strong></button>';
                    }
                }
            } else {
                echo '<h2 style="color:red"><i>Invalid Action</i></h2>';
            }
            ?>

            <div id="hashResult" style="margin-top: 20px;"></div>
        </div>
    </div>
</div>

<script>
    function obtainHash(username, password, selectedAction) {
        // Send data to hash.php using AJAX
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "hash.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                document.body.innerHTML = xhr.responseText; // Replace the entire content of the body with hash.php content
                alert("Hash obtained successfully!");
            }
        };
        xhr.send("username=" + username + "&password=" + password + "&selectedAction=" + selectedAction);
    }
</script>

<!-- Add Bootstrap JS and Popper.js scripts here (if needed) -->
<script src="popper.js"></script>
<script src="bootstrap.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
