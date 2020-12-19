<?php
//Searches students who have finished their exam and shows their results
function searchStudentsAndResults(){
    include 'connection.php';
    //Searches students who have finished their exam
    $query = "SELECT userid,status,result FROM MyDB.exam WHERE status = 'fini'";
    $selectStatement = $connect->prepare($query);
    $selectStatement->execute();
    $questions = ($selectStatement->fetchAll());
    echo "Eleves qui ont fini leur exam : <br/><br/>";
    //Shows students and their results
    foreach($questions as $row) {
        $query = "SELECT name FROM MyDB.users WHERE userid = :userid";
        $selectStatement = $connect->prepare($query);
        $selectStatement->bindParam(':userid',$row["userid"]);
        $selectStatement->execute();
        $userName = ($selectStatement->fetch()['name']);
        echo $userName." - ".$row["result"]. "/5<br/><br/>";
    }
}

//Searches students depending on their exam status
function searchStudents($stat){
    include 'connection.php';
    //Searches students depending on status
    $query = "SELECT userid,status,result FROM MyDB.exam WHERE status = :status";
    $selectStatement = $connect->prepare($query);
    $selectStatement->bindParam(':status',$stat);
    $selectStatement->execute();
    $names = ($selectStatement->fetchAll());
    //Shows students depending on status
    foreach($names as $row) {
        $query = "SELECT name FROM MyDB.users WHERE userid = :userid";
        $selectStatement = $connect->prepare($query);
        $selectStatement->bindParam(':userid',$row["userid"]);
        $selectStatement->execute();
        $userName = ($selectStatement->fetch()['name']);
        echo $userName."<br/><br/>";
    }
}


session_start();
include 'connection.php';
//Checks if user session exists
if(isset($_SESSION["username"]))  
{
    searchStudentsAndResults();
    echo "Eleves qui n'ont pas encore commencé leur exam : <br/><br/>";3
    //Shows students who haven't started their exam
    searchStudents("pas commencé");
    echo "Eleves qui n'ont pas encore fini leur exam : <br/><br/>";
    //Shows students who are still passing their exam
    searchStudents("en cours");
    echo '<br /><br /><a href="logout.php">Se déconnecter</a>';
}
//Redirects to home page if no session exists
else  
{  
    header("location:index.php");  
}  
?>