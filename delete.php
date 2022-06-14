<?php
require "./function.php";

session_start();
checkLoginUser();

$id = $_GET['id'];
try {
	$pdo = dbConnection();
} catch (PDOException $e) {
	exit('DB Connection Error:' . $e->getMessage());
}

if($_SERVER["REQUEST_METHOD"] === "POST") {
	$sql = "DELETE FROM schedules WHERE id = :id";
	$stmt = $pdo->prepare($sql);
	$stmt->bindValue(':id', $id, PDO::PARAM_INT);
	$status = $stmt->execute();
	$status == false ? sqlError($stmt) : redirect("./index.php");
} else {
	$data = getSchedule($id);
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8" />
<title><?php echo $data['title']; ?> | G's TOKYO LAB13th イベントカレンダー version2.0</title>
<meta name="keywords" content="">
<meta name="description" content="">
<meta name="format-detection" content="telephone=no">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="”googlebot”" content="”noindex”" />
<link rel="stylesheet" href="./css/reset.css" />
<link rel="stylesheet" href="./css/style.css" />
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100;500&display=swap" rel="stylesheet" />
</head>
<body class="delete_page">
	<main>
		<div id="container">
			<?php include('./templates/sidebar.php'); ?>
			<div id="show_schedule">
				<form method="POST" class="show_schedule_in">
					<ul>
						<li>
							<dl>
								<dt>
									<span id="show_color" style="background-color:<?php echo $data['color_code']; ?>"></span>
								</dt>
								<dd>
									<span id="show_schedule_title"><?php echo $data['title']; ?></span>
								</dd>
							</dl>
						</li>
						<li>
							<dl>
								<dt>
									<img src="./images/icon_date.png">
								</dt>
								<dd>
									<span id="show_schedule_date"><?php echo date("n/j G:i", strtotime($data['start_at'])); ?> 〜 <?php echo date("n/j G:i", strtotime($data['end_at'])); ?></span>
								</dd>
							</dl>
						</li>
						<li>
							<dl>
								<dt>
									<img src="./images/icon_place.png">
								</dt>
								<dd>
									<span id="show_schedule_place"><?php echo $data['place']; ?></span>
								</dd>
							</dl>
						</li>
						<li>
							<dl>
								<dt>
									<img src="./images/icon_url.png">
								</dt>
								<dd>
									<a href="<?php echo $data['url']; ?>" target="_blank">
										<span id="show_schedule_url"><?php echo $data['url']; ?></span>
									</a>
								</dd>
							</dl>
						</li>
						<li>
							<dl>
								<dt>
									<img src="./images/icon_author.png">
								</dt>
								<dd>
									<span id="show_schedule_author"><?php echo $data['author']; ?></span>
								</dd>
							</dl>
						</li>
						<li>
							<dl>
								<dt>
									<img src="./images/icon_description.png">
								</dt>
								<dd>
									<span id="show_schedule_description"><?php echo $data['description']; ?></span>
								</dd>
							</dl>
						</li>
					</ul>
					<p>上記スケジュールを本当に削除しますか？</p>
					<div class="btn_area">
						<button id="delete_schedule_btn" class="btn">惜しみなく削除</button>
					</div>
				</form>
			</div>
		</div>
	</main>
	<?php include('./templates/script.php'); ?>
</body>
</html>
