<!-- Player Applications Table -->
<section class="bg-gray-50 dark:bg-gray-900">
    <?= title('Player',' Applications') ?>
    <table class="datatable">
        <thead>
            <tr>
                <th class="text-center">
                    <span class="flex items-center">
                        First Name
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                </th>
                <th class="text-center">
                    <span class="flex items-center">
                        Last Name
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                </th>
                <th class="text-center">
                    <span class="flex items-center">
                        Date of Birth
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                </th>
                <th class="text-center">
                    <span class="flex items-center">
                        Recommended Squad
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                </th>
                <th class="text-center">
                    <span class="flex items-center">
                        Application Status
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                </th>
                <th class="text-center">
                    <span class="flex items-center">
                        Submitted On
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
            <?php foreach($applications as $a): ?>
            <tr>
                <td class="font-medium text-heading whitespace-nowrap text-center">
                    <?= h($a['applicant_first_name']) ?>
                </td>
                <td class="text-center"><?= h($a['applicant_last_name']) ?></td>
                <td class="text-center"><?= h($a['applicant_dob']) ?></td>
                <td class="text-center"><?= h($a['recommended_squad']) ?></td>
                <!-- Badge Color for Application Status -->
                <td class="text-center">
                    <?php if($a['application_status'] === 'applied'): ?>
                        <span class="<?= badgeWarning()?>">
                            <?= strtoupper($a['application_status']) ?>
                        </span>
                    <?php elseif($a['application_status'] === 'rejected'): ?>
                        <span class="<?= badgeDanger()?>">
                            <?= strtoupper($a['application_status']) ?>
                        </span>
                    <?php else: ?>
                        <span class="<?= badgeBlue()?>">
                            <?= strtoupper($a['application_status']) ?>
                        </span>
                    <?php endif; ?>
                </td>
                <td class="text-center"><?= h($a['submitted_on']) ?></td>
                <td class="text-center">
                    <button id="playerApplicationAction<?=h($a['application_id'])?>" data-dropdown-toggle="playerApplicationDots<?=h($a['application_id'])?>" class="text-heading bg-neutral-primary box-border border border-transparent hover:bg-neutral-secondary-medium focus:ring-4 focus:ring-neutral-tertiary font-medium leading-5 rounded-base text-sm p-2 focus:outline-none" type="button"> 
                    <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-width="3" d="M6 12h.01m6 0h.01m5.99 0h.01"/></svg>
                    </button>
                        <!-- Dropdown menu -->
                        <div id="playerApplicationDots<?=h($a['application_id'])?>" class="z-10 hidden bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg w-44 dark:divide-gray-600">
                            <ul class="p-2 text-sm text-body font-medium" aria-labelledby="playerApplicationAction<?=h($a['application_id'])?>">
                            <!-- If decision has been made then hide other actions-->
                            <?php if($a['application_status'] === 'applied'):?>
                                <li>
                                    <form method="post" action="/player-applications/application-details">
                                        <input type="hidden" name="id" value="<?= $a['application_id'] ?>">
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit"
                                            class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                                            Approve
                                        </button>
                                    </form>
                                </li>
                                <li>
                                    <form method="post" action="/player-applications/application-details">
                                        <input type="hidden" name="id" value="<?= $a['application_id'] ?>">
                                        <input type="hidden" name="action" value="reject">
                                        <button type="submit"
                                            class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                                            Reject
                                        </button>
                                    </form>
                                </li>
                            <?php endif; ?>
                            <li>
                                <a href="/player-applications/application-details?id=<?= $a['application_id'] ?>" class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">
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
