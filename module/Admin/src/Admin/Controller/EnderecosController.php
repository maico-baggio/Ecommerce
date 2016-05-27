<?php

namespace Ecommerce\Controller;

use Ecommerce\Entity\Endereco;
use Ecommerce\Form\EnderecoForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Ecommerce\Validator\EnderecoValidator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

/**
 * Controlador para cadastrar novos produtos
 *
 * @category Ecommerce
 * @package Controller
 * @author  Maico
 */
class EnderecosController extends AbstractActionController {

    public function indexAction() {

        $page = (int) $_GET['page'];
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $select = $entityManager->createQueryBuilder()
                ->select('Endereco')
                ->from('Ecommerce\Entity\Endereco', 'Endereco')
                //->join('Produto.marca', 'Marca')
                //->join('Produto.subcategoria', 'SubCategoria')
                //->join('Produto.modelo', 'Modelo')
                //->join('Produto.categorias', 'Categoria')
                ->orderBy('Endereco.cidade', 'ASC');

        $adapter = new DoctrineAdapter(new ORMPaginator($select));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(ItensPerPage);

        if ($page > 0)
            $paginator->setCurrentPageNumber($page);

        //var_dump($paginator);exit;

        return new ViewModel(
                array('enderecos' => $paginator)
        );
    }

    public function createAction() {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $form = new EnderecoForm($em);
        $request = $this->getRequest();

        if ($request->isPost()) {
            
            $validator = new EnderecoValidator();
            $form->setInputFilter($validator);
            $values = $request->getPost();
            $form->setData($values);

            if ($form->isValid()) {
                $values = $form->getData();

                $endereco = new Endereco();
                
                $endereco->nome_do_destinatario = $values['nome_do_destinatario'];
                $endereco->cep = $values['cep'];
                $endereco->numero = $values['numero'];
                $endereco->endereco = $values['endereco'];
                $endereco->complemento = $values['complemento'];
                $endereco->bairro = $values['bairro'];
                $endereco->informacao_referencia = $values['informacao_referencia'];
                $endereco->cidade = $values['cidade'];
                $endereco->estado = $values['estado'];
                $endereco->tipo_endereco = $em->find('\Ecommerce\Entity\TipoEndereco', $values['tipo_endereco']);
                
                var_dump($endereco);exit;
                

                $endereco->marca = $em->find('\Ecommerce\Entity\Marca', $values['marca']);
                $endereco->nome = $values['nome'];
                $endereco->descricao = $values['descricao'];
                $endereco->valor = $values['valor'];
                $endereco->margem_de_lucro = $values['margem_de_lucro'];
                $endereco->modelo = $em->find('\Ecommerce\Entity\Modelo', $values['modelo']);
                $endereco->subcategoria = $em->find('\Ecommerce\Entity\SubCategoria', $values['subcategoria']);

                foreach ($values['categorias'] as $categoria)
                    $produto->categorias->add($em->find('\Ecommerce\Entity\Categoria', $categoria));

                $em->persist($produto);

                //var_dump($em);exit;
                // try {
                $em->flush();
                //$this->flashMessenger()->addSuccessMessage('Produto inserido com sucesso');
                //$this->view->resp = "Produto,  " . $produto->nome. ", enviado com sucesso!";

                return $this->redirect()->toUrl('/ecommerce/produtos/index');
                //} catch (\Exception $e) {
                //   $this->flashMessenger()->addErrorMessage('Erro ao inserir produto');
                //}
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
            $produto = $em->find('\Ecommerce\Entity\Produto', $id);

            $produto->marca = $em->find('\Ecommerce\Entity\Marca', $values['marca']);
            $produto->nome = $values['nome'];
            $produto->descricao = $values['descricao'];
            $produto->valor = $values['valor'];
            $produto->margem_de_lucro = $values['margem_de_lucro'];
            $produto->modelo = $em->find('\Ecommerce\Entity\Modelo', $values['modelo']);
            $produto->subcategoria = $em->find('\Ecommerce\Entity\SubCategoria', $values['subcategoria']);

            $produto->categorias->clear();

            foreach ($values['categorias'] as $categoria)
                $produto->categorias->add($em->find('\Ecommerce\Entity\Categoria', $categoria));

            //$em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $em->persist($produto);


            try {
                $em->flush();
                $this->flashMessenger()->addSuccessMessage('Produto editado com sucesso');
                //$this->view->resp = "Produto,  " . $produto->nome. ", enviado com sucesso!";

                return $this->redirect()->toUrl('/ecommerce/produtos/index');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage('Erro ao editar produto');
            }
            //$em->flush();
            //return $this->redirect()->toUrl('/admin/produtos');
        }

        if ($id > 0) {
            $form = new ProdutoForm($em);
            $produto = $em->find('\Ecommerce\Entity\Produto', $id);
            $form->bind($produto);

            return new ViewModel(array('form' => $form));
        }

        $this->request->setStatusCode(404);

        return $this->request;
    }

    public function deleteAction() {
        $id = $this->params()->fromRoute('id', 0);
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $produto = $entityManager->find('\Ecommerce\Entity\Produto', $id);
        $entityManager->remove($produto);

        try {
            $entityManager->flush();
            $this->flashMessenger()->addSuccessMessage('Produto excluido com sucesso');
            //$this->view->resp = "Produto,  " . $produto->nome. ", enviado com sucesso!";

            return $this->redirect()->toUrl('/ecommerce/produtos/index');
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
