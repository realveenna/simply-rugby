<section class="bg-gray-50 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0">
        <div class="<?= cardClassXLNoBg() ?>">
            <!-- Logo -->
            <a href="/" class="flex flex-col items-center justify-center mb-2 text-2xl font-semibold text-gray-900 dark:text-white">
                <img class="w-8 h-8 mr-2" src="/images/logo/main-logo.png" alt="logo">
            </a> 

            <?= titleLeft('Player','Details')?>
             
            <!-- Personal Information -->
            <div class="flex flex-col items-center gap-4 bg-neutral-primary-soft p-6 border border-default rounded-base shadow-xs md:flex-row">
                <!-- Image -->
                <img class="object-cover min-w-[300px] w-full rounded-base h-64 md:h-auto md:w-48 mb-4 md:mb-0" 
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
                            <!-- Position -->
                            <?php if($player['position'] !== null && $player['position'] !== '') : ?>
                            <li class="py-2 sm:py-2">
                                <div class="flex items-center gap-1">
                                    <div class="flex-1 min-w-0 ms-1">
                                        <p class="font-medium text-heading truncate">
                                            Position
                                        </p>
                                        <p class="text-sm text-body truncate">
                                            <?= h($player['position'])?>
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <?php endif ;?>
                            <!-- Height -->
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
                    <form method ="post" action="">
                        <input type="hidden" name="member_id" value="<?=h($player['member_id'])?>">
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center w-auto text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                                Modify
                                <svg class="w-4 h-4 ms-1.5 rtl:rotate-180 -me-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m14 0-4 4m4-4-4-4"/></svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <?= titleLeftSmall('Contact & Address')?>
            
            <!-- Contact Information -->
            <div class="flex flex-col items-center gap-4 mt-3 bg-neutral-primary-soft p-6 border border-default rounded-base shadow-xs md:flex-row">
                <div class="flex flex-col justify-between md:p-4 leading-normal w-full">
                    <div class="grid gap-2 mb-6 md:grid-cols-2 items-start">
                        <div>
                            <!-- Phone and Email -->
                            <h5 class="<?= heading5()?> ">Contact Details </h5>
                            <div class="flow-root">
                                <ul role="list" class="divide-y divide-default">
                                    <!-- Phone Number -->
                                    <li class="py-2 sm:py-2">
                                        <div class="flex items-center gap-1">
                                            <div class="flex-1 min-w-0 ms-1">
                                                <p class="font-medium text-heading truncate">
                                                    Phone Number
                                                </p>
                                                <p class="text-sm text-body truncate">
                                                    <?=h($player['mobile_num'])?>
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                    
                                    <!-- Email Address -->
                                    <?php if ($player['email'] !== null)  :?>
                                    <li class="py-2 sm:py-2">
                                        <div class="flex items-center gap-1">
                                            <div class="flex-1 min-w-0 ms-1">
                                                <p class="font-medium text-heading truncate">
                                                    Email Address
                                                </p>
                                                <p class="text-sm text-body truncate">
                                                    <?=h($player['email'])?>
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                    <?php endif ;?>
                                </ul>
                            </div>
                        </div>
                        <div>
                            <!-- Address -->
                            <h5 class="<?= heading5()?>">Address Information </h5>
                            <div class="flow-root">
                                <ul role="list" class="divide-y divide-default">
                                    <li class="py-2 sm:py-2">
                                        <div class="flex items-center gap-1">
                                            <div class="flex-1 min-w-0 ms-1">
                                                <ul class="max-w-md space-y-1 text-body list-inside">
                                                    <li class="text-sm text-body">
                                                        <?=h($player['address']['line_1'])?>
                                                    </li>
                                                    <li class="text-sm text-body">
                                                        <?=h($player['address']['line_2'])?>
                                                    </li>
                                                    <li class="text-sm text-body">
                                                        <?=h($player['address']['city'])?>
                                                    </li>
                                                    <li class="text-sm text-body">
                                                        <?=h($player['address']['country'])?>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                    
                                    <!-- Email Address -->
                                    <?php if ($player['email'] !== null)  :?>
                                    <li class="py-2 sm:py-2">
                                        <div class="flex items-center gap-1">
                                            <div class="flex-1 min-w-0 ms-1">
                                                <p class="font-medium text-heading truncate">
                                                    Email Address
                                                </p>
                                                <p class="text-sm text-body truncate">
                                                    <?=h($player['email'])?>
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                    <?php endif ;?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <form method ="post" action="">
                        <input type="hidden" name="member_id" value="<?=h($player['member_id'])?>">              
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center w-auto text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                                Modify
                                <svg class="w-4 h-4 ms-1.5 rtl:rotate-180 -me-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m14 0-4 4m4-4-4-4"/></svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <?= titleLeftSmall('Emergency Contact Details')?>
            <!-- Primary Guardian/NOK Information -->
            <div class="flex flex-col items-center gap-4 mt-4 bg-neutral-primary-soft p-6 border border-default rounded-base shadow-xs md:flex-row">
                <div class="flex flex-col justify-between md:p-4 leading-normal w-full">
                    <div class="grid gap-2 mb-6 md:grid-cols-2 items-start">
                        <div class="<?= $pGuardian['address'] === null ? 'md:col-span-2' : '' ?>">
                            <h5 class="<?= heading5()?>"><?= $nok ?></h5>
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
                                                    <?= h($pGuardian['first_name'] . ' ' .$pGuardian['last_name'])?>
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                    <!-- Relationship -->
                                    <li class="py-2 sm:py-2">
                                        <div class="flex items-center gap-1">
                                            <div class="flex-1 min-w-0 ms-1">
                                                <p class="font-medium text-heading truncate">
                                                    Relationship
                                                </p>
                                                <p class="text-sm text-body truncate">
                                                    <?= h($pGuardian['relationship'])?>
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                    <!-- Contact Number -->
                                    <li class="py-2 sm:py-2">
                                        <div class="flex items-center gap-1">
                                            <div class="flex-1 min-w-0 ms-1">
                                                <p class="font-medium text-heading truncate">
                                                    Phone Number
                                                </p>
                                                <p class="text-sm text-body truncate">
                                                    <?= h($pGuardian['mobile_num'])?>
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                    <!-- Nickname -->
                                    <?php if ($pGuardian['email'] !== null)  :?>
                                    <li class="py-2 sm:py-2">
                                        <div class="flex items-center gap-1">
                                            <div class="flex-1 min-w-0 ms-1">
                                                <p class="font-medium text-heading truncate">
                                                    Email Address
                                                </p>
                                                <p class="text-sm text-body truncate">
                                                    <?= h($pGuardian['email'])?>
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                    <?php endif ;?>
                                </ul>
                            </div>
                        </div>
                        <?php if($pGuardian['address'] !== null) :?>
                            <div>
                                <!-- Address -->
                                <h5 class="<?= heading5()?>">Address Information </h5>
                                <div class="flow-root">
                                    <ul role="list" class="divide-y divide-default">
                                        <li class="py-2 sm:py-2">
                                            <div class="flex items-center gap-1">
                                                <div class="flex-1 min-w-0 ms-1">
                                                    <ul class="max-w-md space-y-1 text-body list-inside">
                                                        <li class="text-sm text-body">
                                                            <?=h($pGuardian['address']['line_1'])?>
                                                        </li>
                                                        <li class="text-sm text-body">
                                                            <?=h($pGuardian['address']['line_2'])?>
                                                        </li>
                                                        <li class="text-sm text-body">
                                                            <?=h($pGuardian['address']['city'])?>
                                                        </li>
                                                        <li class="text-sm text-body">
                                                            <?=h($pGuardian['address']['country'])?>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </li>
                                        
                                        <!-- Email Address -->
                                        <?php if ($player['email'] !== null)  :?>
                                        <li class="py-2 sm:py-2">
                                            <div class="flex items-center gap-1">
                                                <div class="flex-1 min-w-0 ms-1">
                                                    <p class="font-medium text-heading truncate">
                                                        Email Address
                                                    </p>
                                                    <p class="text-sm text-body truncate">
                                                        <?=h($player['email'])?>
                                                    </p>
                                                </div>
                                            </div>
                                        </li>
                                        <?php endif ;?>
                                    </ul>
                                </div>
                            </div>
                        <?php endif ;?>
                    </div>
                    <form method ="post" action="">
                        <input type="hidden" name="member_id" value="<?=h($player['member_id'])?>">
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center w-auto text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                                Modify
                                <svg class="w-4 h-4 ms-1.5 rtl:rotate-180 -me-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m14 0-4 4m4-4-4-4"/></svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <?php if ($sGuardian): ?>
                <!-- Secondary guardian details -->
                <div class="flex flex-col items-center gap-4 mt-4 bg-neutral-primary-soft p-6 border border-default rounded-base shadow-xs md:flex-row">
                    <div class="flex flex-col justify-between md:p-4 leading-normal w-full">
                        <div class="grid gap-2 mb-6 md:grid-cols-2 items-start">
                            <div class="<?= $sGuardian['address'] === null ? 'md:col-span-2' : '' ?>">
                                <h5 class="<?= heading5()?>">Secondary Guardian</h5>
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
                                                        <?= h($sGuardian['first_name'] . ' ' .$sGuardian['last_name'])?>
                                                    </p>
                                                </div>
                                            </div>
                                        </li>
                                        <!-- Relationship -->
                                        <li class="py-2 sm:py-2">
                                            <div class="flex items-center gap-1">
                                                <div class="flex-1 min-w-0 ms-1">
                                                    <p class="font-medium text-heading truncate">
                                                        Relationship
                                                    </p>
                                                    <p class="text-sm text-body truncate">
                                                        <?= h($sGuardian['relationship'])?>
                                                    </p>
                                                </div>
                                            </div>
                                        </li>
                                        <!-- Contact Number -->
                                        <li class="py-2 sm:py-2">
                                            <div class="flex items-center gap-1">
                                                <div class="flex-1 min-w-0 ms-1">
                                                    <p class="font-medium text-heading truncate">
                                                        Phone Number
                                                    </p>
                                                    <p class="text-sm text-body truncate">
                                                        <?= h($sGuardian['mobile_num'])?>
                                                    </p>
                                                </div>
                                            </div>
                                        </li>
                                        <!-- Email -->
                                        <?php if ($sGuardian['email'] !== null)  :?>
                                        <li class="py-2 sm:py-2">
                                            <div class="flex items-center gap-1">
                                                <div class="flex-1 min-w-0 ms-1">
                                                    <p class="font-medium text-heading truncate">
                                                        Email Address
                                                    </p>
                                                    <p class="text-sm text-body truncate">
                                                        <?= h($sGuardian['email'])?>
                                                    </p>
                                                </div>
                                            </div>
                                        </li>
                                        <?php endif ;?>
                                    </ul>
                                </div>
                            </div>
                            <?php if($sGuardian['address'] !== null) :?>
                                <div>
                                    <!-- Address -->
                                    <h5 class="<?= heading5()?>">Address Information </h5>
                                    <div class="flow-root">
                                        <ul role="list" class="divide-y divide-default">
                                            <li class="py-2 sm:py-2">
                                                <div class="flex items-center gap-1">
                                                    <div class="flex-1 min-w-0 ms-1">
                                                        <ul class="max-w-md space-y-1 text-body list-inside">
                                                            <li class="text-sm text-body">
                                                                <?=h($sGuardian['address']['line_1'])?>
                                                            </li>
                                                            <li class="text-sm text-body">
                                                                <?=h($sGuardian['address']['line_2'])?>
                                                            </li>
                                                            <li class="text-sm text-body">
                                                                <?=h($sGuardian['address']['city'])?>
                                                            </li>
                                                            <li class="text-sm text-body">
                                                                <?=h($sGuardian['address']['country'])?>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            <?php endif ;?>
                        </div>
                        <form method ="post" action="">
                            <input type="hidden" name="member_id" value="<?=h($player['member_id'])?>">
                            <div class="flex justify-end">
                                <button type="submit" class="inline-flex items-center w-auto text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                                    Modify
                                    <svg class="w-4 h-4 ms-1.5 rtl:rotate-180 -me-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m14 0-4 4m4-4-4-4"/></svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Medical Information -->
            <?= titleLeftSmall('Medical Information')?>
            <div class="flex flex-col items-center gap-4 mt-4 bg-neutral-primary-soft p-6 border border-default rounded-base shadow-xs md:flex-row">
                <div class="flex flex-col justify-between md:p-4 leading-normal w-full">
                    <!-- Current Medical Condition -->
                    <h5 class="<?= heading5()?>">Current Medical Condition </h5>
                    <!-- Current Condition  -->
                    <div class="flex-1 min-w-0 ms-1">
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

                    <!-- Past Medical Condition -->
                    <h5 class="<?= heading5()?> mt-4">Past Medical Condition </h5>
                    <div class="flex-1 min-w-0 ms-1">
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
                    <h5 class="<?= heading5()?> mt-4">Allergies </h5>
                    <div class="flex-1 min-w-0 ms-1">
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
                </div>
            </div>

            <?= titleLeftSmall('Doctor Information')?>
             <!-- Doctor Information -->
            <div class="flex flex-col items-center gap-4 mt-4 bg-neutral-primary-soft p-6 border border-default rounded-base shadow-xs md:flex-row">
                <div class="flex flex-col justify-between md:p-4 leading-normal w-full">
                    <div class="grid gap-2 mb-6 md:grid-cols-2 items-start">
                        <div class="<?= $doctor['address'] === null ? 'md:col-span-2' : '' ?>">
                            <h5 class="<?= heading5()?>">Doctor</h5>
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
                                                    <?= h($doctor['doctor_name'])?>
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                  
                                    <!-- Contact Number -->
                                    <li class="py-2 sm:py-2">
                                        <div class="flex items-center gap-1">
                                            <div class="flex-1 min-w-0 ms-1">
                                                <p class="font-medium text-heading truncate">
                                                    Telephone Number
                                                </p>
                                                <p class="text-sm text-body truncate">
                                                    <?= h($doctor['doctor_tel'])?>
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <?php if($doctor['address'] !== null) :?>
                            <div>
                                <!-- Address -->
                                <h5 class="<?= heading5()?>">Address Information </h5>
                                <div class="flow-root">
                                    <ul role="list" class="divide-y divide-default">
                                        <li class="py-2 sm:py-2">
                                            <div class="flex items-center gap-1">
                                                <div class="flex-1 min-w-0 ms-1">
                                                    <ul class="max-w-md space-y-1 text-body list-inside">
                                                        <li class="text-sm text-body">
                                                            <?=h($doctor['address']['line_1'])?>
                                                        </li>
                                                        <li class="text-sm text-body">
                                                            <?=h($doctor['address']['line_2'])?>
                                                        </li>
                                                        <li class="text-sm text-body">
                                                            <?=h($doctor['address']['city'])?>
                                                        </li>
                                                        <li class="text-sm text-body">
                                                            <?=h($doctor['address']['country'])?>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        <?php endif ;?>
                    </div>
                    <form method ="post" action="">
                        <input type="hidden" name="member_id" value="<?=h($player['member_id'])?>">
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center w-auto text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                                Modify
                                <svg class="w-4 h-4 ms-1.5 rtl:rotate-180 -me-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m14 0-4 4m4-4-4-4"/></svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
      
            <form method="post" class="mt-6">
                <input type="hidden" name="application_id" value="<?= h($player['member_id']) ?>">
                <div class="flex flex-col gap-2">
                    <div class="grid gap-2 md:grid-cols-2 ">
                        <!-- Reject -->
                        <button type="submit" name="action" value="remove"
                            class="<?=dangerBtn()?>">
                            Delete Player Details
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
</section>

