
<section class="bg-gray-50 dark:bg-gray-900">
    <table id="default-table">
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Date of Birth</th>
                <th>Mobile Number</th>
                <th>Membership Status</th>
                <!-- <th>Roles</th> -->
            </tr>
        </thead>

        <tbody>
            <?php foreach($members as $member): ?>
            <tr>
                <td><?= h($member['first_name']) ?></td>
                <td><?= h($member['last_name']) ?></td>
                <td><?= h($member['dob']) ?></td>
                <td><?= h($member['mobile_num']) ?></td>

                <!-- Badge Color for Membership Status -->
                <td>
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

                <!-- <td> role </td> -->
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>






<div role="status" class="flex items-center justify-center h-56 max-w-sm bg-neutral-quaternary rounded-base animate-pulse">
    <svg class="w-11 h-11 text-fg-disabled" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M10 3v4a1 1 0 0 1-1 1H5m14-4v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1ZM9 12h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1v-2a1 1 0 0 1 1-1Zm5.697 2.395v-.733l1.269-1.219v2.984l-1.268-1.032Z"/></svg>
    <span class="sr-only">Loading...</span>
</div>
