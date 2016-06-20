<?php
namespace Auth\Form;

use Zend\Form\Form;
use Zend\InputFilter;

class UserForm extends Form
{
    public function __construct()
    {
        parent::__construct('UserForm');
        $this->setAttribute('method', 'POST');
        //$this->setAttribute('enctype', 'multipart/form-data');
        $this->setAttribute('action', '');
        $this->add(
            array(
                'name' => 'id',
                'type' => 'hidden'
            )
        );
        $this->add(
            array(
                'name' => 'nome',
                'type' => 'text',
                'options' => array(
                    'label' => 'Nome*',
                ),
                'attributes' => array(
                    'placeholder' => 'Ex: Fulano de tal',
                    'class' => 'form-control'
                )
            )
        );
        $this->add(
            array(
                'name' => 'login',
                'type' => 'text',
                'options' => array(
                    'label' => 'Usuário*',
                ),
                'attributes' => array(
                    'placeholder' => 'Ex: fulano10',
                    'class' => 'form-control'
                )
            )
        );
        $this->add(
            array(
                'name' => 'email',
                'type' => 'text',
                'options' => array(
                    'label' => 'E-mail*',
                ),
                'attributes' => array(
                    'placeholder' => 'Ex: fulano@fulano.com',
                    'class' => 'form-control'
                )
            )
        );
        $this->add(
            array(
                'name' => 'password',
                'type' => 'password',
                'options' => array(
                    'label' => 'Senha*',
                ),
                'attributes' => array(
                    'placeholder' => 'Ex: QW85!pl',
                    'class' => 'form-control'
                )
            )
        );
        $this->add(
            array(
                'name' => 'role',
                'type' => 'select',
                'options' => array(
                    'label' => 'Perfil*',
                    'value_options' => array(
                        'ADMIN' => 'ADMINISTRADOR',
                        'EDITOR' => 'CLIENTE'
                    )
                ),
                'attributes' => array(
                    'class' => 'form-control'
                )
            )
        );
        // $this->add(
        //     array(
        //         'name' => 'photo',
        //         'type' => 'file',
        //         'options' => array(
        //             'label' => 'Foto*',
        //         ),
        //         'attributes' => array(
        //             'class' => 'form-control',
        //             //'multiple' => true,
        //         )
        //     )
        // );
        $this->add(
            array(
                'type' => 'submit',
                'name' => 'salvar',
                'attributes' => array(
                    'value' => 'Salvar',
                    'class' => 'btn btn-primary'
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
                    'onclick' => "location.href='/auth/users/index'",
                    'title' => 'Cancelar'
                ),
                'options' => array(
                    'label' => 'Cancelar'
                )
            )
        );

        // $this->addInputFilter();
    }

    // public function addInputFilter()
    // {
    //     $inputFilter = new InputFilter\InputFilter();
    //     $fileInput = new InputFilter\FileInput('photo');
    //     $fileInput->setRequired(true);
    //     $fileInput->getValidatorChain()
    //         ->attachByName('filesize',      array('max' => 204800))
    //         ->attachByName('filemimetype',  array('mimeType' => 'image/jpeg'))
    //         ->attachByName('fileimagesize', array('maxWidth' => 1000, 'maxHeight' => 1000));
    //     $fileInput->getFilterChain()->attachByName(
    //         'filerenameupload',
    //         array(
    //             'target'    => getcwd().'/public/img/photos_users/photo.jpg',
    //             'randomize' => true,
    //         )
    //     );
    //     $inputFilter->add($fileInput);

    //     $this->setInputFilter($inputFilter);
    // }
}