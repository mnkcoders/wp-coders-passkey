<?php defined('ABSPATH') or die;
/**
 * Account Manager
 */
class Dashboard extends PassKey{
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
    public function mainAction(array $input) {
        $this->view('dashboard',true);
        return true;
    }
}


