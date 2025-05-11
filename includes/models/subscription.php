<?php defined('ABSPATH') or die;
/**
 * 
 */
class Subscription extends PassKeyContent{
    const STATUS_ACTIVE = 'active';
    const STATUS_EXPIRED = 'expired';
    /**
     * 
     * @param array $input
     */
    public function __construct($input = array()) {
        $this->define('id',self::TYPE_TEXT,'')
                ->define('account',self::TYPE_TEXT,'')
                ->define('tier',self::TYPE_TEXT,'')
                ->define('status',self::TYPE_TEXT,self::STATUS_ACTIVE)
                ->define('created',self::TYPE_DATETIME,self::timestamp());
        
        parent::__construct($input);
    }
}