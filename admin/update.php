<?php
require("../function.php");

session_start();
checkLoginAdmin();

$id = $_GET['id'];
$name = $_POST['name'];
$admin_flag = $_POST['admin_flag'];
// var_dump($_POST);
// $errors = validation($_POST);
// if (count($errors) === 0) {

try {
	$pdo = dbConnection();
} catch (PDOException $e) {
	exit('DB Connection Error:' . $e->getMessage());
}

$sql = "UPDATE users
SET name = :name, admin_flag = :admin_flag
WHERE id = :id;";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->bindValue(':name', $name, PDO::PARAM_STR);
$stmt->bindValue(':admin_flag', $admin_flag, PDO::PARAM_STR);
$status = $stmt->execute();
$status == false ? sqlError($stmt) : redirect("./index.php");
// }
