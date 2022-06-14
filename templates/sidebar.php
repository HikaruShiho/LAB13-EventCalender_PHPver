<nav id="side_menu">
  <h1>
    <a href="./">
    TOKYO LAB13期<br><span>イベントカレンダー</span>
    </a>
  </h1>
  <ul>
    <li>
      <a href="./store.php">
        <span id="add_schedule">スケジュール追加</span>
      </a>
    </li>
    <li>
      <a href="https://12-shiho-lab13.sakura.ne.jp/02_js_assignment/janken_tpl.html" target="_blank">
        <span>ホッと一息</span>
      </a>
    </li>
<?php if(isset($_SESSION["check_session"])): ?>
  <li>
      <a href="./logout.php">
        <span>ログアウト</span>
      </a>
    </li>
<?php else: ?>
    <li>
      <a href="./login.php">
        <span>ログイン</span>
      </a>
    </li>
    <li>
      <a href="./register.php">
        <span>サインイン</span>
      </a>
    </li>
<?php endif; ?>
    <li class="none">
      <span>工事中・・・</span>
    </li>
    <li class="none">
      <span>工事中・・・</span>
    </li>
    <li class="none">
      <span>工事中・・・</span>
    </li>
  </ul>
  <p>
    <input type="text" id="serch_schedule" placeholder="キーワードで検索">
  </p>
  <!-- <p>
    <a id="download" href="./csv/sample.csv" download="sample.csv">CSVファイルダウンロード</a>
  </p>
  <p>
    <form method="POST" enctype="multipart/form-data">
      <span>編集したCSVファイルを<br>アップロードしてください<br>↓↓↓</span>
      <input type="file" multiple id="upload_csv" name="csv">
      <button type="submit">一括登録</button>
    </form>
  </p> -->
</nav>
