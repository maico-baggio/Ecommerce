<?php

namespace Application\Form;

use Zend\Form\Form;

class Login extends Form
{
    public function __construct()
    {
        parent::__construct('LoginForm');
        $this->add(array(
            'type' => 'text',
            'name' => 'login',
            'options' => array(
                'label' => 'Usuário'
            ),
            'attributes' => array(
                'class' => 'form-control'
            )
        ));
        $this->add(array(
            'type' => 'password',
            'name' => 'password',
            'options' => array(
                'label' => 'Senha'
            ),
            'attributes' => array(
                'class' => 'form-control'
            )
        ));
        $this->add(array(
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => array(
                'class' => 'btn btn-primary',
                'value' => 'Entrar'
            ),
        ));
    }
}