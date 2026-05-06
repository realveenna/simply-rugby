<?php
    namespace Test\Controllers;

    use Test\Controller;


    class HomeController extends Controller
    {
        public function index()
        {
            if (true === isLoggedIn()){
                $this->render('dashboard');
            }
            else{
                $this->render('index');
            }
        }
    }
?>