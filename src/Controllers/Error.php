<?php
    namespace Test\Controllers;
    use Test\Controller;
    
    class Error extends Controller
    {
        // HTTP RESPONSE REDIRECT
        public function index($code, $title, $message = 'Oops! Something went wrong.')
        {
            $this->render('errors/index', [
                'code' => $code,
                'title' => $title,
                'message' => $message
            ]);
            exit;
        }
    }
?>