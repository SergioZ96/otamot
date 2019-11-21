<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('mypdo.class.php');


try{
    $newPDO = new MyPDO();

    foreach(glob("sql/*.sql") as $filename){
        $sql[$filename] = file_get_contents($filename);
    }
    ksort($sql);
    foreach($sql as $key => $value){
        $stmt = $newPDO->prep($value);
        $result = $stmt->execute();
        $error = $stmt->errorInfo();
        if($error && $error[0] !== '00000'){
            echo "<br>Error:<pre>" . var_export($error, true) . "</pre><br>";
        }
        echo "<br>$key result: " . ($result>0?"Success":"Fail") . "<br>";
    }
} catch(Exception $e){
    echo $e->getMessage();
}


?>