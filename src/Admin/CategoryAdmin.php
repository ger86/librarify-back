<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Category;
use App\Repository\UserRepository;
use LogicException;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;

final class CategoryAdmin extends AbstractAdmin
{
    private ?UserRepository $userRepository;

    public function setUserRepository(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    protected function createNewInstance(): object
    {
        if ($this->userRepository === null) {
            throw new LogicException('Not user repository');
        }
        $user = $this->userRepository->findOneBy([]);
        if ($user === null) {
            throw new LogicException('Create at least one user');
        }
        return Category::create('', $user);
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection
            ->add('clone', $this->getRouterIdParameter() . '/clone')
            ->add('import');
    }

    protected function configureActionButtons(
        array $buttonList,
        string $action,
        ?object $object = null
    ): array {
        $buttonList['import'] = ['template' => 'admin/category/list__action_import.html.twig'];

        return $buttonList;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('name');
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('id')
            ->add('name')
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                    'clone' => [
                        'template' => 'admin/category/list__action_clone.html.twig',
                    ],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->add('id', null, ['disabled' => true])
            ->add('name');
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('id')
            ->add('name', null, ['label' => 'Nombre']);
    }
}
