<?php

namespace Admin\Controller;

use Admin\Entity\SubCategoria;
use Admin\Form\SubCategoriaForm;
use Admin\Validator\SubCategoriaValidator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

/**
 * Controlador para cadastrar novas marcas.
 *
 * @category Admin
 * @package  Controller
 * @author   Maico <email@email.com>
 */
class SubCategoriasController extends AbstractActionController
{

    public function indexAction()
    {
        $page = (int) $_GET['page'];

        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $select = $entityManager->createQueryBuilder()
                ->select('SubCategoria')
                ->from('Admin\Entity\SubCategoria', 'SubCategoria')
                ->orderBy('SubCategoria.descricao', 'ASC');

        $adapter = new DoctrineAdapter(new ORMPaginator($select));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(ItensPerPage);

        if ($page > 0) {
            $paginator->setCurrentPageNumber($page);
        }

        return new ViewModel(
                array('subCategorias' => $paginator)
        );
    }

    public function saveAction() 
    {
        $entityManager = $this->getServiceLocator()
            ->get('Doctrine\ORM\EntityManager');
        //$session = $this->getService('Session');

        $id = (int) $this->params()->fromRoute('id', 0);

        $form = new SubCategoriaForm();
        $request = $this->getRequest();

        if ($request->isPost()) {

            $validator = new SubCategoriaValidator();
            $form->setInputFilter($validator);
            $values = $request->getPost();
            $form->setData($values);

            if ($form->isValid()) {

                $values = $form->getData();

                if ($id) {
                    $tipoEndereco = $entityManager
                        ->find('Admin\Entity\SubCategoria', $id);
                } else {
                    $tipoEndereco = new SubCategoria();
                }
                $tipoEndereco->descricao = $values['descricao'];
                
                $entityManager = $this->getServiceLocator()
                    ->get('Doctrine\ORM\EntityManager');
                $entityManager->persist($tipoEndereco);
                
                try {
                    $entityManager->flush();
                    $this->flashMessenger()
                        ->addSuccessMessage('Subcategoria cadastrada com sucesso.');
                    return $this->redirect()->toUrl('/admin/subcategorias/index');
                } catch (\Exception $e) {
                    $this->flashMessenger()
                        ->addErrorMessage('Erro ao cadastrar uma subcategoria.');
                }
            }
        }

        if ($id > 0) {
            try {
                $form = new SubCategoriaForm();
                $tipoEndereco = $entityManager->find('Admin\Entity\SubCategoria', $id);
                $form->bind($tipoEndereco);
            } catch (\Exception $e) {
                $this->flashMessenger()
                    ->addErrorMessage('Erro ao tentar editar a subcategoria');
                $this->redirect()->toUrl('/admin/subcategorias/index');
            }
        }

        return new ViewModel(
            array
            (
                'form' => $form
            )
        );
    }

    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id', 0);
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $subCategoria = $entityManager->find('\Admin\Entity\SubCategoria', $id);
        $entityManager->remove($subCategoria);
        try {
            $entityManager->flush();
            $this->flashMessenger()
                ->addSuccessMessage('Subcategoria excluida com sucesso.');
            return $this->redirect()->toUrl('/admin/subcategorias/index');
        } catch (\Exception $e) {
            $this->flashMessenger()
                ->addErrorMessage('Erro ao excluir uma subcategoria.');
                return $this->redirect()->toUrl('/admin/subcategorias/index');
        }
    }
}
