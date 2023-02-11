<?php

namespace App\Application\Actions\Product;

use App\Entity\Product;
use App\Repository\ProductCategoryRepository;
use App\Repository\ProductImageRepository;
use App\Repository\ProductRepository;
use App\Repository\VendorRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class FetchProductsAction
{
    private ProductImageRepository $productImageRepository;

    private ParameterBagInterface $parameterBag;

    private VendorRepository $vendorRepository;

    private ProductCategoryRepository $productCategoryRepository;

    private ProductRepository $productRepository;

    /**
     * @param ProductImageRepository $productImageRepository
     * @param ParameterBagInterface $parameterBag
     * @param VendorRepository $vendorRepository
     * @param ProductCategoryRepository $productCategoryRepository
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductImageRepository $productImageRepository, ParameterBagInterface $parameterBag, VendorRepository $vendorRepository, ProductCategoryRepository $productCategoryRepository, ProductRepository $productRepository)
    {
        $this->productImageRepository = $productImageRepository;
        $this->parameterBag = $parameterBag;
        $this->vendorRepository = $vendorRepository;
        $this->productCategoryRepository = $productCategoryRepository;
        $this->productRepository = $productRepository;
    }

    public function execute(array $idList): array
    {
        $images = [];
        foreach ($this->productImageRepository->findAll() as $image) {
            if (!$image->getIsDeleted()) {
                $images[$image->getProduct()->getId()][] = [
                    'id' => $image->getId()->toBase32(),
                    'path' => $this->parameterBag->get('app.images_host') . '/product-image/' . $image->getId()->toBase32(),
                    'description' => $image->getDescription() ?? '',
                    'sortOrder' => $image->getSortOrder()
                ];
            }
        }

        $vendors = [];
        foreach ($this->vendorRepository->findAll() as $vendor) {
            $vendors[$vendor->getId()] = [
                'id' => $vendor->getId(),
                'name' => $vendor->getName()
            ];
        }

        $productCategories = [];
        foreach ($this->productCategoryRepository->findAll() as $productCategory) {
            $productCategories[$productCategory->getId()] = [
                'id' => $productCategory->getId(),
                'name' => $productCategory->getName()
            ];
        }

        return array_map(function (Product $product) use ($images, $vendors, $productCategories) {
            $pci = $product->getProductCategoryItems()->first();
            return [
                'id' => $product->getId(),
                'code' => $product->getCode(),
                'name' => $product->getName(),
                'price' => $product->getPrice() ?? 0,
                'count' => $product->getCount() ?? 0,
                'images' => $images[$product->getId()] ?? [],
                'vendor' => $vendors[$product->getVendor()->getId()] ?? [],
                'productCategory' => $productCategories[$pci->getCategory()->getId()] ?? [],
            ];
        }, $this->productRepository->findBy(['id' => $idList]));
    }
}
