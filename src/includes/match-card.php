<div class="relative bg-neutral-primary-soft w-full p-6 border border-default rounded-base shadow-xs">

<!-- If user has hasPermission to update match show buttons -->
    <?php if (hasPermission('update_match') || hasPermission('create_team')): ?>
        <button id="ddMatchMenuBtn<?= $match['match_id'] ?>" data-dropdown-toggle="ddMatchMenu<?= $match['match_id'] ?>" class="absolute top-2 end-2 text-body hover:text-heading bg-neutral-primary-soft box-border border border-transparent hover:bg-neutral-tertiary focus:ring-4 focus:ring-neutral-tertiary rounded-base p-1.5 focus:outline-none" type="button">
            <span class="sr-only">Open dropdown</span>
            <svg class="w-6 h-6 text-brand" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-width="3" d="M6 12h.01m6 0h.01m5.99 0h.01"/></svg>
        </button>
        
        <div id="ddMatchMenu<?= $match['match_id'] ?>" class="min-w-max z-10 bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg w-36 block hidden">
            <ul class="p-2 text-sm text-body font-medium" aria-labelledby="ddMatchMenuBtn<?= $match['match_id'] ?>">
                <!-- Dropdown menu -->
                <!-- Allow to update match details -->
                <?php if(hasPermission('update_match')):?>
                    <li>
                        <a href="/match/update?match_id=<?= $match['match_id'] ?>"
                            class="inline-flex items-center justify-center text-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded-md">
                            Update Match Details
                        </a>
                    </li>
                <?php endif; ?>
                
                <?php if(hasPermission('create_match') && hasPermission('update_match')):?>
                    <!-- If result is pending allow to add match result -->
                    <?php if ($match['result'] === 'Pending' && !isFutureDate($match['match_date'])): ?>
                    <!-- Add Match Result -->
                        <li>
                            <a href="/match/update-result?match_id=<?= $match['match_id'] ?>"
                                class="inline-flex items-center justify-center text-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded-md">
                                Add Match Result
                            </a>
                        </li>
                    <?php endif; ?>
                <?php else: ?>
                <!-- Update Match Result -->
                    <!-- permission -->
                    <?php if(hasPermission('update_match')):?>
                        <li>
                            <a href="/match/update-result?match_id=<?= $match['match_id'] ?>"
                                class="inline-flex items-center justify-center text-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded-md">
                                Update Match Result
                            </a>
                        </li>

                        <li>
                            <a href="/match/match-player-stats?match_id=<?= $match['match_id'] ?>"
                                class="inline-flex items-center justify-center text-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded-md">
                                Add Match Player Stats
                            </a>    
                        </li>
                    <?php endif; ?>

                    <!-- Button if user has permission to record injury -->
                    <?php if ((hasPermission('record_injury')) && $match['result'] !== 'Pending'):?>
                        <li>
                            <a href="/injury?match_id=<?= $match['match_id']?>" 
                                class="inline-flex items-center justify-center text-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded-md">
                                Record Injury
                            </a>    
                        </li>
                    <?php endif; ?>
                <?php endif; ?>

                

                <!-- If has permission to delete match -->
                <?php if (hasPermission('delete_match')): ?>
                    <!-- Delete Button -->
                    <li>
                        <form method="post" action="/match/view" name="action" value="delete">
                            <input type="hidden" name="match_id" value="<?= h($match['match_id'] ?? '') ?>">
                            <button type="submit" name="action" value="delete" 
                                class="flex justify-center items-center text-center w-full p-2 text-fg-danger hover:bg-neutral-tertiary-medium rounded-md">
                                Delete Match
                            </button>
                        </form>
                    </li>
                <?php endif;?>
            </ul>
        </div>
    <?php endif;?>

    
    <div class="flex flex-col items-center">
        <!-- Date and time -->
        <span class="tracking-wide text-brand-strong md:text-sm font-medium ">
            <?= h(formatDateDisplay($match['match_date']) ?? '') ?> -
                <?= h(formatTime($match['kick_off_time']) ?? '') ?>
        </span>
        <!-- Team Names -->
        <div class="flex flex-col md:flex-row justify-center gap-5 w-full py-4">
            <!-- Simply Rugby Team -->
            <div class="flex flex-col items-center flex-1">
                <img class="w-15 h-15" src="/images/logo/main-logo.png" alt="simply-rugby-logo">
                <h5 class="text-xl text-e font-semibold tracking-tight text-heading">
                    <?= strtoupper(h($match['squad_name'])) ?? '' ?>
                </h5>
            </div>

            <!-- Home or Away Badge  + Each Team Scores if available-->
            <div class="flex flex-col md:flex-row items-center gap-3 justify-center text-center min-w-25 mx-5">

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
                <h5 class="text-xl text-e font-semibold tracking-tight text-heading">
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