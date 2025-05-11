<?php defined('ABSPATH') or die;
/**
 * 
 */
class Tier extends PassKeyContent{
    /**
     * @param array $input
     */
    public final function __construct($input = array()) {
        $this->define('id',self::TYPE_TEXT)
                ->define('title',self::TYPE_TEXT,__('New Tier','coders_passkey'))
                ->define('roles',self::TYPE_NUMBER,0)
                ->define('duration',self::TYPE_NUMBER,0)
                ->define('created',self::TYPE_TIMESTAMP,self::timestamp());
        parent::__construct($input);
    }
}