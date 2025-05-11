<?php defined('ABSPATH') or die;
/**
 * 
 */
class Score extends PassKeyContent{
    const SCORE_ADD = 'add';
    const SCORE_SUB = 'sub';
    
    /**
     * @param array $input
     */
    public final function __construct($input = array()) {
        $this->define('id',self::TYPE_TEXT)
                ->define('account',self::TYPE_TEXT)
                ->define('points',self::TYPE_NUMBER)
                ->define('type',self::TYPE_TEXT)
                ->define('detail',self::TYPE_TEXT)
                ->define('created',self::TYPE_TIMESTAMP,self::timestamp());
        parent::__construct($input);
    }
}
