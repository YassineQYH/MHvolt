<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail 
{
    private $api_key = '105c1f0ad9f45b622e03b8a1a90ff8cb';
    private $api_key_secret = '681521a691ef5cd2de1c0da57ff9c151';

    public function send($to_email, $to_name, $subject, $content)
    {
        $mj = new Client($this->api_key, $this->api_key_secret,true,['version' => 'v3.1']);
        $mj->setTimeout(3);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "qayouh.yassine@outlook.be",
                        'Name' => "Pergolazur "
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 1953465,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content,
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
        //$response->success() && dd($response->getData());
    }
}