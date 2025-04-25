<?php
    session_start();

    if (!isset($_SESSION['username']) && !isset($_SESSION['role'])) {
        header("Location: login.php");
        exit();
    }
    $username=$_SESSION['username'];
    
    if (isset($_POST['loutBtn'])) {
        unset($_SESSION['username']);
        unset($_SESSION['role']);
        header("Location: login.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediTrack - Patient Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            background-image: url(image4.jpg);
            background-size: cover;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }
        h1 {
            color: #2c6ad3;
        }
        .userInfo {
            display: flex;
            align-items: center;
        }
        .userInfo span {
            margin-right: 15px;
            font-weight: bold;
        }
        .logoutBtn {
            padding: 8px 15px;
            background-color: #d32c2c;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .logoutBtn:hover {
            background-color: #b32424;
        }
        .medicationsContainer {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .emptyMessage {
            text-align: center;
            color: #666;
            margin: 20px 0;
            font-style: italic;
        }
        .scrollable-div {
            width: 1200px; /* Set width */
            height: 540px; /* Set height */
            border: 1px solid #ccc;
            padding: 10px;
            overflow-y: scroll; /* Enables vertical scrolling */
            overflow-x: hidden; /* Hides horizontal scrolling */
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>MediTrack - Patient Dashboard</h1>
            <div class="userInfo">
                <span id="patientName"><?php echo $username; ?></span>
                <form method="POST">
                <button class="logoutBtn" id="logoutBtn" name="loutBtn">Logout</button>
                </form>
            </div>
        </header>
        
        <div class="medicationsContainer scrollable-div">
            <h2>My Medications</h2>
            <div id="medicationsList">
                <!-- Medications will be displayed here -->

                <?php
                     $servername = "localhost";
                     $user = "root";
                     $password = "";
                     $dbname = "";
                     $con = mysqli_connect($servername,$user,$password,$dbname);                
                     if (!$con) {
                         die("Connection failed: " . mysqli_connect_error());
                     }
                     else{
                        $username=$_SESSION['username'];
                         $query = "SELECT * FROM P$username;";
                         $result = mysqli_query($con, $query);
                         $user = mysqli_fetch_array($result);

                        if ($user) {
                            echo "<div style='border: 2px solid black; border-radius: 3px; padding:3px; box-shadow: 2px 2px 5px 2px;'><div style='font-size: 18px;font-weight: bold;color: #2c6ad3;margin-bottom: 10px;'><strong style='width: 100px;color: #333;'>Medication: </strong>$user[0]</div>
                            <div style='margin-bottom: 5px;color: #555;'><strong style='display: inline-block;width: 100px;color: #333;'>Dosage:</strong>$user[1]</div>
                            <div style='margin-bottom: 5px;color: #555;'><strong style='display: inline-block;width: 100px;color: #333;'>Frequency:</strong>$user[2]</div>
                            <div style='margin-bottom: 5px;color: #555;background-color: #e6f0ff;padding: 8px;border-radius: 5px;margin-top: 5px;'><strong style='display: inline-block;width: 100px;color: #333;'>Time to take:</strong>$user[3]</div>
                            <div style='font-size: 14px;color: #777;margin-top: 10px;font-style: italic;'>Prescribed by Dr. $user[4]</div></div><br>";
                            
                            while($row = mysqli_fetch_array($result)) {
                            echo "<div style='border: 2px solid black; border-radius: 3px; padding:3px; box-shadow: 2px 2px 5px 2px;'><div style='font-size: 18px;font-weight: bold;color: #2c6ad3;margin-bottom: 10px;'><strong style='width: 100px;color: #333;'>Medication: </strong>$row[0]</div>
                            <div style='margin-bottom: 5px;color: #555;'><strong>Dosage:</strong style='display: inline-block;width: 100px;color: #333;'>$row[1]</div>
                            <div style='margin-bottom: 5px;color: #555;'><strong>Frequency:</strong style='display: inline-block;width: 100px;color: #333;'>$row[2]</div>
                            <div style='margin-bottom: 5px;color: #555;background-color: #e6f0ff;padding: 8px;border-radius: 5px;margin-top: 5px;'><strong style='display: inline-block;width: 100px;color: #333;'>Time to take:</strong>$row[3]</div>
                            <div style='font-size: 14px;color: #777;margin-top: 10px;font-style: italic;'>Prescribed by Dr. $row[4]</div></div><br>";
                             }
                            }
                            else{
                                echo "<p class='emptyMessage'>No medications prescribed yet.</p>";
                            }
                     }  
                     mysqli_close($con); 
                ?>
            </div>
        </div>
    </div>
</body>
</html>
