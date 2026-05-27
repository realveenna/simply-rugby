<!-- List of all members with email but no login -->
<section class="bg-gray-50 dark:bg-gray-900">
    <?= title('Members Without','Logins')?>

    <!-- Table -->
    <table class="datatable">
        <thead>
            <tr>
                <th class="text-center">
                    <span class="flex items-center">
                        Name
                    </span>
                </th>
                <th class="text-center">
                    <span class="flex items-center">
                        Email Address
                    </span>
                </th>
                <th class="text-center">
                    <span class="flex items-center">
                        Mobile Number
                    </span>
                </th>
                <th class="text-center">
                    <span class="flex items-center">
                        Membership Status
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
                <td class="text-center"><?= h($member['first_name']) ?> <?= h($member['last_name']) ?></td>
                <td class="text-center"><?= h($member['email']) ?></td>
                <td class="text-center"><?= h($member['mobile_num']) ?></td>

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

                <!-- Action -->
                <td class="text-center">
                    <button id="memberNoLoginAction<?=h($member['member_id'])?>" data-dropdown-toggle="memberNoLoginDots<?=h($member['member_id'])?>" class="text-heading bg-neutral-primary box-border border border-transparent hover:bg-neutral-secondary-medium focus:ring-4 focus:ring-neutral-tertiary font-medium leading-5 rounded-base text-sm p-2 focus:outline-none" type="button"> 
                    <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-width="3" d="M6 12h.01m6 0h.01m5.99 0h.01"/></svg>
                    </button>
                    
                    <!-- Dropdown menu -->
                    <div id="memberNoLoginDots<?=h($member['member_id'])?>" class="min-w-max  z-10 hidden bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg w-44 dark:divide-gray-600">
                        <ul class="p-2 text-sm text-body font-medium" aria-labelledby="memberNoLoginAction<?=h($member['member_id'])?>">
                        <li>
                            <a href="/members/create-login?member_id=<?= $member['member_id'] ?>" class="inline-flex items-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                                Create Login
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
