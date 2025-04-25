<?php

use Twilio\Rest\Client;

$servername = "localhost";
$user = "root";
$password = "";
$dbname = ""; 
$con = mysqli_connect($servername,$user,$password,$dbname);                
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
else{
    $query = "SELECT USERNAME FROM DOCTOR;";
    $result = mysqli_query($con, $query);
    $row_count = mysqli_num_rows($result);
    while($row = mysqli_fetch_array($result)) {
        $dname = $row[0];
        $query1 = "SELECT PATIENT,FREQUENCY,MEDICINE FROM D$dname ;";
        $result1 = mysqli_query($con, $query1);
        while($row1 = mysqli_fetch_array($result1)) {
                $pname = $row1[0];
                $ptime = $row1[1];
                $pmedicine = $row1[2];
                $timings = explode(',',$ptime);
                for($i=0;$i<count($timings);$i++){
                    date_default_timezone_set("Asia/Kolkata"); // set your timezone
                    $current_time = date("H:i");
                    if($timings[$i]==$current_time){
                        $query2 = "SELECT PHONE FROM PATIENT WHERE USERNAME='$pname'";
                        $result2 = mysqli_query($con, $query2);
                        $row2 = mysqli_fetch_array($result2);
                        $phone = trim($row2[0]);
                        // require __DIR__ . '/vendor/autoload.php'; // Autoload files via Composer

                        // use Twilio\Rest\Client;
                        require __DIR__ . '/vendor/autoload.php'; // Autoload files via Composer
                        
                        // Twilio credentials from your dashboard
                        $sid = '';
                        $token = '';
                        $twilio_number = ''; 
            
                        // Your verified mobile number
                        $to = "+91$phone"; 
            
                        $client = new Client($sid, $token);
            
                        try{
                        $message = $client->messages->create(
                        $to,
                        [
                            'from' => $twilio_number,
                            'body' => "Hello! $pname,Take your Medicine: $pmedicine"
                        ]
                        );
            
                        echo "Message sent! SID: " . $message->sid;
                        } catch (Exception $e) {
                            echo "Error: " . $e->getMessage();
                        }   
                    
                    }
                    else echo "no match found";

                }

        }
        
    }
}  
mysqli_close($con); 
?>
