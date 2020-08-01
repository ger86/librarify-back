<?php

namespace App\Controller\Api;

use App\Service\CategoryManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;

class CategoryController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/categories")
     * @Rest\View(serializerGroups={"book"}, serializerEnableMaxDepthChecks=true)
     */
    public function getAction(
        CategoryManager $categoryManager
    ) {
        return $categoryManager->getRepository()->findAll();
    }
}
