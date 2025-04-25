<?php
    session_start();
    if (!isset($_SESSION['username']) && !isset($_SESSION['role'])) {
        header("Location: login.php");
        exit();
    }
    $username = $_SESSION['username'];
    
    if (isset($_POST['logoutBtn'])) {
        session_destroy();
        header("Location: login.php");
        exit();
    }
    
    $servername = "localhost";
    $user = "root";
    $password = "";
    $dbname = "";
    $con = mysqli_connect($servername, $user, $password, $dbname);
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    function loadmedication() {
        global $con, $username; // Use the existing connection
    
        $servername = "localhost";
        $user = "root";
        $password = "";
        $dbname = "";
        $con = mysqli_connect($servername, $user, $password, $dbname);
        if (!$con) {
            die("Connection failed: " . mysqli_connect_error());
        }
    
        $query = "SELECT * FROM D$username;";
        $result = mysqli_query($con, $query);
    
        if (!$result) {
            die("Error fetching data: " . mysqli_error($con));
        }
    
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td>$row[0]</td>
                  <td>$row[1]</td>
                  <td>$row[2]</td>
                  <td>$row[3]</td>
                  <td>$row[4]</td>
                  <td class='actions'>
                      <button class='edit'name='but' onclick=\"editMedication('$row[0]', '$row[1]', '$row[2]', '$row[3]', '$row[4]')\">Edit</button>
                      <button class='delete' onclick=\"confirmDelete('$row[0]', '$row[1]')\">Delete</button>
                  </td>";
            echo "</tr>";
        }
    }
    

    
    if (isset($_POST['addBtn'])) {
        $patientname = trim($_POST['patient']);
        $medicine = trim($_POST['medicine']);
        $dosage = trim($_POST['dosage']);
        $timetotake = trim($_POST['timetotake']);
        
        $servername = "localhost";
        $user = "root";
        $password = "";
        $dbname = "";
        $con = mysqli_connect($servername, $user, $password, $dbname);
        if (!$con) {
            die("Connection failed: " . mysqli_connect_error());
        }


        if (isset($_POST['timings'])) {
            $timings = $_POST['timings']; // this is an array
            $timings_string = implode(",", $timings); // convert array to JSON string

            $query1 = "INSERT INTO D$username VALUES('$patientname','$medicine','$dosage','$timings_string','$timetotake')";
            $query2 = "INSERT INTO P$patientname VALUES('$medicine','$dosage','$timings_string','$timetotake','$username')";
            mysqli_query($con, $query1) or die("Error in inserting doctor data");
            mysqli_query($con, $query2) or die("Error in inserting patient data");
        
        } else {
            echo "No timings submitted.";
        }

    }

    $oldpname=$oldmedicine=$olddosage='';
    
      
   
    if (isset($_POST['updateBtn'])) {
        //$patientname = trim($_POST['patient']);
        $medicine = trim($_POST['medicine']);
        $dosage = trim($_POST['dosage']);
        $timetotake = trim($_POST['timetotake']);
        $timings = $_POST['timings']; 
        $timings_string = implode(",", $timings); 

        $oldpname = trim($_POST['oldpname']);
        $oldmedicine = trim($_POST['oldmedicine']);
        $olddosage = trim($_POST['olddosage']);  
        
        $servername = "localhost";
        $user = "root";
        $password = "";
        $dbname = "";
        $con = mysqli_connect($servername, $user, $password, $dbname);
        if (!$con) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $query1 = "UPDATE D$username SET medicine='$medicine', dosage='$dosage', frequency='$timings_string', timetotake='$timetotake' WHERE patient='$oldpname' and medicine='$oldmedicine' and dosage='$olddosage' ";
        $query2 = "UPDATE P$oldpname SET medicine='$medicine', dosage='$dosage', frequency='$timings_string', timetotake='$timetotake' WHERE medicine='$oldmedicine' and doctor='$username'and dosage='$olddosage' ";
        mysqli_query($con, $query1) or die("Error updating doctor data");
        mysqli_query($con, $query2) or die("Error updating patient data");
    }
    
    if (isset($_POST['deleteBtn'])) {
        $patientname = $_POST['patient'];
        $medicine = $_POST['medicine'];
        $servername = "localhost";
    $user = "root";
    $password = "";
    $dbname = "";
    $con = mysqli_connect($servername, $user, $password, $dbname);
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $query1 = "DELETE FROM D$username WHERE patient='$patientname' AND medicine='$medicine'";
    $query2 = "DELETE FROM P$patientname WHERE medicine='$medicine' AND doctor='$username'";
    mysqli_query($con, $query1) or die("Error deleting doctor data");
    mysqli_query($con, $query2) or die("Error deleting patient data");
    }

    
    

    mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediTrack - Doctor Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            background-image: url(image5.jpg);
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
        .dashboard {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 20px;
        }
        .formContainer, .listContainer {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 3px 4px 8px;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .formGroup {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        input, select {
            margin-bottom:4px;
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            padding: 10px 15px;
            background-color: #2c6ad3;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #1c4da6;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .errorMessage {
            color: #d32c2c;
            font-size: 14px;
            margin-top: 5px;
        }
        .scrollable-div {
            width: 800px; /* Set width */
            height: 465px; /* Set height */
            border: 1px solid #ccc;
            padding: 10px;
            overflow-y: scroll; /* Enables vertical scrolling */
            overflow-x: hidden; /* Hides horizontal scrolling */
        }
        @media (max-width: 768px) {
            .dashboard {
                grid-template-columns: 1fr;
            }
        }
    </style>
    
</head>
<body>
    <div class="container">
        <header>
            <h1>MediTrack - Doctor Dashboard</h1>
            <div class="userInfo">
                <span id="doctorName"><?php echo $username; ?></span>
                <form method="POST">
                    <button class="logoutBtn" name="logoutBtn">Logout</button>
                </form>
            </div>
        </header>
        
        <div class="dashboard">
            <form method="POST">
                <div class="formContainer">
                    <h2 id="formTitle">Add Medication</h2>
                    <label>Patient:</label>
                    <select id="patient" name="patient" required>
                        <option value="" selected disabled>Select Patient</option>
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
                                $query = "SELECT USERNAME FROM PATIENT;";
                                $result = mysqli_query($con, $query);
                                while($row = mysqli_fetch_array($result)) {
                                    echo "<option value='$row[0]'>$row[0]</option>";
                                    }
                            }  
                            mysqli_close($con); 
                        ?>
                    </select>
                    <label>Medicine:</label>
                    <input type="text" id="medicine" name="medicine" required>
                    <label>Dosage:</label>
                    <input type="text" id="dosage" name="dosage" required>
                    <label>Frequency:</label>
                    <select name="number" id="number" onchange="showTimeInputs()" required>
                        <option value="">Select</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                    <div id="timeInputs"></div>
                    <label>Time to Take:</label>
                    <input type="text" id="timetotake" name="timetotake" required>
                    <button id="addBtn" name="addBtn">Add Medication</button>
                    <button id="updateBtn" name="updateBtn" style="display: none;">Update</button>
                    <button id="cancelBtn" type="button" style="display: none;" onclick="resetForm()">Cancel</button>
                    <input type="hidden" id="oldpname" name="oldpname" value="<?php echo htmlspecialchars($oldpname); ?>">
                    <input type="hidden" id="oldmedicine" name="oldmedicine" value="<?php echo htmlspecialchars($oldmedicine); ?>">
                    <input type="hidden" id="olddosage" name="olddosage" value="<?php echo htmlspecialchars($olddosage); ?>">
                </div>
            </form>
            <div class="listContainer scrollable-div">
                <h2>Medications List</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Medicine</th>
                            <th>Dosage</th>
                            <th>Frequency</th>
                            <th>Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody><?php loadmedication(); ?></tbody>
                </table>
            </div>
        </div>
    </div>
    <script>  
    document.getElementById("patient").disabled = false;
    function editMedication(patient, medicine, dosage, frequency, time) {
        document.getElementById("patient").disabled = true;
        document.getElementById("patient").value = patient;
        document.getElementById("medicine").value = medicine;
        document.getElementById("dosage").value = dosage;
        document.getElementById("timetotake").value = time;
                
        document.getElementById("oldpname").value = patient;
        document.getElementById("oldmedicine").value = medicine;
        document.getElementById("olddosage").value = dosage;
        // Change form buttons for update mode
        document.getElementById("formTitle").innerText = "Update Medication";
        document.getElementById("addBtn").style.display = "none";
        document.getElementById("updateBtn").style.display = "inline-block";
        document.getElementById("cancelBtn").style.display = "inline-block";
    }

    function resetForm() {
        document.getElementById("patient").disabled = false;
        document.getElementById("patient").value = "";
        document.getElementById("medicine").value = "";
        document.getElementById("dosage").value = "";
        document.getElementById("number").value = "";
        showTimeInputs();
        document.getElementById("timetotake").value = "";

        // Reset form buttons to add mode
        document.getElementById("formTitle").innerText = "Add Medication";
        document.getElementById("addBtn").style.display = "inline-block";
        document.getElementById("updateBtn").style.display = "none";
        document.getElementById("cancelBtn").style.display = "none";
    }

    function confirmDelete(patient, medicine) {
        if (confirm(`Are you sure you want to delete ${medicine} for ${patient}?`)) {
            let form = document.createElement("form");
            form.method = "POST";
            form.style.display = "none";

            let inputPatient = document.createElement("input");
            inputPatient.type = "hidden";
            inputPatient.name = "patient";
            inputPatient.value = patient;
            form.appendChild(inputPatient);

            let inputMedicine = document.createElement("input");
            inputMedicine.type = "hidden";
            inputMedicine.name = "medicine";
            inputMedicine.value = medicine;
            form.appendChild(inputMedicine);

            let deleteBtn = document.createElement("input");
            deleteBtn.type = "hidden";
            deleteBtn.name = "deleteBtn";
            deleteBtn.value = "1";
            form.appendChild(deleteBtn);

            document.body.appendChild(form);
            form.submit();
        }
    }

        function showTimeInputs() {
            var count = document.getElementById("number").value;
            var container = document.getElementById("timeInputs");
            container.innerHTML = ""; // Clear previous inputs

            for (var i = 1; i <= count; i++) {
                var input = document.createElement("input");
                input.type = "time";
                input.name = "timings[]"; // important: make it an array
                input.required = true;
                container.appendChild(document.createTextNode("Time " + i + ": "));
                container.appendChild(input);
                container.appendChild(document.createElement("br"));
            }
        }
    
</script>

</body>
</html>

