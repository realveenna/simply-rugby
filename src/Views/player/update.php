<section class="bg-gray-50 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0">
        <div class="<?= cardClassXLNoBg() ?>">
            <!-- Logo -->
            <a href="/" class="flex flex-col items-center justify-center mb-2 text-2xl font-semibold text-gray-900 dark:text-white">
                <img class="w-8 h-8 mr-2" src="/images/logo/main-logo.png" alt="logo">
            </a> 

            <?= title('Update Player','Profile')?>

            <!-- Player Information -->
            <?php if ((isset($player)) && !empty($player)):?>
                <?= titleLeftSmall('Player Profile')?>
                <form method="post" class="mt-6">
                    <input type="hidden" name="member_id" value="<?= $player['member_id']?? '' ?>">
                    <div class="flex flex-col items-center gap-4 bg-neutral-primary-soft p-6 border border-default rounded-base shadow-xs md:flex-row">
                        <div class="flex flex-col justify-between md:p-4 leading-normal w-full">
                            <h5 class="<?= heading5()?>">Player Details</h5>    
                            <!-- Position -->
                            <div> 
                                <!-- Select player for position -->
                                <label for="position" class="block mb-2 text-sm font-small text-gray-900 dark:text-white">Position </label>
                                <select class="<?= inputClass()?>" name="position">
                                    <option value="" disabled selected> 
                                        Select a Position: 
                                    </option>
                                    
                                    <!-- Position dropdown -->
                                    <?php foreach ($positions as $position): ?>
                                        <option value="<?= $position ?>"
                                            <?= (($player['position'] ?? '') === $position) ? 'selected' : '' ?>>
                                            <?= h($position) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <!-- Position error -->
                                <p class="<?= smallError() ?>">
                                    <?php echo h($errors['position'] ?? '');?>
                                </p>
                            </div>
                            
                            <!-- Height and Weight -->
                            <div class="grid gap-2 mb-6 md:grid-cols-2">
                                <div>
                                    <label for="height" class="<?= labelClass() ?>">Height</label>
                                    <input type="number" name="height" 
                                        value="<?= h($player['height']) ?? '';?>"
                                        class="<?= inputClass()?>" placeholder="Enter in cm">
                                    <div>
                                        <p class="<?= smallError() ?>"><?= $error['height'] ?? '';?></p>
                                    </div>
                                </div>
                                <div>
                                    <label for="weight" class="<?= labelClass() ?>">Weight</label>
                                    <input type="number" name="weight"
                                        value="<?= h($player['weight']) ?? '';?>"
                                        class="<?= inputClass()?>" placeholder="Enter in kg">
                                    <div>
                                        <p class="<?= smallError() ?>"><?= $error['weight'] ?? '';?></p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Modify Player DetailsButton -->
                            <!-- Permission check update_player_details -->
                            <?php if (hasPermission('update_player_details')):?>
                                <div class="flex justify-end">
                                    <button type="submit" name="edit" value="player_profile"
                                        class="<?= primaryBtn() ?>">
                                        Confirm Details
                                    </button>
                                </div>
                            <?php endif;?>

                            <h5 class="<?= heading5()?>">Player Availability Status</h5>    

                            <!-- Availability Status -->
                            <div class="mb-6 ">
                                <label for="player_availability_status" class="block mb-2 text-sm font-small text-gray-900 dark:text-white">Availability Status </label>
                                <select class="<?= inputClass()?>"
                                    autocomplete="player_availability_status" name="player_availability_status">
                                    <option value="" disabled selected> Select Availability Status:</option>
                                    <option value="Available" class="text-brand"
                                        <?= $player['player_availability_status'] === 'Available' ? 'selected' : ''?>>
                                            Available
                                    </option>
                                    <option value="Unavailable" class="text-danger"
                                        <?= $player['player_availability_status'] === 'Unavailable' ? 'selected' : ''?>>
                                            Unavailable
                                    </option>
                                </select>
                                    <p class="mt-2 text-sm font-xs text-red-500"><?php echo $error['player_availability_status'] ?? '';?></p>
                            </div>

                            <!-- Modify Availability Button -->
                            <!-- Permission check update_player_availability -->
                            <?php if (hasPermission('update_player_availability')):?>
                            <div class="flex justify-end">
                                <button type="submit" name="edit" value="player_availability"
                                    class="<?= primaryBtn() ?>">
                                    Update Status
                                </button>
                            </div>
                            <?php endif;?>
                        </div>
                    </div>
                </form>
            <?php endif;?>


        </div>
    </div>
</section>

