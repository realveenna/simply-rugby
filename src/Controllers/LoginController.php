<?php
    namespace Test\Controllers;

    use Test\Controller;
    use Test\Models\User;
    

    class LoginController extends Controller
    {
        // Login function
        public function login()
        {
            $email = '';
            $emailErr = '';
            $rawPassword = '';
            $rawPasswordErr = '';

            
            if ($_POST) {
                $email = $_POST['email'] ?? '';
                $rawPassword = $_POST['rawPassword'] ?? '';

                // Validate Empty Email and Password Input
                if (empty($rawPassword)) {
                    $rawPasswordErr = "Password is required";
                }

                if (empty($email)) {
                    $emailErr = "Email is required";
                } else {
                    $email = trim($email);
                    // If invalid email format
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $emailErr = "Invalid email format";
                    }
                    // Valid Email Check Database
                    else{
                        $result = User::validate($email, $rawPassword);
                        if (isset($result['success'])) {
                            $_SESSION["loggedIn"] = true; 
                            $_SESSION["member"] = $result['member'];
                            // header("Location: /dashboard");
                            // exit();
                        }
                        else{
                            // Display Input Error
                            if (isset($result['emailError'])) {
                                $emailErr = $result['emailError'];
                            }

                            if (isset($result['passErr'])) {
                                $rawPasswordErr = $result['passErr'];
                            }
                            // header("Location: /login");
                            // exit();
                        }
                    }
                }
            }
            $this->render('login', [
                'email' => $email,
                'emailErr' => $emailErr,
                'rawPasswordErr' => $rawPasswordErr
            ]);
            
        }
    }
?>