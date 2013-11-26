<?php

/**
 * This package will be used to handle the suggest system
 * @author Piyush
 */
/**
 * This array will hold alll types of suggestions
 */
$suggests = array();

require_once 'suggest_storage.php';

class suggest_handler {

    public $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * This method is to be used to register a new suggest type
     * @param type $name The name of the suggest
     * @param type $tpl The tpl to be used while showing user the suggest
     * @param type $parameters Any parameters required by the suggest
     * @return boolean Return true if successfully registered
     */
    public function register_handler($name, $tpl, $parameter) {
        if (empty($name) || empty($tpl) || empty($parameter)) {
            return false;
        }
        // Store all the information of the suggest
        $suggests[] = new suggest_storage($name, $tpl, $parameter);
    }

    /**
     * 
     * @param type $name
     * @param type $parameters
     */
    public function add_suggest($name, $old_value, $new_value, $to) {
        // First find the parameters and structure of the given suggest
        $suggest_structure = $this->find_structure($name);

        if (!$suggest_structure) {
            trigger_error("Wrong Suggestion Name Passed. Please check.", E_USER_ERROR);
        }
        // Now we know on which paramter the suggest depends
        // Store this infomation in the database
        $this->db->query("insert into suggested_info (typesuggest,newvalue,oldvalue,from,to,ts) values('$name','$new_value','$old_value'," . $user->user['id'] . ",$to," . time() . ")");
    }

    /**
     * 
     * @param type $name
     * @return boolean
     */
    public function find_structure($name) {
        $found_key = NULL;
        foreach ($suggests as $key => $value) {
            if ($value->$name == $name) {
                $found_key = $key;
            }
        }
        if ($found_key != null) {
            return $suggests[$found_key];
        } else {
            return false;
        }
    }

}

;