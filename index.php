<?php
	// 環境設定
  require_once("env.php");

	// URL
	define('AUTH_URL', 'https://www.fitbit.com/oauth2/authorize');

	// Fitbitの認証ページにリダイレクトする
	$params = array(
		'client_id' => CONSUMER_KEY,
		'redirect_uri' => CALLBACK_URL,
		'scope' => 'heartrate',
		'response_type' => 'code',
	);
	header("Location: " . AUTH_URL . '?' . http_build_query($params));
?>
