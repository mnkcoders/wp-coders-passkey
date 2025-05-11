<?php defined('ABSPATH') or die;
/**
 * 
 */
class Role extends PassKeyContent{
    /**
     * @param array $input
     */
    public final function __construct($input = array()) {
        $this->define('role',self::TYPE_TEXT)
                ->define('title',self::TYPE_TEXT,__('New Role','coders_passkey'))
                ->define('created',self::TYPE_TIMESTAMP,self::timestamp());
        parent::__construct($input);
    }
}