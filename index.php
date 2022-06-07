<?php
require "./function.php";

try {
  $pdo = dbConnection();
} catch (PDOException $e) {
  exit('DB Connection Error:' . $e->getMessage());
}

$sql = "SELECT schedules.*, authors.name AS author, colors.color_code
FROM schedules
LEFT JOIN authors ON schedules.author_id = authors.id
LEFT JOIN colors ON schedules.color_id = colors.id
ORDER BY start_at;";
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

if ($status == false) {
  sqlError($stmt);
} else {
  $dataLsit = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8" />
<title>G's TOKYO LAB13th イベントカレンダー version2.0</title>
<meta name="keywords" content="">
<meta name="description" content="">
<meta name="format-detection" content="telephone=no">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="”googlebot”" content="”noindex”" />
<link rel="stylesheet" href="./css/reset.css" />
<link rel="stylesheet" href="./css/style.css" />
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100;500&display=swap" rel="stylesheet" />
</head>
<body class="top_page">
	<main>
		<div id="container">
			<?php include('./templates/sidebar.php'); ?>
			<div id="calender">
				<div class="__sec_body__">
					<ul id="display_schedule_area">
<?php
foreach($dataLsit as $k => $v):
$currentDate = date("Y年n月", strtotime($v['start_at']));
$prevDate = date("Y年n月", strtotime($dataLsit[$k-1]['start_at']));
if($currentDate !== $prevDate):
?>
						<li class="section"><span><?php echo $currentDate; ?></span></li>
<?php endif; ?>
						<li class="schedule" data-title="<?php echo $v['title']; ?>">
							<a href="./show.php?id=<?php echo $v['id']; ?>">
								<dl style="background-color:<?php echo $v['color_code']; ?>">
									<dt><?php echo date("n/j G:i", strtotime($v['start_at'])); ?> 〜 <?php echo date("n/j G:i", strtotime($v['end_at'])); ?><span style="color:<?php echo $v['color_code']; ?>"><?php echo $v['author']; ?></span></dt>
									<dd><?php echo $v['title']; ?></dd>
								</dl>
							</a>
						</li>
<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
		<div id="loading_screen">
			<span>G's 第13期 LABコース<br>イベントカレンダー</span>
		</div>
	</main>
	<?php include('./templates/script.php'); ?>
</body>
</html>
