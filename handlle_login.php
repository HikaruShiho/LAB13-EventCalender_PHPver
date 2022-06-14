<?php
require "../function.php";

session_start();

$id = $_POST["id"];
$password = $_POST["password"];

try {
  $pdo = dbConnection();
} catch (PDOException $e) {
  exit('DB Connection Error:' . $e->getMessage());
}
exit();

$sql = "SELECT * FROM users WHERE lid = :lid AND lpw = :lpw AND life_flg = 0;";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':lid', $lid, PDO::PARAM_STR);
$stmt->bindValue(':lpw', $lpw, PDO::PARAM_STR);
$status = $stmt->execute();

$status == false ? sqlError($stmt) : $val = $stmt->fetch();

if ($val["id"] != "") {
  $_SESSION["chk_ssid"]  = session_id();
  $_SESSION["kanri_flg"] = $val['kanri_flg'];
  $_SESSION["name"]      = $val['name'];
  redirect("./select.php");
} else {
  redirect("./login.php");
}
