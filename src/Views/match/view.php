<section class="bg-gray-50 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center px-1 sm:px-4 lg:px-6 py-3 sm:py-6 mx-auto">
        <div class="<?= cardClassXLNoBg() ?>">
            <!-- Empty Match -->
            <?php if(empty($match)): ?>
                    <?= title('No Match', 'Record') ?>

            <!--  MATCH DETAILS -->
            <?php else: ?>
                <div class="<?= formPadding() ?>">
                <?= title($match['squad_name'], 'Match') ?>
                    <!-- Match Details -->
                    <div class="<?= formPadding() ?>">
                        <?= titleLeftSmall($title ?? ''); ?>
                        <!-- Include match card -->
                        <?php require '../src/includes/match-card.php'; ?>
                    </div>

                    <!-- Match Result -->
                    <?php if(isFutureDate($match['match_date'] && in_array($match['result'], ['Win', 'Lose', 'Draw']))):?>
                        <div class="<?= formPadding() ?>">
                            <!-- First half -->
                            <?= titleLeftSmall('First Half'); ?>
                            <div class="grid gap-2 md:grid-cols-2 items-start">
                                <div class="bg-neutral-primary-soft border border-default rounded-base shadow-xs h-full">
                                    <ul role="list" class="space-y-3 p-6 divide-y divide-default">
                                        <li class="flex flex-col pb-3">
                                            <span class="font-bold mb-4">
                                                <?= h($match['squad_name'])?>
                                            </span>
                                        
                                            <span class="font-large">
                                                Points: <?= h($halves[0]['our_points'])?>
                                            </span>
                                        </li>
                                        <li class="flex flex-col pb-3">
                                            <span>
                                                Comments:
                                            </span>
                                            <span>
                                                <?= h($halves[0]['our_comments'])?>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="bg-neutral-primary-soft border border-default rounded-base shadow-xs h-full">
                                    <ul role="list" class="space-y-3 p-6 divide-y divide-default">
                                        <li class="flex flex-col pb-3">
                                            <span class="font-bold mb-4">
                                                <?= h($match['opposition_team_name'])?>
                                            </span>
                                        
                                            <span class="font-large">
                                                Points: <?= h($halves[0]['opponent_points'])?>
                                            </span>
                                        </li>
                                        <li class="flex flex-col pb-3">
                                            <span>
                                                Comments:
                                            </span>
                                            <span>
                                                <?= h($halves[0]['opponent_comments'])?>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Second Half -->
                         <div class="<?= formPadding() ?>">
                            <?= titleLeftSmall('Second Half'); ?>
                            <div class="grid gap-2 md:grid-cols-2 items-start">
                                <div class="bg-neutral-primary-soft border border-default rounded-base shadow-xs h-full">
                                    <ul role="list" class="space-y-3 p-6 divide-y divide-default">
                                        <li class="flex flex-col pb-3">
                                            <span class="font-bold mb-4">
                                                <?= h($match['squad_name'])?>
                                            </span>
                                        
                                            <span class="font-large">
                                                Points: <?= h($halves[1]['our_points'])?>
                                            </span>
                                        </li>
                                        <li class="flex flex-col pb-3">
                                            <span>
                                                Comments:
                                            </span>
                                            <span>
                                                <?= h($halves[1]['our_comments'])?>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="bg-neutral-primary-soft border border-default rounded-base shadow-xs h-full">
                                    <ul role="list" class="space-y-3 p-6 divide-y divide-default">
                                        <li class="flex flex-col pb-3">
                                            <span class="font-bold mb-4">
                                                <?= h($match['opposition_team_name'])?>
                                            </span>
                                        
                                            <span class="font-large">
                                                Points: <?= h($halves[1]['opponent_points'])?>
                                            </span>
                                        </li>
                                        <li class="flex flex-col pb-3">
                                            <span>
                                                Comments:
                                            </span>
                                            <span>
                                                <?= h($halves[1]['opponent_comments'])?>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endif;?>
                        

                    <!-- Lineup -->
                    <div class="<?= formPadding() ?>">
                        <?= titleLeftSmall($match['squad_name']. ' Lineup'); ?>
                          <!-- Message if requires attention -->
                        <?php if(!empty($message) && (hasRole('Coach') || isAdmin())):?>
                            <p class="text-danger mb-6 font-semibold"><?= $message ?? '' ?> </p>
                        <?php endif;?>

                        <div class="bg-neutral-primary-soft border border-default rounded-base shadow-xs h-full">
                            <ul role="list" class="space-y-3 p-6 divide-y divide-default">
                                <?php foreach ($lineup as $player): ?>
                                    <li class="flex items-center justify-between pb-3">
                                        <span>
                                            <?= h($player['player_name']) ?>
                                        </span>
                                        <span class="text-body font-medium">
                                            <!-- If null position and has role access display badge -->
                                            <?php if (empty($player['position']) 
                                                && (hasPermission('update_team'))): ?>
                                                <span class="<?= badgeDanger() ?>">
                                                    Require Position
                                                </span>
                                            <?php else: ?>
                                                <?= h($player['position'] ?? 'TBA') ?>
                                            <?php endif; ?>
                                        </span>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <div class="flex gap-4 justify-end flex-wrap">
                            <!-- Button if user has permission to update match lineup -->
                            <?php if ((hasPermission('create_team')) && $match['result'] === 'Pending'):?>
                                <a href="/match/lineup?match_id=<?= $match['match_id']?>" 
                                    class="flex justify-end">
                                    <button type="button" class="<?=primaryBtnNoBG()?> ">
                                        Update Match Lineup
                                    </button>
                                </a>
                            <?php endif; ?>

                            <!-- Button if user has permission to record injury -->
                            <?php if ((hasPermission('record_injury')) && $match['result'] !== 'Pending'):?>
                                <a href="/injury?match_id=<?= $match['match_id']?>" 
                                    class="flex justify-end">
                                    <button type="button" class="<?=primaryBtnNoBG()?> ">
                                        Record Injury
                                    </button>
                                </a>
                            <?php endif; ?>

                            <!-- Add Player's stats if user has permission to create match lineup -->
                            <?php if ((hasPermission('create_team')) && 
                                $match['result'] !== 'Pending'):?>
                                <a href="/match/match-player-stats?match_id=<?= $match['match_id']?>" 
                                    class="flex justify-end">
                                    <button type="button" class="<?=primaryBtnNoBG()?> ">
                                        Update Match Player Stats
                                    </button>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                
                    <!-- Coaches -->
                    <div class="<?= formPadding() ?>">
                        <?= titleLeftSmall($match['squad_name']. ' Coches'); ?>
                        <div class="bg-neutral-primary-soft border border-default rounded-base shadow-xs h-full">
                            <ul role="list" class="space-y-3 p-6 divide-y divide-default">
                                <?php foreach ($coaches as $coach): ?>
                                    <li class="flex items-center justify-between pb-3">
                                        <span>
                                            <?= h($coach['coach_name']) ?>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>

                    <!-- If has permission to delete match -->
                    <?php if (hasPermission('delete_match')): ?>
                        <!-- Delete Button -->
                        <li>
                            <form method="post" action="/match/view" name="action" value="delete">
                                <input type="hidden" name="match_id" value="<?= h($match['match_id'] ?? '') ?>">
                                <button type="submit" name="action" value="delete" 
                                class="<?= dangerBtn() ?>">
                                    Delete Match
                                </button>
                            </form>
                        </li>
                    <?php endif;?>

                <?php endif ;?>
            </div>
        </div>
    </div>
</section>
