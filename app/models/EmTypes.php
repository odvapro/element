<?php

class EmTypes extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $table;

    /**
     *
     * @var string
     */
    public $field;

    /**
     *
     * @var string
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $required;
    
    /**
     *
     * @var integer
     */
    public $multiple;

    /**
     *
     * @var string
     */
    public $settings;

}
