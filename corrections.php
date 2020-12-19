<?php   


function searchAnswer(){
    include 'connection.php';
    //Searches question and answer that were entered by student
    $query = "SELECT * FROM MyDB.exam_line WHERE examid = :examid";
    $selectStatement = $connect->prepare($query);
    $selectStatement->bindParam(':examid',$_SESSION["username"]);
    $selectStatement->execute();
    $selectResult = ($selectStatement->fetchAll());
    //Searches text from the questions
    foreach ($selectResult as $row) {
        $query = "SELECT text FROM MyDB.question WHERE `questid` = :questid";
        $selectStatement = $connect->prepare($query);
        $selectStatement->bindParam(':questid',$row["questid"]);
        $selectStatement->execute();
        $questionTexts = ($selectStatement->fetch()['text']);
        //Checks if student answered the question or not
        if (is_null($row["answid"])){
            echo $questionTexts. " - je ne sais pas <br/> <br/>";
        }
        //Searches for answer text
        else {
            $query = "SELECT text FROM MyDB.answer WHERE `answid` = :answid";
            $selectStatement = $connect->prepare($query);
            $selectStatement->bindParam(':answid',$row["answid"]);
            $selectStatement->execute();
            $answerTexts = ($selectStatement->fetch()['text']);
            echo $questionTexts. " - " .$answerTexts."<br/><br/>";
        }
    }
}

function searchCorrectAnswer(){
    include 'connection.php';
    //Searches question in DB
    $query = "SELECT * FROM MyDB.question";
    $selectStatement = $connect->prepare($query);
    $selectStatement->bindParam(':questid',$row["questid"]);
    $selectStatement->execute();
    $questions = ($selectStatement->fetchAll());
    //Searches question text and answer that are correct
    foreach($questions as $row) {
        $query = "SELECT text FROM MyDB.answer WHERE correct_answ = 1 AND questid = :questid";
        $selectStatement = $connect->prepare($query);
        $selectStatement->bindParam(':questid',$row["questid"]);
        $selectStatement->execute();
        $answerTexts = ($selectStatement->fetch()['text']);
        echo $row["text"]. " - " .$answerTexts."<br/><br/>";
    }
}

include 'connection.php';
session_start();
echo '<br /><br /><p>Corrections ici</p>';
//Checks if user session exists
if(isset($_SESSION["username"]))  
{
    //Searches student points in DB
    $query = "SELECT result FROM MyDB.exam WHERE userid = :userid";
    $selectStatement = $connect->prepare($query);
    $selectStatement->bindParam(':userid',$_SESSION["username"]);
    $selectStatement->execute();
    $selectResult = ($selectStatement->fetch()['result']);
    echo "<h1>Vous avez ".$selectResult." sur 5 points.</h1> </br>";
    searchAnswer();
    echo "</br><h1>Voici la correction </h1></br>"; 
    searchCorrectAnswer();
    echo '<br /><br /><a href="logout.php">Se déconnecter</a>';
}
//Redirects to home page if no session exists
else  
{  
    header("location:index.php");  
}

?>  