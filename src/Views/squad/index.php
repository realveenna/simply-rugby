<!-- Squad Table -->
<section class="bg-gray-50 dark:bg-gray-900">
    <table class="datatable">
        <thead>
            <tr>
                <th class="text-center">
                    <span class="flex items-center">
                        Squad ID
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                </th>
                <th class="text-center">
                    <span class="flex items-center">
                        Squad Name
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                </th>
                <th class="text-center">
                    <span class="flex items-center">
                        Squad Type
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                </th>
                <th class="text-center">
                    <span class="flex items-center">
                        Season
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                </th>
                <th class="text-center">
                    <span class="flex items-center">
                        Number of Players
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
            <?php foreach($squads as $s): ?>
            <tr>
                <td class="font-medium text-heading whitespace-nowrap text-center">
                    <?= h($s['squad_id']) ?>
                </td>
                <td class="text-center"><?= h($s['squad_name']) ?></td>
                <td class="text-center"><?= h($s['squad_type']) ?></td>
                <td class="text-center"><?= h($s['season']) ?></td>
                <td class="text-center"><?= h($s['player_count']) ?></td>
                <td class="text-center">
                    <button id="squadListAction<?=h($s['squad_id'])?>" data-dropdown-toggle="squadListDots<?=h($s['squad_id'])?>" class="text-heading bg-neutral-primary box-border border border-transparent hover:bg-neutral-secondary-medium focus:ring-4 focus:ring-neutral-tertiary font-medium leading-5 rounded-base text-sm p-2 focus:outline-none" type="button"> 
                        <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-width="3" d="M6 12h.01m6 0h.01m5.99 0h.01"/></svg>
                    </button>
                    <!-- Dropdown menu -->
                    <div id="squadListDots<?=h($s['squad_id'])?>" class="z-10 hidden bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg w-44 dark:divide-gray-600">
                        <ul class="p-2 text-sm text-body font-medium" aria-labelledby="squadListAction<?=h($s['squad_id'])?>">
                              <li>
                                <a href="/squad?type=<?=h(($s['squad_type']))?>" class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                                    View Details
                                </a>    
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
