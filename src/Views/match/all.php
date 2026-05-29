<!-- All Match Details -->
<section class="bg-gray-50 dark:bg-gray-900">
    <?= title($category,'Matches')?>

      <!-- Filter by Category -->
    <div class="py-4 flex justify-end">
        <button id="matchesCategoryButton" data-dropdown-toggle="matchesCategory" class="inline-flex items-center justify-center text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none" type="button">
            Filter By:
        <svg class="w-4 h-4 ms-1.5 -me-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/></svg>
        </button>

        <!-- Dropdown menu -->
        <div id="matchesCategory" class="min-w-max z-10 hidden bg-neutral-primary-medium border border-default-medium rounded-base divide-y divide-default-medium shadow-lg w-44">
            <ul class="min-w-max p-2 text-sm text-body font-medium" aria-labelledby="matchesCategoryButton">
                <!-- All -->
                <li>
                    <a href="/match/all"
                        class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                        All Matches
                    </a>
                </li>

                <!-- Junior Category Dropdown permission -->
                <?php if(in_array(1, $_SESSION['section_access']) ||
                         in_array(2, $_SESSION['section_access']) || isAdmin() ):?>
                    <!-- Junior Upcoming --> 
                    <li>
                        <a href="/match/all?category=Junior Upcoming"
                            class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                            Junior Upcoming Matches
                        </a>
                    </li>

                    <!-- Junior Past -->
                    <li>
                        <a href="/match/all?category=Junior Past"
                            class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                            Junior Past Matches
                        </a>
                    </li>
                <?php endif ;?>

                <!-- Senior Upcoming -->
                <li>
                    <a href="/match/all?category=Senior Upcoming"
                        class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                        Senior Upcoming Matches
                    </a>
                </li>

                <!-- Senior Past -->
                <li>
                    <a href="/match/all?category=Senior Past"
                        class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                        Senior Past Matches
                    </a>
                </li>
            </ul>
        </div>
    </div>
    
    <table class="datatable">
        <thead>
            <tr>
                <th class="text-center">
                    <span class="flex items-center">
                        Our Team
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                </th>
                <th class="text-center">
                    <span class="flex items-center">
                        Opponent Team
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                </th>
                <th class="text-center">
                    <span class="flex items-center">
                        Match Date
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                </th>
                <th class="text-center">
                    <span class="flex items-center">
                        Kick Off
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                </th>
                <th class="text-center">
                    <span class="flex items-center">
                        Result
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                </th>
                <th class="text-center">
                    <span class="flex items-center">
                        Action
                    </span>
                </th>
            </tr>
        </thead>

        <tbody>
            <?php foreach($matches as $match): ?>
            <tr>
                <td class="text-center"><?= h($match['squad_name']) ?></td>
                <td class="text-center"><?= h($match['opposition_team_name']) ?></td>
                <td class="text-center"><?= h($match['match_date']) ?></td>
                <td class="text-center"><?= h($match['kick_off_time']) ?></td>
                <!-- Badge Color for Result Status -->
                <td class="text-center">
                    <?php if($match['result'] === 'Win'): ?>
                        <span class="<?= badgeSuccess()?>">
                            <?= strtoupper($match['result']) ?>
                        </span>
                    <?php elseif($match['result'] === 'Lose'): ?>
                        <span class="<?= badgeWarning()?>">
                            <?= strtoupper($match['result']) ?>
                        </span>
                    <?php elseif($match['result'] === 'Draw'): ?>
                        <span class="<?= badgeGray()?>">
                            <?= strtoupper($match['result']) ?>
                        </span>
                    <?php elseif($match['result'] === 'Pending' && !isFutureDate($match['match_date'])): ?>
                            <span class="<?= badgeDanger()?>">
                                PLEASE UPDATE RESULT
                            </span>
                    <?php elseif($match['result'] === 'Pending'): ?>
                        <span class="<?= badgeDanger()?>">
                            <?= strtoupper($match['result']) ?>
                        </span>
                    <?php else: ?>
                        <span class="<?= badgeDanger()?>">
                            <?= strtoupper('N/A') ?>
                        </span>
                    <?php endif; ?>
                </td>
                <td class="text-center">
                    <button id="allMatch<?=h($match['match_id'])?>" data-dropdown-toggle="allMatchDots<?=h($match['match_id'])?>" class="text-heading bg-neutral-primary box-border border border-transparent hover:bg-neutral-secondary-medium focus:ring-4 focus:ring-neutral-tertiary font-medium leading-5 rounded-base text-sm p-2 focus:outline-none" type="button"> 
                        <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-width="3" d="M6 12h.01m6 0h.01m5.99 0h.01"/></svg>
                    </button>
                    <!-- Dropdown menu -->
                    <div id="allMatchDots<?=h($match['match_id'])?>" class="z-10 hidden bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg w-44 dark:divide-gray-600">
                        <ul class="p-2 text-sm text-body font-medium" aria-labelledby="allMatch<?=h($match['match_id'])?>">
                            <!-- View match details -->
                            <li>
                                <a href="/match/view?match_id=<?= $match['match_id'] ?>"
                                    class="flex justify-center items-center text-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded-md">
                                    View Details
                                </a>
                            </li>

                            <!-- If user has permission to create_team and result is pending -->
                            <?php if (hasPermission('create_team') 
                                && isFutureDate($match['match_date'])
                                && $match['result'] === 'Pending'): ?>
                                <!-- Allow to create team -->
                                <li>
                                    <a href="/match/lineup?match_id=<?= $match['match_id'] ?>"
                                        class="flex justify-center items-center text-center w-full p-2 hover:bg-neutral-tertiary-medium text-brand hover:text-heading rounded-md">
                                        Create Match Team
                                    </a>
                                </li>
                            <?php endif;?>

                            <!-- Button if user has permission to record injury -->
                            <?php if ((hasPermission('record_injury')) && $match['result'] !== 'Pending'):?>
                                <li>
                                    <a href="/injury?match_id=<?= $match['match_id']?>" 
                                        class="inline-flex items-center justify-center text-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded-md">
                                        Record Injury
                                    </a>    
                                </li>
                            <?php endif; ?>

                            <!-- If has hasPermission to update match show buttons -->
                            <?php if (hasPermission('update_match')): ?>
                                
                                <!-- If result is pending and past date allow to add match result -->
                                <?php if ($match['result'] === 'Pending' && !isFutureDate($match['match_date'])): ?>
                                <!-- Add Match Result -->
                                    <li>
                                        <a href="/match/update-result?match_id=<?= $match['match_id'] ?>"
                                            class="flex justify-center items-center text-center w-full p-2 text-brand hover:bg-neutral-tertiary-medium hover:text-heading rounded-md">
                                            Add Match Result
                                        </a>
                                    </li>
                                <?php endif; ?>
                                

                                <!-- Allow user to update player match stats if has permission to update match and has result  -->
                                <?php if ((hasPermission('create_team')) && 
                                    ($match['result'] !== 'Pending')):?>
                                    <li>
                                        <a href="/match/match-player-stats?match_id=<?= $match['match_id'] ?>"
                                            class="inline-flex items-center text-brand text-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded-md">
                                            Add Player Match Stats
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <!-- Allow to update match details -->
                                <li>
                                    <a href="/match/update?match_id=<?= $match['match_id'] ?>"
                                        class="inline-flex items-center text-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded-md">
                                        Update Match Details
                                    </a>
                                </li>


                                <!-- If has permission to delete match -->
                                <?php if (hasPermission('delete_match')): ?>
                                    <!-- Delete Button -->
                                    <li>
                                        <form method="post" action="" name="action" value="delete">
                                            <input type="hidden" name="match_id" value="<?= h($match['match_id'] ?? '') ?>">
                                            <button type="submit" name="action" value="delete" 
                                                class="flex justify-center items-center text-center w-full p-2 text-fg-danger hover:bg-neutral-tertiary-medium rounded-md">
                                                Delete Match
                                            </button>
                                        </form>
                                    </li>
                                <?php endif;?>
                            <?php endif;?>
                        </ul>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
