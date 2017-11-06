<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function response_success($data = null)
{
    header('Content-Type: application/json');
    echo json_encode($data);
}

function response_error($data = null, $error_code = 400)
{
    $resp = array(
        'error' => true,
        'message' => $data,
        'error_code' => $error_code
    );
    header('Content-Type: application/json');
    echo json_encode($resp);
}