<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    public function send($to_email, $to_name, $subject, $content)
    {
        // Récupère les clés depuis les variables d'environnement
        $api_key = $_ENV['MAILJET_APIKEY_PUBLIC'] ?? $_ENV['MAILER_DSN_PUBLIC_KEY'] ?? null;
        $api_secret = $_ENV['MAILJET_APIKEY_PRIVATE'] ?? $_ENV['MAILER_DSN_PRIVATE_KEY'] ?? null;

        $mj = new Client($api_key, $api_secret, true, ['version' => 'v3.1']);

        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "yassine.qyh@gmail.com",
                        'Name' => "Hich Trott"
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
