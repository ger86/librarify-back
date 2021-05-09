<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Book;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

final class BookAdmin extends AbstractAdmin
{
    protected function createNewInstance(): object
    {
        return Book::create('', null, '', null, null, [], []);
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
            ->add('score.value')
            ;
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
            ->add('_action', null, [
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
            ->add('image')
            ->add('categories')
            ->add('authors')
            ->add('description')
            ->add('createdAt')
            ->add('readAt')
            ->add('score.value')
            ;
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
            ->add('score.value')
            ;
    }
}
