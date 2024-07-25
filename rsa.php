<?php

function generateKeyPair()
{
    // Generate two distinct prime numbers (for simplicity, you might want to use a more robust method)
    $p = 61;
    $q = 53;

    $n = $p * $q;
    $phi = ($p - 1) * ($q - 1);

    // Choose public exponent (e) - commonly used value is 65537
    $e = 65537;

    // Calculate private exponent (d)
    $d = modInverse($e, $phi);

    return [
        'publicKey' => [$n, $e],
        'privateKey' => [$n, $d]
    ];
}

function modInverse($a, $m)
{
    $m0 = $m;
    $x0 = 0;
    $x1 = 1;

    while ($a > 1) {
        $q = intdiv($a, $m);
        [$a, $m] = [$m, $a % $m];
        [$x0, $x1] = [$x1 - $q * $x0, $x0];
    }

    return ($x1 + $m0) % $m0;
}

function encryptMessage($message, $publicKey)
{
    [$n, $e] = $publicKey;
    $encryptedMessage = [];

    for ($i = 0; $i < strlen($message); $i++) {
        $char = ord($message[$i]);
        $encryptedMessage[] = bcpowmod((string)$char, (string)$e, (string)$n);
    }

    return $encryptedMessage;
}

function decryptMessage($encryptedMessage, $publicKey, $privateKey)
{
    [$n, $d] = $privateKey;
    $decryptedMessage = '';

    foreach ($encryptedMessage as $char) {
        // Convert $char to string before passing it to bcpowmod
        $decryptedChar = bcpowmod((string)$char, (string)$d, (string)$n);
        $decryptedMessage .= chr(intval($decryptedChar));
    }

    return $decryptedMessage;
}

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

// Retrieve data from POST request
$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$hashedPassword = isset($_POST['hashedPassword']) ? $_POST['hashedPassword'] : '';
$selectedAction = isset($_POST['selectedAction']) ? $_POST['selectedAction'] : '';

// Example usage:
echo '<html lang="en">';
echo '<head>';
echo '    <meta charset="UTF-8">';
echo '    <meta name="viewport" content="width=device-width, initial-scale=1.0">';
echo '    <link rel="stylesheet" href="bootstrap.css">';
echo '    <script src="js-sha256.js"></script>';
echo '    <title>Obtain Hash</title>';
echo '    <style>';
echo '        body {';
echo '            background-image: url("security.png");';
echo '            margin: 0;';
echo '            padding: 0px;';
echo '            height: 100%;';
echo '        }';
echo '';
echo '        .form-container {';
// echo '            max-width: 80%';
// echo '            width: 100px';
echo '            border: 10px solid #ACE5EE	;';
echo '            padding: 0px;';
echo '            margin-top: 50px;';
echo '            text-align: center;';
echo '            background-color: #0277bd;';
echo '        }';
echo '';
echo '        h2 {';
echo '            color: #ad1457;';
echo '        }';
echo '';

echo '        .btn-primary {';
echo '            background-color:#7BB661	;';
echo '            border-color: green;';
echo '        }';
echo '';
echo '        #hashResult {';
echo '            margin-top: 20px;';
echo '        }';

echo '';
echo '         label1 {';        
echo '         display: block;';
//echo '         margin-bottom: 15px;';
echo '          color: #333; /* Text color for labels */}';

