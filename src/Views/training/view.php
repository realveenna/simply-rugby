<!-- Attendance Record Sheet -->
<section class="bg-gray-50 dark:bg-gray-900 dark:text-white">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0">
        <div class="<?= cardClass() ?> ">
          <div class="<?= formPadding() ?>">
            <a href="/" class="flex flex-col items-center justify-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
                <img class="w-8 h-8 mr-2" src="/images/logo/main-logo.png" alt="logo">
            </a>  
            
            <?= h2Center( h($training['squad_name']) .' Training Session') ?>

            <!-- Include training details -->
            <?php require '../src/includes/training_details.php'; ?>
            
            
            <!-- List of Players -->
            <form class="<?= formClass() ?>" method="post">
                <input type="hidden" name="training_session_id" value="<?= $training['training_session_id'] ?>">
                <?= h3('List of Players:') ?>

                <!-- No players -->
                <?php if (empty($players)) : ?>
                    <p class="text-body text-center">No players found.</p>

                <!-- List Players -->
                <?php else : ?>
                <ul class="max-w-md space-y-1 text-body">
                    <?php foreach ($players as $player) :?>
                        <li class="py-1">
                            <!-- Attendance Badge -->
                            <?php if (($player['attendance_status'] ?? '') === 'Present') : ?>
                                <span class="<?= badgeSuccess() ?>">
                                    Present
                                </span>

                            <?php elseif (($player['attendance_status'] ?? '') === 'Absent') : ?>
                                <span class="<?= badgeDanger() ?>">
                                    Absent
                                </span>

                            <?php elseif (($player['attendance_status'] ?? '') === 'Late') : ?>
                                <span class="<?= badgeWarning() ?>">
                                    Late
                                </span>

                            <?php else : ?>
                                <span>
                                </span>
                            <?php endif; ?>
                            
                            <span class="px-4">
                                <!-- Player Name -->
                                <?= $player['player_name'] ?>
                            </span>
                        </li>
                    <?php endforeach ;?>
                    </ul>
                <?php endif ;?>

                <!-- Buttons -->
                <div class="grid gap-2 mb-6 md:grid-cols-2">
                    <!-- update_training_session -->
                    <?php if(hasPermission('update_training_session')): ?>
                        <button type="button" class="<?= secondaryBtn() ?>">
                            <a href="/training/update?training_session_id=<?=h(($training['training_session_id']))?>">
                                Update
                            </a>
                        </button>
                        
                    <!-- Delete Button -->
                    <button type="submit" name="action" value="delete"
                        class="<?=dangerBtn()?> ">
                        Delete
                    </button>
                    <?php endif; ?>
                </div>

                    <?php if((int)$training['pending_count'] > 0 && (hasPermission('record_attendance'))):?>
                        <!-- Record Attendance Button -->
                        <a href="/training/record_attendance?training_session_id=<?=h($training['training_session_id'])?>">
                            <button type="button"
                                class="<?=primaryBtn()?> w-full">
                                Record Attendance
                            </button>
                        </a>
                    <?php endif;?>
                </div>
            </form>
        </div>
      </div>
</section>
