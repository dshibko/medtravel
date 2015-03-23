<?php

namespace Application\Form;

use Application\Form\Filter\CalendarInputFilter;
use Zend\Form\Element;

class CalendarForm extends AbstractForm {

    public function __construct($name = null) {
        parent::__construct();

        $this->setInputFilter(new CalendarInputFilter());
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form');

        $this->add(array(
            'name' => 'title',
            'type'  => 'text',
            'attributes' => array(
                'class' => 'required',
                'placeholder' => '',
                'minlength' => 3,
            ),
            'options' => array(
                'label' => 'Название события',
            ),
        ));

        $this->add(array(
            'name' => 'description',
            'type'  => 'text',
            'attributes' => array(
                'class' => 'required',
				'placeholder' => ''
            ),
            'options' => array(
                'label' => 'Описание',
            ),
        ));

        $this->add(array(
            'name' => 'date',
            'type'  => 'text',
            'attributes' => array(
                'class' => 'required datepicker',
            ),
            'options' => array(
                'label' => 'Дата',
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