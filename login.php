<?php
require "./function.php";

session_start();
if(isset($_SESSION["check_session"])) redirect("./index.php");

if($_SERVER["REQUEST_METHOD"] === "POST") {
	$errors = validationLogin($_POST);
	if(count($errors) === 0) {
		$name = $_POST["name"];
		$password = $_POST["password"];
		try {
			$pdo = dbConnection();
		} catch (PDOException $e) {
			exit('DB Connection Error:' . $e->getMessage());
		}
		$sql = "SELECT * FROM users WHERE name = :name;";
		$stmt = $pdo->prepare($sql);
		$stmt->bindValue(':name', $name, PDO::PARAM_STR);
		$stmt->execute() ? $data = $stmt->fetch() : sqlError($stmt);
    $bool = password_verify($password, $data["password"]);
    if($bool) {
      $_SESSION["check_session"]  = session_id();
      $_SESSION["name"] = $data['name'];
      $_SESSION["admin_flag"] = $data['admin_flag'];
      redirect("./index.php");
    } else {
      redirect("./login.php");
    }
  }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8" />
<title>ログイン | G's TOKYO LAB13th イベントカレンダー version2.0</title>
<meta name="keywords" content="">
<meta name="description" content="">
<meta name="format-detection" content="telephone=no">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="”googlebot”" content="”noindex”" />
<link rel="stylesheet" href="./css/reset.css" />
<link rel="stylesheet" href="./css/style.css" />
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100;500&display=swap" rel="stylesheet" />
</head>
<body class="login_page">
	<main>
		<div id="container">
      <div id="login">
        <form method="POST" class="login_in">
          <p>ログイン</p>
          <ul>
            <li>
              <dl>
                <dt>ID</dt>
                <dd>
                  <input type="text" name="name" value="<?php if(!empty($_POST)) { echo esc($_POST['name']); } ?>"/>
                </dd>
              </dl>
            </li>
            <li>
              <dl>
                <dt>パスワード</dt>
                <dd>
                  <input type="password" name="password" value="<?php if(!empty($_POST)) { echo esc($_POST['password']); } ?>" />
                </dd>
              </dl>
            </li>
          </ul>
<?php foreach($errors as $v): ?>
          <p class="error_message"><?php echo $v ?></p>
<?php endforeach; ?>
          <div class="btn_area">
						<button id="login_btn" class="btn">ログイン</button>
					</div>
          <p>
            <a href="./register.php">初めての方はこちら</a>
          </p>
        </form>
      </div>
		</div>
	</main>
</body>
</html>
