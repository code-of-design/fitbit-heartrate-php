<?php
  // 環境設定
  require_once("env.php");

  // POST送信のヘッダー
  $header = [
  	'Authorization: Basic ' . base64_encode(CONSUMER_KEY.':'.CONSUMER_SECRET),
  	'Content-Type: application/x-www-form-urlencoded',
  ];

  // アクセストークンの取得
  $params = array(
  	'client_id' => CONSUMER_KEY,
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
  $context = stream_context_create($options); // ヘッダと共にHTTPリクエストをTOKEN_URLに対して送信する
  $res = file_get_contents(TOKEN_URL, false, $context); // レスポンス

  // POST送信のレスポンス取得
  $token = json_decode($res, true);
  if(isset($token['error'])){ // エラー処理
  	echo 'ERROR!!!';
  	exit;
  }
  // レスポンスからアクセストークンとユーザIDを取得する
  $access_token = $token['access_token']; // アクセストークン
  $user_id = $token['user_id']; // ユーザID

  // １日の心拍数を取得する（activities/heart）
  $params = array('access_token' => $access_token); // アクセストークン
  // $api_url = 'https://api.fitbit.com/1/user/' . $user_id . '/activities/heart/date/today/1d.json'; // Get Heart Rate Time Series
  $api_url = 'https://api.fitbit.com/1/user/-/activities/heart/date/today/1d/1sec.json'; // Get Heart Rate Intraday Time Series
  $header = 'Authorization: Bearer ' . $access_token;
  $options = array(
  	'http' => array(
  		'method' => 'GET',
  		'header' => $header,
  		'ignore_errors' => true
  	)
  );
  $context = stream_context_create($options); // ヘッダと共にHTTPリクエストを$api_urlに対して送信する
  $res = file_get_contents($api_url, false, $context); // レスポンス

  // 心拍数JSON
  $heartrate_json = $res;
  // 表示する
  // echo $heartrate_json;

  // 心拍数JSONを心拍数配列にデコードする
  $heatrate = json_decode($heartrate_json, true);

  $heatrate_len = count($heatrate["activities-heart-intraday"]["dataset"]);
  echo $heatrate_len."<br>";
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Fitbit HeartRate PHP7.0</title>
  </head>
  <body>
    <?php
      echo "Time: ".$heatrate["activities-heart-intraday"]["dataset"][$heatrate_len-1]["time"]."<br>";
      echo "HeartRate: ".$heatrate["activities-heart-intraday"]["dataset"][$heatrate_len-1]["value"]."<br>";
    ?>
  </body>
</html>
