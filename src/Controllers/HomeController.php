<?php
    namespace Test\Controllers;
    use Test\Controller;
    
    class HomeController extends Controller
    {
        // Index render page for logged in and not logged in user
        public function index()
        {
            if (isLoggedIn()){
                header('Location: /account?member_id=' . $this->member_id);
                exit;
            }
            else{
                $this->render('index');
            }
        }
    }
?>