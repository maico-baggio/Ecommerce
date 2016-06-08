<?php

namespace Admin\Controller;

use Admin\Entity\Produto;
use Admin\Form\ProdutoForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Validator\ProdutoValidator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

/**
 * Controlador para cadastrar novos produtos
 *
 * @category Admin
 * @package Controller
 * @author  Maico Baggio <maico.baggio@unochapeco.edu.br>
 */
class ProdutosController extends AbstractActionController {

    public function indexAction() {

        $page = (int) $_GET['page'];
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $select = $entityManager->createQueryBuilder()
        ->select('Produto')
        ->from('Admin\Entity\Produto', 'Produto')
                //->join('Produto.marca', 'Marca')
                //->join('Produto.subcategoria', 'SubCategoria')
                //->join('Produto.modelo', 'Modelo')
                //->join('Produto.categorias', 'Categoria')
        ->orderBy('Produto.nome', 'ASC');

        $adapter = new DoctrineAdapter(new ORMPaginator($select));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(ItensPerPage);

        if ($page > 0)
            $paginator->setCurrentPageNumber($page);

        //var_dump($paginator);exit;

        return new ViewModel(
            array('produtos' => $paginator)
            );
    }

    public function createAction() {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $form = new ProdutoForm($em);
        $request = $this->getRequest();

        if ($request->isPost()) {

            //$validator = new ProdutoValidator();
            //$form->setInputFilter($validator);

            $values = array_merge_recursive(
                $request->getPost()->toArray(), $request->getFiles()->toArray()
                );

            $form->setData($values);

            if ($form->isValid()) {
                $values = $form->getData();

                //var_dump($values);exit;

                $produto = new Produto();

                $file = str_replace(getcwd() . '/public/img/photos_produtos/', '', $values['photo']['tmp_name']);

                $produto->url_photo = $file;

                //var_dump($values['photo']['tmp_name']);exit;

                $this->thumb_photo($values['photo']['tmp_name'], $file);


                $produto->marca = $em->find('\Admin\Entity\Marca', $values['marca']);
                $produto->nome = $values['nome'];
                $produto->descricao = $values['descricao'];
                $produto->valor = $values['valor'];
                $produto->margem_de_lucro = $values['margem_de_lucro'];
                $produto->modelo = $em->find('\Admin\Entity\Modelo', $values['modelo']);
                $produto->subcategoria = $em->find('\Admin\Entity\SubCategoria', $values['subcategoria']);

                foreach ($values['categorias'] as $categoria)
                    $produto->categorias->add($em->find('\Admin\Entity\Categoria', $categoria));

                $em->persist($produto);

                try {
                    $em->flush();
                    $this->flashMessenger()->addSuccessMessage('Produto inserido com sucesso');
                    return $this->redirect()->toUrl('/admin/produtos/index');
                } catch (\Exception $e) {
                 $this->flashMessenger()->addErrorMessage('Erro ao inserir produto');
             }
         }
     }

     return new ViewModel(array(
        'form' => $form
        ));
 }

 public function updateAction() {
    $id = $this->params()->fromRoute('id', 0);
    $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    $request = $this->getRequest();

    if ($request->isPost()) {
        $values = $request->getPost();
        $produto = $em->find('\Admin\Entity\Produto', $id);

        $produto->marca = $em->find('\Admin\Entity\Marca', $values['marca']);
        $produto->nome = $values['nome'];
        $produto->descricao = $values['descricao'];
        $produto->valor = $values['valor'];
        $produto->margem_de_lucro = $values['margem_de_lucro'];
        $produto->modelo = $em->find('\Admin\Entity\Modelo', $values['modelo']);
        $produto->subcategoria = $em->find('\Admin\Entity\SubCategoria', $values['subcategoria']);

        $produto->categorias->clear();

        foreach ($values['categorias'] as $categoria)
            $produto->categorias->add($em->find('\Admin\Entity\Categoria', $categoria));

            //$em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $em->persist($produto);


        try {
            $em->flush();
            $this->flashMessenger()->addSuccessMessage('Produto editado com sucesso');

            return $this->redirect()->toUrl('/admin/produtos/index');
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage('Erro ao editar produto');
        }
    }

    if ($id > 0) {
        $form = new ProdutoForm($em);
        $produto = $em->find('\Admin\Entity\Produto', $id);
        $form->bind($produto);

        return new ViewModel(array('form' => $form));
    }

    $this->request->setStatusCode(404);

    return $this->request;
}



public function visualizarAction(){
        $id = $this->params()->fromRoute('id', 0);
        var_dump($id);exit;

}




public function deleteAction() {
    $id = $this->params()->fromRoute('id', 0);
    $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    $produto = $entityManager->find('\Admin\Entity\Produto', $id);
    $entityManager->remove($produto);

    try {
        $entityManager->flush();
        $this->flashMessenger()->addSuccessMessage('Produto excluido com sucesso');
            //$this->view->resp = "Produto,  " . $produto->nome. ", enviado com sucesso!";

        return $this->redirect()->toUrl('/admin/produtos/index');
    } catch (\Exception $e) {
        $this->flashMessenger()->addErrorMessage('Erro ao excluir produto');
    }
}

public function thumb_photo($file, $file_name) {
    header('Content-type: image/jpeg');
    list($width, $height) = getimagesize($file);
    $new_width = 120;
    $new_height = 100;
    $image_p = imagecreatetruecolor($new_width, $new_height);
    $image = imagecreatefromjpeg($file);
    imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    imagejpeg($image_p, getcwd() . '/public/img/photos_produtos/thumb/' . $file_name, 50);
    imagedestroy($image_p);
}

}
