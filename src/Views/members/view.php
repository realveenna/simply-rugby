<section class="bg-gray-50 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0">
        <div class="<?= cardClassXLNoBg() ?>">
            <?= titleLeft('Member','Details')?>
            
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
                                            <?=h(($member['first_name'].' '. $member['last_name']))?>
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <!-- Dob -->
                            <?php if(isset($member['dob']) && $member['dob'] !== '0000-00-00'):?>
                                <li class="py-2 sm:py-2">
                                    <div class="flex items-center gap-1">
                                        <div class="flex-1 min-w-0 ms-1">
                                            <p class="font-medium text-heading truncate">
                                                Date of Birth 
                                            </p>
                                            <p class="text-sm text-body truncate">
                                                <?= h($member['dob'])?>
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            <?php endif;?>

                             <!-- Roles -->
                            <?php if($member['roles'] !== null && $member['roles'] !== '') : ?>
                            <li class="py-2 sm:py-2">
                                <div class="flex items-center gap-1">
                                    <div class="flex-1 min-w-0 ms-1">
                                        <p class="font-medium text-heading truncate">
                                            Role Name
                                        </p>
                                        <p class="text-sm text-body truncate">
                                            <?= h($member['roles'])?>
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <?php endif ;?>
                            
                             <!-- Squads -->
                            <?php if($member['squads'] !== null) : ?>
                            <li class="py-2 sm:py-2">
                                <div class="flex items-center gap-1">
                                    <div class="flex-1 min-w-0 ms-1">
                                        <p class="font-medium text-heading truncate">
                                            Squads
                                        </p>
                                        <p class="text-sm text-body truncate">
                                            <?= h($member['squads'])?>
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <?php endif ;?>

                             <!-- Sections -->
                            <?php if($member['sections'] !== null && $member['sections'] !== '') : ?>
                            <li class="py-2 sm:py-2">
                                <div class="flex items-center gap-1">
                                    <div class="flex-1 min-w-0 ms-1">
                                        <p class="font-medium text-heading truncate">
                                            Section Name
                                        </p>
                                        <p class="text-sm text-body truncate">
                                            <?= h($member['sections'])?>
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <?php endif ;?>
                        </ul>
                    </div>
                    
                    <!-- Modify Button -->
                    <div class="flex justify-end">
                        <a href="<?= $updateUrl ?>/update?member_id=<?= $member['member_id'] ?>" class="inline-flex items-center w-auto text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                            Modify
                            <svg class="w-4 h-4 ms-1.5 rtl:rotate-180 -me-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m14 0-4 4m4-4-4-4"/></svg>
                        </a>
                    </div>
                </div>
            </div>

             <!-- Contact Information -->
            <div class="grid gap-2 mb-6 md:grid-cols-2 items-start">
                <!-- Phone and Email -->
                <div class="<?= !empty($address['address_id']) ? '': 'md:col-span-2' ?>">
                    <?= titleLeftSmall('Contact Details')?>
                    <div class="flex flex-col items-center gap-4 mt-3 bg-neutral-primary-soft p-6 border border-default rounded-base shadow-xs md:flex-row">
                        <div class="flex flex-col justify-between md:p-4 leading-normal w-full">
                            <div>
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
                                                        <?=h($member['mobile_num'])?>
                                                    </p>
                                                </div>
                                            </div>
                                        </li>
                                        
                                        <!-- Email Address -->
                                        <?php if ($member['email'] !== null)  :?>
                                        <li class="py-2 sm:py-2">
                                            <div class="flex items-center gap-1">
                                                <div class="flex-1 min-w-0 ms-1">
                                                    <p class="font-medium text-heading truncate">
                                                        Email Address
                                                    </p>
                                                    <p class="text-sm text-body truncate">
                                                        <?=h($member['email'])?>
                                                    </p>
                                                </div>
                                            </div>
                                        </li>
                                        <?php endif ;?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Address -->
                <?php if (!empty($address['address_id']))  :?>
                    <div>
                        <?= titleLeftSmall('Address')?>
                        <div class="flex flex-col items-center gap-4 mt-3 bg-neutral-primary-soft p-6 border border-default rounded-base shadow-xs md:flex-row">
                            <div class="flex flex-col justify-between md:p-4 leading-normal w-full">
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
                                                            <?=h($address['line_1'])?>
                                                        </li>
                                                        <li class="text-sm text-body">
                                                            <?=h($address['line_2'])?>
                                                        </li>
                                                        <li class="text-sm text-body">
                                                            <?=h($address['city'])?>
                                                        </li>
                                                        <li class="text-sm text-body">
                                                            <?=h($address['country'])?>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>     
                        </div>
                        </div>
                    </div>
                <?php endif;?>
            </div>

            <!-- Children  -->
            <?php if(!empty($children)) : ?>
            <?= titleLeft('Children','Details')?>
                <div class="flex-col flex gap-6">
                <?php foreach ($children as $player) :?>
                    <!-- Children Information -->
                    <div class="flex flex-col gap items-center gap-4 bg-neutral-primary-soft p-6 border border-default rounded-base shadow-xs md:flex-row">
                        <!-- Image -->
                        <?php if ($player['section_id'] === 3 ): ?>
                            <img class="object-cover min-w-[300px] w-full rounded-base h-64 md:h-auto md:w-48 mb-4 md:mb-0" 
                                src="https://images.unsplash.com/photo-1581403341630-a6e0b9d2d257?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" 
                                alt="player-image">
                        <?php endif; ?>

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
                                    <?php if ($player['dob'] !== null)  :?>
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
                                    <?php endif ;?>

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
                            <!-- Modify Button -->
                            <div class="flex justify-end">
                                <a href="members/update?member_id=<?= $player['member_id'] ?>" class="inline-flex items-center w-auto text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                                    Modify
                                    <svg class="w-4 h-4 ms-1.5 rtl:rotate-180 -me-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m14 0-4 4m4-4-4-4"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach;?>
                </div>
            <?php endif;?>
  
            <form method="post" class="mt-6">
                <input type="hidden" name="member_id" value="<?= h($member['member_id']) ?>">
                <div class="flex flex-col gap-2">
                    <div class="grid gap-2 md:grid-cols-2 ">
                        <!-- Delete -->
                        <button type="submit" name="action" value="delete"
                            class="<?=dangerBtn()?>">
                            Delete Member Details
                        </button>   
                        
                        <!-- Edit Button -->
                        <a href="/members/update?member_id=<?= $member['member_id'] ?>"
                            class="<?=primaryBtn()?> text-center">
                            Edit
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

