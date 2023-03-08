<?php

namespace App\Controller\Api\V1;

use App\Application\Actions\ProductGroup\CreateProductGroupAction;
use App\Application\Actions\ProductGroup\DTO\CreateProductGroupRequest;
use App\Entity\ProductGroup;
use App\Repository\ProductGroupRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductGroupController extends AbstractController
{
    /**
     * @Route("/api/v1/private/product-group/create", methods={"POST"})
     */
    public function create(
        Request $request,
        CreateProductGroupAction $createProductGroupAction
    ) {
        $getName = function (array $requestParams): string {
            $value = $requestParams['name'] ?? null;

            if (!(   is_string($value)
                  && (strlen($value) > 0)
                 )
            ) {
                throw new \Exception('name: expected not empty string');
            }

            return $value;
        };

        $productGroup = $createProductGroupAction->execute(new CreateProductGroupRequest(
            mb_strtolower($getName($request->toArray()), 'UTF-8')
        ));

        return $this->json([
            'id' => $productGroup->getId()
        ]);
    }

    private function serialize(ProductGroup $productGroup): array
    {
        return [
            'id' => $productGroup->getId(),
            'name' => $productGroup->getName()
        ];
    }

    /**
     * @Route("/api/v1/public/product-group/list")
     */
    public function getList(ProductGroupRepository $productGroupRepository)
    {
        return $this->json([
            'payload' => array_map(fn(ProductGroup $item) => $this->serialize($item), $productGroupRepository->findAll())
        ]);
    }
}
