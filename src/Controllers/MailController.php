<?php
    namespace Test\Controllers;

    use Test\Controller;
    use Test\Models\Club;
    use Test\Models\User;
    use Test\Database;
    
    use Mailtrap\Helper\ResponseHelper;
    use Mailtrap\MailtrapClient;
    use Mailtrap\Mime\MailtrapEmail;
    use Symfony\Component\Mime\Address;

    class MailController extends Controller
    {

        public function __construct()
        {

        }


        public function sendMail(){

        }
   

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
                alert('error', $mailtrap->$result, '/');
            }
        }
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
                alert('error', $mailtrap->$result, '/player-applications');
            }
        }
    }
?>
