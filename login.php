<?php
// session_set_cookie_params(0); 
session_start();
$activeForm = "login";

$username = $userpassword = $urole = $userError = $upassError = $error = "";
$nusername = $nuserpassword = $nurole = $nuserError = $nupassError = $nerror = $phoneError = "";
if(isset($_POST['loginBtn'])){

    if($_SERVER["REQUEST_METHOD"] == "POST"){
   
    $servername = "localhost";
    $user = "root";
    $password = "Password";

    $dbname = "MYDATABASE";
    $con = mysqli_connect($servername,$user,$password,$dbname);

   if (!$con) {
            die("Connection failed: " . mysqli_connect_error());
          }
    else{
     
        $username = trim($_POST["uname"]);
        $userpassword = trim($_POST["upassword"]);  
        $urole = trim($_POST['role']);
        if (empty($_POST["uname"])) {
            $userError = "*Please Enter Username!!!";
            
        } 
        if (empty($_POST["upassword"])) {
            $upassError = "*Please Enter Password!!!";
        } 
        else{
        $query = "SELECT * FROM $urole WHERE USERNAME = '$username' AND PASSWORD = '$userpassword';";
        $result = mysqli_query($con, $query);
        $user = mysqli_fetch_assoc($result);

        if ($user) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $urole;            
        } else {
            $error = "Invalid Username or Password";
            $username = $userpassword = "";
        }
    
        }
    }  
    mysqli_close($con); 
    }
}
if (isset($_SESSION['username'])) {
    if($_SESSION['role']=="patient"){
        header("Location: patient-dashboard.php");
        exit();
    }
    else if($_SESSION['role']=="doctor"){
        header("Location: doctor-dashboard.php");
        exit();
    }
}
$nphone='';
if(isset($_POST['signupBtn'])){

    if($_SERVER["REQUEST_METHOD"] == "POST"){
   
    $servername = "localhost";
    $user = "root";
    $password = "Password";

    $dbname = "MYDATABASE";
    $con = mysqli_connect($servername,$user,$password,$dbname);

   if (!$con) {
            die("Connection failed: " . mysqli_connect_error());
          }
    else{
     
        $nusername = trim($_POST["new-username"]);
        $nuserpassword = trim($_POST["new-password"]);  
        $nurole = trim($_POST['new-role']);
        $nphone = trim($_POST['phone']);
        if (empty($_POST["new-username"])) {
            $nuserError = "*Please Enter Username!!!";
            $activeForm = "signup"; // Keep signup form open
        } 
        if (empty($_POST["new-password"])) {
            $nupassError = "*Please Enter Password!!!";
            $activeForm = "signup"; // Keep signup form open
        } 
        if (empty($_POST['phone'])) {
            $phoneError = "*Please Enter Moblie Number!!!";
            $activeForm = "signup"; // Keep signup form open
        } 

        else{
        $query = "SELECT * FROM $nurole WHERE USERNAME = '$nusername';";
        $result = mysqli_query($con, $query);
        $user = mysqli_fetch_assoc($result);

        if ($user) {
            $nerror = "Entered Username already exist! Try new Username!";
            $activeForm = "signup"; 
        } 
        else {
            $query = "INSERT INTO $nurole VALUES ('$nusername','$nuserpassword','$nphone');";
            
            if(mysqli_query($con, $query)){
                
                if($nurole=="doctor"){
                $query = "CREATE TABLE D$nusername(
                          PATIENT VARCHAR(30),
                          MEDICINE VARCHAR(40),
                          DOSAGE VARCHAR(20),
                          FREQUENCY VARCHAR(30),
                          TIMETOTAKE VARCHAR(30));";
                          $result = mysqli_query($con, $query);
                }
                else if($nurole=="patient"){
                    $query = "CREATE TABLE P$nusername(
                        MEDICINE VARCHAR(40),
                        DOSAGE VARCHAR(20),
                        FREQUENCY VARCHAR(30),
                        TIMETOTAKE VARCHAR(30),
                        DOCTOR VARCHAR(30));";
                        $result = mysqli_query($con, $query);
                }
                echo "<script>
                      alert('Signup successful! Redirecting to login...');
                      window.location.href = 'login.php';
                      </script>";
            }
            else{
                die("Error in Creating New Account!!!");
            }
        }
    
        }
    }  
    mysqli_close($con); 
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediTrack - Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            background-color: #f0f5ff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: url(image2.jpg);
            background-size: cover;
        }
        .container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 500px;
            padding: 30px;
        }
        h1 {
            text-align: center;
            color: #2c6ad3;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #2c6ad3;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background-color: #1c4da6;
        }
        .switch-form {
            text-align: center;
            margin-top: 20px;
        }
        .switch-form a {
            color: #2c6ad3;
            text-decoration: none;
        }
        .switch-form a:hover {
            text-decoration: underline;
        }
        .errorMessage {
            color: #d32c2c;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>MediTrack</h1>
        <div id="loginForm" name="loginForm">
            <h2 style="text-align: center; margin-bottom: 20px;">Login</h2>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" >
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="uname" name="uname" value="<?php echo htmlspecialchars($username); ?>" >
                <p id="lusernameError" style="color: red;"><?php echo $userError; ?></p>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="upassword" value="<?php echo htmlspecialchars($userpassword); ?>" >
                <p id="lpasswordError" style="color: red;"><?php echo $upassError; ?></p>
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role">
                    <option value="doctor">Doctor</option>
                    <option value="patient">Patient</option>
                </select>
            </div>
            <div id="loginError" class="errorMessage"><?php echo $error; ?></div>
            <button id="loginButton" name="loginBtn" type="submit">Login</button>
        </form>
            <div class="switch-form">
                <p>Don't have an account? <a href="#" id="showSignup">Sign Up</a></p>
            </div>
        </div>
        
        <div id="signupForm" name="signupForm" style="display: none;">
            <h2 style="text-align: center; margin-bottom: 20px;">Sign Up</h2>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
            <div class="form-group">
                <label for="new-username">Username</label>
                <input type="text" id="new-username" name="new-username" value="<?php echo htmlspecialchars($nusername); ?>">
                <p id="susernameError" style="color: red;"><?php echo $nuserError ?></p>
            </div>
            <div class="form-group">
                <label for="new-password">Password</label>
                <input type="password" id="new-password" name="new-password" value="<?php echo htmlspecialchars($nuserpassword); ?>">
                <p id="spasswordError" style="color: red;"><?php echo $nupassError ?></p>
            </div>
            <div class="form-group">
                <label for="phone">Mobile Number:</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($nphone); ?>">
                <p id="sphoneError" style="color: red;"><?php echo $phoneError ?></p>
            </div>

            <div class="form-group">
                <label for="new-role">Role</label>
                <select id="new-role" name="new-role">
                    <option value="doctor">Doctor</option>
                    <option value="patient">Patient</option>
                </select>
            </div>
            <div id="signupError" class="errorMessage"><?php echo $nerror; ?></div>
            <button id="signupButton" name="signupBtn" type="submit">Sign Up</button>
        </form>
            <div class="switch-form">
                <p>Already have an account? <a href="#" id="showLogin">Login</a></p>
            </div>
        </div>
    </div>

    <script>
    // Determine which form should be displayed
    var activeForm = "<?php echo $activeForm; ?>";
    if (activeForm === "signup") {
        document.getElementById('loginForm').style.display = 'none';
        document.getElementById('signupForm').style.display = 'block';
    } else {
        document.getElementById('signupForm').style.display = 'none';
        document.getElementById('loginForm').style.display = 'block';
    }
</script>

    <script>
        // Toggle between login and signup forms
        document.getElementById('showSignup').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('loginForm').style.display = 'none';
            document.getElementById('signupForm').style.display = 'block';
        });

        document.getElementById('showLogin').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('signupForm').style.display = 'none';
            document.getElementById('loginForm').style.display = 'block';
        });

        // Handle signup
        document.getElementById('signupButton').addEventListener('click', function() {
            const username = document.getElementById('new-username').value;
            const password = document.getElementById('new-password').value;
            const role = document.getElementById('new-role').value;
            const errorElement = document.getElementById('signupError');
            const phone = document.getElementById('phone').value;
            
            if (!username || !password) {
                errorElement.textContent = 'Please fill in all fields!';
                event.preventDefault(); // Prevent form submission
                return;
            }
            else{
                errorElement.textContent = '';
            }

            if(username.length<5 || username.length>15)
            {
                document.getElementById('susernameError').textContent = "*Please enter valid username 5-15 characters";
                event.preventDefault(); // Prevent form submission
                return;
            }
            else if(username.match(/^[\d\W_]/))
            {
                document.getElementById('susernameError').textContent = "*Please enter valid username can't begin with digits/symbols";
                event.preventDefault(); // Prevent form submission
                return;
            }
            document.getElementById('susernameError').textContent = "";
            
            
            if(password.length<7 || password.length>15)
            {
                document.getElementById('spasswordError').textContent = "*Please enter valid password 7-15 characters";
                event.preventDefault(); // Prevent form submission
                return;
            }
            else if(password.match(/^[\d\W_]/))
            {
                document.getElementById('spasswordError').textContent = "*Please enter valid password can't begin with digits/symbols";
                event.preventDefault(); // Prevent form submission
                return;
            }
            else if(!(password.match(/^[A-Za-z][A-Za-z\d\W_]*(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]*$/)))
            {
                document.getElementById('spasswordError').textContent = "*Please enter valid password with numbers,symbols..";
                event.preventDefault(); // Prevent form submission
                return;
            }
            document.getElementById('spasswordError').textContent = "";

            if(!(phone.match(/^[1-9]{1}[0-9]{9}/)))
            {
                document.getElementById('sphoneError').textContent = "*Please Enter Valid Mobile Number!";
                return;
            }
            else{
                document.getElementById('sphoneError').textContent = "";
            }
            
            
            // // Clear fields and show success
            // document.getElementById('new-username').value = '';
            // document.getElementById('new-password').value = '';
            // errorElement.textContent = '';
            
            // alert('Account created successfully! Please login.');
            
            // // Switch to login form
            // document.getElementById('signupForm').style.display = 'none';
            // document.getElementById('loginForm').style.display = 'block';
        });

        // Handle login
    document.getElementById('loginButton').addEventListener('click', function(event) {
    const username = document.getElementById('uname').value.trim();
    const password = document.getElementById('password').value.trim();
    const errorElement = document.getElementById('loginError');
    
    let isValid = true; // Track if form is valid

    if (!username || !password) {
        errorElement.textContent = 'Please fill in all fields!';
        isValid = false;
    } else {
        errorElement.textContent = '';
    }

    if (!isValid) {
        event.preventDefault(); // Prevent form from submitting
    }
    });

    </script>
</body>
</html>