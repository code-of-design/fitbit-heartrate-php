<?php
  require_once("env.php"); // 環境設定

	// Fitbitの認証ページにリダイレクトする
	$params = array(
		'client_id' => CLIENT_ID,
		'redirect_uri' => CALLBACK_URL,
		'scope' => 'heartrate',
		'response_type' => 'code',
	);
	header("Location: " . AUTH_URL . '?' . http_build_query($params)); // GET送信
?>
