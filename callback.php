<?php
  require_once("env.php"); // 環境設定
  // require_once("heartrate.php"); // 心拍数

  // アクセストークンを取得する
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
  // レスポンス
  $context = stream_context_create($options);
  $response = file_get_contents(TOKEN_URL, false, $context);
  $token = json_decode($response, true); // アクセストークン
  // エラー処理
  if(isset($token['error'])){
  	echo 'ERROR!!!';
  	exit;
  }
  $access_token = $token['access_token']; // アクセストークン
  // $user_id = $token['user_id']; // ユーザID
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Fitbit HeartRate PHP7.0</title>
  </head>
  <body>
    <p>Fitbit API by PHP7.0 [ <a href="https://github.com/code-of-design/fitbit-heartrate-php">https://github.com/code-of-design/fitbit-heartrate-php</a> ]</p>
    <p>Time:<span class="time"></span></p>
    <p>HeartRate:<span class="heartrate"></span></p>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
      function getHeartrate(){
        $.post("heartrate.php", {"token": "<?php echo $access_token; ?>"},function(data){
          console.log(data);
          // var d = $.parseJSON(data);
          // console.log(d);
        });
      }
      setInterval("getHeartrate()", 3000);
    </script>
  </body>
</html>
