<?php defined('ABSPATH') or die;
/**
 * Front end User profile Controller
 */
class User extends PassKey{
    /**
     * @var \Session Session data
     */
    private $_session = null;
    /**
     * @var \Subscription Active Subscription
     */
    private $_subscription = null;
    /**
     * 
     */
    protected function __construct() {
        //require the content classes
        $this->preload('Session')
                ->preload('Account')
                ->preload('Subscription');
        
        parent::__construct();
        
    }
    /**
     * @param String $action
     * @param array $arguments
     * @return \Boolean
     */
    protected function __action($action = 'main', array $arguments = array()) {
        //check the active session from cookie
        if( $this->resumeSession() ){
            //redirect to logedin user content
            return parent::__action($action, $arguments);
        }
        //capture key if got a link to login
        if( $action === 'key' && count($arguments) ){
            //redirect to createsession
            if( $this->createSession($arguments[0])){
                //redirect to main
                return $this->mainAction();
            }
        }
        //back to login
        return $this->loginAction();
    }

    /**
     * Default dashboard view
     * @param array $input
     * @return boolean
     */
    public function mainAction(array $input) {

        $this->setContent($this->loadContent('Account',$this->session()->account));
        
        if( !$this->loadSubscription($this->session()->account)){
            //send some message or activate subs highlight in user profile?
        }
        
        $this->layout('main');

        return true;        
    }
    /**
     * Redirect to Login Form view
     * @return boolean
     */
    public function loginAction( ){
        
        $this->layout('login');
        
        return true;
    }
    /**
     * Session resume method
     * @param array $input
     * @return Boolean
     */
    public function sessionAction( array $input){
        
        $session = self::loadContent('Session' , $input );
        
        if( $session->is_valid() ){
            return $this->resumeAction();
        }
        
        return $this->loginAction();
    }
    /**
     * @return Boolean
     */
    protected function hasKey(){
        return strlen($this->getKey()) > 0;
    }
    /**
     * @return Boolean
     */
    protected function hasSession(){
        return !is_null($this->session());
    }
    /**
     * @return Boolean
     */
    protected function hasSubscription(){
        return !is_null($this->subscription());
    }

    /**
     * @return String
     */
    protected function getKey(){
        if( strlen($this->_key) === 0){
            $this->_key = $this->importKey();
        }
        return $this->_key;
    }
    /**
     * Helper to preload the current available tier in this account
     * @param String $account_id Account Id
     * @return \Subscription
     */
    private function loadSubscription( $account_id = '' ){
        $data = array();
        //query ACTIVE tiers to the model
        $sub = \Subscription::collection(array(
            'account'=>$account_id,
            'status' => \Subscription::STATUS_ACTIVE,
            ));
        $this->_subscription = count($sub) ? new \Subscription( $sub ) : null;
        return $this->hasSubscription();
    }
    /**
     * @param String $id
     * @return Boolean
     */
    private function createSession( $id = '' ){
        $this->_session = \Session::createSession($id);
        return $this->hasSession();
    }
    /**
     * Store session data from the cookie
     * @param array $input
     * @return Boolean
     */
    private function resumeSession(  ){
        $key = self::importKey();
        if(strlen($key)){
            $sessions = \Session::collection(array(
                'key' => $key,
                'status' => \Session::STATUS_ACTIVE,
                ));
            $this->_session = count($sessions) ? $sessions[0] : null;
        }
        return $this->hasSession();
    }
    /**
     * @return \Session
     */
    private function session(){
        return $this->_session;
    }
    /**
     * @return \Subscription
     */
    private function subscription(){
        return $this->_subscription;
    }
    /**
     * @return String
     */
    private static function importKey(){
        return filter_input(INPUT_COOKIE, 'keypass_id') ?? '';
    }    
}




