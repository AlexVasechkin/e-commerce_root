<?php

namespace App\Application\Actions\Product;

use App\Entity\ProductCategoryItem;
use App\Repository\CategoryWebpageRepository;
use App\Repository\ProductCategoryItemRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductWebpageRepository;

class FilterProductsAction
{
    private ProductRepository $productRepository;

    private FilterByIndexAction $filterByIndexAction;

    private ProductWebpageRepository $productWebpageRepository;

    private CategoryWebpageRepository $categoryWebpageRepository;

    private ProductCategoryItemRepository $productCategoryItemRepository;

    public function __construct(
        ProductRepository $productRepository,
        FilterByIndexAction $filterByIndexAction,
        ProductWebpageRepository $productWebpageRepository,
        CategoryWebpageRepository $categoryWebpageRepository,
        ProductCategoryItemRepository $productCategoryItemRepository
    ) {
        $this->productRepository = $productRepository;
        $this->filterByIndexAction = $filterByIndexAction;
        $this->productWebpageRepository = $productWebpageRepository;
        $this->categoryWebpageRepository = $categoryWebpageRepository;
        $this->productCategoryItemRepository = $productCategoryItemRepository;
    }

    public function execute(array $filters): array
    {
        $resultSet = $this->productRepository->getFullIdList();

        if (isset($filters['categoryId']) && is_int($filters['categoryId'])) {
            $resultSet = array_intersect(
                $resultSet,
                $this->productCategoryItemRepository->filterByCategories([$filters['categoryId']])
            );
        }

        if (   isset($filters['categoryAlias'])
            && is_string($filters['categoryAlias'])
        ) {
            $categoryWebpage = $this->categoryWebpageRepository->findCategoryWebpageByAlias($filters['categoryAlias']);

            if ($categoryWebpage) {
                $resultSet = array_intersect(
                    $resultSet,
                    $categoryWebpage->getCategory()->getProductCategoryItems()->map(
                        fn(ProductCategoryItem $categoryItem) => $categoryItem->getProduct()->getId()
                    )->toArray()
                );
            } else {
                $resultSet = [];
            }
        }

        if (   isset($filters['productPageActive'])
            && is_bool($filters['productPageActive'])
        ) {
            $resultSet = array_intersect(
                $resultSet,
                $this->productWebpageRepository->filterProductsByActivity($filters['productPageActive'])
            );
        }

        return $resultSet;
    }
}
