<?php
    namespace Test\Controllers;

    use Test\Controller;
    use Test\Models\Club;
    use Mailtrap\Helper\ResponseHelper;
    use Mailtrap\MailtrapClient;
    use Mailtrap\Mime\MailtrapEmail;
    use Symfony\Component\Mime\Address;
    use Test\Models\Member;
    use Test\Models\Role;

    class MailController extends Controller
    {

        public function __construct()
        {
            parent::__construct();
        }

        // render mail page
        public function sendMail()
        {
            $pdo = $this->pdo;
            $error =[];
            $recipients = $_POST['recipients'] ?? [];

            // Hide roles with no email
            $exceptRole = ['Junior Player', 'Next of Kin'];

            // Get all roles for dropdown
            $roles = Role::getRole();

            // Remove hidden roles
            $roles = array_filter($roles, function($role) use ($exceptRole){
                return !in_array($role['role_name'], $exceptRole);
            });

            if ($_POST){
                // Trim post
                $recipient_role = trimPost('role_name');
                $message = trimPost('message') ?? '';

                // validate empty inputs
                $error['role_name'] = ifEmpty($recipient_role, 'Please select a role');
                $error['message'] = ifEmpty($message, 'Please enter a message');

                // no errors
                if(!array_filter($error)){
                    // Get members with role name
                    $members = Member::getMembersByRole($pdo, $recipient_role) ?? [];

                    // Send mail to each member
                    foreach($members as $member){
                        self::send($member['email'], $message, $member['first_name']);
                    }
                }
            }

            // Render
            $this->render('mail/index', [
                'recipients' => $recipients ?? [],
                'members' => $members ?? [],
                'roles' => $roles ?? [],
                'error' => $error,
            ]);

        }

        // private reusable method for sending mail 
        private function send($email, $message, $first_name){
            try {
                $club = new Club();

                $apiKey = '4d609627dab818729ea0d0cb1f7c5480';
                $mailtrap = MailtrapClient::initSendingEmails(
                    apiKey: $apiKey,
                    inboxId: 4629275,
                    isSandbox: true,
                );

                $email = (new MailtrapEmail())
                    ->from(new Address('hello@demomailtrap.co', 'Mailtrap Test'))
                    ->to(new Address($email))
                    ->templateUuid('5657b2c1-420c-48dd-a8cb-a9f1b685cb91')
                    ->templateVariables([
                        'company_info_name' => $club->name,
                        'first_name' => $first_name,
                        'message' => $message,
                        'company_info_address' => $club->line1,
                        'company_info_city' => $club->city,
                        'company_info_zip_code' => $club->zipcode,
                        'company_info_country' => $club->country,
                    ])
                ;
                
                $response = $mailtrap->send($email);
                $result = ResponseHelper::toArray($response);
                if(!$response){
                    throw new \Exception("Email not sent");
                }
            } catch (\Exception $e) {
                // As comment for testing purposes
                // alert('error', $mailtrap->$result, '/');
                alert('error', $e->getMessage(), '/mail');

            }
            alert('success', 'Message Sent Successfully!', '/mail');
        }
   
        // Reset password message for new players and members
        public static function newResetPassword($fname, $email, $member_id, $password)
        {
            $club = new Club();

            try {
                $apiKey = '4d609627dab818729ea0d0cb1f7c5480';
                $mailtrap = MailtrapClient::initSendingEmails(
                    apiKey: $apiKey,
                    inboxId: 4629275,
                    isSandbox: true,
                );

                $email = (new MailtrapEmail())
                    ->from(new Address('hello@demomailtrap.co', 'Mailtrap Test'))
                    ->to(new Address($email))
                    ->templateUuid('c494f85b-242f-4bf6-b149-320e5fe9f621')
                    ->templateVariables([
                        'company_info_name' => $club->name,
                        'name' => $fname,
                        'company_info_address' => $club->line1,
                        'company_info_city' => $club->city,
                        'company_info_zip_code' => $club->zipcode,
                        'company_info_country' => $club->country,
                        'password' => $password,
                        'reset_password_link' => 'http://localhost:9999/account/reset-password?member_id=' . $member_id
                    ])
                ;
                $response = $mailtrap->send($email);
                if(!$response){
                    throw new \Exception("Email not sent");
                }
                $result = ResponseHelper::toArray($response);
            } catch (\Exception $e) {
                // alert('error', $mailtrap->$result, '/');
            }
        }

        // Send welcome message for new member with existing email/ more for parents
        public static function newMember($fname, $email)
        {
            $club = new Club();

            try {
                $apiKey = '4d609627dab818729ea0d0cb1f7c5480';
                $mailtrap = MailtrapClient::initSendingEmails(
                    apiKey: $apiKey,
                    inboxId: 4629275,
                    isSandbox: true,
                );

                $email = (new MailtrapEmail())
                    ->from(new Address('hello@demomailtrap.co', 'Mailtrap Test'))
                    ->to(new Address($email))
                    ->templateUuid('60a22e5f-dbc9-4edc-90ab-c5c7e59be5eb')
                    ->templateVariables([
                        'company_info_name' => $club->name,
                        'name' => $fname,
                        'company_info_address' => $club->line1,
                        'company_info_city' => $club->city,
                        'company_info_zip_code' => $club->zipcode,
                        'company_info_country' => $club->country,
                    ])
                ;
                $response = $mailtrap->send($email);
                $result = ResponseHelper::toArray($response);

            } catch (\Exception $e) {
                // As comment for testing purposes
                // alert('error', $mailtrap->$result, '/player-applications');
            }
        }

        // send new training email to players
        public static function newTraining($data)
        {
            $club = new Club();

            try {
                $apiKey = '4d609627dab818729ea0d0cb1f7c5480';
                $mailtrap = MailtrapClient::initSendingEmails(
                    apiKey: $apiKey,
                    inboxId: 4629275,
                    isSandbox: true,
                );


               $email = (new MailtrapEmail())
                    ->from(new Address('hello@demomailtrap.co', 'Mailtrap Test'))
                    ->to(new Address($data['recipient']))
                    ->templateUuid('e07d67b7-4a77-4782-96ec-9af83a79642c')
                    ->templateVariables([
                        'company_info_name' => $club->name,
                        'name' => $data['name'],
                        'date' => $data['date'],
                        'start_time' => $data['start_time'],
                        'end_time' => $data['end_time'],
                        'activities' => $data['skills_activities'],
                        'coach_name' => 
                            ucwords($_SESSION['user']['first_name'] . ' ' . 
                            $_SESSION['user']['last_name']) ,
                        'coach_email' => strtolower($_SESSION['user']['email']),
                        'company_info_address' => $club->line1,
                        'company_info_city' => $club->city,
                        'company_info_zip_code' => $club->zipcode,
                        'company_info_country' => $club->country
                    ])
                ;
                $response = $mailtrap->send($email);
                $result = ResponseHelper::toArray($response);

            } catch (\Exception $e) {
                // As comment for testing purposes
                // alert('error', $mailtrap->$result, '/training/create');
            }
        }
    }
?>
