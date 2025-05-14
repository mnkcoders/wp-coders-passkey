<?php  //namespace CODERS\PassKey;

defined('ABSPATH') or die;

/**
 * 
 */
abstract class PassKey{
    /**
     * 
     */
    const KEYPASS_ID = 'keypass_id';
    
    /**
     * @var array
     */
    private static $_messages = array();
    
    /**
     * @var \PassKeyContent 
     */
    private $_content = null;
    
    protected function __construct() {
        
        
    }
    
    /**
     * @param STring $name
     * @return String
     */
    public function __get( $name ){
        //parse through all magic call types (list_,has_,is_,count_ ...)
        return $this->hasContent() ? $this->content()->$name : '';
    }
    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call( $name , $arguments ){
        $call = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $name))));
        switch(true){
            case preg_match('/^list_/', $name):
                return $this->hasContent() ?
                    $this->content()->$name(...$arguments) :
                    ( method_exists($this, $call) ? $this->$call(...$arguments) : array() );
            case preg_match('/^link_/', $name):
                //create a link URL helper with this
                return $this->link(
                        substr($name, 5),
                        count($arguments ) && is_array($arguments[0]) ? $arguments[0] : array() ,
                        is_admin() );
            case preg_match('/^form_/', $name):
                return $this->form(substr($name, 5),...$arguments);
            case preg_match('/^part_/', $name):
                require $this->part(substr($name, 5));
                return sprintf('<!-- PART[%s] -->',$name);
            default:
                //redirect to default model content calls
                return $this->hasContent() ? $this->content()->$name : '';
        }
    }
    /**
     * @param String $action
     * @param array $inupt
     * @return Boolean
     */
    protected function redirect( $action = 'main', array $inupt  = array()){
                $call = sprintf('%sAction',$action);
                return method_exists($this,$call) ?
                        $this->$call( $inupt ) :
                        $this->errorAction($action,$inupt);        
    }
    /**
     * @param string $task
     * @param array $get
     * @return string
     */
    protected function form($task, array $get = array()) {
        
        if(is_admin()){
            $get['action'] = 'passkey_action';
            $get['task'] = $task;
            
        }

        $query = array();
        foreach ($get as $var => $val) {
            $query[] = sprintf('%s=%s', $var, $val);
        }

        return is_admin() ?
            admin_url('admin-post.php?' . implode('&', $query)) :
            sprintf('%s/passkey/%s', get_site_url(),
                count($query) ? $task . '?' . implode('&', $query) : $task );
    }
    /**
     * @param string $path
     * @param array $get
     * @param boolean ·$admin
     * @return string|url
     */
    protected function link( $path = '' , $get = array() , $admin = false ){
        if($admin){
            $query = array('page' => strlen($path) ? 'coders_passkey_' . $path : 'coders_passkey');
            foreach( $get as $var => $val ){
                $query[$var] = $val;
            }
            return add_query_arg($query, admin_url('admin.php'));
        }
        
        $query = array();
        foreach ($get as $var => $val ){
            $query[] = sprintf('%s=%s',$var,$val);
        }

        return count($query) ?
            $this->url( $path ) . '?' . implode('&', $query) :
            $this->url($path);
    }
    /**
     * @param String $content
     * @return \PassKey
     */
    protected function preload( $content = ''){
        self::loadContent($content,array(),true);
        return $this;
    }
    /**
     * @param string $controller
     * @param array $input
     * @return \PassKey
     */
    protected static function loadController( $controller = ''){
        $path = sprintf('%s/includes/controllers/%s.php',
                self::basepath(),
                strtolower($controller));
        if(file_exists($path)){
            require_once $path;
            $class = ucfirst($controller);
            if(class_exists($class,true)){
                return new $controller();
            }
            self::log(sprintf('Invalid controller %s',$class), 'error');
        }
        else{
            self::log(sprintf('Invalid controller %s',$path), 'error');
        }
        return null;
    }
    /**
     * @param string $content
     * @param array $input
     * @param Boolean $preload only preload content file, do not return an instance
     * @return \PassKeyContent
     */
    protected static function loadContent( $content = '' , $input = array(), $preload = false ){
        $path = sprintf('%s/includes/models/%s.php',
                self::basepath(),
                strtolower($content));
        if(file_exists($path)){
            require_once $path;
            if( !$preload ){
                //load content instance when required
                $class = ucfirst($content);
                if(class_exists($class,true)){
                    return new $content($input);
                }
                self::log(sprintf('Invalid content %s',$class), 'error');
            }
        }
        else{
            self::log(sprintf('Invalid content %s',$path), 'error');
        }
        return null;
    }
    /**
     * @param string $layout
     * @return boolean
     */
    protected function view( $layout = 'default' ){
        $view = $this->layout($layout, is_admin());
        if(file_exists($view)){
            require $view;
            return true;
        }
        return $this->errorAction($layout);
    }
    /**
     * @param string $asset
     * @param boolean $admin
     * @return string|path
     */
    protected function asset( $asset , $admin = false){
        return sprintf('%shtml/%s/%s',self::basepath(),$admin ? 'admin' : 'public', $asset);
    }
    /**
     * @param string $layout
     * @param boolean $admin
     * @return string|path
     */
    protected function layout( $layout , $admin = false){
        return sprintf('%shtml/%s/layouts/%s.php',self::basepath(),$admin ? 'admin' : 'public', $layout);
    }
    /**
     * @param string $part
     * @param boolean $admin
     * @return string|path
     */
    protected function part( $part , $admin = false){
        return sprintf('%shtml/%s/parts/%s.php',self::basepath(),$admin ? 'admin' : 'public', $part);
    }
    /**
     * @param string $path
     * @return string
     */
    public function path($path = ''){
        return strlen($path) ? sprintf('%s/%s',self::basepath(),$path) : self::basepath();
    }
    /**
     * @param string $url
     * @return string
     */
    public function url($url = ''){
        return strlen($url) ? self::baseurl() . $url : self::baseurl();
    }
    /**
     * @return \PassKeyContent
     */
    protected function content(){
        return $this->_content;
    }
    /**
     * @return Boolean
     */
    public function hasContent(){
        return $this->content() !== null;
    }
    /**
     * @param PassKeyContent $content
     * @return \PassKey
     */
    protected function setContent(PassKeyContent $content = null){
        if( $content ){
            $this->_content = $content;
        }
        return $this;
    }
    

    /**
     * @param array|mixed $input
     * @return boolean
     */
    abstract public function mainAction( array $input );
    /**
     * @param string $action
     * @return boolean
     */
    public function errorAction( $action ){
        self::log(sprintf('Undefined Action: %s', $action ),'error');
        $this->view('error');
        return false;
    }
    /**
     * @param string $content
     * @param string $type
     */
    public static function log( $content = '', $type = 'info' ){
        self::$_messages[] = array(
            'content' => $content,
            'type' => $type,
        );
    }
    /**
     * @return Array
     */
    protected static function messages(){
        return self::$_messages;
    }
    /**
     * @return ARray
     */
    public function listMessages(){
        return self::messages();
    }

    /**
     * @return String
     */
    public static function basepath(){
        return defined('CODERS_PASSKEY_DIR') ? CODERS_PASSKEY_DIR : __DIR__ . '/';
    }
    /**
     * @return String
     */
    public static function baseurl(){
        return defined('CODERS_PASSKEY_URL') ? CODERS_PASSKEY_URL : '';
    }
    
    /**
     * @param string $key
     * @return string
     */
    public static function createId( $key = '') {
        $seed = $key . microtime(true) . rand();
        return substr(md5($seed), 0, 16);
    }    
    
    
    /**
     * @return array
     */
    protected static function post(){
        return filter_input_array(INPUT_POST) ?? array();
    }
    /**
     * @return array
     */
    protected static function get(){
        return filter_input_array(INPUT_GET) ?? array();
    }
    /**
     * @return array
     */
    protected static function request(){
        return array_merge(self::get(),self::post());
        //return filter_input_array(INPUT_REQUEST) ?? array();
    }
    /**
     * @param string $context
     * @param string $action
     * @param string $id
     * @return Boolean
     */
    public static function run( $context = 'Session' , $action = 'main' , $id = '' ){
        $controller = self::loadController($context);
        if( $controller ){
            //var_dump($action);    
            $input = self::request();
            $input['context'] = strtolower( $context );          
            if(strlen($id)){
                $input['id'] = $id;
            }
            return $controller->redirect($action,$input);
        }
        else{
            var_dump(self::$_messages);
        }
        
        return false;
    }
    /**
     * @return String
     */
    public static final function importKey(){
        return filter_input(INPUT_COOKIE, self::KEYPASS_ID) ?? '';
    }    
}

