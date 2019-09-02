<?php

namespace Contact\Model;

use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Filter\ToInt;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\StringLength;

/**
 * Class Contact
 *
 * @package Contact\Model
 * @author Hafiz "Pisyek" Suhaimi <hi@pisyek.com>
 * @copyright 2019 Pisyek Studios
 * @link www.pisyek.com
 */
class Contact implements InputFilterAwareInterface
{
    public $id;
    public $name;
    public $phone;

    private $inputFilter;

    /**
     * @param array $data
     */
    public function exchangeArray(array $data)
    {
        $this->id = !empty($data['id']) ? $data['id'] : null;
        $this->name = !empty($data['name']) ? $data['name'] : null;
        $this->phone = !empty($data['phone']) ? $data['phone'] : null;
    }

    /**
     * @return array
     */
    public function getArrayCopy()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
        ];
    }

    /**
     * Set input filter
     *
     * @param InputFilterInterface $inputFilter
     * @return InputFilterAwareInterface
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }

    /**
     * Retrieve input filter
     *
     * @return InputFilterInterface
     */
    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();

        $inputFilter->add([
            'name' => 'id',
            'required' => true,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);

        $inputFilter->add([
            'name' => 'name',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'phone',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);

        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }
}