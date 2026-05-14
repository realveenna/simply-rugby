<?php
require '../src/Database.php';
require '../src/Models/Application.php';
?>

<?php
require '../vendor/autoload.php';

use Mailtrap\Helper\ResponseHelper;
use Mailtrap\MailtrapClient;
use Mailtrap\Mime\MailtrapEmail;
use Symfony\Component\Mime\Address;

$password = "Password1234";
$member_id = 1;
$fname = "raven";

try {
    $apiKey = '4d609627dab818729ea0d0cb1f7c5480';
    $mailtrap = MailtrapClient::initSendingEmails(
        apiKey: $apiKey,
        inboxId: 4629275,
        isSandbox: true,
    );

    $email = (new MailtrapEmail())
        ->from(new Address('hello@demomailtrap.co', 'Mailtrap Test'))
        ->to(new Address("vennny25@gmail.com"))
        ->templateUuid('c494f85b-242f-4bf6-b149-320e5fe9f621')
        ->templateVariables([
            'company_info_name' => 'Simply Rugby',
            'name' => $fname,
            'company_info_address' => 'Test_Company_info_address',
            'company_info_city' => 'Test_Company_info_city',
            'company_info_zip_code' => 'Test_Company_info_zip_code',
            'company_info_country' => 'Test_Company_info_country',
            'password' => $password,
            'reset_password_link' => 'http://localhost:9999/reset-password?id=' . $member_id
        ])
    ;

    $response = $mailtrap->send($email);

    $result = ResponseHelper::toArray($response);
    if($result['success'] === true){
        die('Email Sent Successfully');
    }
    else{
        die('Email was not sent');
    }
} catch (\Exception $e) {
    die($mailtrap->$result);
}
?>

