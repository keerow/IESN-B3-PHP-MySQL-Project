<?php   
//Searches and shows a question's text
function searchQuestion($id) {
    include 'connection.php';
    $query = "SELECT text FROM MyDB.question WHERE questid = :idquest";
    $selectStatement = $connect->prepare($query);
    $selectStatement->bindParam(':idquest',$id);
    $selectStatement->execute();
    $selectResult = ($selectStatement->fetch()['text']);
    echo '<label for="question "'.$id.'>'.$selectResult.': </label>';
    echo '<select name="'.$id.'">';
}
//Searches and shows answers for a question
function searchAnswer($id){
    include 'connection.php';
    $query = "SELECT answid,text,correct_answ FROM MyDB.answer WHERE questid = :idquest";
    $selectStatement = $connect->prepare($query);
    $selectStatement->bindParam(':idquest',$id);
    $selectStatement->execute();
    $selectResult = ($selectStatement->fetchAll());
    $count = 0;
    foreach ($selectResult as $row) {
        echo "<option value=".$row["answid"].">".$row["text"]."</option>";
    }
    echo '<option value="idk">Je ne sais pas</option>';
    echo '</select> <br/> <br/>';
}

include 'connection.php';
session_start();
$count = 1;
$pointsInDB = 0;
//Sets status of exam in DB
$query = "UPDATE MyDB.exam SET status='en cours' WHERE examid = :examid";
$updateStatement = $connect->prepare($query);
$updateStatement->bindParam(':examid',$_SESSION["username"]);
$updateStatement->execute();

//Checks if user session exists
if(isset($_SESSION["username"]))  
{  
    //Checks if user pressed submit button
    if(isset($_POST["submit"]))  {
        //Points out of 5
        $points = 0;
        for ($c = 1; $c <= 5; $c++){
            //Checks if user answered 'i don't know' in form
            if($_POST[$c] == "idk") {
                #echo 'Ah dommage tu savais pas (-0) <br/> <br/>';
                $pointsInDB = 0;
                $insertQuery = "INSERT INTO MyDB.exam_line(examid, questid,result) VALUES (:examid,:questid,:result)";
                $insertStatement = $connect->prepare($insertQuery);
                $insertStatement->bindParam(':examid',$_SESSION["username"]);
                $insertStatement->bindParam(':questid',$c);
                $insertStatement->bindParam(':result',$pointsInDB);
                $insertStatement->execute();
            }
            else {
                //Checks if answer's 'correct_answ' entry is 1 or 0 in DB
                $query = "SELECT correct_answ FROM MyDB.answer WHERE answid = :answid";
                $selectStatement = $connect->prepare($query);
                $selectStatement->bindParam(':answid',$_POST[$c]);
                $selectStatement->execute();
                $selectResult = ($selectStatement->fetch()['correct_answ']);
                if ($selectResult == 1){
                    #echo $c. " : Bonne réponse (+1) <br/> <br/>";
                    $points = $points + 1;
                    $pointsInDB = 1;
                }
                else{
                    #echo $c. " : Mauvaise réponse (-0.5) <br/> <br/>";
                    $points = $points - 0.5;
                    $pointsInDB = -0.5;
                    
                }
                //Inserts exam_line entry in DB
                $insertQuery = "INSERT INTO MyDB.exam_line(examid, questid, answid,result) VALUES (:examid,:questid,:answid,:result)";
                $insertStatement = $connect->prepare($insertQuery);
                $insertStatement->bindParam(':examid',$_SESSION["username"]);
                $insertStatement->bindParam(':questid',$c);
                $insertStatement->bindParam(':answid',$c);
                $insertStatement->bindParam(':result',$pointsInDB);
                $insertStatement->execute();
            }
        }
        //Sets status of exam in DB
        $query = "UPDATE MyDB.exam SET status='fini', result= :points WHERE examid = :examid";
        $updateStatement = $connect->prepare($query);
        $updateStatement->bindParam(':points',$points);
        $updateStatement->bindParam(':examid',$_SESSION["username"]);
        $updateStatement->execute();
        #echo $points. " sur 5 <br/> <br/>";
        header("location:corrections.php");
    }



    echo 
    '<!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
            <title>QCM</title>
        </head>
        <body>
            <form method="post">';
            //Shows 5 questions with answers in dropdown lists
            while ($count <= 5) {
                    searchQuestion($count);
                    searchAnswer($count);
                    $count++;
            }
            echo '<input type="submit" name="submit" value="Validation"/>
            </form>
            </div>
            <br /><br /><a href="logout.php">Se déconnecter</a>
        </body>
    </html>';
}  
//Redirects to home page if no session exists
else  
{  
    header("location:index.php");  
}
?>  