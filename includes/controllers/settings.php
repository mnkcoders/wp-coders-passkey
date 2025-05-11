<?php defined('ABSPATH') or die;
/**
 * Score Manager and credit gifts
 */
class Settings extends PassKey{
    
    protected function __construct() {
        
        parent::__construct();
        
    }

    /**
     * @param array $input
     * @return boolean
     */
    public function mainAction($input = array()) {
        
        $this->view('settings');
        
        return true;
    }
}