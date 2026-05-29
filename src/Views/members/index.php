<!-- Member Applications Table -->
 <section class="bg-gray-50 dark:bg-gray-900">
    <?= title('All','Members')?>

    <!-- Filter by role Dropdown -->
    <div class="py-4 flex justify-end">
        <button id="memberRoleButton" data-dropdown-toggle="memberRole" class="inline-flex items-center justify-center text-white bg-brand box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none" type="button">
            Filter By:
        <svg class="w-4 h-4 ms-1.5 -me-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/></svg>
        </button>

        <!-- Dropdown menu -->
        <div id="memberRole" class=" min-w-max  z-10 hidden bg-neutral-primary-medium border border-default-medium rounded-base divide-y divide-default-medium shadow-lg w-44">
            <ul class="p-2 text-sm text-body font-medium" aria-labelledby="memberRoleButton">
                <!-- All -->
                <li>
                    <a href="/members"
                        class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                        All Members
                    </a>
                </li>

                <!-- Junior -->
                <li>
                    <a href="/members?role=Junior Player"
                        class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                        Junior Players
                    </a>
                </li>

                <!-- Senior -->
                <li>
                    <a href="/members?role=Senior Player"
                        class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                        Senior Players
                    </a>
                </li>

                <!-- Coaches -->
                <li>
                    <a href="/members?role=Coach"
                        class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                        Coaches
                    </a>
                </li>

                <!-- Parents -->
                <li>
                    <a href="/members?role=Parent"
                        class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                        Parents
                    </a>
                </li>

                <!-- Admin -->
                <li>
                    <a href="/members?role=Admin"
                        class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                        Admin
                    </a>
                </li>
            </ul>
        </div>
    </div>


    <!-- Table -->
    <table class="datatable">
        <thead>
            <tr>
                <th class="text-center">
                    <span class="flex items-center">
                        Full Name
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                </th>
               
                <th class="text-center">
                    <span class="flex items-center">
                        Email
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                </th>
                <th class="text-center">
                    <span class="flex items-center">
                        Mobile Number
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                </th>
                <th class="text-center">
                    <span class="flex items-center">
                        Membership Status
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                </th>
                <th class="text-center">
                    <span class="flex items-center">
                        Role
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
            <?php foreach($members as $member): ?>
            <tr>
                <td class="font-medium text-heading whitespace-nowrap text-center">
                    <?= h($member['first_name']) ?> <?= h($member['last_name']) ?>
                </td>
                <td class="text-center"><?= h($member['email'] ?? 'N/A') ?></td>
                <td class="text-center"><?= h($member['mobile_num'] ?? 'N/A') ?></td>

                <!-- Badge Color for Membership Status -->
                <td class="text-center">
                    <?php if($member['membership_status'] === 'active'): ?>
                        <span class="<?= badgeBlue()?>">
                            <?= strtoupper($member['membership_status']) ?>
                        </span>
                    <?php else: ?>
                        <span class="<?= badgeGray()?>">
                            <?= strtoupper($member['membership_status']) ?>
                        </span>
                    <?php endif; ?>
                </td>

                <!-- Role -->
                <td class="text-center">
                    <?= h($member['roles']) ?>
                </td>
                <!-- Action -->
                <td class="text-center">
                    <button id="memberAction<?=h($member['member_id'])?>" data-dropdown-toggle="memberDots<?=h($member['member_id'])?>" class="text-heading bg-neutral-primary box-border border border-transparent hover:bg-neutral-secondary-medium focus:ring-4 focus:ring-neutral-tertiary font-medium leading-5 rounded-base text-sm p-2 focus:outline-none" type="button"> 
                    <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-width="3" d="M6 12h.01m6 0h.01m5.99 0h.01"/></svg>
                    </button>

                    <!-- Dropdown menu -->
                    <div id="memberDots<?=h($member['member_id'])?>" class="min-w-max  z-10 hidden bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg w-44 dark:divide-gray-600">
                        <ul class="p-2 text-sm text-body font-medium" aria-labelledby="memberAction<?=h($member['member_id'])?>">
                            <li>
                                <a href="/members/view?member_id=<?= $member['member_id'] ?>" class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                                    View Details
                                </a>
                            </li>
                            <li>
                                <a href="/members/view?member_id=<?= $member['member_id'] ?>" class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                                    Remove
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
