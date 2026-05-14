<?php
require '../src/Database.php';
require '../src/Models/Application.php';
?>

<?php
require '../vendor/autoload.php';

use MailerSend\MailerSend;
use MailerSend\Helpers\Builder\Recipient;
use MailerSend\Helpers\Builder\EmailParams;

$successCount = 0;
$voucherSection = '';
$unsubscribeSection = '';

$emails = ['vennny25@gmail.com'];
$subject = 'test';

if (!empty($voucherMail)) {
    $voucherSection = '
        <br>
        <div style="margin:1rem;padding:15px;border:1px dashed #ccc;text-align:center;">
            <strong>Your Voucher Code:</strong><br>
            <span style="font-size:20px;font-weight:bold;">' . e($voucherMail) . '</span>
        </div>
        <br>
    ';
    $unsubscribeSection = '
        <hr>
          <span> Unsubscribe </span>
    ';
}


try {
    $mailersend = new MailerSend([
      'api_key' => 'mlsn.4106b5f37a02b46176f093634cfffe8a2483492e4b08b91688c441cdd101ac6e'
    ]);

    foreach($emails as $email){
      $recipients = [
        new Recipient($email, 'Customer')
      ];
  
    $emailParams = (new EmailParams())
      ->setFrom('hello@test-pzkmgq7965nl059v.mlsender.net')
      ->setFromName('Nail Utopia')
      ->setRecipients($recipients)
      ->setSubject($subject)
      ->setHtml('
          <img src="https://nail-utopia.infinityfreeapp.com/uploads/logo/logo.png" alt="Nail Utopia Logo" width="80" style="display:block;margin:auto;"> </img>
          <hr>
          <br>
          <strong> test </strong>
          <br>
          <br>
          <small style="display:block;margin:auto;">
            © All Rights Reserved Nail Utopia 2026
          </small>        
        ');
      $mailersend->email->send($emailParams);
      $successCount ++;
    }
} catch (\Exception $e) {
  $_SESSION['errors'] = $e->getMessage() ."<br>";
  die($e->getMessage());
}

if($successCount > 0){
  $_SESSION['success'] = 'Message successfully sent to ' .$successCount. ' emails!';
  die('success');
}
?>

