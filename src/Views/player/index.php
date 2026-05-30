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
                <?php if ($player['section_id'] === 3 ): ?>
                    <img class="object-cover min-w-[300px] w-full rounded-base h-64 md:h-auto md:w-48 mb-4 md:mb-0" 
                        src="https://images.unsplash.com/photo-1581403341630-a6e0b9d2d257?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" 
                        alt="player-image">
                <?php endif; ?>

                <!-- Personal information -->
                <div class="flex flex-col justify-between md:p-4 leading-normal w-full">
                    <div class="flex justify-between">
                        <h5 class="<?= heading5()?>">Personal Information</h5>
                         <!-- Include badge status -->
                        <?php require '../src/includes/player_availability_status.php'; ?>
                    </div>

                    <!-- Membership Renewal -->
                    <?php if (!empty($renewalReminder) && 
                        // If own account or child account
                        (isOwner($_SESSION['user']['member_id'], $player['member_id']) ||
                        hasPlayerAccess($player['member_id']))): ?>

                        <p class="text-danger font-semibold">
                            Your membership is ending soon! Click 
                            <a href="/members/renewal?member_id=<?= $player['member_id'] ?>" 
                            class="underline"> here </a>
                             to renew your membership.
                        </p>
                    <?php endif; ?>

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
                   <!-- Modify Button -->
                    <div class="flex justify-end">
                        <a href="/members/update?member_id=<?= $player['member_id'] ?>" class="inline-flex items-center w-auto text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                            Modify
                            <svg class="w-4 h-4 ms-1.5 rtl:rotate-180 -me-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m14 0-4 4m4-4-4-4"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Match and Training Attendance -->
            <?php if (!empty($matchAttendance) || !empty($trainingAttendance)):?>
                <!-- Match and Training  Attendance -->
                <?= titleLeftSmall('Player Attendance')?>
                <div class="grid gap-6 mb-6 xl:grid-cols-2 items-start">
                    <div class="flex flex-col items-start gap-4 mt-4 bg-neutral-primary-soft p-6 border border-default rounded-base shadow-xs md:flex-row h-full">
                        <div class="w-full">
                            <h5 class="<?= heading5()?>">
                                Match Attendance
                            </h5>
                            <div class="flow-root">
                                <ul role="list">
                                    <?php foreach ($matchAttendance as $match): ?>
                                        <li class="flex justify-between pb-3 items-start">
                                            <span>
                                                <p class="font-medium text-heading truncate">
                                                    <?= h($player['squad_name'] .'-'. $match['opposition_team_name']) ?>
                                                </p>
                                                <p class="<?= $match['match_venue'] === 'Away' ? badgeSuccess() : badgeBlue() ?>">
                                                    <?= h($match['match_venue'])?>
                                                </p>
                                            </span>
                                            
                                            <p class="text-sm text-body truncate">
                                                <?= h($match['match_date']) ?>
                                            </p>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col items-start gap-4 mt-4 bg-neutral-primary-soft p-6 border border-default rounded-base shadow-xs md:flex-row h-full">
                        <!-- Training Attendance -->
                        <div class="w-full">
                            <h5 class="<?= heading5()?>">
                                Training Attendance
                            </h5>
                            <div class="flow-root">
                                <ul role="list">
                                    <?php foreach ($trainingAttendance as $training): ?>
                                        <li class="flex items-center justify-between pb-3 items-start">
                                            <span>
                                                <p class="font-medium text-heading truncate max-w-xs">
                                                    <?= h($training['skills_activities'])?>
                                                </p>
                                                <p class="<?= $training['attendance_status'] === 'Present' ? badgeSuccess() : badgeDanger() ?>">
                                                    <?= h($training['attendance_status'])?>
                                                </p>
                                            </span>
                                            
                                            <p class="text-sm text-body truncate">
                                                <?= h($training['date']) ?>
                                            </p>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Permission to view player skills -->
            <?php if (hasPermission('view_player_skills')):?>
                <!-- Player Skills -->
                <?= titleLeftSmall('Player Match Stats')?>
                
                <div class="flex flex-col items-center gap-4 mt-4 bg-neutral-primary-soft p-6 border border-default rounded-base shadow-xs md:flex-row">
                    <div class="flex flex-col justify-between md:p-4 leading-normal w-full">
                        <div class="grid gap-6 mb-6 xl:grid-cols-2 items-start">
                            <div class="w-full md:w-100">
                                <h5 class="<?= heading5()?>">
                                    Performance Summary
                                </h5>
                                <div class="flow-root">
                                    <ul role="list">
                                        <?php foreach ($allStats as $key => $value): ?>
                                            <li class="flex items-center justify-between pb-3">
                                                <p class="font-medium text-heading truncate">
                                                    <?= ucwords(str_replace('_', ' ', $key)
                                                    ) ?>
                                                </p>
                                                <p class="text-sm text-body truncate">
                                                    <?= h($value) ?>
                                                </p>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>

                                <!-- Chart labels -->
                                <?php
                                    $matchCategories = [
                                        'Tries',
                                        'Conversions',
                                        'Penalties',
                                        'Drop Goals'
                                    ];
                                    $stats = [
                                        $allStats['tries'],
                                        $allStats['conversions'],
                                        $allStats['penalties'],
                                        $allStats['drop_goals']
                                    ];
                                ?>
                            </div>
                            <div class="space-y-4">
                                <!-- Player Match Stats -->
                                <div class="flex justify-between border-light border-b pb-3">
                                    <dl>
                                    <dt class="text-body">Total Points</dt>
                                    <dd class="text-2xl font-semibold text-heading"><?= (int)$allStats['total_points'] ?> Points</dd>
                                    </dl>
                                </div>
                                <div id="bar-chart" class="w-full"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Player Training Stats -->
                <?= titleLeftSmall('Player Skills Stats')?>
                <div class="flex flex-col items-center gap-4 mt-4 bg-neutral-primary-soft p-6 border border-default rounded-base shadow-xs md:flex-row">
                    <div class="flex flex-col justify-between md:p-4 leading-normal w-full">
                        <div class="grid gap-6 mb-6 xl:grid-cols-2 items-start">
                            <div class="w-full md:w-100">
                                <h5 class="<?= heading5()?>">
                                    Skill Analysis
                                </h5>
                                <div class="flow-root">
                                    <ul role="list">
                                        <?php foreach ($averages as $average): ?>
                                            <li class="flex items-center justify-between pb-3">
                                                <p class="font-medium text-heading truncate">
                                                    <?= h($average['skill_category_name']) ?>
                                                </p>
                                                <p class="text-sm text-body truncate">
                                                    <?= h($average['average_rating']) ?>/5
                                                </p>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                <!-- Chart labels -->
                                <?php
                                    $skillLabels = [];
                                    $ratings = [];
                                    foreach ($averages as $average) {
                                        $skillLabels[] = $average['skill_category_name'];
                                        $ratings[] = ((float)$average['average_rating']);
                                    }
                                ?>
                            </div>
                            <div class="space-y-4">
                                <!-- Playey Training Skills Chart -->
                                <canvas id="radarChart"></canvas> 
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif;?>

            
            <!-- Permission to view_player_details -->
            <?php if (hasPermission('view_player_details')):?>

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
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- Modify Button -->
                        <div class="flex justify-end">
                            <a href="/members/update?member_id=<?= $player['member_id'] ?>" class="inline-flex items-center w-auto text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                                Modify
                                <svg class="w-4 h-4 ms-1.5 rtl:rotate-180 -me-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m14 0-4 4m4-4-4-4"/></svg>
                            </a>
                        </div>
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
                                        <!-- Guradian email if available -->
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
                    </div>
                </div>

                <!-- Secondary Gurdian Information -->
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
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Injury Information -->
                <?php if (!empty($injuries)):?>
                    <?= titleLeftSmall('Injuries')?>
                    <div id="injuries" class="flex flex-col items-center gap-4 mt-4 bg-neutral-primary-soft p-6 border border-default rounded-base shadow-xs">
                        <div class="flex flex-col justify-between md:p-4 leading-normal w-full">
                            <div class="flex-1 min-w-0 ms-1">
                                <?php if($injuries):?>
                                    <?php foreach ($injuries as $injury): ?>
                                        <ul class="space-y-1 text-body list-disc list-inside">
                                            <li class="flex items-center justify-between">
                                                <div class="flex items-center gap-4">
                                                    
                                                    <!-- Update Injury Button with Permission -->
                                                    <?php if (hasPermission('record_injury')):?>
                                                        <a href="/injury/update?player_injury_id=<?= $injury['player_injury_id'] ?>">
                                                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
                                                            </svg>
                                                        </a>
                                                    <?php endif;?>

                                                    <div class="flex flex-col">
                                                        <span class="font-semibold text-heading">
                                                            <?= h($injury['injury_name'] ?? '') ?>
                                                        </span>
                                                        <span class="text-sm text-body">
                                                            Date of Injury: <?= h($injury['injury_date'] ?? '') ?>
                                                        </span>
                                                        <?php if (!empty($injury['recovery_date'])):?>
                                                        <span class="text-sm text-body">
                                                            Date Recovered: <?= h($injury['recovery_date'] ?? '') ?>
                                                        </span>
                                                        <?php endif;?>
                                                    </div>
                                                </div>
                                                <span class="
                                                <?= 
                                                    $injury['injury_status'] === 'Active' ? badgeDanger() :
                                                    ($injury['injury_status'] === 'Recovering' ? badgeWarning() : badgeBlue())
                                                ?>">
                                                    <?= h($injury['injury_status'] ?? '') ?>
                                                </span>
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
                    </div>
                </div>

            <?php endif;?>
      
            <!-- Permission to update_player_details -->
            <?php if(!hasPermission('update_player_details')):?>
                <form method="post" class="mt-6">
                    <input type="hidden" name="application_id" value="<?= h($player['member_id']) ?>">
                    <div class="flex flex-col gap-2">
                        <!-- Reject -->
                        <button type="submit" name="action" value="delete"
                            class="<?=dangerBtn()?>">
                            Delete Player Details
                        </button>
                    </div>
                </form>
            <?php endif;?>

        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

