<?php defined('ABSPATH') or die;
/**
 * Account Manager
 */
class AccountMan extends PassKey{
    /**
     * 
     */
    protected function __construct() {
        
        parent::__construct();
        
    }
    /**
     * @param array $input
     * @return boolean
     */
    public function mainAction($input = array()) {
        //list accounts
        $this->view('accounts');
        return true;
    }
}


