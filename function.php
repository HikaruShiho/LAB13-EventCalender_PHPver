<?php
require_once('./config.php');

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
 * SQLエラー表示
 * @param { PDOStatement } $stmt
 * @return { Viod }
 */
function sqlError($stmt)
{
  $error = $stmt->errorInfo();
  exit("SQL Error:" . $error[2]);
}

/**
 * SQLエラー表示
 * @param { PDOStatement } $stmt
 * @return { Viod }
 */
function redirect($path)
{
  header("Location: $path");
  exit();
}
