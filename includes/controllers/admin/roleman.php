<?php defined('ABSPATH') or die;
/**
 * Role Manager
 */
class RoleMan extends PassKey{
    
    protected function __construct() {
        $this->preload('Role');
        parent::__construct();
        
    }
    /**
     * @return array
     */
    protected function listRoles(){
        return Role::collection();
    }

    /**
     * @param array $input
     * @return boolean
     */
    public function mainAction($input = array()) {
        //$this->setContent($this->loadContent('Role'));
        $this->view('roles');
        return true;
    }
}