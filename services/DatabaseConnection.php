<?php

class DatabaseConnection
{
    const DB_USER = "root";
    const DB_PASSWORD = "root";
    const DB_HOST = "localhost";
    const DB_NAME = "e_commerce";

    private $dbc;

    function __construct()
    {
        $this->dbc = @mysqli_connect(self::DB_HOST, self::DB_USER, self::DB_PASSWORD, self::DB_NAME)
            or die('Could not connect to MySQL: ' . mysqli_connect_errno());

        mysqli_set_charset($this->dbc, 'utf8');
    }

    function prepare_string($dbc, $string)
    {
        $string_trimmed = trim($string);
        $string = mysqli_real_escape_string($dbc, $string_trimmed);
        return $string;
    }

    function get_dbc()
    {
        return $this->dbc;
    }
}
