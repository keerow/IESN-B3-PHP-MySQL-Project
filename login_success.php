<?php  
    session_start();
    include 'connection.php';
    //Checks if user session exists
    if(isset($_SESSION["username"]))  
    {   
        echo '<h3>Login Success, Welcome - '.$_SESSION["username"].'</h3>';
        //Checks status of the student's exam
        $query = "SELECT status FROM MyDB.exam WHERE userid = :username";
        $selectStatement = $connect->prepare($query);
		$selectStatement->bindParam(':username',$_SESSION["username"]);
        $selectStatement->execute();
        $selectResult = ($selectStatement->fetch()['status']);
        
        //Gives link based on exam status
        if($selectResult == 'fini') {
            echo '<a href="corrections.php">Consultez le correctif ici.</a>';
        }
        elseif ($selectResult == 'en cours'){
            echo '<a href="exam.php">Continuez votre examen ici.</a>';
        }
        elseif ($selectResult == 'pas commencé'){
            echo '<a href="exam.php">Continuez votre examen ici.</a>';
        }
        //Creates exam if student has no exam in DB
        else{
            $insertQuery = "INSERT INTO MyDB.exam (`userid`, `qcmid`, `status`, `result`) VALUES (:username,1,'pas commencé',0)";
            $insertStatement = $connect->prepare($insertQuery);
		    $insertStatement->bindParam(':username',$_SESSION["username"]);
            $insertStatement->execute();
            echo '<p>Questinnaire crée.</p>';
            echo '<a href="exam.php">Démarrer votre exam ici.</a>';
        }

        echo '<br /><br /><a href="logout.php">Se déconnecter</a>';
    }
    //Redirects to home page if no session exists
    else  
    {  
        header("location:index.php");  
    }  
 ?>  