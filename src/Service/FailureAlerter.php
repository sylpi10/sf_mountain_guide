<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Alerte push (ntfy) quand un mail ne part pas : une alerte par mail serait perdue avec lui.
 * Pas de données du visiteur dans l'alerte, elle transite par un service tiers.
 */
class FailureAlerter
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private LoggerInterface $logger,
        private string $ntfyTopic,
    ) {}

    /**
     * @param string $title en ASCII : il part dans un en-tête HTTP
     */
    public function alert(string $title, string $message, \Throwable $e): void
    {
        if ($this->ntfyTopic === "") {
            return;
        }

        try {
            $this->httpClient->request("POST", "https://ntfy.sh/" . $this->ntfyTopic, [
                "headers" => [
                    "Title" => $title,
                    "Priority" => "high",
                    "Tags" => "warning",
                ],
                "body" => $message . "\n" . mb_substr($e->getMessage(), 0, 300),
                "timeout" => 5,
            ])->getStatusCode();
        } catch (\Throwable $alertError) {
            $this->logger->error("Alerte ntfy impossible", ["exception" => $alertError]);
        }
    }
}
