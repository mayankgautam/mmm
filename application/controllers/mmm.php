<?php

require_once '../id3/getid3/getid3.php';
require_once 'suggest/suggest_handler.php';

/**
 * This the default controller of CI
 */
class mmm extends CI_Controller {

    public $id3, $data, $suggest, $user;

    public function __construct() {

        //CI Dependencies
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->helper('url');

        //Now check if a user is authenticated
        //if yes then add the user id in the above user variable
        $this->user = $this->session->userdata('id');

        //MetaData dependencies
        $this->id3 = new getID3;
        $this->suggest = new suggest_handler($this->db, $this->user);

        //Other variables needed by every page
        define("BASEURL", base_url());
        define("INDEX", index_page());
        $this->data['baseurl'] = BASEURL;
        $this->data['index'] = INDEX;
        $this->data['session'] = $this->session;

        //Register all the suggest handlers
        $this->suggest->register_handler(TITLE, "suggest_titleedit", array("username", "songname", "old_value", "new_value"));
        $this->suggest->register_handler(ARTIST, "suggest_artistedit", array("username", "songname", "old_value", "new_value"));
        $this->suggest->register_handler(ALBUM, "suggest_albumedit", array("username", "songname", "old_value", "new_value"));
    }

    public function index() {
        $this->data['nodata'] = $this->db->query("select id from musicinfo")->num_rows();
        $this->load->view("header", $this->data);
        $this->load->view('main', $this->data);
        $this->load->view("footer", $this->data);
    }

    public function submitdata() {
        $this->data['error'] = FALSE;
        $this->data['success'] = false;
        $this->data['errmsg'] = "";

        //Enter all the accepted mime types.
        $allowed = array("audio/mp3");
        if (isset($_POST['submit'])) {
            //Check for file upload errors
            if ($_FILES['musicfile']['error'] > 0) {
                $this->data['error'] = true;
                $this->data['errmsg'] = "File was not uploaded properly. Please try and upload again";
            } elseif (!in_array($_FILES['musicfile']['type'], $allowed)) {
                $this->data['error'] = true;
                $this->data['errmsg'] = "File type not supported";
            } else {
                //Now we can upload the file
                $fileinfo = $this->id3->analyze($_FILES['musicfile']["tmp_name"]);

                //Add to database
                if (!$this->addto_database($fileinfo['tags_html']['id3v2'])) {
                    $this->data['error'] = TRUE;
                    $this->data['errmsg'] = "Unable to add information in the database. Please Try again";
                } else {
                    $this->data['success'] = true;
                }
            }
        }
        $this->load->view("header", $this->data);
        $this->load->view("submitdata", $this->data);
        $this->load->view("footer", $this->data);
    }

    public function showall() {
        $query = $this->db->query("select * from musicinfo");
        $this->data['songs'] = $query->result();
        if ($this->session->userdata('authenticated') == TRUE) {
            $this->data['authenticated'] = TRUE;
        } else {
            $this->data['authenticated'] = false;
        }
        $this->load->view('header', $this->data);
        $this->load->view("showall", $this->data);
        $this->load->view('footer', $this->data);
    }

    public function edit() {
        //Get the parameters
        $type = $_POST['type'];
        $id = $_POST['id'];
        $newvalue = $_POST['new_value'];

        $this->suggest->add_suggest($type, $newvalue, $id);
    }

    public function suggestion() {

        //Check if we have any submitted information
        if (isset($_POST['suggestionapproval'])) {
            $action = $_POST['action'];
            $id = $_POST['id'];

            //Apply the action on the given suggest
            $this->suggest->approve($id, $action);
        }
        $query = $this->db->query("select * from suggested_info where id not in (select suggest_id from suggestion_approval where user_id=$this->user)")->result();
        //Before providing all the data to the view, Prepare it
        $finalarray = array();
        foreach ($query as $row) {
            $newtemparray = array();
            $newtemparray['id'] = $row->id;
            $result = $this->suggest->getviewname($row);
            $newtemparray['view'] = $this->load->view($result[0], $result[1], TRUE);
            $finalarray[] = $newtemparray;
        }

        $this->data['suggestion'] = $finalarray;

        $this->load->view('header', $this->data);
        $this->load->view('suggestion', $this->data);
        $this->load->view('footer', $this->data);
    }

