<?php

/**
 * Created by PhpStorm.
 * User: cezar
 * Date: 11/04/16
 * Time: 21:35
 */

namespace Admin\Form;

use Zend\Form\Form;
use Zend\InputFilter;

use Zend\Form\Element;

use Doctrine\ORM\EntityManager;
use Zend\InputFilter\Factory as InputFactory;
//use Zend\InputFilter\InputFilterAwareInterface;
//use Zend\InputFilter\InputFilterInterface;

/**
 * Form para cadastrar produtos
 * @category Admin
 * @package form
 * @author Maico <email@eamil.com>
 */
class ProdutoForm extends Form {

    public function __construct($em) {

        parent::__construct('ProdutoForm');
        $this->setAttribute('method', 'POST');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setAttribute('action', '');


        $this->add(array(
            'name' => 'id',
            'type' => 'hidden'
            ));
        $this->add(array(
            'name' => 'nome',
            'type' => 'text',
            'options' => array(
                'label' => 'Produto*:'
                ),
            'attributes' => array(
                //'placeholder' => 'Ex: fulano10',
                //'class' => 'form-control'
                'class'  => 'form-control',
                'size' => '35'
                ),
            ));
        $this->add(
            array(
                'name' => 'descricao',
                'type' => 'textarea',
                'height' => '500px',
                'options' => array(
                    'label' => 'Descrição*:'
                    ),
                'attributes' => array(
                        //'placeholder' => 'Ex: fulano10',
                    'class' => 'form-control',
                    'cols' => '30',
                    'rows' => '8'

                    ),
                )
            );
        $this->add(array(
            'name' => 'valor',
            'type' => 'text',
            'options' => array(
                'label' => 'Valor*:'
                ),
            'attributes' => array(
                'placeholder' => 'Ex: 00.00',
                'class' => 'form-control',
                'style' => 'text-align: right;',
                //'onkeypress' => 'return SomenteNumero(event);',//Função para receber apenas numeros.
                ),
            ));
        $this->add(array(
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'marca',
            'options' => array(
                'label' => 'Marca*:',
                'object_manager' => $em,
                'target_class' => '\Admin\Entity\Marca',
                'property' => 'descricao',
                'empty_option' => 'SELECIONE UMA MARCA',
                'label_generator' => function($target) {
                    return $target->descricao;
                }
                ),
            'attributes' => array(
                //'placeholder' => 'Ex: fulano10',
                'class' => 'form-control'
                ),
            ));

        $this->add(array(
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'modelo',
            'options' => array(
                'label' => 'Modelos*:',
                'object_manager' => $em,
                'target_class' => '\Admin\Entity\Modelo',
                'property' => 'descricao',
                'empty_option' => 'SELECIONE UM MODELO',
                'label_generator' => function($target) {
                    return $target->descricao;
                }
                ),
            'attributes' => array(
                //'placeholder' => 'Ex: fulano10',
                'class' => 'form-control'
                ),
            ));

        $this->add(array(
            'type' => 'DoctrineModule\Form\Element\ObjectMultiCheckbox',
            'name' => 'categorias',
            'options' => array(
                'label' => 'Categorias*:',
                'object_manager' => $em,
                'target_class' => '\Admin\Entity\Categoria',
                'property' => 'descricao',
                'empty_option' => 'SELECIONE UMA CATEGORIA',
                'label_generator' => function($target) {
                    return $target->descricao;

                }
                ),
            'attributes' => array(
                //'placeholder' => 'Ex: fulano10',
                 //'class' => 'form-control'
                ),
            ));

        $this->add(
            array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'subcategoria',
                'options' => array(
                    'label' => 'Sub Categoria*:',
                    'object_manager' => $em,
                    'target_class' => '\Admin\Entity\SubCategoria',
                    'property' => 'descricao',
                    'empty_option' => 'SELECIONE UMA SUB CATEGORIA',
                    'label_generator' => function($target) {
                        return $target->descricao;
                    }
                    ),
                'attributes' => array(
                //'placeholder' => 'Ex: fulano10',
                    'class' => 'form-control'
                    ),
                ));

        $this->add(
            array(
                'name' => 'photo',
                'type' => 'file',
                'options' => array(
                    'label' => 'Foto*',
                    ),
                'attributes' => array(
                    //'class' => 'form-control',
                    //'multiple' => true,
                    )
                )
            );

        $this->add(array(
            'name' => 'salvar',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Salvar',
                'class' => 'btn btn-primary'
                )
            ));

        $this->add(array(
            'type' => 'button',
            'name' => 'cancelar',
            'attributes' => array(
                'value' => 'Cancelar',
                'class' => 'btn',
                'onclick' => "location.href='/admin/produtos/index'",
                'title' => 'Cancelar'
                ),
            'options' => array(
                'label' => 'Cancelar'
                )
            ));

        //$this->addInputFilter();
    }

    public function addInputFilter()
    {
        $inputFilter = new InputFilter\InputFilter();
        $fileInput = new InputFilter\FileInput('photo');
        $fileInput->setRequired(true);
        $fileInput->getValidatorChain()
        ->attachByName('filesize', array('max' => 400000))
        ->attachByName('filemimetype', array('mimeType' => 'image/jpeg'))
        ->attachByName('fileimagesize', array('maxWidth' => 1366, 'maxHeight' => 1080));
        $fileInput->getFilterChain()->attachByName(
            'filerenameupload', array(
                'target' => getcwd() . '/public/img/photos_produtos/photo.jpg',
                'randomize' => true,
                )
            );
            
            $factory = new InputFactory();

        $this->add($factory->createInput(array(
           'name' => 'nome',
           'required' => true,
           'filters' => array(
               array('name' => 'StripTags'),
               array('name' => 'StringTrim'),
               array('name' => 'StringToUpper',
                   'options' => array('encoding' => 'UTF-8')
                   ),
               ),
           'validators' => array(
               array(
                   'name' => 'StringLength',
                   'options' => array(
                       'encoding' => 'UTF-8',
                       'min' => 3,
                       'max' => 255,
                       ),
                   ),
               array(
                   'name' => 'NotEmpty',
                   )),
           )));


        $inputFilter->add($fileInput);

        //$this->setInputFilter($inputFilter);
        $this->inputFilter = $inputFilter;
    }
    
    public function getOptions()
    {
        return array('class' => 'form-horizontal');
    }

}
