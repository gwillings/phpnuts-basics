<?php

namespace PhpNuts;

use PhpNuts\Literal\BasicObject;
use stdClass;


/**
 * Class Person
 *
 * @method string getFirstName()
 *
 * @method $this setFirstName(string $name)
 *
 * @package PhpNuts
 */
class Person extends BasicObject
{

    /**
     * Person constructor.
     * @param array|stdClass|Person $properties
     */
    public function __construct($properties = [])
    {
        parent::__construct([
            'firstName' => '',
            'lastName' => ''
        ]);
        $this->merge($properties);
    }

}