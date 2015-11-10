<?php
/**
 * Модель для работы с новыми названиями таблиц
 */
class EmNames extends ModelBase
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
     * @var integer
     */
    public $type;

    /**
     *
     * @var string
     */
    public $name;

    /**
     * показывать в меню
     * @var string
     */
    public $show;

}
