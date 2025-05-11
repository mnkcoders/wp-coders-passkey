<?php defined('ABSPATH') or die;
/**
 * Role Manager
 */
class RoleMan extends PassKey{
    
    protected function __construct() {
        
        parent::__construct();
        
    }

    /**
     * @param array $input
     * @return boolean
     */
    public function mainAction($input = array()) {
        var_dump($input);
        return true;
    }
}