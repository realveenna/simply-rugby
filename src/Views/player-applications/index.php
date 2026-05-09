<!-- Player Applications Table -->
<section class="bg-gray-50 dark:bg-gray-900">
    <table id="default-table">
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Date of Birth</th>
                <th>Recommended Squad</th>
                <th>Application Status</th>
                <th>Submitted On</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach($applications as $a): ?>
            <tr>
                <td><?= h($a['applicant_first_name']) ?></td>
                <td><?= h($a['applicant_last_name']) ?></td>
                <td><?= h($a['applicant_dob']) ?></td>
                <td><?= h($a['recommended_squad']) ?></td>
                <!-- Badge Color for Application Status -->
                <td>
                    <?php if($a['application_status'] === 'applied'): ?>
                        <span class="<?= badgeBlue()?>">
                            <?= strtoupper($a['application_status']) ?>
                        </span>
                    <?php else: ?>
                        <span class="<?= badgeGray()?>">
                            <?= strtoupper($a['application_status']) ?>
                        </span>
                    <?php endif; ?>
                </td>
                <td><?= h($a['submitted_on']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
