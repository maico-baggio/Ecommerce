<?php
namespace Admin\Form;

use Zend\Form\Form;

/**
 * Form para cadastrar subcategoria
 *
 * @category Admin
 * @package  Form
 * @author   Maico <email@email.com>
 */
class SubCategoriaForm extends Form
{
    /**
    * Form para cadastrar subcategoria
    */
    public function __construct()
    {
        parent::__construct('SubCategoriaForm');
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
                    'label' => 'Descrição da sub categoria*:'
                ),
                'attributes' => array(
                    'placeholder' => 'Informe uma sub categoria',
                    'class' => 'form-control',
                    'size' => '30'
                ),
            )
        );
        $this->add(
            array(
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
                    'onclick' => "location.href='/admin/subcategorias/index'",
                    'title' => 'Cancelar'
                ),
                'options' => array(
                    'label' => 'Cancelar'
                )
            )
        );
    }

}
