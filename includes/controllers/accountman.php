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
        var_dump($input);
        return true;
    }
    /**
     * @param array $input
     * @return boolean
     */
    public function viewAction( array $input = array()){
        //account display view
        var_dump($input);
        return true;
    }
    /**
     * @param array $input
     * @return boolean
     */
    public function updateAction( array $input = array()){
        //update with input, then redirect to view
        var_dump($input);
        return true;
    }
    /**
     * @param array $input
     * @return boolean
     */
    public function removeAction( array $input = array()){
        //remove then redirect to default (list)
        return true;
    }
}