/**
 * 
 */
abstract class PassKeyContent{
    
    const TYPE_TEXT = 'text';
    const TYPE_NUMBER = 'number';
    const TYPE_FLOAT = 'float';
    const TYPE_TEXTAREA = 'textarea';
    const TYPE_EMAIL = 'email';
    const TYPE_CHECKBOX = 'bool';
    const TYPE_TIMESTAMP = 'timestamp';
    const TYPE_DATETIME = 'datetime';
    
    private $_content = array(
        //data dictionary here
    );
    /**
     * @param array $input
     */
    public function __construct( $input = array()) {
        
        $this->populate($input);
    }
    /**
     * @return string
     */
    protected static function __table(){
        $name = explode("\\", self::class);
        //return the last name in the namespace definition
        return $name[count($name)-1];
    }

    /**
     * @return String
     */
    public function __toString() {
        return strval($this);
    }
    /**
     * @param String $name
     * @param String $type
     * @param mixed $value
     * @return \PassKeyContent
     */
    protected function define($name ,$type = self::TYPE_TEXT , $value = ''){
        if(!$this->has($name)){
            $this->_content[$name] = array(
                'type' => $type,
                'value' => $value,
                //'updated' => false,
                //'placeholder' => '',
            );
        }
        return $this;
    }    
    /**
     * @param array $input
     * @param boolean $update
     * @return \PassKeyContent
     */
    protected function populate( $input = array(),$update = false){
        foreach( $input as $key => $value ){
            if( $this->has($key) ){
                $this->set($key, $value,$update);
            }
        }
        return $this;
    }
    /**
     * @param String $name
     * @param String $attribute
     * @return Boolean
     */
    public function has( $name , $attribute = '' ){
        return array_key_exists($name, $this->_content) &&
            ( strlen($attribute) === 0 || 
                array_key_exists($attribute, $this->_content[$name] ) );
    }
    /**
     * 
     * @param type $name
     * @param type $value
     * @param type $updated
     * @return \PassKeyContent
     */
    private function set( $name , $value , $updated = false){
        if( $this->has($name)){
            $this->__tag($name, 'value', $this->cast($value));
            $this->__tag($name, 'updated',true);
        }
        return $this;
    }
    /**
     * @param String $name
     * @param String $attribute
     * @param mixed $value
     * @return \PassKeyContent
     */
    private function __tag( $name , $attribute , $value = ''){
        if( $this->has($name)){
            $this->_content[$name][$attribute] = $value;
        }
        return $this;
    }
    /**
     * @param String $name
     * @param String $attribute
     * @param mixed $default
     * @return mixed
     */
    private function __attribute( $name , $attribute , $default = false){
        return $this->has($name,$attribute) ? $this->_content[$name][$attribute] : $default;
    }
    /**
     * 
     * @param String $value
     * @param String $type
     * @return mixed
     */
    protected function cast( $value , $type = self::TYPE_TEXT){
        switch($type){
            case self::TYPE_NUMBER:
                return (int)$value;
            case self::TYPE_FLOAT:
                return (float)$value;
            case self::TYPE_CHECKBOX:
                return (bool)$value;
            case self::TYPE_EMAIL:
                return $this->isEmail($value) ? $value : '';
        }
        return $value;
    }



