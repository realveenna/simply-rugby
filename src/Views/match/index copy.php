<section class="bg-gray-50 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center px-1 sm:px-4 lg:px-6 py-3 sm:py-6 mx-auto">
        <div class="<?= cardClassXLNoBg() ?>">
            <!-- SENIOR UPCOMING MATCH -->
            <?php if(!empty($seniorUpcoming)): ?>
                <?= title('Senior', 'Matches') ?>

                <div class="<?= formPadding() ?>">
                    <?= titleLeftSmall('Upcoming Match'); ?>
                    
                    <?php foreach($seniorUpcoming as $match): ?>
                        <div class="relative bg-neutral-primary-soft w-full p-6 border border-default rounded-base shadow-xs">
                            <div class="flex flex-col items-center">
                                <!-- Date and time -->
                                <span class="tracking-wide text-brand-strong md:text-sm font-medium ">
                                    <?= h(formatDateDisplay($match['match_date']) ?? '') ?> -
                                        <?= h(formatTime($match['kick_off_time']) ?? '') ?>
                                </span> 
                                <!-- Team Names -->
                                <div class="flex justify-center gap-5 w-full py-4">
                                    <!-- Simply Rugby Team -->
                                    <div class="flex flex-col items-center flex-1">
                                        <img class="w-15 h-15" src="/images/logo/main-logo.png" alt="simply-rugby-logo">
                                        <h5 class="text-xl text-center font-semibold tracking-tight text-heading">
                                            <?= strtoupper(h($match['squad_name'])) ?? '' ?>
                                        </h5>
                                    </div>

                                    <!-- Home or Away Badge  + Each Team Scores if available-->
                                    <div class="flex items-center gap-3 justify-center text-center min-w-25 mx-5">

                                        <!-- Our Team Score -->
                                        <?php if($match['result'] !== 'Pending'): ?>
                                            <span class="text-2xl px-4 font-semibold text-heading">
                                                <?= h($match['our_total_points']);  ?>
                                            </span>
                                        <?php endif; ?>

                                        <!-- Home -->
                                        <?php if($match['match_venue'] === 'Home'): ?>
                                            <span class="<?= badgeBlue()?>">
                                                <?= h($match['match_venue'])  ?>
                                            </span>
                                        <!-- All Marked and Past Date-->
                                        <?php elseif($match['match_venue'] === 'Away'): ?>
                                            <span class="<?= badgeSuccess()?>">
                                                <?= h($match['match_venue'])  ?>
                                            </span>
                                        <!-- None -->
                                        <?php else :?>
                                            <span class="<?= badgeWarning()?>">
                                                TBD
                                            </span>
                                        <?php endif; ?>

                                        <!-- Opponent Team Score -->
                                        <?php if($match['result'] !== 'Pending'): ?>
                                            <span class="text-2xl px-4 font-semibold text-heading">
                                                <?= h($match['opponent_total_points']); ?>
                                            </span>
                                        <?php endif; ?>

                                    </div>

                                    <!-- Opponent Team -->
                                    <div class="flex flex-col items-center flex-1">
                                        <img class="w-15 h-15" src="/images/logo/main-logo.png" alt="simply-rugby-logo">
                                        <h5 class="text-xl text-center font-semibold tracking-tight text-heading">
                                            <?= strtoupper(h($match['opposition_team_name'])) ?? '' ?>
                                        </h5>
                                    </div>
                                </div>
                                <div class="flex justify-center">
                                    <a href="/match/view?match_id=<?= $match['match_id'] ?>"
                                        class="text-body hover:text-brand flex flex-col items-center gap-0 text-center">
                                        View More Details 
                                        <svg class="w-[16px] h-[16px] dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <!-- SENIOR PAST MATCH -->
            <?php if(!empty($seniorPast)): ?>
                <div class="<?= formPadding() ?>">
                    <?= titleLeftSmall('Past Match'); ?>
                    
                    <?php foreach($seniorPast as $match): ?>
                        <div class="relative bg-neutral-primary-soft w-full p-6 border border-default rounded-base shadow-xs">
                            <div class="flex flex-col items-center">
                                <!-- Date and time -->
                                <span class="tracking-wide text-brand-strong md:text-sm font-medium ">
                                    <?= h(formatDateDisplay($match['match_date']) ?? '') ?> -
                                        <?= h(formatTime($match['kick_off_time']) ?? '') ?>
                                </span>
                                <!-- Team Names -->
                                <div class="flex justify-center gap-5 w-full py-4">
                                    <!-- Simply Rugby Team -->
                                    <div class="flex flex-col items-center flex-1">
                                        <img class="w-15 h-15" src="/images/logo/main-logo.png" alt="simply-rugby-logo">
                                        <h5 class="text-xl text-center font-semibold tracking-tight text-heading">
                                            <?= strtoupper(h($match['squad_name'])) ?? '' ?>
                                        </h5>
                                    </div>

                                    <!-- Home or Away Badge  + Each Team Scores if available-->
                                    <div class="flex items-center gap-3 justify-center text-center min-w-25 mx-5">

                                        <!-- Our Team Score -->
                                        <?php if($match['result'] !== 'Pending'): ?>
                                            <span class="text-2xl px-4 font-semibold text-heading">
                                                <?= h($match['our_total_points']);  ?>
                                            </span>
                                        <?php endif; ?>

                                        <!-- Home -->
                                        <?php if($match['match_venue'] === 'Home'): ?>
                                            <span class="<?= badgeBlue()?>">
                                                <?= h($match['match_venue'])  ?>
                                            </span>
                                        <!-- All Marked and Past Date-->
                                        <?php elseif($match['match_venue'] === 'Away'): ?>
                                            <span class="<?= badgeSuccess()?>">
                                                <?= h($match['match_venue'])  ?>
                                            </span>
                                        <!-- None -->
                                        <?php else :?>
                                            <span class="<?= badgeWarning()?>">
                                                TBD
                                            </span>
                                        <?php endif; ?>

                                        <!-- Opponent Team Score -->
                                        <?php if($match['result'] !== 'Pending'): ?>
                                            <span class="text-2xl px-4 font-semibold text-heading">
                                                <?= h($match['opponent_total_points']); ?>
                                            </span>
                                        <?php endif; ?>

                                    </div>

                                    <!-- Opponent Team -->
                                    <div class="flex flex-col items-center flex-1">
                                        <img class="w-15 h-15" src="/images/logo/main-logo.png" alt="simply-rugby-logo">
                                        <h5 class="text-xl text-center font-semibold tracking-tight text-heading">
                                            <?= strtoupper(h($match['opposition_team_name'])) ?? '' ?>
                                        </h5>
                                    </div>
                                </div>
                                <div class="flex justify-center">
                                    <a href="/match/view?match_id=<?= $match['match_id'] ?>"
                                        class="text-body hover:text-brand flex flex-col items-center gap-0 text-center">
                                        View More Details 
                                        <svg class="w-[16px] h-[16px] dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- JUNIOR UPCOMING MATCH WITH PERMISSION -->
            <?php if(!empty($juniorUpcoming) || !empty($juniorPast)): ?>
                <?php if(hasPermission('view_junior_match')): ?>
                    <?= title('Junior', 'Matches') ?>
                    <?php if(!empty($juniorUpcoming)): ?>
                        <div class="<?= formPadding() ?>">
                            <?= titleLeftSmall('Upcoming Match'); ?>
                            
                            <?php foreach($juniorUpcoming as $match): ?>
                                <div class="relative bg-neutral-primary-soft w-full p-6 border border-default rounded-base shadow-xs">
                                    <div class="flex flex-col items-center">
                                        <!-- Date and time -->
                                        <span class="tracking-wide text-brand-strong md:text-sm font-medium ">
                                            <?= h(formatDateDisplay($match['match_date']) ?? '') ?> -
                                                <?= h(formatTime($match['kick_off_time']) ?? '') ?>
                                        </span>
                                        <!-- Team Names -->
                                        <div class="flex justify-center gap-5 w-full py-4">
                                            <!-- Simply Rugby Team -->
                                            <div class="flex flex-col items-center flex-1">
                                                <img class="w-15 h-15" src="/images/logo/main-logo.png" alt="simply-rugby-logo">
                                                <h5 class="text-xl text-center font-semibold tracking-tight text-heading">
                                                    <?= strtoupper(h($match['squad_name'])) ?? '' ?>
                                                </h5>
                                            </div>

                                            <!-- Home or Away Badge  + Each Team Scores if available-->
                                            <div class="flex items-center gap-3 justify-center text-center min-w-25 mx-5">

                                                <!-- Our Team Score -->
                                                <?php if($match['result'] !== 'Pending'): ?>
                                                    <span class="text-2xl px-4 font-semibold text-heading">
                                                        <?= h($match['our_total_points']);  ?>
                                                    </span>
                                                <?php endif; ?>

                                                <!-- Home -->
                                                <?php if($match['match_venue'] === 'Home'): ?>
                                                    <span class="<?= badgeBlue()?>">
                                                        <?= h($match['match_venue'])  ?>
                                                    </span>
                                                <!-- All Marked and Past Date-->
                                                <?php elseif($match['match_venue'] === 'Away'): ?>
                                                    <span class="<?= badgeSuccess()?>">
                                                        <?= h($match['match_venue'])  ?>
                                                    </span>
                                                <!-- None -->
                                                <?php else :?>
                                                    <span class="<?= badgeWarning()?>">
                                                        TBD
                                                    </span>
                                                <?php endif; ?>

                                                <!-- Opponent Team Score -->
                                                <?php if($match['result'] !== 'Pending'): ?>
                                                    <span class="text-2xl px-4 font-semibold text-heading">
                                                        <?= h($match['opponent_total_points']); ?>
                                                    </span>
                                                <?php endif; ?>

                                            </div>

                                            <!-- Opponent Team -->
                                            <div class="flex flex-col items-center flex-1">
                                                <img class="w-15 h-15" src="/images/logo/main-logo.png" alt="simply-rugby-logo">
                                                <h5 class="text-xl text-center font-semibold tracking-tight text-heading">
                                                    <?= strtoupper(h($match['opposition_team_name'])) ?? '' ?>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="flex justify-center">
                                            <a href="/match/view?match_id=<?= $match['match_id'] ?>"
                                                class="text-body hover:text-brand flex flex-col items-center gap-0 text-center">
                                                View More Details 
                                                <svg class="w-[16px] h-[16px] dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                <!-- JUNIOR PAST MATCH -->
                <?php if(!empty($juniorPast)): ?>
                    <div class="<?= formPadding() ?>">
                        <?= titleLeftSmall('Past Match'); ?>
                        <?php foreach($juniorPast as $match): ?>
                            <div class="relative bg-neutral-primary-soft w-full p-6 border border-default rounded-base shadow-xs">
                                <div class="flex flex-col items-center">
                                    <!-- Date and time -->
                                    <span class="tracking-wide text-brand-strong md:text-sm font-medium ">
                                        <?= h(formatDateDisplay($match['match_date']) ?? '') ?> -
                                            <?= h(formatTime($match['kick_off_time']) ?? '') ?>
                                    </span>
                                    <!-- Team Names -->
                                    <div class="flex justify-center gap-5 w-full py-4">
                                        <!-- Simply Rugby Team -->
                                        <div class="flex flex-col items-center flex-1">
                                            <img class="w-15 h-15" src="/images/logo/main-logo.png" alt="simply-rugby-logo">
                                            <h5 class="text-xl text-center font-semibold tracking-tight text-heading">
                                                <?= strtoupper(h($match['squad_name'])) ?? '' ?>
                                            </h5>
                                        </div>

                                        <!-- Home or Away Badge  + Each Team Scores if available-->
                                        <div class="flex items-center gap-3 justify-center text-center min-w-25 mx-5">

                                            <!-- Our Team Score -->
                                            <?php if($match['result'] !== 'Pending'): ?>
                                                <span class="text-2xl px-4 font-semibold text-heading">
                                                    <?= h($match['our_total_points']);  ?>
                                                </span>
                                            <?php endif; ?>

                                            <!-- Home -->
                                            <?php if($match['match_venue'] === 'Home'): ?>
                                                <span class="<?= badgeBlue()?>">
                                                    <?= h($match['match_venue'])  ?>
                                                </span>
                                            <!-- All Marked and Past Date-->
                                            <?php elseif($match['match_venue'] === 'Away'): ?>
                                                <span class="<?= badgeSuccess()?>">
                                                    <?= h($match['match_venue'])  ?>
                                                </span>
                                            <!-- None -->
                                            <?php else :?>
                                                <span class="<?= badgeWarning()?>">
                                                    TBD
                                                </span>
                                            <?php endif; ?>

                                            <!-- Opponent Team Score -->
                                            <?php if($match['result'] !== 'Pending'): ?>
                                                <span class="text-2xl px-4 font-semibold text-heading">
                                                    <?= h($match['opponent_total_points']); ?>
                                                </span>
                                            <?php endif; ?>

                                        </div>

                                        <!-- Opponent Team -->
                                        <div class="flex flex-col items-center flex-1">
                                            <img class="w-15 h-15" src="/images/logo/main-logo.png" alt="simply-rugby-logo">
                                            <h5 class="text-xl text-center font-semibold tracking-tight text-heading">
                                                <?= strtoupper(h($match['opposition_team_name'])) ?? '' ?>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="flex justify-center">
                                        <a href="/match/view?match_id=<?= $match['match_id'] ?>"
                                            class="text-body hover:text-brand flex flex-col items-center gap-0 text-center">
                                            View More Details 
                                            <svg class="w-[16px] h-[16px] dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
        </div>
    </div>
</section>