echo '';
echo '        #resultText, #userData {';
echo '            font-weight: bold;';
echo '            color: #E9D66B		;'; // green color for user data
echo '        }';
echo '';
echo '        #resultText{';
echo '            color: #E9D66B	;'; // Green color for result text
echo '        }';
echo '    </style>';
echo '</head>';
echo '<body>';
echo '';
echo '<div class="container">';
echo '    <div class="row justify-content-center">';
echo '        <div class="col-md-6 form-container">';
if ($selectedAction == 'signup') {
    // Generate key pair
    $keyPair = generateKeyPair();
    $publicKey = $keyPair['publicKey'];
    $privateKey = $keyPair['privateKey'];

    // Use hashed password for encryption
    $encryptedMessage = encryptMessage($hashedPassword, $publicKey);

    // Convert the encrypted data to base64 for storage
    $encryptedPasswordBase64 = base64_encode(implode(',', $encryptedMessage));

    // Store data in the same sequence: username, password, public_key, private_key, encrypted_password
    $password = $hashedPassword;
    $sql = "INSERT INTO madhi (username, Password, public_key, private_key, encrypted_password) VALUES ('$username', '$password', '$publicKey[0],$publicKey[1]', '$privateKey[0],$privateKey[1]', '$encryptedPasswordBase64')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Sign Up Successful');</script>";
        // Display user details in a form
        echo "<div id='userData' style='text-align:center;'>";
        echo "<h2>User Details</h2>";
        echo "<form>";
        echo "<label1>Username: </label1><span id='resultText'>$username</span><br>";
        echo "<br>";
        echo "<label1>Hashed Password: </label1><span id='resultText'></span><br>";
        function displayTextInLines2($hashedPassword, $charactersPerLine = 50) {
            $lines = str_split($hashedPassword, $charactersPerLine);
            foreach ($lines as $line) {
                echo $line . "<br>";
            }
        }
        displayTextInLines2($hashedPassword);
        echo "<br>";
        echo "<label1>Encrypted Password: </label1><span id='resultText'></span><br>";

        function displayTextInLines($encryptedPasswordBase64, $charactersPerLine = 50) {
            $lines = str_split($encryptedPasswordBase64, $charactersPerLine);
            foreach ($lines as $line) {
                echo $line . "<br>";
            }
        }
        displayTextInLines($encryptedPasswordBase64);
        echo "<br>"; 
        //echo "<label>Encrypted Password: </label><span id='resultText'>$encryptedPasswordBase64</span><br>";
        echo "<form action='2.php' method='post'>";
        // echo "<input type='submit' name='finishButton' value='Finish' style='background-color:#7BB661;font-weight: bold;' 	>";
        if ($selectedAction === 'signup') {
            // ... (your existing signup code)
        
            echo '<script>alert("Thank you for signing up!");</script>';
            echo '<meta http-equiv="refresh" content="5;url=2.php">';
        }
        
        echo "</form>";
        echo "</form>";
         
        echo "</div>";
      
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} elseif ($selectedAction == 'validate') {
    // Retrieve data in the same sequence: username, password, public_key, private_key, encrypted_password
    $sql = "SELECT username, password, public_key, private_key, encrypted_password FROM madhi WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result === false) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    } elseif ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $publicKeyDB = $row['public_key'];
        $privateKeyDB = $row['private_key'];
        $encryptedPasswordBase64 = $row['encrypted_password'];

        // Convert the base64 encoded string back to an array
        $encryptedMessageDB = array_map('intval', explode(',', base64_decode($encryptedPasswordBase64)));

        // Use the correct private key retrieved from the database
        $privateKeyDB = array_map('intval', explode(',', $privateKeyDB));
        $decryptedPassword = decryptMessage($encryptedMessageDB, $publicKeyDB, $privateKeyDB);

        // Check if the decrypted password matches the provided hashed password
        if ($decryptedPassword == $hashedPassword) {
            echo "<script>alert('Sign In Successful');</script>";
            // Display user details in a form
            echo "<div id='userData' style='text-align:center;'>";
            echo "<h2>User Details</h2>";
            echo "<form>";
            echo "<label1>Username: </label1><span id='resultText'>$username</span><br>";
            echo"<br>";
            echo "<label1>Hashed Password: </label1><span id='resultText'></span><br>";

            function displayTextInLines2($hashedPassword, $charactersPerLine = 50) {
                $lines = str_split($hashedPassword, $charactersPerLine);
                foreach ($lines as $line) {
                    echo $line . "<br>";
                }
            }
            displayTextInLines2($hashedPassword);
            echo"<br>";
            echo "<label1>Encrypted Password: </label1><span id='resultText'></span><br>";

            function displayTextInLines($encryptedPasswordBase64, $charactersPerLine = 50) {
                $lines = str_split($encryptedPasswordBase64, $charactersPerLine);
                foreach ($lines as $line) {
                    echo $line . "<br>";
                }
            }
            displayTextInLines($encryptedPasswordBase64);
        echo "<br>"; 
            
            echo "<form action='2.php' method='post'>";

            if ($selectedAction === 'validate') {
                // ... (your existing validation code)
            
                echo '<script>alert("Sign In Successful. Redirecting to GeeksforGeeks in 5 seconds.");</script>';
                echo '<meta http-equiv="refresh" content="5;url=https://www.geeksforgeeks.org">';
            }
            
        } else {
            echo "<script>alert('Invalid Username or Password');</script>";
            echo '<meta http-equiv="refresh" content="0;url=2.php">';

        }
    } 
}

// Additional code for redirection
if(isset($_POST['finishButton'])){
    header("Location: 2.php");
    exit();
}

echo '</div>';
echo '</div>';
echo '</div>';
echo '';
echo '<script src="popper.js"></script>';
echo '<script src="bootstrap.js"></script>';
echo '</body>';
echo '</html>';

// Close the database connection
$conn->close();
?>
