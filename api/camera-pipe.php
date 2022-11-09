<?php

function callAPI($method, $url, $data, $header, $auth)
{
	$curl = curl_init();

	if ($header) curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

	switch ($method) {
		case "POST":
			curl_setopt($curl, CURLOPT_POST, 1);
			if ($data) curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			break;
		case "PUT":
			curl_setopt($curl, CURLOPT_PUT, 1);
			if ($data) curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			break;
		case "DELETE":
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
			break;
		default:
			if ($data) $url = sprintf("%s?%s", $url, http_build_query($data));
	}

	// Optional Authentication:
	if ($auth) {
		curl_setopt($curl, CURLOPT_HTTPAUTH, $auth[0]);

		curl_setopt($curl, CURLOPT_USERPWD, $auth[1]);
	}

	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);


	$result = curl_exec($curl);
	// $contentLength = curl_getinfo($curl, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
	// $rescode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	curl_close($curl);

	return $result;
}


$imgData = callAPI("GET", "http://185.94.82.19/ISAPI/Streaming/channels/1/picture", false, false, [CURLAUTH_DIGEST, 'live:WebcamLive']);

// send the right headers
header("Content-Type: image/jpeg; charset=\"UTF-8\"");
$contentLength = mb_strlen($imgData, '8bit');
header("Content-Length: " . $contentLength);

print($imgData);
