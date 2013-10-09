<?php

require_once '../id3/getid3/getid3.php';

class mmm extends CI_Controller {

    public $id3;

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->id3 = new getID3;
    }

    public function index() {
        $this->load->view("header");
        if (file_exists("song.mp3")) {
            //Process information that we have
            $fileinfo = $this->id3->analyze("song.mp3");
        }
        $this->load->view("footer");
    }

}

?>
