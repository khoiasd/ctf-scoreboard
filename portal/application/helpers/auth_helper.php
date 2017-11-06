<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


function Auth_logged()
{
    $CI = &get_instance();
    $session_data = $CI->session->userdata('user');
    return $session_data;
}

function Auth_username()
{
    $user_session = Auth_logged();
    return $user_session['username'];
}

function Auth_id()
{
    $user_session = Auth_logged();
    return $user_session['id'];
}

function Auth_playing()
{
    if (time() < strtotime(STARTED_AT)){
        return false;
    }elseif(strtotime(STARTED_AT) <= time() and time() <= strtotime(STOPPED_AT)){
        return true;
    }else{
        return null;
    }
}