    public function editinfo($id = NULL) {
        if (is_null($id)) {
            return FALSE;
        }

        $this->data['error'] = false;
        if (isset($_POST['submit'])) {

            $db_array = array(
                "title" => $_POST['name'],
                "artist" => $_POST['artist'],
                "album" => $_POST['album']
            );

            //Add all the suggestion individually
            $this->suggest->add_suggest(TITLE, $_POST['name'], $id);
            $this->suggest->add_suggest(ARTIST, $_POST['artist'], $id);
            $this->suggest->add_suggest(ALBUM, $_POST['album'], $id);
        }

        //Get the music details
        $query = $this->db->query("select * from musicinfo where id=$id");
        $this->data['song'] = $query->row(0);
        $this->load->view('header', $this->data);
        $this->load->view('editinfo', $this->data);
        $this->load->view('footer', $this->data);
    }

    public function deleteinfo($id = NULL) {
        if (is_null($id)) {
            return FALSE;
        }
        $this->data['error'] = false;

        if ($this->db->delete('musicinfo', array('id' => $id))) {
            redirect('mmm/showall');
        }
    }

    public function search() {
        //Get the thing which is to be searched
        if (isset($_GET['searchsubmit'])) {
            $string = $_GET['searchvalue'];

            //Now run three queries and collect the result
            $query = $this->db->query("select * from musicinfo where title like '%$string%'")->result();
            $query2 = $this->db->query("select * from musicinfo where album like '%$string%'")->result();
            $query3 = $this->db->query("select * from musicinfo where artist like '%$string%'")->result();
            $this->data['song'] = $query;
            $this->data['album'] = $query2;
            $this->data['artist'] = $query3;
        }
        $this->load->view('header', $this->data);
        $this->load->view("search", $this->data);
        $this->load->view('footer', $this->data);
    }

    public function addto_database($data) {
        if (!is_array($data)) {
            return false;
        }
//preg_match_all('#[-a-zA-Z0-9@:%_\+.~\#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~\#?&//=]*)?#si', $targetString, $result);
        //Prepare the data
        $db_array = array(
            "album" => isset($data['album'][0]) ? $data['album'][0] : null,
            "artist" => isset($data['artist'][0]) ? $data['artist'][0] : null,
            "band" => isset($data['band'][0]) ? $data['band'][0] : NULL,
            "composer" => isset($data['composer'][0]) ? $data['composer'][0] : null,
            "date" => isset($data['date'][0]) ? $data['date'][0] : NULL,
            "genre" => isset($data['genre'][0]) ? $data['genre'][0] : NULL,
            "title" => isset($data['title'][0]) ? $data['title'][0] : NULL,
            "year" => isset($data['year'][0]) ? $data['year'][0] : NULL,
            "other" => json_encode($data)
        );

        return $this->db->insert("musicinfo", $db_array);
    }

    public function register_form() {


        $this->load->view('header', $this->data);
        $this->load->view('register_form', $this->data);
        $this->load->view('footer', $this->data);
    }

    public function register() {
        if ($_POST['registerbutton']) {
            //Get the variables
            $name = $_POST['name'];
            $username = $_POST['username'];
            $password = $_POST['password'];

            //Now check if this username is already there or not
            $query = $this->db->query("select * from user where username='$name'");

            if ($query->num_rows() > 0) {
                //Return the same page with errors
                $data['error_username'] = true;
                $this->load->view('header', $this->data);
                $this->load->view('register_form', $this->data);
                $this->load->view('footer', $this->data);
                return;
            }

            //Now insert this info in the database
            $this->db->insert("user", array(
                "fullname" => $_POST['name'],
                "username" => $_POST['username'],
                "password" => $_POST['password']
            ));

            //And go back to the old page
            $this->index();
        }
    }

    public function login_form() {

        $this->load->view("header", $this->data);
        $this->load->view("login_form", $this->data);
        $this->load->view("footer", $this->data);
    }

    public function login() {
        $data['error'] = false;
        if (isset($_POST['submit'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $query = $this->db->query("select * from user where username='$username' and password='$password' ");
            $row = $query->row(0);
            if ($query->num_rows() == 1) {
                //The person has successfull logged in
                //Now set the cookies

                $this->session->set_userdata(array(
                    "authenticated" => true,
                    "id" => $row->id,
                    "name" => $row->fullname
                ));
                redirect("/mmm/index");
            } else {
                $this->data['error'] = true;
            }

            //Now unable to successfully
            $this->load->view("header", $this->data);
            $this->load->view("login_form", $this->data);
            $this->load->view("footer", $this->data);
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect("/mmm/index");
    }

}

?>