// FLOWBITE APEXCHART

  // Get the CSS variable --color-brand and convert it to hex for ApexCharts
  const getBrandColor = () => {
    // Get the computed style of the document's root element
    const computedStyle = getComputedStyle(document.documentElement);
    
    // Get the value of the --color-brand CSS variable
    return computedStyle.getPropertyValue('--color-fg-brand').trim() || "#1447E6";
  };

  const getWarningColor = () => {
    const computedStyle = getComputedStyle(document.documentElement);
    return computedStyle.getPropertyValue('--color-warning').trim() || "#1447E6";
  };

  const getSuccessColor = () => {
    const computedStyle = getComputedStyle(document.documentElement);
    return computedStyle.getPropertyValue('--color-success').trim() || "#1447E6";
  };

  const getNeutralSecondaryMediumColor = () => {
    const computedStyle = getComputedStyle(document.documentElement);
    return computedStyle.getPropertyValue('--color-neutral-secondary-medium').trim() || "#1447E6";
  };

  const brandColor = getBrandColor();
  const warningColor = getWarningColor();
  const successColor = getSuccessColor();
  const neutralSecondaryMediumColor = getNeutralSecondaryMediumColor();

  const getChartOptions = () => {
    return {
      series: <?= json_encode($ratings) ?>,
      colors: [brandColor, warningColor, successColor],
      chart: {
        height: "350px",
        width: "100%",
        type: "radialBar",
        sparkline: {
          enabled: true,
        },
      },
      plotOptions: {
        radialBar: {
          track: {
            background: neutralSecondaryMediumColor,
          },
          dataLabels: {
            value: {
                formatter: function(val) {
                    return (val / 20).toFixed(1) + "/5";
                }
            }
        },
          hollow: {
            margin: 0,
            size: "32%",
          }
        },
      },
      grid: {
        show: false,
        strokeDashArray: 4,
        padding: {
          left: 2,
          right: 2,
          top: -23,
          bottom: -20,
        },
      },
      labels: <?= json_encode($skillLabels) ?>,
      legend: {
        show: true,
        position: "bottom",
        fontFamily: "Inter, sans-serif",
      },
      tooltip: {
        enabled: true,
        x: {
          show: false,
        },
      },
      yaxis: {
        show: false,
        labels: {
          formatter: function (value) {
            return value + '%';
          }
        }
      }
    }
  }

  if (document.getElementById("radial-chart") && typeof ApexCharts !== 'undefined') {
    const chart = new ApexCharts(document.querySelector("#radial-chart"), getChartOptions());
    chart.render();
  }
