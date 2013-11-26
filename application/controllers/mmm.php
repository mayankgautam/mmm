<?php

require_once '../id3/getid3/getid3.php';
require_once '../../suggest/suggest_handler.php';

/**
 * This the default controller of CI
 */
class mmm extends CI_Controller {

    public $id3, $data, $suggest;

    public function __construct() {

        //CI Dependencies
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->helper('url');

        //MetaData dependencies
        $this->id3 = new getID3;
        $this->suggest = new suggest_handler($this->db);

        //Other variables needed by every page
        define("BASEURL", base_url());
        define("INDEX", index_page());
        $this->data['baseurl'] = BASEURL;
        $this->data['index'] = INDEX;
        $this->data['session'] = $this->session;
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
        $this->load->view('header', $this->data);
        $this->load->view("showall", $this->data);
        $this->load->view('footer', $this->data);
    }

    public function editinfo($id = NULL) {
        if (is_null($id)) {
            return FALSE;
        }

        $data['error'] = false;
        if (isset($_POST['submit'])) {
            $db_array = array(
                "title" => $_POST['name'],
                "artist" => $_POST['artist'],
                "album" => $_POST['album']
            );

            $this->db->where('id', $id);
            if (!$this->db->update('musicinfo', $db_array)) {
                $this->data['error'] = true;
            }
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

    public function addto_database($data) {
        if (!is_array($data)) {
            return false;
        }

        //Prepare the data
        $db_array = array("album" => $data['album'][0],
            "artist" => $data['artist'][0],
            "band" => $data['band'][0],
            "composer" => $data['composer'][0],
            "date" => $data['date'][0],
            "genre" => $data['genre'][0],
            "title" => $data['title'][0],
            "year" => $data['year'][0],
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
                    "name" => $row->name
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

}

?>
