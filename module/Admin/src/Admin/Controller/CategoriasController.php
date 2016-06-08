<?php

namespace Admin\Controller;

use Admin\Entity\Categoria;
use Admin\Form\CategoriaForm;
use Admin\Validator\CategoriaValidator;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

/**
 * Controlador para cadastrar novas categorias.
 *
 * @category Admin
 * @package  Controller
 * @author   Maico <e-amil@email.com>
 */
class CategoriasController extends AbstractActionController
{
    public function indexAction()
    {
        $page = (int) $_GET['page'];
        $entityManager = $this->getServiceLocator()
            ->get('Doctrine\ORM\EntityManager');
            
        $select = $entityManager->createQueryBuilder()
            ->select('Categoria')
            ->from('Admin\Entity\Categoria', 'Categoria')
            ->orderBy('Categoria.descricao', 'ASC');

        $adapter = new DoctrineAdapter(new ORMPaginator($select));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(ItensPerPage);

        if ($page > 0) {
            $paginator->setCurrentPageNumber($page);
        }

        return new ViewModel(
            array(
                'categorias' => $paginator
            )
        );
    }

    public function saveAction() 
    {
        $entityManager = $this->getServiceLocator()
            ->get('Doctrine\ORM\EntityManager');
        //$session = $this->getService('Session');

        $id = (int) $this->params()->fromRoute('id', 0);

        $form = new CategoriaForm();
        $request = $this->getRequest();

        if ($request->isPost()) {

            $validator = new CategoriaValidator();
            $form->setInputFilter($validator);
            $values = $request->getPost();
            $form->setData($values);

            if ($form->isValid()) {

                $values = $form->getData();

                if ($id) {
                    $categoria = $entityManager
                        ->find('Admin\Entity\Categoria', $id);
                } else {
                    $categoria = new Categoria();
                }
                $categoria->descricao = $values['descricao'];
                
                $entityManager = $this->getServiceLocator()
                    ->get('Doctrine\ORM\EntityManager');
                $entityManager->persist($categoria);
                
                try {
                    $entityManager->flush();
                    $this->flashMessenger()
                        ->addSuccessMessage('Categoria cadastrada com sucesso.');
                    return $this->redirect()->toUrl('/admin/categorias/index');
                } catch (\Exception $e) {
                    $this->flashMessenger()
                        ->addErrorMessage('Erro ao cadastrar uma categoria.');
                }
            }
        }

        if ($id > 0) {
            try {
                $form = new CategoriaForm();
                $categoria = $entityManager
                    ->find('Admin\Entity\Categoria', $id);

                $form->bind($categoria);
            } catch (\Exception $e) {
                $this->flashMessenger()
                    ->addErrorMessage('Erro ao tentar editar a categoria');
                $this->redirect()->toUrl('/admin/categorias/index');
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
        $entityManager = $this->getServiceLocator()
            ->get('Doctrine\ORM\EntityManager');
        $categoria = $entityManager->find('\Admin\Entity\Categoria', $id);
        $entityManager->remove($categoria);

        try {
            $entityManager->flush();
            $this->flashMessenger()
                ->addSuccessMessage('Categoria excluída com sucesso.');
            return $this->redirect()->toUrl('/admin/categorias');
        } catch (\Exception $e) {
            $msg = 'Erro ao excluir categoria, 
                verifique os vinculos ou contate o administrador.';
            $this->flashMessenger()->addErrorMessage($msg);
            return $this->redirect()->toUrl('/admin/categorias');
        }
        
    }

}
