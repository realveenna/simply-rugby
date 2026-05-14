<section class="bg-gray-50 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0">
        <div class="<?= cardClassXL() ?>">
          <div class="<?= formPadding() ?>">
            <a href="/" class="flex flex-col items-center justify-center mb-2 text-2xl font-semibold text-gray-900 dark:text-white">
                <img class="w-8 h-8 mr-2" src="/images/logo/main-logo.png" alt="logo">
                 <?= h2(h($data['recommended_squad']). " Player Application Details") ?>
            </a>  
            <div class="flex justify-between gap-2 mb-1 md:flex-col">
                <?= h3('Personal Information') ?>

                <!-- Application Status -->
                <div>
                    <?php if($data['application_status'] === 'applied'): ?>
                        <span class="<?= badgeBlue()?>">
                            <?= h(strtoupper($data['application_status'])) ?>
                        </span>
                    <?php else: ?>
                        <span class="<?= badgeGray()?>">
                            <?= h(strtoupper($data['application_status'])) ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Personal Information -->
            <div>
                <ul class="max-w-md space-y-1 text-body list-inside">
                    <li>
                        Name: <?=h($data['applicant_first_name'] . ' ' . $data['applicant_last_name'])?>
                    </li>
                    <li>
                        Date of Birth <?= $data['applicant_dob']?>
                    </li>
                    <?php if ($data['nickname'] !== '')  :?>
                        <li>
                            Nickname: <?= h($data['nickname'])?>
                        </li>
                    <?php endif ;?>
                    <li>
                        Height: <?= h($data['playerHeight'])?>
                    </li>
                    <li>
                        Weight: <?= h($data['playerWeight'])?>
                    </li>
                </ul>
            </div>

            <!-- Contact Information -->
            <div>
                <?= h4('Contact Details') ?>
                <ul class="max-w-md space-y-1 text-body list-inside">
                    <li>
                        <?=h($data['email'])?>
                    </li>
                    <li>
                        <?=h($data['mobile_num'])?>
                    </li>
                </ul>
            </div>
            
            <!-- Address Information -->
            <div>
                <?= h4('Address') ?>
                <ul class="max-w-md space-y-1 text-body list-inside">
                    <li>
                        <?=h($data['address']['line_1'])?>
                    </li>
                    <li>
                        <?=h($data['address']['line_2'])?>
                    </li>
                    <li>
                        <?=h($data['address']['city'])?>
                    </li>
                    <li>
                        <?=h($data['address']['country'])?>
                    </li>
                </ul>
            </div>

            <!-- Guardian/NOK Information -->
            <div>
                <!-- If Senior display NOK else Guardian -->
                <?= h3($nok .' Details')  ?>
                <ul class="max-w-md space-y-1 text-body list-inside">
                    <li>
                        <?= 'Name: ' .h($primaryGuardian['first_name'] . ' ' .$primaryGuardian['last_name'])?>
                    </li>
                    <li>
                        <?= 'Relationship: '. h($primaryGuardian['relationship'])?>
                    </li>
                    <li>
                        <?= 'Mobile Number: '. h($primaryGuardian['mobile_number'])?>
                    </li>
                    <!-- If Junior display  more guardian details -->
                    <?php if($primaryGuardian['email'] !== null) :?>
                    <li>
                        <?= 'Email: '. h($primaryGuardian['email'])?>
                    </li>
                    <li>
                        Need to Apply Coach: <?= h($primaryGuardian['apply_coach'] === 1 ? 'Yes' : 'No')?>
                    </li>
                    <?php endif;?>
                </ul>
            </div>

            <!-- Display Guarrdian address and secondary guardian details -->
            <?php if($primaryGuardian['address'] !== null) :?>
            <div>
                <?= h4('Primary Guardian Address') ?>
                <ul class="max-w-md space-y-1 text-body list-inside">
                    <li>
                        <?=h($primaryGuardian['address']['line_1'])?>
                    </li>
                    <li>
                        <?=h($primaryGuardian['address']['line_2'])?>
                    </li>
                    <li>
                        <?=h($primaryGuardian['address']['city'])?>
                    </li>
                    <li>
                        <?=h($primaryGuardian['address']['country'])?>
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
                        <?= 'Mobile Number: '. h($secondaryGuardian['mobile_number'])?>
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
                <?php if($currentConditions):?>
                    <?php foreach($currentConditions as $current) :?>
                        <ul class="max-w-md space-y-1 text-body list-disc list-inside">
                            <li>
                                <?= h($current['condition_name'])?>
                            </li>
                        </ul>
                    <?php endforeach ;?>
                <?php else :?>
                    None
                <?php endif ;?>
            </div>

            <!-- Past -->
            <div>
                <?= h4('Past Medical Condition')?>
                <?php if($pastConditions):?>
                    <?php foreach($pastConditions as $past) :?>
                        <ul class="max-w-md space-y-1 text-body list-disc list-inside">
                            <li>
                                <?= h($past['condition_name'])?>
                            </li>
                        </ul>
                    <?php endforeach ;?>
                <?php else :?>
                    None
                <?php endif ;?>
            </div>

            <!-- Allergies -->
            <div>
                <?= h4('Allergies')?>
                <?php if($allergies):?>
                    <?php foreach($allergies as $allergy) :?>
                            None
                        <ul class="max-w-md space-y-1 text-body list-disc list-inside">
                            <li>
                                <?= h($allergy['allergy_name'])?>
                            </li>
                        </ul>
                    <?php endforeach ;?>
                <?php else :?>
                    <ul class="max-w-md space-y-1 text-body list-inside">
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
                <input type="hidden" name="application_id" value="<?= h($data['application_id']) ?>">
                <div class="flex flex-col gap-2">
                    <!-- If decision has been made then hide other button-->
                    <?php if($data['application_status'] === 'applied') :?>
                        <div class="grid gap-2 md:grid-cols-2 ">
                            <!-- Reject -->
                            <button type="submit" name="action" value="reject"
                                class="<?=dangerBtn()?>">
                                Reject
                            </button>
                            
                            <!-- Approve Button -->
                            <button type="submit" name="action" value="approve" 
                                class="<?=primaryBtn()?>">
                                Approve
                            </button>
                        </div>
                    <?php endif;?>
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