    /**
     * @param String $name
     * @param mixed $value
     */
    public function __set( $name,  $value){
        $this->set($name, $value,true);
    }
    /**
     * @param STring $name
     * @return String
     */
    public function __get( $name ){
        return $this->__attribute($name, 'value','');
    }
    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call( $name , $arguments ){
        $call = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $name))));
        switch(true){
            case preg_match('/^is_/', $name):
            case preg_match('/^can_/', $name):
            case preg_match('/^has_/', $name):
                return method_exists($this, $call) ? $this->$call(...$arguments) : false;
            case preg_match('/^get_/', $name):
                return method_exists($this, $call) ? $this->$call(...$arguments) : '';
            case preg_match('/^count_/', $name):
                return method_exists($this, $call) ? $this->$call(...$arguments) : 0;
            case preg_match('/^list_/', $name):
                return method_exists($this, $call) ? $this->$call(...$arguments) : array();
        }
        return '';
    }
    
    /**
     * @return array
     */
    public function listContent($updated = false){
        $output = array();
        foreach($this->_content as $key => $meta ){
            $output[$key] = $meta['value'];
            if( !$updated || array_key_exists('updated', $meta) && $meta['updated']){
                $output[$key] = $meta['value'];
            }
        }
        return $output;
    }
    /**
     * @return array
     */
    public function listUpdated(){
        $output = array();
        foreach($this->_content as $key => $meta ){
            if( array_key_exists('updated', $meta) && $meta['updated']){
                $output[$key] = $meta['value'];
            }
        }
        return $output;
    }
    
    
    /**
     * @param String $email
     * @return Boolean
     */
    public function isEmail($email ){
        return preg_match('/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i', $email);
    }
    
    /**
     * @param Boolean $updated
     * @return String|JSON
     */
    public function toJson( $updated = false ){
        return json_encode(
                $updated ? $this->listUpdated() : $this->listContent() ,
                JSON_PRETTY_PRINT );
    }
    
    /**
     * @return String 
     */
    public static function timestamp(){
        return date('Y-m-d H:i:s');
    }    
    
    /**
     * @global wpdb $wpdb
     * @param array $input
     * @return boolean
     */
    protected static function create( $input = array() ){
        $db = new PasskeyDB(static::class);
        return $db->create($input);
    }
    /**
     * @param array $data
     * @param array $filter
     * @return boolean
     */
    protected static function update( array $data  = array(),array $filter = array()){
        $db = new PasskeyDB(static::class);
        return $db->update($data, $filter);
    }
    /**
     * @global wpdb $wpdb
     * @param array $filter
     * @return boolean
     */
    protected static function delete( $filter  = array()){
        if(count($filter) === 0){
            PassKey::log('Empty DELETE filter','warning');
            return false;
        }
        $db = new PasskeyDB(static::class);
        return $db->delete($filter);
    }
    /**
     * @global wpdb $wpdb
     * @param array $filter
     * @param array $order
     * @return boolean
     */
    protected static function list( array $filter  = array(),array $order = array()){        
        $db = new PasskeyDB(static::class);
        return $db->list($filter, $order);
    }
    /**
     * @param array $filter
     * @return \PassKeyContent
     */
    public static function collection( array $filter = array()){
        $collection = array();
        $class = static::class;
        foreach(self::list($filter) as $item ){
            $collection[] = new $class($item);
        }
        return $collection;
    }
}


