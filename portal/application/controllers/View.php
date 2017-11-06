<?php

/**
 * Created by PhpStorm.
 * User: khoidv1
 * Date: 10/31/2017
 * Time: 3:52 PM
 */
class View extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('Auth_helper');
    }

    function index()
    {
        $this->load->view('IndexView');
    }

    function home()
    {
        $this->load->view('HomeView');
    }

    function sign_in()
    {
        if (Auth_logged()) {
            echo "<h4 class='text-center'>Bạn không có quyền truy cập chức năng này</h4>";
            return;
        }
        return $this->load->view('SignView');
    }

    function user()
    {
        if (!Auth_logged()) {
            echo "<h4 class='text-center'>Bạn không có quyền truy cập chức năng này</h4>";
            return;
        }
        return $this->load->view('UserView');
    }

    function challenge()
    {
        if (!Auth_logged()) {
            echo "<h4 class='text-center'>Bạn không có quyền truy cập chức năng này</h4>";
            return;
        }
        return $this->load->view('ChallengeView');
    }
    function scoreboard()
    {
//        if (!Auth_logged()) {
//            echo "<h4 class='text-center'>Bạn không có quyền truy cập chức năng này</h4>";
//            return;
//        }
        return $this->load->view('ScoreboardView');
    }

}