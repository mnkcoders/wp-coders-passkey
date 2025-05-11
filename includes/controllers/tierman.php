<?php defined('ABSPATH') or die;
/**
 * Tier Manager
 */
class TierMan extends PassKey{
    
    protected function __construct() {
        
        parent::__construct();
        
    }

    /**
     * @param array $input
     * @return boolean
     */
    public function mainAction($input = array()) {
        $this->view('tiers');
        return true;
    }
}