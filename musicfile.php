<?php

class musicfile
{
    public $musicdata;
    public function __construct($analyzedata) {
        $this->musicdata=$analyzedata['tags_html']['id3v2'];
    }
}
?>
