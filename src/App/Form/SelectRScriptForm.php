<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 25.02.16
 * Time: 11:21
 */

namespace App\Form;


use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Form\Element\Select;

class SelectRScriptForm extends Form
{
    public function __construct($name, array $options)
    {
        parent::__construct($name, $options);
        $this->setAttribute('method', 'POST');
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'scriptName',
            'options' => array(
                'label' => 'Выберите скрипт',
                'value_options' => $options
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'id' => 'submitScript',
                'value' => "Выбрать скрипт",
            ),
        ));

    }
}