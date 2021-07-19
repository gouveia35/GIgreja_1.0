<?php
    try{
    	$db = new PDO("mysql:dbname=igrejadb; host=127.0.0.1; charset=utf8","root","afnl@1234");
    }catch(PDOException $e){
    	echo $e->getMessage();
    }
?>