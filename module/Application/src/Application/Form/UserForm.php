<?php

namespace Application\Form;

use Application\Form\Filter\UserInputFilter;
use Zend\Form\Element;

class UserForm extends AbstractForm {

    public function __construct($name = null) {
        parent::__construct();

        $this->setInputFilter(new UserInputFilter());
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form');

        $this->add(array(
            'name' => 'displayName',
            'type'  => 'text',
            'attributes' => array(
                'class' => 'required',
                'placeholder' => '',
                'minlength' => 3,
            ),
            'options' => array(
                'label' => 'Имя',
            ),
        ));

        $this->add(array(
            'name' => 'email',
            'type'  => 'text',
            'attributes' => array(
                'class' => 'required email',
				'placeholder' => ''
            ),
            'options' => array(
                'label' => 'Email',
            ),
        ));

        $this->add(array(
            'name' => 'password',
            'type'  => 'text',
            'attributes' => array(
                'class' => 'required password',
				'placeholder' => '',
                'minlength' => 6,
            ),
            'options' => array(
                'label' => 'Пароль',
            ),
        ));

        $this->add(array(
            'name' => 'role',
            'type'  => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'required form-control',
                'placeholder' => ''
            ),
            'options' => array(
                'label' => 'Роль',
                'value_options' => array(2 => 'Менеджер', 1 => 'Администратор')
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'type'  => 'submit',
            'attributes' => array(
                'value' => 'Войти',
                'id' => 'submitbutton',
            ),
        ));
    }
}