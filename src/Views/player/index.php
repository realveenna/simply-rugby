<section class="bg-gray-50 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0">
        <div class="<?= cardClassXL() ?>">
          <div class="<?= formPadding() ?>">
            <a href="/" class="flex flex-col items-center justify-center mb-2 text-2xl font-semibold text-gray-900 dark:text-white">
                <img class="w-8 h-8 mr-2" src="/images/logo/main-logo.png" alt="logo">
            </a>  
            <!-- Personal Information -->
            <div>
                <?= h2(ucwords((h($player['first_name'] .' '. h($player['last_name']))))) ?>
            </div>
            <div class="flex justify-between gap-2 mb-1 md:flex-col">
                <?= h3('Personal Information') ?>
            </div>
            <div>
                <ul class="max-w-md space-y-1 text-body list-inside">
                    <li>
                        Date of Birth <?= $player['dob']?>
                    </li>
                    <?php if ($player['nickname'] !== '')  :?>
                        <li>
                            Nickname: <?= h($player['nickname'])?>
                        </li>
                    <?php endif ;?>
                    <li>
                        Height: <?= h($player['height'])?>
                    </li>
                    <li>
                        Weight: <?= h($player['weight'])?>
                    </li>
                </ul>
            </div>

            <!-- Contact Information -->
            <div>
                <?= h4('Contact Details') ?>
                <ul class="max-w-md space-y-1 text-body list-inside">
                    <li>
                        <?=h($player['email'])?>
                    </li>
                    <li>
                        <?=h($player['mobile_num'])?>
                    </li>
                </ul>
            </div>
            
            <!-- Address Information -->
            <div>
                <?= h4('Address') ?>
                <ul class="max-w-md space-y-1 text-body list-inside">
                    <li>
                        <?=h($player['address']['line_1'])?>
                    </li>
                    <li>
                        <?=h($player['address']['line_2'])?>
                    </li>
                    <li>
                        <?=h($player['address']['city'])?>
                    </li>
                    <li>
                        <?=h($player['address']['country'])?>
                    </li>
                </ul>
            </div>

            <!-- Guardian/NOK Information -->
            <div>
                <!-- If Senior display NOK else Guardian -->
                <?= h3($nok .' Details')  ?>
                <ul class="max-w-md space-y-1 text-body list-inside">
                    <li>
                        <?= 'Name: ' .h($pGuardian['first_name'] . ' ' .$pGuardian['last_name'])?>
                    </li>
                    <li>
                        <?= 'Relationship: '. h($pGuardian['relationship'])?>
                    </li>
                    <li>
                        <?= 'Mobile Number: '. h($pGuardian['mobile_num'])?>
                    </li>
                    <!-- If Junior display  more guardian details -->
                    <?php if($pGuardian['email'] !== null) :?>
                    <li>
                        <?= 'Email: '. h($pGuardian['email'])?>
                    </li>
                    <?php endif;?>
                </ul>
            </div>

            <!-- Display Guarrdian address and secondary guardian details -->
            <?php if($pGuardian['address'] !== null) :?>
            <div>
                <?= h4('Primary Guardian Address') ?>
                <ul class="max-w-md space-y-1 text-body list-inside">
                    <li>
                        <?=h($pGuardian['address']['line_1'])?>
                    </li>
                    <li>
                        <?=h($pGuardian['address']['line_2'])?>
                    </li>
                    <li>
                        <?=h($pGuardian['address']['city'])?>
                    </li>
                    <li>
                        <?=h($pGuardian['address']['country'])?>
                    </li>
                </ul>
            </div>

            <div>
                <?= h3('Secondary Guardian Details')  ?>
                <ul class="max-w-md space-y-1 text-body list-inside">
                    <li>
                        <?= 'Name: ' .h($secondaryGuardian['first_name'] . ' ' .$secondaryGuardian['last_name'])?>
                    </li>
                    <li>
                        <?= 'Relationship: '. h($secondaryGuardian['relationship'])?>
                    </li>
                    <li>
                        <?= 'Mobile Number: '. h($secondaryGuardian['mobile_num'])?>
                    </li>
                </ul>
            </div>
             <div>
                <?= h4('Secondary Guardian Address') ?>
                <ul class="max-w-md space-y-1 text-body list-inside">
                    <li>
                        <?=h($secondaryGuardian['address']['line_1'])?>
                    </li>
                    <li>
                        <?=h($secondaryGuardian['address']['line_2'])?>
                    </li>
                    <li>
                        <?=h($secondaryGuardian['address']['city'])?>
                    </li>
                    <li>
                        <?=h($secondaryGuardian['address']['country'])?>
                    </li>
                </ul>
            </div>
            <?php endif ;?>
            <!-- Medical/ Health Information -->
            <?= h3('Medical Information')?>
            <div>
                <!-- Current -->
                <?= h4('Current Medical Condition')?>
                <?php if($current):?>
                    <?php foreach($current as $c) :?>
                        <ul class="max-w-md space-y-1 text-body list-disc list-inside">
                            <li>
                                <?= h($c['condition_name'])?>
                            </li>
                        </ul>
                    <?php endforeach ;?>
                <?php else :?>
                    <ul class="max-w-md space-y-1 text-body list-disc list-inside">
                        <li>
                            None
                        </li>
                    </ul>
                <?php endif ;?>
            </div>

            <!-- Past -->
            <div>
                <?= h4('Past Medical Condition')?>
                <?php if($past):?>
                    <?php foreach($past as $p) :?>
                        <ul class="max-w-md space-y-1 text-body list-disc list-inside">
                            <li>
                                <?= h($p['condition_name'])?>
                            </li>
                        </ul>
                    <?php endforeach ;?>
                <?php else :?>
                    <ul class="max-w-md space-y-1 text-body list-disc list-inside">
                        <li>
                            None
                        </li>
                    </ul>
                <?php endif ;?>
            </div>

            <!-- Allergies -->
            <div>
                <?= h4('Allergies')?>
                <?php if($allergies):?>
                    <?php foreach($allergies as $allergy) :?>
                        <ul class="max-w-md space-y-1 text-body list-disc list-inside">
                            <li>
                                <?= h($allergy['allergy_name'])?>
                            </li>
                        </ul>
                    <?php endforeach ;?>
                <?php else :?>
                    <ul class="max-w-md space-y-1 text-body list-disc list-inside">
                        <li>
                            None
                        </li>
                    </ul>
                <?php endif ;?>

            </div>

            <!-- Display Doctor Information-->
             <div>
                <?= h3('Doctor/GP Details')  ?>
                <ul class="max-w-md space-y-1 text-body list-inside">
                    <li>
                        <?= 'Name: ' .h($doctor['doctor_name'])?>
                    </li>
                    <li>
                        <?= 'Telephone: '. h($doctor['doctor_tel'])?>
                    </li>
                </ul>
            </div>

            <!-- Doctor Address -->
            <div>
                <?= h4('Address') ?>
                <ul class="max-w-md space-y-1 text-body list-inside">
                    <li>
                        <?=h($doctor['address']['line_1'])?>
                    </li>
                    <li>
                        <?=h($doctor['address']['line_2'])?>
                    </li>
                    <li>
                        <?=h($doctor['address']['city'])?>
                    </li>
                    <li>
                        <?=h($doctor['address']['country'])?>
                    </li>
                </ul>
            </div>
            <form method="post">
                <input type="hidden" name="application_id" value="<?= h($player['member_id']) ?>">
                <div class="flex flex-col gap-2">
                    <div class="grid gap-2 md:grid-cols-2 ">
                        <!-- Reject -->
                        <button type="submit" name="action" value="remove"
                            class="<?=dangerBtn()?>">
                            Remove Player
                        </button>
                        
                        <!-- Approve Button -->
                        <button type="submit" name="action" value="edit" 
                            class="<?=primaryBtn()?>">
                            Edit
                        </button>
                    </div>
                    <!-- Back Button -->
                    <button type="submit" name="action" value="back"
                        class="<?=secondaryBtn()?>">
                        Back
                    </button>
                </div>
            </form>
        </div>
      </div>
    </div>
</section>

