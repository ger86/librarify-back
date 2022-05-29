<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Author;
use App\Repository\UserRepository;
use LogicException;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

final class AuthorAdmin extends AbstractAdmin
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
        return Author::create('', $user);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('id')
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
            ->add('name');
    }
}
