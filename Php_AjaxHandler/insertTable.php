<?php
//just a copy of selectTAble, as it contain inserting,

$tableX = $_POST['ServerTableID'];
$unixX = $_POST['ServerDate_UnixID']; // Unix  var
$interval = $_POST['serverInterval']; // Time interval   ServerName
$nameZ = $_POST['ServerName']; // Time interval     
$TimeNormal = $_POST['TimeNormal']; // i.e 2017-11-29
                
echo "Insert handler status-> </br>";
/*echo $tableX . "</br>";
echo $unixX . "</br>";
echo $interval . "</br>";
echo $nameZ . "</br>";*/
//-------------------------------------
 global $conn;
include '../Classes/ConnectDB.php';
include '../Classes/DB_INSERT.php'; // not working Class
$singeltone = ConnectDB::getInstance(); //creates connection $con;  //was deactivated in index.php

// Check if any record exist, prevent dublicate inserting
try {  
    $resFR = $conn->query("SELECT * FROM bookingTable WHERE b_bookedUnix = '{$_POST['ServerDate_UnixID']}' AND b_table = '{$_POST['ServerTableID']}' AND b_timeInterval = '{$_POST['serverInterval']}'ORDER BY b_timeInterval ASC "); 
    if($resFR->rowCount() == 0) {
        echo "<p class='sign'> Feel free to take </p>"; 
        echo "<p class='sign'> Booked successfully!!!! </p>";      
        //DB_INSERT:: Booking_Date(); //Insert algorithm located in  Classes/DB_INSERT.php
        //Booking_Date();

        //Start INSERT (from function StartBooking())---------
        $sth = $conn ->prepare("INSERT INTO bookingTable (b_booker, b_ip, b_table, b_timeInterval, b_bookedDate, b_bookedUnix) VALUES (:logins, :ip, :tableID, :timeInterval, :bookedDate, :bookedDateUnix) ");
        $sth->bindValue(':logins', $_POST['ServerName']);   // Who is the booker
        $sth->bindValue(':ip', $_SERVER['REMOTE_ADDR']); //IP
        $sth->bindValue(':tableID', $_POST['ServerTableID']); // TAble Number ID
        $sth->bindValue(':timeInterval', $_POST['serverInterval']);
        $sth->bindValue(':bookedDate', $_POST['TimeNormal']);  //$_POST['serverDateID']  //2017-11-29
        $sth->bindValue(':bookedDateUnix',$_POST['ServerDate_UnixID'] );
        //$sth->bindValue(':whenBooked',date("Y-m-d H:i:s") );
        $sth->execute();
//END INSERT (from function StartBooking())---------


    } else {
	    echo "<p class='sign'> Someone has booked it while u were thinking! </p>";   \
    }  
} catch(PDOException $e) {
    echo "ERR-ed";
    echo "Error: " . $e->getMessage();
}
$conn = null;
// END Check if any record exist, prevent dublicate inserting
//-------------



// Start StartBooking()
// **************************************************************************************
// **************************************************************************************
//                                                                                     **  
function Booking_Date() {
    try {
        //b_when_was_booked  :whenBooked
        $sth = $conn ->prepare("INSERT INTO bookingTable(b_booker, b_ip, b_table, b_timeInterval, b_bookedDate, b_bookedUnix) VALUES (:logins, :ip, :tableID, :timeInterval, :bookedDate,  :bookedDateUnix) ");
        $sth->bindValue(':logins',$_POST['ServerName']);   // Who is the booker
        $sth->bindValue(':ip', $_SERVER['REMOTE_ADDR']);//IP
        $sth->bindValue(':tableID', $_POST['ServerTableID']);// TAble Number ID
        $sth->bindValue(':timeInterval',$_POST['serverInterval']);
        $sth->bindValue(':bookedDate', 'null');  //$_POST['serverDateID']  //2017-11-29
        $sth->bindValue(':bookedDateUnix', $_POST['ServerDate_UnixID'] );
        //$sth->bindValue(':whenBooked', date("Y-m-d H:i:s") );
        $sth->execute();
//-----------------  
        echo "New booking record created successfully";
    } catch(PDOException $e) {
        echo "Failed booking </br>";
        echo $e->getMessage();
    }
    $conn = null;
 }
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************
// END StartBooking()

?>
