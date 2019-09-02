<?php

namespace Contact\Form;

use Zend\Form\Form;

/**
 * Class ContactForm
 *
 * @package Contact\Form
 * @author Hafiz "Pisyek" Suhaimi <hi@pisyek.com>
 * @copyright 2019 Pisyek Studios
 * @link www.pisyek.com
 */
class ContactForm extends Form
{
    public function __construct($name = null, $options = [])
    {
        parent::__construct('contact');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);

        $this->add([
            'name' => 'name',
            'type' => 'text',
            'options' => [
                'label' => 'Name',
            ],
        ]);

        $this->add([
            'name' => 'phone',
            'type' => 'text',
            'options' => [
                'label' => 'Phone Number',
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Go',
                'id' => 'submitbutton'
            ],
        ]);
    }
}