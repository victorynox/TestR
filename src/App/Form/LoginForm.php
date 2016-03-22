<?php
/**
 * Created by PhpStorm.
 * User: victorynox
 * Date: 12.02.16
 * Time: 13:03
 */

namespace App\Form;


use Zend\Form\Form;
use Zend\Form\Element;

class LoginForm extends Form
{
    public function __construct($name=null, array $options = array())
    {
        parent::__construct("test", $options);
        $this->setAttribute('method', 'get');
        $this->add(array(
            'name' => 'user_name',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'min' => 5,
                'max' => 25,
                'label' => 'Login',
            ),
        ));
        $this->add(array(
            'name' => 'password',
            'type' => 'Zend\Form\Element\Password',
            'options' => array(
                'min' => 6,
                'max' => 25,
                'label' => 'Password',
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'id' => 'submitbutton',
            ),
        ));


    }
}