<?php defined('ABSPATH') or die;
/**
 * Score Manager and credit gifts
 */
class Score extends PassKey{
    
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