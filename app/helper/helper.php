<?php

    function app_url(){
        return URL::To('');
    }
    function public_url($fileUrl = ""){
        return $fileUrl == "" ? asset('/public') : asset('/public'.$fileUrl);
    }
    function file_exists_in_folder($directory,$fileName){
        if($fileName!="" && file_exists(public_path($directory.'/'.$fileName)))
        {
            return public_url().'/'.$directory.'/'.$fileName;
        }
        else{
            if($directory == "sitesetting"){
                return public_url().'/default_images/blank_image.jpeg';
            }
            elseif($directory == "profile_pic"){
                return public_url().'/default_images/default_user.jpg';
            }
            elseif($directory == "id_proof"){
                return public_url().'/default_images/blank_image.jpeg';
            }
        }
    }
    function old_file_remove($directory,$fileName){
        if($fileName !="" && file_exists(public_path($directory.'/'.$fileName)))
        {
            return unlink(public_path($directory.'/'.$fileName));
        }
    }
    function get_language_name($objectName,$name = ""){
        if(!empty($objectName)){
            $locale = app()->getLocale();
            $result = json_decode(json_encode($objectName, true));
            $result = (array) $result;
            return $result[$name.'_'.$locale];
        }
        else{
            return '';
        }
    }
    function get_timestamp($time=""){
        return $time = $time == "" ? strtotime("now") : strtotime($time);

    }
    function get_time($timestamp="", $type=""){
        if($timestamp == ""){
            return $time = $type == "" ? date("Y/m/d") : date($type);
        }
        else{
            return $time = $type == "" ? date("Y/m/d",$timestamp) : date($type,$timestamp);
        }
    }
