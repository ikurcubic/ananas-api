<?php

declare(strict_types=1);

namespace IvanKurcubic\AnanasAPI;

class AnanasApi extends Client
{
    /**
     * @param string $searchStr
     * @param int $perPage
     * @return array<Product>
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAllProducts(string $searchStr = '', int $perPage = 20): array
    {
        $currentPage = 1;
        $products = [];
        do {
            $jsonData = $this->getProducts($searchStr, $currentPage, $perPage);
            $lastPage = $jsonData['last_page'];
            $jsonData['products'] = array_map(
                function(array $productData){
                    return Product::createFromArray($productData);
                },
                $jsonData['products']);
            $products = array_merge($products, $jsonData['products']);
            $currentPage++;
        } while ($currentPage <= $lastPage);
        return $products;
    }
}
