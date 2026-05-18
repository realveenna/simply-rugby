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
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
