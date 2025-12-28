<?php
$ch = curl_init("https://api.mailjet.com/v3.1/send");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
$result = curl_exec($ch);
if(curl_errno($ch)) {
    echo 'Erreur cURL : ' . curl_error($ch);
} else {
    echo 'cURL OK';
}
curl_close($ch);
