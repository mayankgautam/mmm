<?php

require_once '../id3/getid3/getid3.php';

/**
 * This the default controller of CI
 */
class mmm extends CI_Controller {

    public $id3;

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
        $this->id3 = new getID3;
        define("BASEURL", base_url());
        define("INDEX", index_page());
    }

    public function index() {
        $data['baseurl'] = BASEURL;
        $data['index'] = INDEX;
        $data['nodata'] = $this->db->query("select id from musicinfo")->num_rows();
        $this->load->view("header", $data);
        $this->load->view('main', $data);
        $this->load->view("footer", $data);
    }

    public function submitdata() {
        $data['baseurl'] = BASEURL;
        $data['index'] = INDEX;
        $data['error'] = FALSE;
        $data['success'] = false;
        $data['errmsg'] = "";
        $allowed = array("audio/mp3");
        if (isset($_POST['submit'])) {
            //Check for file upload errors
            if ($_FILES['musicfile']['error'] > 0) {
                $data['error'] = true;
                $data['errmsg'] = "File was not uploaded properly. Please try and upload again";
            } elseif (!in_array($_FILES['musicfile']['type'], $allowed)) {
                $data['error'] = true;
                $data['errmsg'] = "File type not supported";
            } else {
                //Now we can upload the file
                $fileinfo = $this->id3->analyze($_FILES['musicfile']["tmp_name"]);

                //Add to database
                if (!$this->addto_database($fileinfo['tags_html']['id3v2'])) {
                    $data['error'] = TRUE;
                    $data['errmsg'] = "Unable to add information in the database. Please Try again";
                } else {
                    $data['success'] = true;
                }
            }
        }
        $this->load->view("header", $data);
        $this->load->view("submitdata", $data);
        $this->load->view("footer", $data);
    }

    public function showall() {
        $data['baseurl'] = BASEURL;
        $data['index'] = INDEX;
        $query = $this->db->query("select * from musicinfo");
        $data['songs'] = $query->result();
        $this->load->view('header', $data);
        $this->load->view("showall", $data);
        $this->load->view('footer', $data);
    }

    public function editinfo($id = NULL) {
        if (is_null($id)) {
            return FALSE;
        }
        $data['baseurl'] = BASEURL;
        $data['index'] = INDEX;
        $data['error'] = false;
        if (isset($_POST['submit'])) {
            $db_array = array(
                "title" => $_POST['name'],
                "artist" => $_POST['artist'],
                "album" => $_POST['album']
            );

            $this->db->where('id', $id);
            if (!$this->db->update('musicinfo', $db_array)) {
                $data['error'] = true;
            }
        }

        //Get the music details
        $query = $this->db->query("select * from musicinfo where id=$id");
        $data['song'] = $query->row(0);
        $this->load->view('header', $data);
        $this->load->view('editinfo', $data);
        $this->load->view('footer', $data);
    }

    public function deleteinfo($id = NULL) {
        if (is_null($id)) {
            return FALSE;
        }
        $data['baseurl'] = BASEURL;
        $data['index'] = INDEX;
        $data['error'] = false;

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

}

?>
