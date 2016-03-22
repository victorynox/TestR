<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 25.02.16
 * Time: 10:25
 */

namespace App\Form;


use Zend\Form\Form;
use Zend\Form\Element;

class RScriptForm extends Form
{

    public function __construct($name, array $options)
    {
        parent::__construct($name, $options);
        $this->setAttribute('method', 'GET');
        foreach ($options['fields'] as $field) {
            $item = array(
                'name' => $field['name'],
                'type' => $field['type'],
                'options' => array(
                    'label' => $field['label'],
                ),
                'attributes' => array(
                    'required' => $field['required'],

                )
            );
            if (isset($field['format'])) {
                $item['options']['format'] = $field['format'];
            }
            if ($field['type'] === 'Zend\Form\Element\DateTimeLocal') {
                $item['attributes']['step'] = '1';
                $item['attributes']['min'] = '2010-01-01T00:00:00';
                $item['attributes']['max'] = '2020-01-01T00:00:00';
            }

            $this->add($item);
        }
        $this->add(array(
            'name' => 'scriptName',
            'type' => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'id' => 'scriptName',
                'value' => $options['scriptName'],
            ),
        ));
        $this->add(array(
            'name' => 'submitSetting',
            'type' => 'Zend\Form\Element\Button',
            'attributes' => array(
                'id' => 'submitSetting',
                'value' => 'Обработать'
            ),
        ));


    }

}