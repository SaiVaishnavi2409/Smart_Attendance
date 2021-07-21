<?php
    // sanitize input--------
    function sanitize($value)
    {
        $len = strlen($value);
        for ($i=0; $i < $len; $i++) 
        { 
            if ($value[$i] == "--" || $value[$i] == "<" || $value[$i] == ">" || $value[$i] == "/" || $value[$i] == "'" || $value[$i] == "\"" || $value[$i] == ";")
                $value[$i] = "";
        }
        return $value;
    }
?>