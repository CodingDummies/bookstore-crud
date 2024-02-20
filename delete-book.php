<?php

require_once("app/app.php");

$id = $_POST["id"];
$bookList->deleteBookById($id);
exit();
