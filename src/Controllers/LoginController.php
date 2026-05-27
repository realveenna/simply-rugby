<?php
    namespace Test\Controllers;

    use Test\Controller;
    use Test\Models\User;
    use Test\Models\Role;
    use Test\Models\AccessControl;
    use Test\Models\PrivilegedUser;
    use Test\Models\Guardian;
    use Test\Models\Squad;
    use Test\Database;

    class LoginController extends Controller
    {
        // Login function
        public function login()
        {

            $pdo = Database::getInstance()->getConnection();

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
                        $isUser = User::findEmail($email);
                        if (!$isUser) {
                            $emailErr ='Email is not registered.';
                        }
                        else{
                            $member = new User;
                            $member = $member->getCredentials($isUser['member_id']);

                            // Check password       
                            $salt ="4g£yc7!L(";
                            $password = md5($rawPassword.$salt);

                            // Correct Password
                            if($member['pass'] === $password){
                                $_SESSION['loggedIn'] = true; 
                                $_SESSION['user'] = $member;
                                $_SESSION['id'] = session_id();
                                $_SESSION['rbac'] = PrivilegedUser::getPrivilegedMember
                                    ($_SESSION['user']['member_id']) ?? null;

                                $_SESSION['player_access'] = 
                                    AccessControl::getAccessPlayers($pdo, $_SESSION['user']['member_id']);
                                $_SESSION['squad_access'] = 
                                    AccessControl::getAccessSquads($pdo, $_SESSION['user']['member_id']);
                                $_SESSION['section_access'] = 
                                    AccessControl::getAccessSections($pdo, $_SESSION['user']['member_id']);

                                alert('success','Login Successfully!', '/');
                            }
                            // Incorrect Password
                            else{
                                $rawPasswordErr = 'Incorrect Password.';
                            }
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

        public function logout(){
            // remove and destroy session
            session_unset();
            session_destroy();

            header("Location: /login");
            exit();
        }
    }
?>