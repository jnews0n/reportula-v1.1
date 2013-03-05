<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 * VD
 * usage : vd::dump($your_var);
 */
class vd {
    function dump ($var, $name = '') {
        $style = "background-color: whitesmoke; padding: 8px 8px 8px 8px; border: 1px solid black; text-align: left;";
        echo "<pre style='$style'>" .
            ($name != '' ? "$name : " : '') .
            vd::_get_info_var ($var, $name) .
            "</pre>";
    }
    
    function get ($var, $name = '') {
        return ($name != '' ? "$name : " : '') . vd::_get_info_var ($var, $name);
    }
    
    function _get_info_var ($var, $name = '', $indent = 0) {
        static $methods = array ();
        $indent > 0 or $methods = array ();

        $indent_chars = '  ';
        $spc = $indent > 0 ? str_repeat ($indent_chars, $indent ) : '';
        
        $out = '';
        if (is_array ($var)) {
            $out .= "<span style='color:red;'><b>Array</b></span> " . count ($var) . " (\n";
            foreach (array_keys ($var) as $key) {
                $out .= "$spc  [<span style='color:red;'>$key</span>] => ";
                if (($indent == 0) && ($name != '') && (! is_int ($key)) && ($name == $key)) {
                    $out .= "LOOP\n";
                } else {
                    $out .= vd::_get_info_var ($var[$key], '', $indent + 1);
                }
            }
            $out .= "$spc)";
        } else if (is_object ($var)) {
            $class = get_class ($var);
            $out .= "<span style='color:purple;'><b>Object</b></span> $class";
            $parent = get_parent_class ($var);
            $out .= $parent != '' ? " <span style='color:purple;'>extends</span> $parent" : '';
            $out .= " (\n";
            $arr = get_object_vars ($var);
            while (list($prop, $val) = each($arr)) {
                $out .= "$spc  " . "-><span style='color:purple;'>$prop</span> = ";
                $out .= vd::_get_info_var ($val, $name != '' ? $prop : '', $indent + 1);
            }
            $arr = get_class_methods ($var);
            $out .= "$spc  " . "$class methods: " . count ($arr) . " ";
            if (in_array ($class, $methods)) {
                $out .= "[already listed]\n";
            } else {
                $out .= "(\n";
                $methods[] = $class;
                while (list($prop, $val) = each($arr)) {
                    if ($val != $class) {
                        $out .= $indent_chars . "$spc  " . "->$val();\n";
                    } else {
                        $out .= $indent_chars . "$spc  " . "->$val(); [<b>constructor</b>]\n";
                    }
                }
                $out .= "$spc  " . ")\n";
            }
            $out .= "$spc)";
        } else if (is_resource ($var)) {
            $out .= "<span style='color:steelblue;'><b>Resource</b></span> [" . get_resource_type($var) . "] ( <span style='color:steelblue;'>" . $var . "</span> )";
        } else if (is_int ($var)) {
            $out .= "<span style='color:blue;'><b>Integer</b></span> ( <span style='color:blue;'>" . $var . "</span> )";
        } else if (is_float ($var)) {
            $out .= "<span style='color:blue;'><b>Float</b></span> ( <span style='color:blue;'>" . $var . "</span> )";
        } else if (is_numeric ($var)) {
            $out .= "<span style='color:blue;'><b>Numeric string</b></span> " . strlen($var) . " ( \"<span style='color:green;'>" . $var . "</span>\" )";
        } else if (is_string ($var)) {
            $out .= '<span style="color:green;"><b>String</b></span> ' . strlen($var) . ' ( "<span style="color:green;">' . nl2br(htmlentities($var)) . '</span>" )';
        } else if (is_bool ($var)) {
            $out .= "<span style='color:darkorange;'><b>Boolean</b></span> ( <span style='color:darkorange;'>" . ($var ? 'True' : 'False') . "</span> )";
        } else if (! isset ($var)) {
            $out .= "<b>Null</b>";
        } else {
            $out .= "<b>Other</b> ( " . $var . " )";
        }
        
        return $out . "\n";
    }
}
/* End of file */  