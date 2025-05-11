<?php defined('ABSPATH') or die;
/**
 * 
 */
class Session extends PassKeyContent{
    
    const STATUS_CREATED = 'created';
    const STATUS_ACTIVE = 'active';
    const STATUS_EXPIRED = 'expired';
    const STATUS_CLOSED = 'closed';
    
    /**
     * 
     * @param array $input
     */
    protected function __construct($input = array()) {
        $this->define('key',self::TYPE_TEXT)
                ->define('account',self::TYPE_TEXT)
                //->define('subscription',self::TYPE_TEXT)
                ->define('status',self::STATUS_CREATED)
                ->define('created',self::timestamp());
        
        parent::__construct($input);
    }
    /**
     * @return array
     */
    public function listStatus(){
        return array(
            self::STATUS_CREATED => __('Account Activation','coders_passkey'),
            self::STATUS_ACTIVE => __('Active','coders_passkey'),
            self::STATUS_EXPIRED => __('Expired','coders_passkey'),
            self::STATUS_CLOSED => __('Closed','coders_passkey'),
        );
    }
    
    
    /**
     * @param String $account_id
     * @return \Session
     */
    public static function createSession( $account_id = '' ){
        $ts = self::ts();
        $key = self::createKey($ts);
        //$subs = self::subscriptions($account_id);
        $data = self::create(array(
            'key' => $key,
            'account' => $account_id,
            //'subscription' => count($subs) ? $subs[0] : '',
            'created' => $ts,
        ));
        return new Session($data);
    }
    /**
     * @return String
     */
    public static function ts(){
        return date('yyyy-mm-dd H:i:s');
    }
    /**
     * @param String $data
     * @return String
     */
    public static function createKey( $data ){
        return md5($data);
    }
}


