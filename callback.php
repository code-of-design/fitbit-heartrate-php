<?php
  // 環境設定
  require_once("env.php");

  // アクセストークンとユーザIDを取得する
  // POSTヘッダを生成する
  $header = [
  	'Authorization: Basic ' . base64_encode(CLIENT_ID.':'.CLIENT_SECRET),
  	'Content-Type: application/x-www-form-urlencoded',
  ];
  // POSTパラメータを生成する
  $params = array(
  	'client_id' => CLIENT_ID,
  	'grant_type' => 'authorization_code',
  	'redirect_uri' => CALLBACK_URL,
  	'code' => $_GET['code'],
  );
  // POST送信
  $options = array(
  	'http' => array(
  		'method' => 'POST',
  		'header' => implode(PHP_EOL,$header),
  		'content' => http_build_query($params),
  		'ignore_errors' => true
  	)
  );
  $context = stream_context_create($options);
  $response = file_get_contents(TOKEN_URL, false, $context);
  $token = json_decode($response, true); // アクセストークン
  // エラー処理
  if(isset($token['error'])){
  	echo 'ERROR!!!';
  	exit;
  }
  $access_token = $token['access_token']; // アクセストークン
  $user_id = $token['user_id']; // ユーザID

  // 心拍数を取得する
  // $api_url = 'https://api.fitbit.com/1/user/' . $user_id . '/activities/heart/date/today/1d.json'; // Get Heart Rate Time Series
  $api_url = 'https://api.fitbit.com/1/user/-/activities/heart/date/today/1d/1sec.json'; // Get Heart Rate Intraday Time Series
  // GETヘッダを生成する
  $header = 'Authorization: Bearer ' . $access_token;
  // $params = array('access_token' => $access_token); // アクセストークン
  $options = array(
  	'http' => array(
  		'method' => 'GET',
  		'header' => $header,
  		'ignore_errors' => true
  	)
  );
  $context = stream_context_create($options); // HTTPリクエストを$api_urlに対して送信する
  $response = file_get_contents($api_url, false, $context); // レスポンス
  // GETのレスポンスを取得する
  $heartrate_json = $response; // 心拍数JSON
  // echo $heartrate_json; // 表示する
  // 心拍数JSONを心拍数配列にデコードする
  $heatrate = json_decode($heartrate_json, true);
  $heatrate_len = count($heatrate["activities-heart-intraday"]["dataset"]); // 1日の心拍数のログサイズ
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Fitbit HeartRate PHP7.0</title>
    <style>
      p{
        font-size: 48px;
        margin: 20px;
      }
    </style>
  </head>
  <body>
    <p>最新の心拍数</p>
    <?php
      echo "<p>Time: ".$heatrate["activities-heart-intraday"]["dataset"][$heatrate_len-1]["time"]."</p>";
      echo "<p>HeartRate: ".$heatrate["activities-heart-intraday"]["dataset"][$heatrate_len-1]["value"]."</p>";
    ?>
  </body>
</html>
