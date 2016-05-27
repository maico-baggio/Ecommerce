<?php

namespace Ecommerce\Form;

use Zend\Form\Form;
use Zend\InputFilter;

/**
 * Form para cadastrar produtos
 * @category Ecommerce
 * @package form
 * @author Maico
 */
class EnderecoForm extends Form {

    public function __construct($em) {

        parent::__construct('EnderecoForm');
        $this->setAttribute('method', 'POST');
        $this->setAttribute('action', '');


        $this->add(array(
            'name' => 'id',
            'type' => 'hidden'
        ));
        $this->add(array(
            'name' => 'nome_do_destinatario',
            'type' => 'text',
            'options' => array(
                'label' => 'Nome do destinatário*:'
            ),
            'attributes' => array(
                //'placeholder' => 'Ex: fulano10',
                'class' => 'form-control'
            ),
        ));

        $this->add(array(
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'tipo_endereco',
            'options' => array(
                'label' => 'Tipo endereço*:',
                'object_manager' => $em,
                'target_class' => '\Ecommerce\Entity\TipoEndereco',
                'property' => 'descricao',
                'empty_option' => 'SELECIONE UM TIPO DE ENDEREÇO',
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
                    'name' => 'cep',
                    'type' => 'text',
                    'options' => array(
                        'label' => 'CEP*:'
                    ),
                    'attributes' => array(
                        //'placeholder' => 'Ex: fulano10',
                        'class' => 'form-control'
                    ),
                )
        );
        $this->add(array(
            'name' => 'endereco',
            'type' => 'text',
            'options' => array(
                'label' => 'Endereço*:'
            ),
            'attributes' => array(
                //'placeholder' => 'Ex: fulano10',
                'class' => 'form-control'
            ),
        ));
        $this->add(array(
            'name' => 'numero',
            'type' => 'text',
            'options' => array(
                'label' => 'Numero*:'
            ),
            'attributes' => array(
                //'placeholder' => 'Ex: fulano10',
                'class' => 'form-control'
            ),
        ));

        $this->add(array(
            'name' => 'complemento',
            'type' => 'text',
            'options' => array(
                'label' => 'Complemento*:'
            ),
            'attributes' => array(
                //'placeholder' => 'Ex: fulano10',
                'class' => 'form-control'
            ),
        ));

        $this->add(array(
            'name' => 'informacao_referencia',
            'type' => 'text',
            'options' => array(
                'label' => 'informação de referencia*:'
            ),
            'attributes' => array(
                //'placeholder' => 'Ex: fulano10',
                'class' => 'form-control'
            ),
        ));

        $this->add(array(
            'name' => 'bairro',
            'type' => 'text',
            'options' => array(
                'label' => 'Bairro*:'
            ),
            'attributes' => array(
                //'placeholder' => 'Ex: fulano10',
                'class' => 'form-control'
            ),
        ));

        $this->add(array(
            'name' => 'cidade',
            'type' => 'text',
            'options' => array(
                'label' => 'Cidade*:'
            ),
            'attributes' => array(
                //'placeholder' => 'Ex: fulano10',
                'class' => 'form-control'
            ),
        ));

        $this->add(array(
            'name' => 'estado',
            'type' => 'text',
            'options' => array(
                'label' => 'Estado*:'
            ),
            'attributes' => array(
                //'placeholder' => 'Ex: fulano10',
                'class' => 'form-control'
            ),
        ));

        $this->add(array(
            'name' => 'Salvar',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Salvar',
                'class' => 'btn btn-primary'
            )
        ));
    }
}
