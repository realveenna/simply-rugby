<section class="bg-gray-50 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center px-1 sm:px-4 lg:px-6 py-3 sm:py-6 mx-auto">
        <div class="<?= cardClassXLNoBg() ?>">
            <!-- Empty Match -->
            <?php if(empty($match)): ?>
                    <?= title('No Match', 'Record') ?>

            <!--  Lineup Details -->
            <?php else: ?>
                <div class="<?= formPadding() ?>">

                <?= title($match['squad_name'].' Match Player', 'Stats') ?>
                    <div class="<?= formPadding() ?>">
                        <!-- Include match card -->
                        <?php require '../src/includes/match-card.php'; ?>
                    </div>

                <!-- Form for Player Match Stats -->
                <form method="post">
                    <!-- Table -->
                    <div class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default">
                        <table class="w-full text-sm text-left rtl:text-right text-body">
                            <thead class="text-sm text-body bg-neutral-secondary-soft border-b rounded-base border-default">
                                <tr>
                                    <th scope="col" class="px-6 py-3 font-medium text-center">
                                        Name
                                    </th>
                                    <th scope="col" class="px-6 py-3 font-medium text-center">
                                        Position
                                    </th>
                                    <th scope="col" class="px-3 py-2 font-medium text-center">
                                        Mins Played
                                    </th>
                                    <th scope="col" class="px-3 py-2 font-medium text-center">
                                        Tries
                                    </th>
                                    <th scope="col" class="px-3 py-2 font-medium text-center">
                                        Conversion
                                    </th>
                                    <th scope="col" class="px-3 py-2 font-medium text-center">
                                        Penalties
                                    </th>
                                    <th scope="col" class="px-3 py-2 font-medium text-center">
                                        Drop Goals
                                    </th>
                                     <th scope="col" class="px-3 py-2 font-medium text-center">
                                        Yellow Cards
                                    </th>
                                     <th scope="col" class="px-3 py-2 font-medium text-center">
                                        Red Cards
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($lineup as $player): ?>
                                <tr class="bg-neutral-primary border-b border-default">

                                    <!-- Player Name -->
                                    <th scope="row" class="px-3 py-2 text-center font-medium text-heading whitespace-nowrap">
                                        <?= h($player['player_name']) ?>
                                    </th>

                                    <!-- Position -->
                                    <td class="px-3 py-2 text-center">
                                        <?= h($player['position'] ?? '-') ?>
                                    </td>

                                     <!-- Minutes Played -->
                                    <td class="px-3 py-2">
                                        <input type="number" min="0" 
                                        name="stats[<?= $player['player_id'] ?>][minutes_played]"
                                            value="<?= h($_POST['minutes_played'][$player['player_id']]['minutes_played'] ?? $player['minutes_played'] ?? 80) ?>"
                                            class="<?= inputClass() ?>">
                                    </td>

                                        <!-- Tries -->
                                        <td class="px-3 py-2">
                                            <input type="number" min="0" 
                                                name="stats[<?= $player['player_id'] ?>][tries]"
                                                value="<?= h($_POST['stats'][$player['player_id']]['tries'] ?? $player['tries'] ?? 0) ?>"
                                                class="<?= inputClass() ?>">
                                        </td>

                                        <!-- Conversions -->
                                        <td class="px-3 py-2">
                                            <input type="number" min="0" 
                                                name="stats[<?= $player['player_id'] ?>][conversions]"
                                                value="<?= h($_POST['stats'][$player['player_id']]['conversions'] ?? $player['conversions'] ?? 0) ?>"
                                                class="<?= inputClass() ?>">
                                        </td>

                                        <!-- Penalties -->
                                        <td class="px-3 py-2">
                                            <input type="number" min="0" 
                                                name="stats[<?= $player['player_id'] ?>][penalties]"
                                                value="<?= h($_POST['stats'][$player['player_id']]['penalties'] ?? $player['penalties'] ?? 0) ?>"
                                                class="<?= inputClass() ?>">
                                        </td>

                                        <!-- Drop Goals -->
                                        <td class="px-3 py-2">
                                            <input type="number" min="0" 
                                                name="stats[<?= $player['player_id'] ?>][drop_goals]"
                                                value="<?= h($_POST['stats'][$player['player_id']]['drop_goals'] ?? $player['drop_goals'] ?? 0) ?>"
                                                class="<?= inputClass() ?>">
                                        </td>

                                        <!-- Yellow Cards -->
                                        <td class="px-3 py-2">
                                            <input type="number" min="0" 
                                                name="stats[<?= $player['player_id'] ?>][yellow_cards]"
                                                value="<?= h($_POST['stats'][$player['player_id']]['yellow_cards'] ?? $player['yellow_cards'] ?? 0) ?>"
                                                class="<?= inputClass() ?>">
                                        </td>

                                        <!-- Red Cards -->
                                        <td class="px-3 py-2">
                                            <input type="number" min="0" 
                                                name="stats[<?= $player['player_id'] ?>][red_cards]"
                                                value="<?= h($_POST['stats'][$player['player_id']]['red_cards'] ?? $player['red_cards'] ?? 0) ?>"
                                                class="<?= inputClass() ?>">
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Error Message -->
                        <div>
                            <p class="<?= smallError() ?>"><?php echo h($errors['message'] ?? '');?></p>
                        </div>

                        <!-- Button if user has permission to update match  -->
                        <?php if ((hasPermission('create_team')) && 
                            ($match['result'] !== 'Pending')):?>
                            <div class="flex justify-end">
                                <button type="submit" class="<?=primaryBtn()?> ">
                                    Add Player Match Stats
                                </button>
                            </div>
                        <?php endif; ?>

                    </form>
                <?php endif ;?>
            </div>
        </div>
    </div>
</section>