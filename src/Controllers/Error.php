<?php
    namespace Test\Controllers;

    use Test\Controller;
    
    class Error extends Controller
    {
        public function __construct()
        {
            
        }

        // Page not found
        public function notFound($message = 'Page Not Found')
        {
            http_response_code(404);
            $this->render('errors/404', [
                'error' => $message
            ]);
            exit;
        }

         // Forbidden page access
        public function forbidden($message = 'You do not have permission to access this page or resource.')
        {
            http_response_code(403);
            $this->render('errors/403', [
                'error' => $message
            ]);

            exit;
        }
    }
?>