<?php
  /**
   * 心拍数クラス
   *
   */
   /*
  class Heartrate {
    public $api_url = 'https://api.fitbit.com/1/user/-/activities/heart/date/today/1d/1sec.json'; // Get Heart Rate Intraday Time Series
    public $header = 'Authorization: Bearer ' . $access_token; // アクセストークン
    // $params = array('access_token' => $access_token);
    public $options = array(
      'http' => array(
        'method' => 'GET',
        'header' => $header,
        'ignore_errors' => true
      )
    );

    public $heatrate;　// 心拍数配列
    public $heartrate_json;　// 心拍数JSON
    public $heatrate_len; // 心拍数配列の大きさ

    // 心拍数を取得する
    public function getHeartrate(){
      $context = stream_context_create($options); // HTTPリクエストを$api_urlに対して送信する
      $heartrate_json = file_get_contents($api_url, false, $context); // レスポンス
      $heatrate = json_decode($heartrate_json, true); // 心拍数配列
    }

    // 心拍数配列の大きさを取得する
    public function getHeartrateSize(){
      $heatrate_len = count($heatrate["activities-heart-intraday"]["dataset"]);
    }
  }
  */

  $access_token = $_POST["token"];

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
  $heatrate = json_decode($heartrate_json, true); // 心拍数配列にデコードする
  $heatrate_len = count($heatrate["activities-heart-intraday"]["dataset"]);

  echo $heatrate["activities-heart-intraday"]["dataset"][$heatrate_len-1]["value"];
?>
