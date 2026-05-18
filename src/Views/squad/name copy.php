<!-- Squad Table -->
<section class="bg-gray-50 dark:bg-gray-900">
    <!-- Title -->
    <?= title($squad['squad_name'],'Squad')?>
    
    <!-- Datatable -->
    <table class="datatable">
        <thead>
            <tr>
                <th class="text-center">
                    <span class="flex items-center">
                        SRU Number
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                </th>
                <th class="text-center">
                    <span class="flex items-center">
                        Name
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                </th>
                <th class="text-center">
                    <span class="flex items-center">
                        Position
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                </th>
                <th class="text-center">
                    <span class="flex items-center">
                        Weight
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                </th>
                <th class="text-center">
                    <span class="flex items-center">
                        Height
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                </th>
                <th class="text-center">
                    <span class="flex items-center">
                        Availability Status
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
            <?php foreach($players as $player): ?>
            <tr>
                <td class="font-medium text-heading whitespace-nowrap text-center">
                    <?= h($player['sru_number']) ?>
                </td>
                <td class="text-center"><?= ucwords(h($player['first_name'])) .' '.ucwords(h($player['last_name'])) ?> </td>
                <td class="text-center"><?= h($player['position']) ?: 'N/A' ?></td>
                <td class="text-center"><?= h($player['weight']) ?></td>
                <td class="text-center"><?= h($player['height']) ?></td>
                <td class="text-center">
                    <!-- Badge Color for Availability Status -->
                    <?php if ($player['player_availability_status']  === 'Available') :?>
                        <span class="<?= badgeSuccess()?>">
                            <?= ucfirst($player['player_availability_status']) ?>
                        </span>
                    <?php else: ?>
                        <span class="<?= badgeDanger()?>">
                            <?= ucfirst($player['player_availability_status']) ?>
                        </span>
                    <?php endif; ?>
                </td>

                <td class="text-center">
                    <button id="squadPlayerAction<?=h($player['member_id'])?>" data-dropdown-toggle="squadPlayerDots<?=h($player['member_id'])?>" class="text-heading bg-neutral-primary box-border border border-transparent hover:bg-neutral-secondary-medium focus:ring-4 focus:ring-neutral-tertiary font-medium leading-5 rounded-base text-sm p-2 focus:outline-none" type="button"> 
                        <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-width="3" d="M6 12h.01m6 0h.01m5.99 0h.01"/></svg>
                    </button>
                    <!-- Dropdown menu -->
                    <div id="squadPlayerDots<?=h($player['member_id'])?>" class="z-10 hidden bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg w-44 dark:divide-gray-600">
                        <ul class="p-2 text-sm text-body font-medium" aria-labelledby="squadPlayerAction<?=h($player['member_id'])?>">
                              <li>
                                <a href="/player?id=<?=h(($player['member_id']))?>" class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                                    View All Players
                                </a>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Permission Action Buttons -->
    <div class="flex mt-4 md:mt-6 gap-4">

        <!-- create_training_session -->
        <?php if(hasPermission('create_training_session')): ?>
            <button type="button" class="inline-flex items-center text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                Create Training
            </button>
        <?php endif; ?>

        <!-- create_match -->
        <?php if(hasPermission('create_match')): ?>
            <button type="button" class="inline-flex self-start w-auto text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
                Create Match
            </button>
        <?php endif; ?>
    </div>
</section>
