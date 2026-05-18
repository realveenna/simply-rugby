<section class="bg-gray-50 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0">
        <div class="<?= cardClassXL() ?>">
          <div class="<?= formPadding() ?>">
            <!-- Logo -->
            <a href="/" class="flex flex-col items-center justify-center mb-2 text-2xl font-semibold text-gray-900 dark:text-white">
                <img class="w-8 h-8 mr-2" src="/images/logo/main-logo.png" alt="logo">
            </a> 

            <?= titleLeft('Player','Details')?>
             
            <!-- Personal Information -->

            <div class="flex flex-col items-center gap-4 bg-neutral-primary-soft p-6 border border-default rounded-base shadow-xs md:flex-row">
                <!-- Image -->
                <img class="object-cover w-full rounded-base h-64 md:h-auto md:w-48 mb-4 md:mb-0" 
                     src="https://images.unsplash.com/photo-1581403341630-a6e0b9d2d257?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" 
                    alt="">
                <div class="flex flex-col justify-between md:p-4 leading-normal w-full">
                    <h5 class="<?= heading5()?>">Personal Information</h5>
                     <div class="flow-root">
                        <ul role="list" class="divide-y divide-default">
                            <!-- Name -->
                            <li class="py-2 sm:py-2">
                                <div class="flex items-center gap-1">
                                    <div class="flex-1 min-w-0 ms-1">
                                        <p class="font-medium text-heading truncate">
                                            Full Name
                                        </p>
                                        <p class="text-sm text-body truncate">
                                            <?=h(($player['first_name'].' '. $player['last_name']))?>
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <!-- Dob -->
                            <li class="py-2 sm:py-2">
                                <div class="flex items-center gap-1">
                                    <div class="flex-1 min-w-0 ms-1">
                                        <p class="font-medium text-heading truncate">
                                            Date of Birth 
                                        </p>
                                        <p class="text-sm text-body truncate">
                                            <?= h($player['dob'])?>
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <!-- Nickname -->
                            <?php if ($player['nickname'] !== null)  :?>
                            <li class="py-2 sm:py-2">
                                <div class="flex items-center gap-1">
                                    <div class="flex-1 min-w-0 ms-1">
                                        <p class="font-medium text-heading truncate">
                                            Nickname
                                        </p>
                                        <p class="text-sm text-body truncate">
                                            <?= h($player['nickname'])?>
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <?php endif ;?>
                            <li class="py-2 sm:py-2">
                                <div class="flex items-center gap-1">
                                    <div class="flex-1 min-w-0 ms-1">
                                        <p class="font-medium text-heading truncate">
                                            Height
                                        </p>
                                        <p class="text-sm text-body truncate">
                                            <?= h($player['height'])?>
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <li class="py-2 sm:py-2">
                                <div class="flex items-center gap-1">
                                    <div class="flex-1 min-w-0 ms-1">
                                        <p class="font-medium text-heading truncate">
                                            Weight
                                        </p>
                                        <p class="text-sm text-body truncate">
                                            <?= h($player['weight'])?>
                                        </p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" class="inline-flex items-center w-auto text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                            Read more
                            <svg class="w-4 h-4 ms-1.5 rtl:rotate-180 -me-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m14 0-4 4m4-4-4-4"/></svg>
                        </button>
                    </div>
                </div>
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

