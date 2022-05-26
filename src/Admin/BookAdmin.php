<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Book;
use App\Repository\UserRepository;
use LogicException;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

final class BookAdmin extends AbstractAdmin
{
    private ?UserRepository $userRepository;

    protected function createNewInstance(): object
    {
        if ($this->userRepository === null) {
            throw new LogicException('Not user repository');
        }
        $user = $this->userRepository->findOneBy([]);
        if ($user === null) {
            throw new LogicException('Create at least one user');
        }
        return Book::create(
            title: '',
            user: $user,
            image: null,
            description: null,
            score: null,
            readAt: null,
            authors: [],
            categories: []
        );
    }

    public function setUserRepository(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('id')
            ->add('title')
            ->add('image')
            ->add('description')
            ->add('createdAt')
            ->add('readAt')
            ->add('score.value');
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('id')
            ->add('title')
            ->add('image')
            ->add('description')
            ->add('createdAt')
            ->add('readAt')
            ->add('score.value')
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
            ->add('title')
            ->add('user')
            ->add('image')
            ->add('categories')
            ->add('authors')
            ->add('description')
            ->add('createdAt')
            ->add('readAt')
            ->add('score.value');
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('id')
            ->add('title')
            ->add('categories')
            ->add('authors')
            ->add('image')
            ->add('description')
            ->add('createdAt')
            ->add('readAt')
            ->add('score.value');
    }
}
