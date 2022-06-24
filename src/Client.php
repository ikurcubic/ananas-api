<?php

declare(strict_types=1);

namespace IvanKurcubic\AnanasAPI;


use GuzzleHttp\Client as GuzzleClient;

/**
 *
 */
class Client
{
    const ENV_STAGE = 'stage';
    const ENV_PRODUCTION = 'production';
    private string $clientId;
    private string $clientSecret;
    private string $environment;
    private ?string $accessToken = null;
    private GuzzleClient $http;

    public function __construct(string $clientId, string $clientSecret, string $environment)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->environment = $environment;

        $host = $this->isProductionEnv() ? 'https://api.ananas.rs/' : 'https://api.stage.ananastest.com/';
        $this->http = new GuzzleClient(['base_uri' => $host]);
    }

    public function isProductionEnv(): bool
    {
        return $this->environment == self::ENV_PRODUCTION;
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array $params
     * @param bool $useAccessToken
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function request(string $method, string $endpoint, array $params = [], bool $useAccessToken = true): ?array
    {
        if ($useAccessToken) {
            $params['headers'] = [
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Accept' => 'application/json',
            ];
        }
        $response = $this->http->request($method, $endpoint, $params);
        if ($response->getStatusCode() >= 400) {
            throw new HttpResponseException($response);
        }
        return json_decode($response->getBody()->getContents(), true);
    }

    public function getToken(): void
    {
        $params = [
            "grantType" => "CLIENT_CREDENTIALS",
            "clientId" => $this->clientId,
            "clientSecret" => $this->clientSecret,
            "scope" => "public_api/full_access"

        ];
        $jsonData = $this->request('POST', '/iam/api/v1/auth/token', ['json' => $params], false);
        $this->accessToken = $jsonData["access_token"] ?? null;
    }


    /**
     * @param string $searchStr
     * @param int $page
     * @param int $perPage
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getProducts(string $searchStr = '', int $page = 1, int $perPage = 20): array
    {
        $endpoint = '/product/api/v1/merchant-integration/products';
        $query = [
            'page' => $page,
            'size' => $perPage
        ];
        if ($searchStr) {
            $query['search'] = $searchStr;
        }

        return $this->request('GET', $endpoint, ['query' => $query]);
    }

    /**
     * @param array $data
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function bulkAddEdit(array $data): string
    {
        $endpoint = '/product/api/v1/merchant-integration/import';
        $jsonData = $this->request('POST', $endpoint, ['json' => $data]);
        return $jsonData['id'];
    }

    /**
     * @param int $id
     * @param array $data
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function editProduct(int $id, array $data): array
    {
        $data = array_filter($data, function ($value, $key) {
            return in_array($key,
                ['packageWeightValue', 'packageWeightUnit', 'basePrice', 'vat', 'stockLevel', 'serviceable', 'sku']);
        });

        $endpoint = "/product/api/v1/merchant-integration/product/{$id}";
        $jsonData = $this->request('PUT', $endpoint, ['json' => $data]);
        return $jsonData;
    }

    public function bulkEditProducts(array $data): array
    {
        $data = array_filter($data, function ($value, $key) {
            return in_array($key,
                [
                    'id',
                    'packageWeightValue',
                    'packageWeightUnit',
                    'basePrice',
                    'vat',
                    'stockLevel',
                    'serviceable',
                    'sku'
                ]);
        });

        $endpoint = "/product/api/v1/merchant-integration/product/bulk";
        $jsonData = $this->request('PUT', $endpoint, ['json' => $data]);
        return $jsonData;
    }

    /**
     * @param array $eanCodes
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function eanExists(array $eanCodes): array
    {
        $eanCodes = array_values($eanCodes);

        $endpoint = "/product/api/v1/merchant-integration/ean/exists";
        $jsonData = $this->request('POST', $endpoint, ['json' => $eanCodes]);
        return $jsonData;
    }

    /**
     * @param array $discounts
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function bulkScheduleDiscounts(array $discounts): array
    {
        $endpoint = "/payment/api/v1/merchant-integration/discounts";
        $jsonData = $this->request('POST', $endpoint, ['json' => $discounts]);
        return $jsonData;
    }

    public function bulkUpdateDiscounts(array $discounts): array
    {
        $endpoint = "/payment/api/v1/merchant-integration/discounts";
        $jsonData = $this->request('PUT', $endpoint, ['json' => $discounts]);
        return $jsonData;
    }

    public function getDiscounts(string $dateFrom, string $dateTo): array
    {
        $endpoint = "/payment/api/v1/merchant-integration/discounts";
        $jsonData = $this->request('GET', $endpoint, ['query' => ['dateFrom' => $dateFrom, 'dateTo' => $dateTo]]);
        return $jsonData;
    }

    public function cancelDiscounts(string $discountId): array
    {
        $endpoint = "/payment/api/v1/merchant-integration/discounts/{$discountId}/cancellations";
        $jsonData = $this->request('PUT', $endpoint);
        return $jsonData;
    }
}
