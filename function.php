<?php
require(dirname(__FILE__) . '/config.php');

/**
 * DBに接続
 * @param { Void }
 * @return { Object }
 */
function dbConnection()
{
  $DB_NAME = DB_NAME;
  $HOST = HOST;
  $USER = USER;
  $PASSWORD = PASSWORD;
  $LOCALHOST_DB_NAME = LOCALHOST_DB_NAME;
  $LOCALHOST_USER = LOCALHOST_USER;
  if ($_SERVER["HTTP_HOST"] == 'localhost') {
    return new PDO("mysql:dbname={$LOCALHOST_DB_NAME};charset=utf8;host=localhost", "{$LOCALHOST_USER}", "");
  } else {
    return new PDO("mysql:dbname={$DB_NAME};charset=utf8;host={$HOST}", $USER, $PASSWORD);
  }
}

/**
 * 特定のテーブル全データを取得
 * @param { String } $tableName
 * @return { Array }
 */
function getAllData($tableName)
{
  try {
    $pdo = dbConnection();
  } catch (PDOException $e) {
    exit('DB Connection Error:' . $e->getMessage());
  }
  $sql = "SELECT * FROM. $tableName";
  $stmt = $pdo->prepare($sql);
  $status = $stmt->execute();

  if ($status == false) {
    sqlError($stmt);
  } else {
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}

/**
 * schedulesテーブルの1レコードを取得
 * @param { Int } $id
 * @return { Array }
 */
function getSchedule($id)
{
  try {
    $pdo = dbConnection();
  } catch (PDOException $e) {
    exit('DB Connection Error:' . $e->getMessage());
  }

  $sql = "SELECT schedules.*, authors.name AS author, colors.color_code
  FROM schedules
  LEFT JOIN authors ON schedules.author_id = authors.id
  LEFT JOIN colors ON schedules.color_id = colors.id
  WHERE schedules.id = :id;";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  $status = $stmt->execute();

  if ($status == false) {
    sqlError($stmt);
  } else {
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
}

/**
 * usersテーブルの1レコードを取得
 * @param { Int } $id
 * @return { Array }
 */
function getUser($id)
{
  try {
    $pdo = dbConnection();
  } catch (PDOException $e) {
    exit('DB Connection Error:' . $e->getMessage());
  }

  $sql = "SELECT * FROM users WHERE id = :id;";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);
  $status = $stmt->execute();

  if ($status == false) {
    sqlError($stmt);
  } else {
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
}

/**
 * 入力された文字のエスケープ
 * @param { String } $_POST['xxxx']
 * @return { String }
 */
function esc($str)
{
  return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}

/**
 * バリデーションチェック
 * @param { String } $_POST['xxxx']
 * @return { String }
 */
function validation($inputValue)
{
  $errors = [];
  $start_at = strtotime($inputValue["start_date"] . " " . $inputValue["start_time"] . ":00");
  $end_at = strtotime($inputValue["end_date"] . " " . $inputValue["end_time"] . ":00");
  if (empty($inputValue["title"])) {
    $errors["title"] = "※タイトルを入力してください";
  }

  if (
    empty($inputValue["start_date"]) ||
    empty($inputValue["start_time"]) ||
    empty($inputValue["end_date"]) ||
    empty($inputValue["end_time"])
  ) {
    $errors["time"] = "※日付、時刻を選択してください";
  } elseif ($start_at - $end_at > 0) {
    $errors["time"] = "※日付、時刻を正しく入力してください";
  }

  if (!empty($inputValue["url"]) && !filter_var($inputValue["url"], FILTER_VALIDATE_URL)) {
    $errors["url"] = "※URLを入力してください";
  }

  if (empty($inputValue["author"])) {
    $errors["author"] = "※作成者を選択してください";
  }

  if (empty($inputValue["color"])) {
    $errors["color"] = "※カラーを選択してください";
  }
  return $errors;
}

/**
 * ログインバリデーションチェック
 * @param { String } $_POST['xxxx']
 * @return { String }
 */
function validationLogin($inputValue)
{
  try {
    $pdo = dbConnection();
  } catch (PDOException $e) {
    exit('DB Connection Error:' . $e->getMessage());
  }
  $sql = "SELECT COUNT(*) FROm users WHERE name = ${$inputValue['name']};";
  $stmt = $pdo->prepare($sql);
  $stmt->execute() ? sqlError($stmt) : $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
  var_dump($users);





  $errors = [];
  if (empty($inputValue["name"])) {
    $errors["name"] = "※IDを入力してください";
  } else if (!preg_match("/^[a-zA-Z0-9]{5,100}$/", $inputValue["name"])) {
    $errors["name"] = 'IDは英数字5文字以上100文字以下です';
  }
  if (empty($inputValue["password"])) {
    $errors["password"] = "※パスワードを入力してください";
  } else if (!preg_match("/^[a-zA-Z0-9]{8,100}$/", $inputValue["password"])) {
    $errors["password"] = 'パスワードは英数字8文字以上100文字以下です';
  }
  return $errors;
}

/**
 * SQLエラー表示
 * @param { PDOstatement } $stmt
 * @return { viod }
 */
function sqlError($stmt)
{
  $error = $stmt->errorInfo();
  exit("SQL Error:" . $error[2]);
}

/**
 * リダイレクトの処理
 * @param { string } ./index.php
 * @return { viod }
 */
function redirect($path)
{
  header("Location: $path");
  exit();
}

/**
 * ログインしているかのチェック
 * @param { viod }
 * @return { viod }
 */
function checkLoginUser()
{
  if (!isset($_SESSION["check_session"]) || $_SESSION["check_session"] != session_id()) {
    redirect("./login.php");
  } else {
    session_regenerate_id(true);
    $_SESSION["check_session"] = session_id();
  }
}

/**
 * 管理者ユーザーかチェック
 * @param { viod }
 * @return { viod }
 */
function checkLoginAdmin()
{
  if (!isset($_SESSION["check_session"]) || $_SESSION["check_session"] != session_id() || $_SESSION["admin_flag"] !== "1") {
    redirect("/lab13_event_calender_ver2.0/login.php");
  } else {
    session_regenerate_id(true);
    $_SESSION["check_session"] = session_id();
  }
}

/**
 * csvでスケジュール一括登録
 * @param { csv } $csvFile
 * @return { viod }
 */
// function csvScan($csvFile)
// {
//   try {
//     $pdo = dbConnection();
//   } catch (PDOException $e) {
//     exit('DB Connection Error:' . $e->getMessage());
//   }

//   $sql = "INSERT INTO schedules(title, start_at, end_at, place, url, author_id, color_id, description, update_at, create_at) VALUES(:title, :start_at, :end_at, :place, :url, :author_id, :color_id, :description, sysdate(), sysdate());";

//   $dataList = [];
//   foreach (file($csvFile) as $k => $v) {
//     if ($k == 0) continue;
//     $dataList = explode(",", $v);
//     echo "<pre>";
//     var_dump($dataList);
//     echo "</pre>";


//     $title = $dataList[0];
//     $start_at = $dataList[1] . " " . $dataList[2] . ":00";
//     $end_at = $dataList[3] . " " . $dataList[4] . ":00";
//     $place = $dataList[5];
//     $url = $dataList[6];
//     $author = $dataList[7];
//     $color = $dataList[8];
//     $description = $dataList[9];

//     $stmt = $pdo->prepare($sql);
//     $stmt->bindValue(':title', $title, PDO::PARAM_STR);
//     $stmt->bindValue(':start_at', $start_at, PDO::PARAM_STR);
//     $stmt->bindValue(':end_at', $end_at, PDO::PARAM_STR);
//     $stmt->bindValue(':place', $place, PDO::PARAM_STR);
//     $stmt->bindValue(':url', $url, PDO::PARAM_STR);
//     $stmt->bindValue(':author_id', $author, PDO::PARAM_INT);
//     $stmt->bindValue(':color_id', $color, PDO::PARAM_INT);
//     $stmt->bindValue(':description', $description, PDO::PARAM_STR);
//     $status = $stmt->execute();
//     $status == false ? sqlError($stmt) : redirect("./index.php");
//   }
// }
