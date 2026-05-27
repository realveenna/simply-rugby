<section class="bg-gray-50 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center px-1 sm:px-4 lg:px-6 py-3 sm:py-6 mx-auto">
        <div class="<?= cardClassXLNoBg() ?>">
            <!-- Empty Match -->
            <?php if(empty($match)): ?>
                    <?= title('No Match', 'Record') ?>

            <!--  Lineup Details -->
            <?php else: ?>
                <div class="<?= formPadding() ?>">
                <?= title($match->squad_name.' Match ', 'Details') ?>
                    <div class="<?= formPadding() ?>">
                        <!-- Dropdown Button with Permission Access -->
                        <div class="relative bg-neutral-primary-soft w-full p-6 border border-default rounded-base shadow-xs">
                            <button id="ddMatchMenuBtn" data-dropdown-toggle="ddMatchMenu" class="absolute top-2 end-2 text-body hover:text-heading bg-neutral-primary-soft box-border border border-transparent hover:bg-neutral-tertiary focus:ring-4 focus:ring-neutral-tertiary rounded-base p-1.5 focus:outline-none" type="button">
                                <span class="sr-only">Open dropdown</span>
                                <svg class="w-6 h-6 text-brand" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-width="3" d="M6 12h.01m6 0h.01m5.99 0h.01"/></svg>
                            </button>

                            <!-- Dropdown menu -->
                            <div id="ddMatchMenu" class="min-w-max z-10 bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg w-36 block hidden">
                                <ul class="p-2 text-sm text-body font-medium" aria-labelledby="ddMatchMenuBtn">
                                <!-- If has hasPermission to update match show buttons -->
                                    <?php if (hasPermission('update_match')): ?>
                                        <!-- Update Match -->
                                        <li>
                                            <a href="/match/edit?match_id=<?= $match->match_id ?>"
                                                class="inline-flex items-center text-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded-md">
                                                Update Match Details
                                            </a>
                                        </li>
                                        <?php if (empty($match->result === 'Pending')): ?>
                                        <!-- Add Match Result -->
                                            <li>
                                                <a href="/match/result?match_id=<?= $match->match_id ?>"
                                                    class="inline-flex items-center text-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded-md">
                                                    Add Match Result
                                                </a>
                                            </li>

                                        <!-- Past Match -->
                                        <?php else: ?>
                                            <li>
                                                <a href="/match/result?match_id=<?= $match->match_id ?>"
                                                    class="inline-flex items-center text-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded-md">
                                                    Update Match Result
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    <?php endif;?>

                                    <!-- If has permission to delete match -->
                                    <?php if (hasPermission('delete_match')): ?>
                                        <!-- Delete Button -->
                                        <li>
                                            <form method="post" action="" name="action" value="delete">
                                                <input type="hidden" name="match_id" value="<?= h($match->match_id ?? '') ?>">
                                                <button type="submit" name="action" value="delete" 
                                                    class="flex justify-center items-center text-center w-full p-2 text-fg-danger hover:bg-neutral-tertiary-medium rounded-md">
                                                    Delete Match
                                                </button>
                                            </form>
                                        </li>
                                    <?php endif;?>
                                </ul>
                            </div>

                            <div class="flex flex-col items-center">
                                <!-- Date and time -->
                                <span class="tracking-wide text-brand-strong md:text-sm font-medium ">
                                    <?= h(formatDateDisplay($match->match_date) ?? '') ?> -
                                        <?= h(formatTime($match->kick_off_time) ?? '') ?>
                                </span>
                                <!-- Team Names -->
                                <div class="flex justify-center gap-5 w-full py-4">
                                    <!-- Simply Rugby Team -->
                                    <div class="flex flex-col items-center flex-1">
                                        <img class="w-15 h-15" src="/images/logo/main-logo.png" alt="simply-rugby-logo">
                                        <h5 class="text-xl font-semibold tracking-tight text-heading">
                                            <?= strtoupper(h($match->squad_name)) ?? '' ?>
                                        </h5>
                                    </div>

                                    <!-- Home or Away Badge -->
                                    <div class="flex items-center justify-center text-center min-w-25 mx-5">
                                        <!-- Home -->
                                        <?php if($match->match_venue === 'Home'): ?>
                                            <span class="<?= badgeBlue()?>">
                                                <?= h($match->match_venue)  ?>
                                            </span>
                                        <!-- All Marked and Past Date-->
                                        <?php elseif($match->match_venue === 'Away'): ?>
                                            <span class="<?= badgeSuccess()?>">
                                                <?= h($match->match_venue)  ?>
                                            </span>
                                        <!-- None -->
                                        <?php else :?>
                                            <span class="<?= badgeWarning()?>">
                                                TBD
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Opponent Team -->
                                    <div class="flex flex-col items-center flex-1">
                                        <img class="w-15 h-15" src="/images/logo/main-logo.png" alt="simply-rugby-logo">
                                        <h5 class="text-xl font-semibold tracking-tight text-heading">
                                            <?= strtoupper(h($match->opposition_team_name)) ?? '' ?>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lineup -->
                    <div class="<?= formPadding() ?>">
                        <?= titleLeftSmall('Match Lineup'); ?>

                        <!-- Message if requires attention -->
                        <?php if(!empty($message)):?>
                            <p class="text-danger mb-6 font-semibold"><?= $message ?> </p>
                        <?php endif;?>

                        <!-- Form for lineup selection -->
                        <div class="bg-neutral-primary-soft border border-default rounded-base shadow-xs">
                            <form class="<?= formClass() ?> p-6" method="post" action="">
                                <input type="hidden" name="match_id" value="<?= h($match->match_id ?? '') ?>">
                                <ul role="list" class="space-y-3 divide-y divide-default">
                                    <?php foreach ($positions as $key => $position): ?>
                                    <li class="grid gap-2 mb-6 md:grid-cols-2 pb-3">
                                        <span>
                                            <?= h($position) ?>
                                        </span>
                                            <span> 
                                                <!-- Select player for position -->
                                                <select class="<?= inputClass()?>" name="<?= $key ?>">
                                                    <option value="" disabled selected> 
                                                        Select a Player: 
                                                    </option>
                                                    
                                                    <!-- Player dropdown -->
                                                    <?php foreach ($lineup as $player): ?>
                                                        <option value="<?= $player['player_id'] ?>"
                                                            <?= (($player['position'] ?? '') === $position) ? 'selected' : '' ?>>
                                                            <?= h($player['player_name']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </span>
                                        <!-- Position error -->
                                        <p class="<?= smallError() ?>">
                                            <?php echo h($errors[$position] ?? '');?>
                                        </p>
                                    </li>
                                    <?php endforeach; ?>

                                    <!-- Duplicate error -->
                                    <li>
                                        <p class="<?= smallError() ?>"> 
                                            <?php echo h($errors['duplicate'] ?? '');?>
                                        </p>
                                    </li>
                                </ul>

                                <!-- Button if user has permission to update match lineup -->
                                <?php if ((hasPermission('create_team')) && 
                                    ($match->result === 'Pending')):?>
                                    <div class="flex justify-end">
                                        <button type="submit" class="<?=primaryBtn()?> ">
                                            Update Match Lineup
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                <?php endif ;?>
            </div>
        </div>
    </div>
</section>