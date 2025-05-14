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
        //var_dump($this->list_messages());
        $this->view('settings');
        
        return true;
    }
    /**
     * @return boolean
     */
    public function rewriteAction(){
        
        flush_rewrite_rules();
        parent::log('Rewrite Rules Done','updated');
        return $this->mainAction();
    }
}

