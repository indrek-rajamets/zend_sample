<?php
namespace Homework\Form;

use Zend\Form\Form;

class HomeworkForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('homework');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Name',
            ),
        ));
        $this->add(array(
            'name' => 'date',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'datePicker',
            ),
            'options' => array(
                'label' => 'Date',


            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Insert',
                'id' => 'submitbutton',
            ),
        ));
    }
}