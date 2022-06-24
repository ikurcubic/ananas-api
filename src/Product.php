<?php

namespace IvanKurcubic\AnanasAPI;

class Product
{
    public ?int $id; //Long Product unique identifier
    public ?string $externalId; //String Unique product identifier on merchant side
    public ?string $ean; //String European Article Number
    public ?string $name; //String Product’s name
    public ?string $description; //String Product description
    public ?string $brand; //String Product’s brand
    public ?string $sku; //String Stock keeping unit
    public ?string $productType; //String Type of product
    public ?array $categories; //List<String> List of categories where product belongs
    public ?float $basePrice; //BigDecimal/Double Product price with VAT included
    public ?float $vat; //BigDecimal/Double Product VAT
    public int $stockLevel; //Integer Product available stock level
    public ?float $packageWeightValue; //BigDecimal/Double Package weight of product
    public ?string $packageWeightUnit; //String Product weight package unit

    public ?string $coverImage; //Product cover image
    public ?array $gallery; //Product images
    public ?string $parentEan; //The product will be a variation of the product to which the EAN is entered
    public ?string $category; //Main category where product belongs
    public ?array $attributes; //Attributes of product like color, size, material type, type of RAM, etc.

    public static function createFromArray(array $data): self
    {
        $obj = new self();
        $obj->id = $data['id'] ?? null;
        $obj->externalId = $data['externalId'] ?? null;
        $obj->ean = $data['ean'] ?? null;
        $obj->name = $data['name'] ?? null;
        $obj->description = $data['description'] ?? null;
        $obj->brand = $data['brand'] ?? null;
        $obj->sku = $data['sku'] ?? null;
        $obj->productType = $data['productType'] ?? null;
        $obj->categories = $data['categories'] ?? null;
        $obj->basePrice = $data['basePrice'] ?? null;
        $obj->vat = $data['vat'] ?? null;
        $obj->stockLevel = $data['stockLevel'] ?? 0;
        $obj->packageWeightValue = $data['packageWeightValue'] ?? null;
        $obj->packageWeightUnit = $data['packageWeightUnit'] ?? null;

        $obj->coverImage = $data['coverImage'] ?? null;
        $obj->gallery = $data['gallery'] ?? null;
        $obj->parentEan = $data['parentEan'] ?? null;
        $obj->category = $data['category'] ?? null;
        $obj->attributes = $data['attributes'] ?? null;
        return $obj;
    }

    public function toArray(): array
    {
        $data = [];
        $data['id'] = $this->id;
        $data['externalId'] = $this->externalId;
        $data['ean'] = $this->ean;
        $data['name'] = $this->name;
        $data['description'] = $this->description;
        $data['brand'] = $this->brand;
        $data['sku'] = $this->sku;
        $data['productType'] = $this->productType;
        $data['categories'] = $this->categories;
        $data['basePrice'] = $this->basePrice;
        $data['vat'] = $this->vat;
        $data['stockLevel'] = $this->stockLevel;
        $data['packageWeightValue'] = $this->packageWeightValue;
        $data['packageWeightUnit'] = $this->packageWeightUnit;
        $data['coverImage'] = $this->coverImage;
        $data['gallery'] = $this->gallery;
        $data['parentEan'] = $this->parentEan;
        $data['category'] = $this->category;
        $data['attributes'] = $this->attributes;
        return $data;
    }
}
