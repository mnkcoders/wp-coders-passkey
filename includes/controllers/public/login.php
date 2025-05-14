<?php defined('ABSPATH') or die;
/**
 * Front end User profile Controller
 */
class Login extends PassKey{
    /**
     * 
     */
    protected function __construct() {
        //require the content classes
        $this->preload('Session');
        
        parent::__construct();
        
    }
    /**
     * @param String $action
     * @param array $input
     * @return bool
     */
    protected function redirect($action = 'main' , array $input = array()) {
        return parent::redirect($action, $input);
        if( $this->hasKey()){
            return $this->welcomeAction($input);
        }
        return $this->mainAction();
    }
    /**
     * @return String
     */
    protected function getKey() {
        return self::importKey();
    }
    /**
     * @return Boolean
     */
    protected function hasKey() {
        return strlen($this->getKey()) > 0;
    }

    /**
     * Default dashboard view
     * @param array $input
     * @return boolean
     */
    public function mainAction(array $input = array()) {
        return $this->hasKey() ? $this->welcomeAction() : $this->loginAction();
    }
    /**
     * @return Boolean
     */
    protected function loginAction( ) {
        return $this->view('register');
    }
    /**
     * @param array $input
     * @return Boolean
     */
    protected function openAction( array $input = array()) {
        if( $this->register(isset( $input['email'] ) ? $input['email'] : '' ) ){
            return $this->welcomeAction();
        }
        return $this->loginAction();
    }
    /**
     * @param array $input
     * @return Boolean
     */
    protected function welcomeAction() {
        return $this->view('welcome');
    }
    /**
     * @param string $email
     * @return boolean
     */
    private function register($email = ''){
        return $this->saveKey($this->importEmail($email));
    }
    /**
     * @param string $email
     * @return booolean
     */
    private function matchEmail($email){
        return preg_match('/^[\w.\-+]+@[\w\-]+\.[a-z]{2,}$/i', $email);
    }
    /**
     * 
     * @param string $email
     * @return string
     */
    private function importEmail($email = ''){
        if( $this->matchEmail($email)){
            return md5(strtolower($email));
        }
        return '';
    }
    /**
     * @param string $key
     * @return boolean
     */
    private function saveKey($key = '') {
        return strlen($key) ? setcookie(self::KEYPASS_ID,$key) : false;
    }
}






