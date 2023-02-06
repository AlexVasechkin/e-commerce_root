<?php

namespace App\Controller\ControlPanel;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductCategoryEditorController extends AbstractController
{
    /**
     * @Route("/control-panel/product-category", methods={"GET"}, name="control-panel__product-categories")
     */
    public function main()
    {
        return $this->render('cp/product-category-editor.html.twig', [
            'title' => 'Категории товаров'
        ]);
    }
}
