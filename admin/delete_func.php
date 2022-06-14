<?php
require("../function.php");

session_start();
checkLoginAdmin();

$id = $_GET['id'];

try {
	$pdo = dbConnection();
} catch (PDOException $e) {
	exit('DB Connection Error:' . $e->getMessage());
}

$sql = "UPDATE users
SET admin_flag = :admin_flag, delete_flag	 = :delete_flag
WHERE id = :id;";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->bindValue(':admin_flag', '0', PDO::PARAM_STR);
$stmt->bindValue(':delete_flag', '1', PDO::PARAM_STR);
$status = $stmt->execute();
$status == false ? sqlError($stmt) : redirect("./index.php");
