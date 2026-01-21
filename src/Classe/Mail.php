<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    private string $api_key;
    private string $api_key_secret;

    public function __construct()
    {
        $this->api_key = $_ENV['MAILJET_PUBLIC_KEY'] ?? '';
        $this->api_key_secret = $_ENV['MAILJET_PRIVATE_KEY'] ?? '';
    }

    public function send($to_email, $to_name, $subject, $content)
    {
        $mj = new Client($this->api_key, $this->api_key_secret, true, ['version' => 'v3.1']);
        $mj->setTimeout(3);

        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "yassine.qyh@gmail.com",
                        'Name' => "MHvolt"
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
        return $response->success();
    }
}
