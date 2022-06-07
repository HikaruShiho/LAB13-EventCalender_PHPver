<?php
require "./function.php";

if($_SERVER["REQUEST_METHOD"] === "POST") {
	$errors = validation($_POST);
	if(count($errors) === 0) {
    $title = $_POST["title"];
		$start_at = $_POST["start_date"]. " ". $_POST["start_time"]. ":00";
		$end_at = $_POST["end_date"]. " ". $_POST["end_time"]. ":00";
		$place = $_POST["place"];
		$url = $_POST["url"];
		$author = $_POST["author"];
		$color = $_POST["color"];
		$description = $_POST["description"];

		try {
			$pdo = dbConnection();
		} catch (PDOException $e) {
			exit('DB Connection Error:' . $e->getMessage());
		}

		$sql = "INSERT INTO テーブル名(name, url, naiyo, date)VALUES(:name, :url, :naiyo, sysdate())";

		$sql = "INSERT INTO schedules(title, start_at, end_at, place, url, author_id, color_id, description, update_at, create_at) VALUES(:title, :start_at, :end_at, :place, :url, :author_id, :color_id, :description, sysdate(), sysdate());";

		$stmt = $pdo->prepare($sql);
		$stmt->bindValue(':title', $title, PDO::PARAM_STR);
		$stmt->bindValue(':start_at', $start_at, PDO::PARAM_STR);
		$stmt->bindValue(':end_at', $end_at, PDO::PARAM_STR);
		$stmt->bindValue(':place', $place, PDO::PARAM_STR);
		$stmt->bindValue(':url', $url, PDO::PARAM_STR);
		$stmt->bindValue(':author_id', $author, PDO::PARAM_INT);
		$stmt->bindValue(':color_id', $color, PDO::PARAM_INT);
		$stmt->bindValue(':description', $description, PDO::PARAM_STR);
		$status = $stmt->execute();
		$status == false ? sqlError($stmt) : redirect("./index.php");
  }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8" />
<title>スケジュール登録 | G's TOKYO LAB13th イベントカレンダー version2.0</title>
<meta name="keywords" content="">
<meta name="description" content="">
<meta name="format-detection" content="telephone=no">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="”googlebot”" content="”noindex”" />
<link rel="stylesheet" href="./css/reset.css" />
<link rel="stylesheet" href="./css/style.css" />
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100;500&display=swap" rel="stylesheet" />
</head>
<body class="store_page">
	<main>
		<div id="container">
			<?php include('./templates/sidebar.php'); ?>
			<form method="POST" id="edit_schedule">
				<div class="__sec_body__">
					<ul>
						<li>
							<dl>
								<dt>タイトル　<span>※必須</span></dt>
								<dd>
									<input type="text" name="title" value="<?php if(!empty($_POST)) { echo esc($_POST['title']); } ?>" id="schedule_title" class="w100">
								</dd>
							</dl>
						</li>
						<li>
							<dl>
								<dt>日時　<span>※必須</span></dt>
								<dd class="date">
									<input type="date" value="<?php if(!empty($_POST['start_date'])) { echo esc($_POST['start_date']); } ?>" name="start_date" id="schedule_start_date" class="type01">
									<input type="time" value="<?php if(!empty($_POST['start_time'])) { echo esc($_POST['start_time']); } ?>" name="start_time" id="schedule_start_time" class="type02">〜
									<input type="date" value="<?php if(!empty($_POST['end_date'])) { echo esc($_POST['end_date']); } ?>" name="end_date" id="schedule_end_date" class="type01">
									<input type="time" value="<?php if(!empty($_POST['end_time'])) { echo esc($_POST['end_time']); } ?>" name="end_time" id="schedule_end_time" class="type02">
								</dd>
							</dl>
						</li>
						<li>
							<dl>
								<dt>場所</dt>
								<dd>
									<input type="text" name="place" value="<?php if(!empty($_POST)) { echo esc($_POST['place']); } ?>" id="schedule_place" class="w100">
								</dd>
							</dl>
						</li>
						<li>
							<dl>
								<dt>URL</dt>
								<dd>
									<input type="url" name="url" value="<?php if(!empty($_POST)) { echo esc($_POST['url']); } ?>" id="schedule_url" class="w100">
								</dd>
							</dl>
						</li>
						<li>
							<dl>
								<dt>作成者　<span>※必須</span></dt>
								<dd>
									<select name="author" id="schedule_author">
										<option value="">選択してください</option>
<?php foreach(getAllData("authors") as $v): ?>
										<option value="<?php echo $v['id']; ?>" <?php if($_POST['author'] == $v['id']){echo 'selected';};?>><?php echo $v['name']; ?></option>
<?php endforeach; ?>
									</select>
								</dd>
							</dl>
						</li>
						<li>
							<dl>
								<dt>カラー　<span>※必須</span></dt>
								<dd>
<?php foreach(getAllData("colors") as $v): ?>
									<label>
										<input type="radio" name="color" value="<?php echo $v['id']; ?>" <?php if($_POST['color'] == $v['id']){echo 'checked';};?>>
										<span style="background-color:<?php echo $v['color_code']; ?>"></span>
									</label>
<?php endforeach; ?>
								</dd>
							</dl>
						</li>
						<li>
							<dl>
								<dt>説明</dt>
								<dd>
									<textarea name="description" value="<?php if(!empty($_POST)) { echo esc($_POST['description']); } ?>" id="schedule_description"></textarea>
								</dd>
							</dl>
						</li>
					</ul>
<?php foreach($errors as $v): ?>
					<p class="error_message"><?php echo $v ?></p>
<?php endforeach; ?>
					<div class="btn_area">
						<button id="add_schedule_btn" class="btn">追加</button>
					</div>
				</div>
			</form>
		</div>
	</main>
	<script src="./js/gsap/gsap.min.js"></script>
	<script src="./js/main.js" type="module"></script>
</body>
</html>
