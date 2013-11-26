<?php

/**
 * Class defined to store a suggest and all the info required by the suggest
 *
 * @author piyush
 */

/**
 * Defining all the Fields that can be altered
 */
define("ALBUM","album");
define("ARTIST", "artist");
define("BAND", "band");
define("COMPOSER", "composer");
define("DATE", "date");
define("GENRE", "genre");
define("TITLE", "title");
define("YEAR", "year");


class suggest_storage {

    /**
     *
     * @var string Unique name of the suggest
     */
    public $name;

    /**
     *
     * @var string The filename of the template file to be used with this suggest
     */
    public $tpl;

    /**
     *
     * @var array Array containing all the parameters to be used by the template
     */
    public $parameter;

    /**
     * This function is used to set the suggest parameters that are required
     * @param string $sname
     * @param string $stpl
     * @param array $sparameters
     */
    public function add($sname, $stpl, $sparameter) {
        $this->name = $sname;
        $this->tpl = $stpl;
        $this->parameter = $sparameter;
    }

}

?>