/**
 * 
 */
class PasskeyDB {
    /**
     * @var String
     */
    private $_model;

    public function __construct(string $model) {
        $this->_model = strtolower( $model );
    }
    /**
     * @global wpdb $wpdb
     * @return \wpdb
     */
    private function wpdb(){
        global $wpdb;
        return $wpdb;
    }
    /**
     * 
     */
    private function logError(){
        PassKey::log( $this->wpdb()->last_error , 'error' );
    }
    /**
     * @global wpdb $wpdb
     * @return String
     */
    protected function table(){
        return sprintf('%spasskey_%s',$this->wpdb()->prefix,$this->_model);
    }
    /**
     * @param array $data
     * @return int
     */
    public function create(array $data) {
        $created = $this->wpdb()->insert($this->table(), $data);
        if( $created !== false ){
            return $created;
        }
        $this->logError();
        return 0;
    }
    /**
     * @param array $data
     * @param array $filter
     * @return int
     */
    public function update(array $data, array $filter) {
        $updated = $this->wpdb()->update($this->table(), $data, $filter);
        if( $updated !== false ){
            return $updated;
        }
        $this->logError();
        return 0;
    }
    /**
     * @param array $filter
     * @return int
     */
    public function delete(array $filter) {
        $deleted = $this->wpdb()->delete($this->table(), $filter);
        if( $deleted !== false ){
            return $deleted;
        }
        $this->logError();
        return 0;
    }
    /**
     * @param array $filter
     * @param array $order
     * @return array
     */
    public function list(array $filter = [], array $order = []) {
        
        $db = $this->wpdb();
        
        $sql = sprintf('SELECT * FROM `%s`',$this->table());

        if (!empty($filter)) {
            $where = [];
            foreach ($filter as $k => $v) {
                $where[] = $db->prepare("`$k` = %s", $v);
            }
            $sql .= sprintf(' WHERE %s', implode(' AND ', $where));
        }
        if (!empty($order)) {
            $sql .= sprintf(' ORDER BY %s',implode(', ', $order));
        }
        $list = $db->get_results($sql, ARRAY_A);
        if( $list !== false ){
            return $list;
        }
        $this->logError();
        return array();
    }
    /**
     * 
     * @param string $sql
     * @param array $params
     * @param string $return
     * @return mixed
     */
    public function query(string $sql, array $params = [], string $return = 'results') {
        
        $db = $this->wpdb();

        $prepared = $db->prepare($sql, $params);
        switch( $return ){
            case 'row':
                return $db->get_row($prepared, ARRAY_A);
            case 'col':
                return $db->get_col($prepared, ARRAY_A);
            case 'var':
                return $db->get_var($prepared, ARRAY_A);
            default:
                return $db->get_results($prepared, ARRAY_A);
        }
    }
    /**
     * @global wpdb $wpdb
     * @param string  $name
     * @param array $fields
     */
    public static function install( ) {
        
        global $wpdb;
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix. "passkey_account(
            id VARCHAR(64) NOT NULL PRIMARY KEY,
            name VARCHAR(32) NOT NULL,
            status VARCHAR(16) NOT NULL,
            created DATETIME DEFAULT CURRENT_TIMESTAMP
            ) $charset_collate;";
        
