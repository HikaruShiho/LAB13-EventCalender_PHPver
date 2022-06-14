<?php
require("../function.php");

session_start();
checkLoginAdmin();

$id = $_GET['id'];
$user = getUser($id);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8" />
<title>ユーザー情報編集 | G's TOKYO LAB13th イベントカレンダー version2.0</title>
<meta name="keywords" content="">
<meta name="description" content="">
<meta name="format-detection" content="telephone=no">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="”googlebot”" content="”noindex”" />
<link rel="stylesheet" href="../css/reset.css" />
<link rel="stylesheet" href="../css/style.css" />
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100;500&display=swap" rel="stylesheet" />
</head>
<body class="admin_show_page">
	<main>
		<div id="container">
			<div id="login">
				<div class="__sec_body__">
					<form method="POST" class="login_in" action="./delete_func.php?id=<?php echo $id; ?>">
						本当に削除しますか？
						<ul>
							<li>
								<dl>
									<dt>ID: <?php echo $user["id"]; ?></dt>
								</dl>
							</li>
							<li>
								<dl>
									<dt>名前: <?php echo $user["name"]; ?></dt>
								</dl>
							</li>
							<li>
								<dl>
									<dt>ユーザータイプ:  <?php echo $user['admin_flag'] === '1'? '管理者' :'一般ユーザー'; ?></dt>
								</dl>
							</li>
							<li>
								<dl>
									<dt>ステータス：<?php echo $user["delete_flag"] === "0" ? "利用中": "退会"; ?></dt>
								</dl>
							</li>
						</ul>
						<div class="btn_area">
							<button id="login_btn" class="btn">アカウント停止</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</main>
</body>
</html>
