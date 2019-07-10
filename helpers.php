<?php


if (! function_exists('str_root_password')) {
    /**
     * Returns a random root password
     * 
     * @return string
     *
     * */
    function str_root_password()
    {
        return str_shuffle(str_random(10) . '{}[]%&');
    }
}
