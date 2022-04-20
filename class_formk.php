<?php

class Formk
{
    public static function create($type, $attrs = [])
    {
        $output = "";
        if ((strtolower($type) == 'text') ||
            (strtolower($type) == 'number') ||
            (strtolower($type) == 'date') ||
            (strtolower($type) == 'email')
        ) {
            $output .= "<input type='$type' ";
            foreach ($attrs as $key => $value) {
                $output .= " $key='$value' ";
            }
            $output .= "/>";
        } elseif (strtolower($type) == 'currency') {
            $output = self::create_currency($attrs);
        } elseif (strtolower($type) == 'select') {
            $output .= "<select ";
            foreach ($attrs as $key => $value) {
                if (strtolower($key) != 'options') {
                    $output .= " $key='$value' ";
                }
            }
            $output .= ">";
            foreach ($attrs['options'] as $option) {
                $output .= "<option ";
                foreach ($option as $key => $value) {
                    if (strtolower($key) != 'label') {
                        $output .= " $key='$value' ";
                    }
                }
                $output .= "/>" . $option['label'] . "</option>";
            }
        }
        return $output;
    }

    private static function create_currency($attrs)
    {
        $symbol = isset($attrs['symbol']) ? $attrs['symbol'] : '$';
        $output = "<span style='display:flex; flex-direction: row;'><input type='number' style='direction:rtl;' ";
        foreach ($attrs as $key => $value) {
            if (strtolower($key) != 'symbol') {
                $output .= " $key='$value' ";
            }
        }
        $output .= "/><span style='display:flex; align-items: center;font-weight:bold;margin-left:10px;'>$symbol</span></span>";
        return $output;
    }
}
