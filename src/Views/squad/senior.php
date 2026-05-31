<section class="bg-gray-50 dark:bg-gray-900">
  <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0">
      <div class="<?= cardClassXLNoBg() ?>">
        <!-- Title -->
        <?= title($squad['squad_name'],' Squad')?>

        <!-- Players -->
        <?= titleLeftSmall('Squad Players') ?>
        <div class="grid w-full grid-cols-2 gap-4 mt-4 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
            <?php foreach ($players as $player): ?>
            <div class="flex flex-col items-center justify-between bg-white border border-gray-200 rounded-sm shadow-sm sm:flex dark:border-gray-700 pb-2 dark:bg-gray-800">
                    <!-- Player Image -->
                    <?php if (isset($player) || $player['section_id'] === 3): ?>
                        <img class="object-cover rounded-sm overflow-hidden" 
                            src="https://images.unsplash.com/photo-1581403341630-a6e0b9d2d257?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" 
                            alt="<?= $player['first_name'] ?>-player-image">
                    <?php endif; ?>

                    <!-- Player Details -->
                    <div class="flex-1 p-2 text-center">
                        <!-- Name -->
                        <?= h3(h($player['first_name'] .' '. $player['last_name'])) ?>

                        <!-- Position -->
                        <?php if (!empty($player['position'])):?>
                            <?= p('Position: '. h($player['position'] ?? 'N/A')) ?>
                        <?php endif ;?>

                        <!-- Height -->
                        <?= p('Height: '. h($player['height'] .' kg' ?? 'N/A')) ?>

                        <!-- Weight -->
                        <?= p('Weight: '. h($player['weight'] .' cm' ?? 'N/A')) ?>
                    </div>

            </div>

            <?php endforeach; ?>
        </div>

        <!-- Coaches -->
        <?= titleLeftSmall('Squad Coaches') ?>
        <div class="grid w-full grid-cols-2 gap-4 mt-4 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
            <?php foreach ($coaches as $coach): ?>
            <div class="flex flex-col items-center justify-between bg-white border border-gray-200 rounded-sm shadow-sm dark:border-gray-700 pb-2 dark:bg-gray-800">
                <!-- Coach Image -->
                <img class="object-cover rounded-sm overflow-hidden" 
                    src="https://images.unsplash.com/photo-1581403341630-a6e0b9d2d257?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" 
                    alt="<?= $player['coach_name'] ?>-coach-image">

                <!-- Coach Details -->
                <div class="flex-1 p-2 text-center">
                    <!-- Name -->
                    <?= h3(h($coach['coach_name'])) ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    </div>
  </div>
</section>
