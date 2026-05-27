<?php
    namespace Test\Controllers;
    use Test\Controller;
    
    class HomeController extends Controller
    {
        public function index()
        {
            if (true === isLoggedIn()){
                header('Location: /account?member_id=' . $this->member_id);
                exit;
            }
            else{
                $this->render('index');
            }
        }
    }
?>