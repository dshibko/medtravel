<?php

namespace Application\Form;

use Zend\Form\Element;
use Zend\Form\Form;
use \Application\Form\Filter\LoginInputFilter;

class LoginForm extends Form {

    public function __construct($name = null) {
        parent::__construct('auth');

        $this->setInputFilter(new LoginInputFilter());
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'login-form');
        $this->setAttribute('id', 'login');
        $this->add(array(
            'name' => 'email',
            'type'  => 'text',
            'attributes' => array(
                'class' => 'required email',
				'placeholder' => 'Enter Your Email'
            ),
            'options' => array(
                'label' => 'Email',
            ),
        ));

        $this->add(array(
            'name' => 'password',
            'type'  => 'password',
            'attributes' => array(
                'class' => 'required password',
				'placeholder' => 'Enter Your Password'
            ),
            'options' => array(
                'label' => 'Пароль',
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