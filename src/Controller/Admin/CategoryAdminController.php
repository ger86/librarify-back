<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\{Response, RedirectResponse, Request};
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryAdminController extends CRUDController
{
    public function cloneAction($id): Response
    {
        /** @var Category */
        $object = $this->admin->getSubject();
        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id: %s', $id));
        }

        $clonedCategory = Category::create($object->getName());
        $this->admin->create($clonedCategory);

        $this->addFlash('sonata_flash_success', 'Cloned successfully');

        return new RedirectResponse($this->admin->generateUrl('list'));
    }

    public function importAction(Request $request): Response
    {
        return new Response('Esto es la función de importar');
    }
}
