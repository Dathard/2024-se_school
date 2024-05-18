<?php

declare(strict_types=1);

namespace App\Model\Integration;

class Nbu
{
    /**
     * @param string $currencyCode
     * @return float
     * @throws \Exception
     */
    public function getRate(string $currencyCode): float
    {
        $queryParams = [
            'valcode' => $currencyCode,
            'json' => true
        ];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://bank.gov.ua/NBUStatService/v1/statdirectory/exchange?" . http_build_query($queryParams),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
            ]
        ]);

        $response = curl_exec($curl);
        if ($error = curl_error($curl)) {
            throw new \Exception("cURL Error #: " . $error);
        }

        curl_close($curl);

        $response = json_decode($response, true);

        if (! $response) {
            throw new \Exception('Empty response received from NBU');
        }

        return (float) array_shift($response)['rate'];
    }
}