<?php

namespace Admin\Form;

use Zend\Form\Form;
/**
 * Form para cadastrar tipo de endereco
 * @category Admin
 * @package  Form
 * @author   Maico <email@email.com>
 */
class TipoEnderecoForm extends Form
{

    public function __construct()
    {
        parent::__construct('TipoEnderecoForm');
        $this->setAttribute('method', 'POST');
        $this->add(
            array(
                'name' => 'id',
                'type' => 'hidden'
                )
        );
        $this->add(
            array(
                'name' => 'descricao',
                'type' => 'text',
                'options' => array(
                    'label' => 'Descrição do endereço*:'
                    ),
                'attributes' => array(
                    'placeholder' => 'Informe um tipo de endereço.',
                    'class' => 'form-control',
                    'size' => '30'
                    ),
                )
        );
        $this->add(
            array
            (
                'name' => 'salvar',
                'type' => 'submit',
                'attributes' => array(
                    'value' => 'Salvar',
                    'class' => 'btn btn-primary',
                    'title' => 'Salvar'
                )
            )
        );

        $this->add(
            array(
                'type' => 'button',
                'name' => 'cancelar',
                'attributes' => array(
                    'value' => 'Cancelar',
                    'class' => 'btn',
                    'onclick' => "location.href='/admin/tipo-enderecos/index'",
                    'title' => 'Cancelar'
                    ),
                'options' => array(
                    'label' => 'Cancelar'
                    )
                )
        );
    }
}