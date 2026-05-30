<section class="bg-gray-50 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center px-1 sm:px-4 lg:px-6 py-3 sm:py-6 mx-auto">
        <div class="<?= cardClassXLNoBg() ?>">

            <?php if(!empty($seniorUpcoming) || !empty($seniorPast)): ?>
                <?= title('Senior', 'Matches') ?>
            <?php endif; ?>
            

            <!-- SENIOR UPCOMING MATCH -->
            <?php if(!empty($seniorUpcoming)): ?>
                <div class="<?= formPadding() ?>">
                    <?= titleLeftSmall('Upcoming Match'); ?>
                    <?php foreach($seniorUpcoming as $match): ?>
                        <?php require '../src/includes/match-card.php'; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <!-- SENIOR PAST MATCH -->
            <?php if(!empty($seniorPast)): ?>
                <div class="<?= formPadding() ?>">
                    <?= titleLeftSmall('Past Match'); ?>
                    <?php foreach($seniorPast as $match): ?>
                        <?php require '../src/includes/match-card.php'; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>


            <!-- JUNIOR UPCOMING MATCH WITH PERMISSION -->
            <?php if(!empty($juniorUpcoming) || !empty($juniorPast)): ?>
                <?php if(hasPermission('view_junior_match')): ?>
                    <?= title('Junior', 'Matches') ?>
                    <?php if(!empty($juniorUpcoming)): ?>
                        <div class="<?= formPadding() ?>">
                            <?= titleLeftSmall('Upcoming Match'); ?>
                            
                            <?php foreach($juniorUpcoming as $match): ?>
                                <?php require '../src/includes/match-card.php'; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                <!-- JUNIOR PAST MATCH -->
                <?php if(!empty($juniorPast)): ?>
                    <div class="<?= formPadding() ?>">
                        <?= titleLeftSmall('Past Match'); ?>
                        <?php foreach($juniorPast as $match): ?>
                            <?php require '../src/includes/match-card.php'; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
        </div>
    </div>
</section>
