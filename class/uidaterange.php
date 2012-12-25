<?php

// plan: implement http://www.dangrossman.info/2012/08/20/a-date-range-picker-for-twitter-bootstrap/

class uidaterange extends uiElement
{
    public function __construct()
    {
    }

    public function __toString()
    {
        try
        {
            $output="<html goes here>";

            return($output.$this->GenerateContent());
        }
        catch (Exception $e)
        {
            siError($e);
            return('');
        }
    }
}
