<?php
/**
 * Created by PhpStorm.
 * User: 邓伟
 * Date: 2017/5/2
 * Time: 17:28
 */

namespace Home\Controller;


class LoginController extends HomeController
{
    public function __construct()
    {
        parent::__construct();
        if(!is_login()){
            $this->redirect('Home/User/login');
        }
    }
}