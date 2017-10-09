<?php
  // 心拍数
  $access_token = $_POST["token"]; // アクセストークン

  $api_url = 'https://api.fitbit.com/1/user/-/activities/heart/date/today/1d/1sec.json'; // Get Heart Rate Intraday Time Series
  $header = 'Authorization: Bearer ' . $access_token; // アクセストークン
  // $params = array('access_token' => $access_token);
  $options = array(
    'http' => array(
      'method' => 'GET',
      'header' => $header,
      'ignore_errors' => true
    )
  );

  $context = stream_context_create($options); // HTTPリクエストを$api_urlに対して送信する
  $heartrate_json = file_get_contents($api_url, false, $context); // レスポンス
  $heatrate = json_decode($heartrate_json, true); // 配列デコード
  $heatrate_len = count($heatrate["activities-heart-intraday"]["dataset"]);

  // レスポンス配列
  $response = array();

  $response[] = array(
    "time" => $heatrate["activities-heart-intraday"]["dataset"][$heatrate_len-1]["time"],
    "heartrate" => $heatrate["activities-heart-intraday"]["dataset"][$heatrate_len-1]["value"]
  );

  // レスポンスを返す
  echo json_encode($response); // JSONエンコード
?>
