<section class="bg-gray-50 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0">
        <div class="<?= cardClassXLNoBg() ?>">
            <!-- Logo -->
            <a href="/" class="flex flex-col items-center justify-center mb-2 text-2xl font-semibold text-gray-900 dark:text-white">
                <img class="w-8 h-8 mr-2" src="/images/logo/main-logo.png" alt="logo">
            </a> 

            <?= titleLeft('Member','Details')?>
             
            <!-- Personal Information -->
            <form method="post" class="mt-6">
                <input type="hidden" name="member_id" value="<?= $member->member_id ?? '' ?>">
                <div class="flex flex-col items-center gap-4 bg-neutral-primary-soft p-6 border border-default rounded-base shadow-xs md:flex-row">
                    <!-- Image -->
                    <?php if (empty($player) || $player['section_id'] === 3): ?>
                        <img class="object-cover min-w-[300px] w-full rounded-base h-64 md:h-auto md:w-48 mb-4 md:mb-0" 
                            src="https://images.unsplash.com/photo-1581403341630-a6e0b9d2d257?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" 
                            alt="player-image">
                    <?php endif; ?>
                    <div class="flex flex-col justify-between md:p-4 leading-normal w-full">
                        <h5 class="<?= heading5()?>">Personal Information</h5>       

                        <div class="flow-root">
                            <ul role="list">
                                <!-- Name -->
                                <li class="py-2 sm:py-2">
                                    <div class="flex items-center gap-1">
                                        <div class="flex-1 min-w-0 ms-1">
                                            <div class="grid gap-2 md:grid-cols-2 items-start">
                                                <div>
                                                    <label for="first_name" class="<?= labelClass() ?>">First Name</label>
                                                    <input type="text" name="first_name" 
                                                        value="<?=  h($member->first_name ?? '');?>"
                                                        class="<?= inputClass() ?>" placeholder="First Name" required>
                                                    <div>
                                                        <p class="mt-2 text-sm font-xs text-red-500"><?= $error['first_name'] ?? '' ?></p>
                                                    </div>
                                                </div>
                                                <div>
                                                    <label for="last_name" class="<?= labelClass() ?>">Last Name</label>
                                                    <input type="text" name="last_name" 
                                                        value="<?=  h($member->last_name ?? '');?>"
                                                        class="<?= inputClass() ?>" placeholder="Last Name" required>
                                                    <div>
                                                        <p class="mt-2 text-sm font-xs text-red-500"><?= $error['last_name'] ?? '' ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <!-- Dob -->
                                <?php if (!empty($member->dob)):?>
                                    <li class="py-2 sm:py-2">
                                        <div class="flex items-center gap-1">
                                            <div class="flex-1 min-w-0 ms-1">
                                                <div>
                                                    <label for="dob" class="<?= labelClass() ?>">Date of Birth</label>
                                                    <div class="relative">
                                                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                                            <svg class="w-4 h-4 text-body" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 10h16m-8-3V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Zm3-7h.01v.01H8V13Zm4 0h.01v.01H12V13Zm4 0h.01v.01H16V13Zm-8 4h.01v.01H8V17Zm4 0h.01v.01H12V17Zm4 0h.01v.01H16V17Z"/></svg>
                                                        </div>
                                                        <input datepicker-format="yyyy-mm-dd" name="dob" type="text" value="<?= h($member->dob ?? '');?>"
                                                        class="block w-full ps-9 pe-3 py-2.5 <?= inputClass()?>" placeholder="Select date">
                                                    </div>
                                                    <div>
                                                        <p class="<?= smallError() ?>"><?= $error['dob'] ?? '';?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                <?php endif;?>

                                <!-- Roles -->
                                <?php if(!empty($data['roles'])) : ?>
                                <li class="py-2 sm:py-2">
                                    <div class="flex items-center gap-1">
                                        <div class="flex-1 min-w-0 ms-1">
                                            <p class="font-medium text-heading truncate">
                                                Role Name
                                            </p>
                                            <p class="text-sm text-body truncate">
                                                <?= h($data['roles'])?>
                                            </p>
                                        </div>
                                    </div>
                                </li>
                                <?php endif ;?>

                                <!-- Squads -->
                                <?php if(!empty($data['squads'])) : ?>
                                <li class="py-2 sm:py-2">
                                    <div class="flex items-center gap-1">
                                        <div class="flex-1 min-w-0 ms-1">
                                            <p class="font-medium text-heading truncate">
                                                Squads
                                            </p>
                                            <p class="text-sm text-body truncate">
                                                <?= h($data['squads'])?>
                                            </p>
                                        </div>
                                    </div>
                                </li>
                                <?php endif ;?>

                                <!-- Sections -->
                                <?php if(!empty($data['sections'])) : ?>
                                <li class="py-2 sm:py-2">
                                    <div class="flex items-center gap-1">
                                        <div class="flex-1 min-w-0 ms-1">
                                            <p class="font-medium text-heading truncate">
                                                Section
                                            </p>
                                            <p class="text-sm text-body truncate">
                                                <?= h($data['sections'])?>
                                            </p>
                                        </div>
                                    </div>
                                </li>
                                <?php endif ;?>
                            </ul>
                        </div>
                        
                        <!-- If can update member, has player access or own account -->
                        <?php if (hasPermission('update_member') || 
                                  hasPlayerAccess($member->member_id) || 
                                  (int)$member->member_id == (int)$_SESSION['user']['member_id']): ?>
                            <!-- Modify Button -->
                            <div class="flex justify-end">
                                <button type="submit" name="edit" value="personal_details"
                                    class="<?= primaryBtn() ?>">
                                    Confirm Details
                                </button>
                            </div>
                        <?php endif ;?>
                    </div>
                </div>
            </form>


            <!-- Player Information -->
            <?php if ((isset($player)) && !empty($player)):?>
                <?= titleLeftSmall('Player Profile')?>
                <form method="post" class="mt-6">
                    <input type="hidden" name="member_id" value="<?= $member->member_id ?? '' ?>">
                    <div class="flex flex-col items-center gap-4 bg-neutral-primary-soft p-6 border border-default rounded-base shadow-xs md:flex-row">
                            <div class="flex flex-col justify-between md:p-4 leading-normal w-full">
                            <h5 class="<?= heading5()?>">Player Details</h5>    
                            <div class="grid gap-2 mb-6 md:grid-cols-2 pb-3">
                                <!-- Availability Status -->
                                <div>
                                    <label for="player_availability_status" class="block mb-2 text-sm font-small text-gray-900 dark:text-white">Availability Status </label>
                                    <select class="<?= inputClass()?>"
                                        autocomplete="player_availability_status" name="player_availability_status">
                                        <option value="" disabled selected> Select Availability Status:</option>
                                        <option value="Available" class="text-brand"
                                            <?= $player['player_availability_status'] === 'Available' ? 'selected' : ''?>>
                                                Available
                                        </option>
                                        <option value="Unavailable" class="text-danger"
                                            <?= $player['player_availability_status'] === 'Unavailable' ? 'selected' : ''?>>
                                                Unavailable
                                        </option>
                                    </select>
                                        <p class="mt-2 text-sm font-xs text-red-500"><?php echo $error['player_availability_status'] ?? '';?></p>
                                </div>
                            
                                <!-- Position -->
                                <div> 
                                    <!-- Select player for position -->
                                    <label for="position" class="block mb-2 text-sm font-small text-gray-900 dark:text-white">Position </label>
                                    <select class="<?= inputClass()?>" name="position">
                                        <option value="" disabled selected> 
                                            Select a Position: 
                                        </option>
                                        
                                        <!-- Position dropdown -->
                                        <?php foreach ($positions as $position): ?>
                                            <option value="<?= $position ?>"
                                                <?= (($player['position'] ?? '') === $position) ? 'selected' : '' ?>>
                                                <?= h($position) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <!-- Position error -->
                                    <p class="<?= smallError() ?>">
                                        <?php echo h($errors['position'] ?? '');?>
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Height and Weight -->
                            <div class="grid gap-2 mb-6 md:grid-cols-2">
                                <div>
                                    <label for="height" class="<?= labelClass() ?>">Height</label>
                                    <input type="number" name="height" 
                                        value="<?= h($player['height']) ?? '';?>"
                                        class="<?= inputClass()?>" placeholder="Enter in cm">
                                    <div>
                                        <p class="<?= smallError() ?>"><?= $error['height'] ?? '';?></p>
                                    </div>
                                </div>
                                <div>
                                    <label for="weight" class="<?= labelClass() ?>">Weight</label>
                                    <input type="number" name="weight"
                                        value="<?= h($player['weight']) ?? '';?>"
                                        class="<?= inputClass()?>" placeholder="Enter in kg">
                                    <div>
                                        <p class="<?= smallError() ?>"><?= $error['weight'] ?? '';?></p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Modify Button -->
                            <div class="flex justify-end">
                                <button type="submit" name="edit" value="player_details"
                                    class="<?= primaryBtn() ?>">
                                    Confirm Details
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            <?php endif;?>


            <?= titleLeftSmall('Contact and Address')?>
            <!-- Contact Information -->
            <!-- Phone and Email -->
            <form method="post" class="mt-6">
                <input type="hidden" name="member_id" value="<?= (int)$member->member_id ?? '' ?>">
                <div class="<?= (!empty($address['address_id'])) ? '': 'md:col-span-2' ?>">
                    <div class="flex flex-col items-start gap-4 mt-3 bg-neutral-primary-soft p-6 border border-default rounded-base shadow-xs md:flex-row h-full">
                        <div class="flex flex-col justify-between md:p-4 leading-normal w-full h-full">
                            <div>
                                <h5 class="<?= heading5()?>">Contact Details </h5>

                                <div class="flow-root">
                                    <ul role="list">
                                        <!-- Phone Number -->
                                        <li class="py-2 sm:py-2">
                                            <div class="flex items-center gap-1">
                                                <div class="flex-1 min-w-0 ms-1">
                                                    <div>
                                                        <label for="mobile_num" class="<?= labelClass() ?>">Mobile Number</label>
                                                        <input type="tel" name="mobile_num"
                                                            pattern="^07\d{9}$" placeholder="07123456789"
                                                            value="<?= h($member->mobile_num ?? '') ?>"
                                                            class="<?= inputClass() ?>" placeholder="Enter Mobile Number" required>
                                                        <div>
                                                            <p class="mt-2 text-sm font-xs text-red-500"><?= $error['mobile_num'] ?? '' ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        
                                        <!-- Email Address -->
                                        <?php if ($member->email !== null)  :?>
                                        <li class="py-2 sm:py-2">
                                            <div class="flex items-center gap-1">
                                                <div class="flex-1 min-w-0 ms-1">
                                                    <!-- Email -->
                                                    <div>
                                                        <label for="email" class="<?= labelClass() ?>">Email Address</label>
                                                        <input type="email" name="email"
                                                            value="<?= h($member->email ?? ''); ?>"
                                                            class="<?= inputClass() ?>" placeholder="your@email.com">
                                                        <div>
                                                            <p class="<?= smallError() ?>"><?= h($error['email'] ?? '');?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <?php endif ;?>
                                    </ul>
                                </div>
                            </div>
                            <!-- Modify Button -->
                            <div class="flex justify-end">
                                <button type="submit" name="edit" value="contact"
                                    class="<?= primaryBtn() ?>">
                                    Confirm Contact Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Address -->
            <?php if (isset($address['address_id'])) :?>
                <form method="post" class="mt-6">
                    <input type="hidden" name="member_id" value="<?= (int)$member->member_id ?? '' ?>">

                    <div>
                        <div class="flex flex-col items-start gap-4 mt-3 bg-neutral-primary-soft p-6 border border-default rounded-base shadow-xs md:flex-row h-full">
                            <div class="flex flex-col justify-between md:p-4 leading-normal w-full">
                            <div>
                                <!-- Address -->
                                <h5 class="<?= heading5()?>">Address Information </h5>
                                <div class="flow-root">
                                    <ul role="list">
                                        <li class="py-2 sm:py-2">
                                            <div class="flex items-center gap-1">
                                                <div class="flex-1 min-w-0 ms-1">
                                                    <div>
                                                        <label for="line1" class="block mb-2 text-sm font-small text-gray-900 dark:text-white">Address Line 1 </label>
                                                        <input type="text" name="line1" 
                                                            placeholder="House number + Street"
                                                            value="<?php echo h($address['line_1'] ?? '');?>"
                                                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                        <div>
                                                            <p class="mt-2 text-sm font-xs text-red-500"><?php echo h($error['line1'] ?? '');?></p>
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <label for="line2" class="block mb-2 text-sm font-small text-gray-900 dark:text-white">Address Line 2 </label>
                                                        <input type="text" name="line2" 
                                                            placeholder="Flat / Apartment (optional)"
                                                            value="<?php echo h($address['line_2'] ?? '');?>"
                                                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                        <div>
                                                            <p class="mt-2 text-sm font-xs text-red-500"><?php echo h($error['line2'] ?? '');?></p>
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <label for="city" class="block mb-2 text-sm font-small text-gray-900 dark:text-white">City </label>
                                                        <input type="text" name="city" 
                                                            placeholder="Enter City"
                                                            value="<?php echo h($address['city']);?>"
                                                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                        <div>
                                                            <p class="mt-2 text-sm font-xs text-red-500"><?php echo h($error['city'] ?? '');?></p>
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <label for="postcode" class="block mb-2 text-sm font-small text-gray-900 dark:text-white">Postcode </label>
                                                        <input type="text" name="postcode" 
                                                            placeholder="Enter Postcode"
                                                            value="<?php echo h($address['postcode']);?>"
                                                            class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                        <div>
                                                            <p class="mt-2 text-sm font-xs text-red-500"><?php echo h($error['postcode'] ?? '');?></p>
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <label for="country" class="block mb-2 text-sm font-small text-gray-900 dark:text-white">Country </label>
                                                        <select class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                            autocomplete="country" name="country">
                                                            <option value="" disabled selected> Select Country:</option>
                                                            <?php foreach ($countries as $c): ?>
                                                                <option value="<?= $c ?>"
                                                                    <?php 
                                                                        if($address['country'] === $c){
                                                                            echo 'selected';
                                                                        }
                                                                    ?>>
                                                                    <?= $c?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                            <p class="mt-2 text-sm font-xs text-red-500"><?php echo $error['country'] ?? '';?></p>
                                                    </div>

                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>  
                            <!-- Modify Button -->
                            <div class="flex justify-end">
                                <button type="submit" name="edit" value="address"
                                    class="<?= primaryBtn() ?>">
                                    Confirm Address
                                </button>
                            </div>   
                            </div>
                        </div>
                    </div>
                </form>
            <?php endif;?>

            <form method="post" class="mt-6">
                <input type="hidden" name="application_id" value="<?= h($member->member_id)  ?? '' ?>">
                <div class="grid gap-2 md:grid-cols-2 ">
                    <!-- Reject -->
                    <button type="submit" name="action" value="delete"
                        class="<?=dangerBtn()?>">
                        Delete Details
                    </button>

                    <!-- Reset Password -->
                    <a href="/account/reset-password"
                        class="<?=secondaryBtn()?>">
                            Reset Password
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>

