<?php

namespace Application\Form;

use Application\Form\Filter\ClientsInputFilter;
use Application\Form\Filter\UserInputFilter;
use Zend\Form\Element;

class ClientsForm extends AbstractForm {

    private $selects;

    public function __construct($selects = array('services' => array(), 'clinics' => array(), 'doctors' => array(), 'countries' => array()), $name = null) {
        parent::__construct();

        $this->setInputFilter(new ClientsInputFilter());
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form');
        $this->setAttribute('enctype', 'multipart/formdata');

        $this->selects['services'] = $selects['services'];
        $this->selects['clinics'] = $selects['clinics'];
        $this->selects['doctors'] = $selects['doctors'];
        $this->selects['countries'] = $selects['countries'];

        $this->add(array(
            'name' => 'fio',
            'type'  => 'text',
            'attributes' => array(
                'class' => 'required',
                'placeholder' => '',
                'minlength' => 3,
            ),
            'options' => array(
                'label' => 'ФИО',
            ),
        ));

        $this->add(array(
            'name' => 'service',
            'type'  => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'required form-control',
                'placeholder' => ''
            ),
            'options' => array(
                'label' => 'Мед. услуга',
                'value_options' => $this->selects['services']
            ),
        ));

        $this->add(array(
            'name' => 'diagnosis',
            'type'  => 'textarea',
            'attributes' => array(
                'class' => 'required',
				'placeholder' => '',
                'minlength' => 6,
            ),
            'options' => array(
                'label' => 'Диагноз, жалобы',
            ),
        ));

        $this->add(array(
            'name' => 'contacts',
            'type'  => 'text',
            'attributes' => array(
                'class' => 'required',
                'placeholder' => '',
                'minlength' => 6,
            ),
            'options' => array(
                'label' => 'Контакты',
            ),
        ));

        $this->add(array(
            'name' => 'dos',
            'type'  => 'text',
            'attributes' => array(
                'class' => 'required datepicker',
                'placeholder' => '',
            ),
            'options' => array(
                'label' => 'Дата обращения',
            ),
        ));

        $this->add(array(
            'name' => 'status',
            'type'  => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'required form-control',
                'placeholder' => ''
            ),
            'options' => array(
                'label' => 'Статус',
                'value_options' => array(
                    'Не обработан' => 'Не обработан',
                    'В работе' => 'В работе',
                    'Согласование' => 'Согласование',
                    'Думает' => 'Думает',
                    'Архив' => 'Архив',
                    'Пролечен' => 'Пролечен',
                    'Записан в календарь' => 'Записан в календарь',
                )
            ),
        ));

        $this->add(array(
            'name' => 'comments',
            'type'  => 'textarea',
            'attributes' => array(
                'class' => 'required',
                'placeholder' => '',
                'minlength' => 6,
            ),
            'options' => array(
                'label' => 'Комментарии',
            ),
        ));

        $this->add(array(
            'name' => 'country',
            'type'  => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'required form-control',
                'placeholder' => ''
            ),
            'options' => array(
                'label' => 'Страна',
                'value_options' => $this->selects['countries']
            ),
        ));

        $this->add(array(
            'name' => 'newCountry',
            'type'  => 'text',
            'attributes' => array(
                'class' => '',
                'placeholder' => 'Новая клиника',
            ),
            'options' => array(
            ),
        ));

        $this->add(array(
            'name' => 'contactType',
            'type'  => 'text',
            'attributes' => array(
                'class' => 'datepicker',
                'placeholder' => '',
                'minlength' => 6,
            ),
            'options' => array(
                'label' => 'Следующий<br>запланированный контакт',
            ),
        ));

        $this->add(array(
            'name' => 'attachments[]',
            'type'  => 'Zend\Form\Element\File',
            'attributes' => array(
            ),
            'options' => array(
                'label' => 'Документы',
            ),
        ));

        $this->add(array(
            'name' => 'clinic',
            'type'  => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'required form-control',
                'placeholder' => ''
            ),
            'options' => array(
                'label' => 'Клиника',
                'value_options' => $this->selects['clinics']
            ),
        ));

        $this->add(array(
            'name' => 'newClinic',
            'type'  => 'text',
            'attributes' => array(
                'class' => '',
                'placeholder' => 'Новая клиника',
            ),
            'options' => array(
            ),
        ));

        $this->add(array(
            'name' => 'doctor',
            'type'  => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'required form-control',
                'placeholder' => ''
            ),
            'options' => array(
                'label' => 'Врач',
                'value_options' => $this->selects['doctors']
            ),
        ));

        $this->add(array(
            'name' => 'newDoctor',
            'type'  => 'text',
            'attributes' => array(
                'class' => '',
                'placeholder' => 'Новый врач',
            ),
            'options' => array(
            ),
        ));

        $this->add(array(
            'name' => 'conclusions[]',
            'type'  => 'Zend\Form\Element\File',
            'attributes' => array(
            ),
            'options' => array(
                'label' => 'Заключение (PDF)',
            ),
        ));

        $this->add(array(
            'name' => 'payment',
            'type'  => 'text',
            'attributes' => array(
                'class' => 'required',
                'placeholder' => '',
                'minlength' => 6,
            ),
            'options' => array(
                'label' => 'Способ оплаты',
            ),
        ));

        $this->add(array(
            'name' => 'informed',
            'type'  => 'Zend\Form\Element\Checkbox',
            'attributes' => array(
                'required' => false
            ),
            'options' => array(
                'use_hidden_element' => false,
                'checked_value' => 1,
                'unchecked_value' => 0,
                'label' => 'О подготовке проинформирован',
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