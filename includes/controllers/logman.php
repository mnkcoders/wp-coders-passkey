<?php defined('ABSPATH') or die;
/**
 * Score Manager and credit gifts
 */
class LogMan extends PassKey{
    
    protected function __construct() {
        
        parent::__construct();
        
    }

    /**
     * @param array $input
     * @return boolean
     */
    public function mainAction($input = array()) {
        $this->view('logs');
        return true;
    }
}