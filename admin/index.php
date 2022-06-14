<?php
require("../function.php");
var_dump(dirname(__FILE__));
var_dump(__DIR__);
session_start();
checkLoginAdmin();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8" />
<title>管理画面トップ | G's TOKYO LAB13th イベントカレンダー version2.0</title>
<meta name="keywords" content="">
<meta name="description" content="">
<meta name="format-detection" content="telephone=no">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="”googlebot”" content="”noindex”" />
<link rel="stylesheet" href="../css/reset.css" />
<link rel="stylesheet" href="../css/style.css" />
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100;500&display=swap" rel="stylesheet" />
</head>
<body class="admin_top_page">
	<main>
		<div id="container">
			<div id="admin">
				<div class="__sec_body__">
					<table>
						<tr>
							<th style="width: 100px;">ID</th>
							<th>名前</th>
							<th style="width: 100px;">管理者</th>
							<th>ステータス</th>
							<th style="width: 100px;">編集</th>
							<th style="width: 100px;">削除</th>
						</tr>
<?php foreach(getAllData("users") as $v): ?>
						<tr>
							<td>
								<?php echo $v["id"]; ?>
							</td>
							<td>
								<?php echo $v["name"]; ?>
							</td>
							<td>
								<?php echo $v["admin_flag"] === "1" ? "◯": "-"; ?>
							</td>
							<td>
								<?php echo $v["delete_flag"] === "0" ? "-": "退会"; ?>
							</td>
							<td>
								<a href="./show.php?id=<?php echo $v['id']; ?>" class="edit">編集</a>
							</td>
							<td>
								<a href="./delete.php?id=<?php echo $v['id']; ?>" class="delete">削除</a>
							</td>
						</tr>
<?php endforeach; ?>
					</table>
				</div>
			</div>
		</div>
	</main>
</body>
</html>
