<?php defined('ABSPATH') or die;
/**
 * 
 */
class Account extends PassKeyContent{    
    /**
     * @param array $input
     */
    public function __construct($input = array()) {
        $this->define('id',self::TYPE_TEXT)
                ->define('name',self::TYPE_TEXT)
                ->define('status',self::TYPE_TEXT)
                ->define('created',self::timestamp());
        parent::__construct($input);
    }
}