<!-- Squad Table -->
<section class="bg-gray-50 dark:bg-gray-900 dark:text-white">
    <!-- All Squads for Higher Admins -->
    <?php if(isAdmin()):?>
        <?= title('Player','Injuries')?>
    <!-- Coach -->
    <?php else: ?>
        <?= title($data[0]['squad_name'], 'Injuries') ?>
    <?php endif; ?>

    <table class="datatable">
        <thead>
            <tr>
                <th class="text-center">
                    <span class="flex items-center">
                        Player Name
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                </th>
                
                <th class="text-center">
                    <span class="flex items-center">
                        Injury Name
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                </th>
                <th class="text-center">
                    <span class="flex items-center">
                        Date Injured
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                </th>
                <th class="text-center">
                    <span class="flex items-center">
                        Date Recovered
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                </th>
                <th class="text-center">
                    <span class="flex items-center">
                        Status
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
            <?php foreach($data as $player): ?>
            <tr>
                <td class="font-medium text-heading whitespace-nowrap text-center">
                    <?= h($player['player_name']) ?>
                </td>
                <td class="text-center"><?= h($player['injury_name']) ?></td>
                <td class="text-center"><?= h($player['injury_date']) ?></td>
                <td class="text-center"><?= h($player['recovery_date'] ?? 'N/A') ?></td>
                <td class="text-center">
                    <span class="
                        <?= 
                            $player['injury_status'] === 'Active' ? badgeDanger() :
                            ($player['injury_status'] === 'Recovering' ? badgeWarning() : badgeBlue())
                        ?>">
                        <?= h($player['injury_status'] ?? '') ?>
                    </span>
                </td>

                <td class="text-center">
                    <button id="injuryListAction<?=h($player['member_id'])?>" data-dropdown-toggle="injuryListDots<?=h($player['member_id'])?>" class="text-heading bg-neutral-primary box-border border border-transparent hover:bg-neutral-secondary-medium focus:ring-4 focus:ring-neutral-tertiary font-medium leading-5 rounded-base text-sm p-2 focus:outline-none" type="button"> 
                        <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-width="3" d="M6 12h.01m6 0h.01m5.99 0h.01"/></svg>
                    </button>
                    
                    <!-- Dropdown menu -->
                    <!-- View all player injuries -->
                    <div id="injuryListDots<?=h($player['member_id'])?>" class="min-w-max z-10 hidden bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg w-44 dark:divide-gray-600">
                        <ul class="p-2 text-sm text-body font-medium" aria-labelledby="injuryListAction<?=h($player['player_injury_id'])?>">
                              <li>
                                <a href="/player?id=<?=h(($player['member_id']))?>#injuries" class="inline-flex  justify-center items-center w-full p-1 hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                                    View All Injuries
                                </a>    
                            </li>
                        </ul>

                        <!-- update_injury -->
                        <?php if(hasPermission('record_injury')): ?>
                            <ul class="p-2 text-sm text-body font-medium" aria-labelledby="injuryListAction<?=h($player['player_injury_id'])?>">
                                <li>
                                    <a href="/injury/update?player_injury_id=<?=h(($player['player_injury_id']))?>" class="inline-flex  justify-center items-center w-full p-1 hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                                        Update Injury Status
                                    </a>    
                                </li>
                            </ul>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
