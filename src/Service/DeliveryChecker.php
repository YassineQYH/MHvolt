<?php

namespace App\Service;

use App\Classe\Mail;
use App\Entity\Order;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;

class DeliveryChecker
{
    public function __construct(
        private readonly OrderRepository $orderRepository,
        private readonly EntityManagerInterface $em
    ) {}

    public function checkDeliveries(): void
    {
        $orders = $this->orderRepository->findBy(['deliveryState' => Order::STATE_SHIPPING]);

        foreach ($orders as $order) {
            $primaryTracking = $order->getTrackingNumber();
            $primaryCarrier = $order->getCarrier();

            if (!$primaryTracking || !$primaryCarrier) {
                continue;
            }

            $delivered = $this->isDelivered($primaryTracking, $primaryCarrier);

            if ($delivered) {
                $order->setDeliveryState(Order::STATE_DELIVERED);

                $user = $order->getUser();
                if ($user && $user->getEmail()) {
                    $mail = new Mail();
                    $content = sprintf(
                        "Bonjour %s,<br><br>Votre commande #<strong>%s</strong> a été livrée avec succès.<br>Merci pour votre confiance !",
                        htmlspecialchars($user->getFirstName() ?? 'Client'),
                        htmlspecialchars($order->getReference())
                    );

                    $mail->send(
                        $user->getEmail(),
                        $user->getFirstName() ?? 'Client',
                        "Votre commande MHvolt a été livrée !",
                        $content
                    );
                }
            }
        }

        $this->em->flush();
    }

    private function isDelivered(string $trackingNumber, string $carrier): bool
    {
        $apiKey = $_ENV['TRACK123_API_KEY'] ?? null;
        if (!$apiKey) {
            return false;
        }

        $url = sprintf("https://api.track123.com/v1/trackings/%s?carrier=%s", urlencode($trackingNumber), urlencode($carrier));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$apiKey}",
            "Accept: application/json"
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (!$response || $httpCode !== 200) {
            return false;
        }

        $data = json_decode($response, true);

        return isset($data['status']) && strtolower($data['status']) === 'delivered';
    }
}