</script>

<script>
const data = {
  labels: <?= json_encode($skillLabels) ?>,
  datasets: [{
    label: 'Player Scoring Statistics',
    data: <?= json_encode($ratings) ?>,
    fill: true,
    backgroundColor: 'rgba(255, 99, 132, 0.2)',
    borderColor: 'rgb(255, 99, 132)',
    pointBackgroundColor: 'rgb(255, 99, 132)',
    pointBorderColor: '#fff',
    pointHoverBackgroundColor: '#fff',
    pointHoverBorderColor: 'rgb(255, 99, 132)'
  }]
};

const config = {
  type: 'radar',
  data: data,
  options: {
    elements: {
      line: {
        borderWidth: 3
      }
    }
  },
};

new Chart(document.getElementById('radarChart'), config);

</script>



<!-- Bar char Apex -->
<script>
    
const options = {
  series: [{
    name: "Player Stats",
    data:
        <?= json_encode($stats) ?>
}],
  chart: {
    sparkline: {
      enabled: false,
    },
    type: "bar",
    width: "100%",
    height: 220,
    toolbar: {
      show: false,
    }
  },
  fill: {
    opacity: 1,
  },
  plotOptions: {
    bar: {
      horizontal: true,
      distributed: true, 
      barHeight: "30%",
      columnWidth: "100%",
      borderRadiusApplication: "end",
      borderRadius: 6,
      dataLabels: {
        position: "top",
      },
    },
  },
  
  legend: {
    show: true,
    position: "bottom",
  },
  dataLabels: {
    enabled: false,
  },
  tooltip: {
    shared: true,
    intersect: false,
  },
  xaxis: {
    labels: {
      show: true,
      style: {
        fontFamily: "Inter, sans-serif",
        cssClass: 'text-xs font-normal fill-body'
      }
    },
    categories:
            <?= json_encode($matchCategories) ?>,
    axisTicks: {
      show: false,
    },
    axisBorder: {
      show: false,
    },
  },
  yaxis: {
    labels: {
      show: true,
      style: {
        fontFamily: "Inter, sans-serif",
        cssClass: 'text-xs font-normal fill-body'
      }
    }
  },
  grid: {
    show: true,
    strokeDashArray: 4,
    padding: {
      left: 2,
      right: 2,
      top: -20
    },
  },
  fill: {
    opacity: 1,
  }
}

if(document.getElementById("bar-chart") && typeof ApexCharts !== 'undefined') {
  const chart = new ApexCharts(document.getElementById("bar-chart"), options);
  chart.render();
}

</script>