        dbDelta($sql);
        
        $sql = "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix. "passkey_role(
            role VARCHAR(16) NOT NULL PRIMARY KEY,
            title VARCHAR(32) NOT NULL,
            created DATETIME DEFAULT CURRENT_TIMESTAMP
            ) $charset_collate;";
        dbDelta($sql);
        
        $sql = "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix. "passkey_session(
            key VARCHAR(32) NOT NULL PRIMARY KEY,
            account VARCHAR(64) NOT NULL,
            status VARCHAR(16) DEFAULT 'created',
            updated DATETIME,
            created DATETIME DEFAULT CURRENT_TIMESTAMP
            ) $charset_collate;";
        dbDelta($sql);
        
        $sql = "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix. "passkey_tier(
            id VARCHAR(16) NOT NULL PRIMARY KEY,
            title VARCHAR(64) NOT NULL,
            roles VARCHAR(64) DEFAULT '',
            duartion SMALLINT UNSIGNED DEFAULT '0',
            created DATETIME DEFAULT CURRENT_TIMESTAMP
            ) $charset_collate;";
        dbDelta($sql);
        
        $sql = "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix. "passkey_subscription(
            id VARCHAR(32) NOT NULL PRIMARY KEY,
            account VARCHAR(64) NOT NULL,
            tier VARCHAR(16) DEFAULT '',
            status VARCHAR(16) DEFAULT 'active',
            updated DATETIME,
            created DATETIME DEFAULT CURRENT_TIMESTAMP
            ) $charset_collate;";
        dbDelta($sql);
        
        $sql = "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix. "passkey_score(
            id INT(32) UNSIGNED NOT NULL PRIMARY KEY,
            account VARCHAR(64) NOT NULL,
            points SMALLINT UNSIGNED DEFAULT '0',
            type VARCHAR(4) DEFAULT 'test',
            detail VARCHAR(64) DEFAULT '',
            created DATETIME DEFAULT CURRENT_TIMESTAMP
            ) $charset_collate;";
        dbDelta($sql);
        
    }
}
