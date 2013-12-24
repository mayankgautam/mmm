<?php

/**
 * This package will be used to handle the suggest system
 * @author Piyush
 */
/**
 * This array will hold all types of suggestions
 */
$suggests = array();

require_once 'suggest_storage.php';

class suggest_handler {

    public $db, $user;

    public function __construct($db, $user) {
        $this->db = $db;
        $this->user = $user;
    }

    public function getviewname($detail) {
        //Find the tpl name of the given suggest
        $struct = $this->find_structure($detail->typesuggest);

        // This is a system error. So if not proceeded safely we should give an error
        if ($struct == false) {
            trigger_error("Cannot find structure of the given suggest: $detail->typesuggest", E_USER_ERROR);
        }
        //Now check if all the suggest parameters are there
        //Needed parameters are the following
        //Name-songname-old_value-new_value
        //fetch info about the user
        $song = $this->db->query("select * from musicinfo where id=$detail->to")->row(0);

        //fetch info about the user
        $user = $this->db->query("select * from user where id=$detail->from")->row(0);

        //Prepare the array
        $maparray = array(
            "username" => $user->fullname,
            "songname" => $song->title,
            "old_value" => $detail->oldvalue,
            "new_value" => $detail->newvalue
        );

        //Check if we are satisfying all the needs
        $error = FALSE;
        foreach ($struct->parameter as $value) {
            if (!isset($maparray[$value])) {
                $error = true;
            }
        }

        if (!$error) {
            //Yes we have all that we needed
            return array($struct->tpl, $maparray);
        } else {
            return FALSE;
        }
    }

    /**
     * This method is to be used to register a new suggest type
     * @param type $name The name of the suggest
     * @param type $tpl The tpl to be used while showing user the suggest
     * @param type $parameters Any parameters required by the suggest
     * @return boolean Return true if successfully registered
     */
    public function register_handler($name, $tpl, $parameter) {
        global $suggests;
        if (empty($name) || empty($tpl) || empty($parameter)) {
            return false;
        }
        // Store all the information of the suggest
        $suggests[] = new suggest_storage($name, $tpl, $parameter);
    }

    /**
     * This function is used to add a new suggestion of a edit in the database
     * @param string $name The name of the suggest handler
     * @param type $new_value The new edited value
     * @param type $to The id of the info which was edited
     */
    public function add_suggest($name, $new_value, $to) {
        //Check for the old value
        $query = $this->db->query("select $name from musicinfo where id=$to")->row_array(0);

        $old_value = $query[$name];

        // Now we know on which paramter the suggest depends
        // Store this infomation in the database
        //Perform the following operation only if new value is different from old value
        if ($new_value != $old_value) {
            return $this->db->insert('suggested_info', array(
                        "typesuggest" => $name,
                        "newvalue" => $new_value,
                        "oldvalue" => $old_value,
                        "from" => $this->user,
                        "to" => $to,
                        "ts" => time()
                    ));
        } else {
            return false;
        }
    }

    /**
     * 
     * @param string $name The name of the suggest handler
     * @return boolean|suggest_storage
     */
    public function find_structure($name) {
        global $suggests;
        $found_key = -1;
        foreach ($suggests as $key => $value) {
            if ($value->name == $name) {
                $found_key = $key;
            }
        }
        if ($found_key >= 0) {
            return $suggests[$found_key];
        } else {
            return false;
        }
    }

    public function approve($id, $action) {
        //Only work of this function is to add the approval in the database

        $db_array = array(
            "action" => $action,
            "suggest_id" => $id,
            "user_id" => $this->user
        );

        $this->db->insert('suggestion_approval', $db_array);

        //check if this suggestion has crossed 60% mark
        $this->check($id);
    }

    public function check($id) {
        //get all the actions of the user
        $query = $this->db->query("select * from suggestion_approval where suggest_id=$id")->result();
        $query2 = $this->db->query("select count(*) as totaluser from user where id!=$this->user")->row(0);

        if ($query2->totaluser == 0) {
            //No one has approved this yet Nothing that we can do
            return;
        }
        $approval = 0;
        $rejection = 0;

        foreach ($query as $row) {
            if ($row->action == 1) {
                $approval++;
            } elseif ($row->action == 0) {
                $rejection++;
            }
        }

        //And then calculate
        if ($approval / ($query2->totaluser) >= 0.6) {
            $this->apply($id);
        } elseif ($rejection / ($query2->totaluser) >= 0.6) {
            $this->reject($id);
        }
    }

    public function apply($id) {
        //Fetch the neccesary information
        $query = $this->db->query("select * from suggested_info where id=$id")->row(0);

        $this->db->where('id', $query->to);
        $this->db->update('musicinfo', array(
            $query->typesuggest => $query->newvalue
        ));

        //delete the suggestion and all the approval material
        $this->db->delete("suggested_info", array("id" => $id));

        //delete all the suggestion approval
        $this->db->delete("suggestion_approval", array("suggest_id" => $id));
    }

    public function reject($id) {
        //Fetch the neccesary information
        $query = $this->db->query("select * from suggested_info where id=$id")->row(0);

        //delete the suggestion and all the approval material
        $this->db->delete("suggested_info", array("id" => $id));

        //delete all the suggestion approval
        $this->db->delete("suggestion_approval", array("suggest_id" => $id));
    }

}

;