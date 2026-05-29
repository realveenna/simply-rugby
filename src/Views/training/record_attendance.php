<!-- Attendance Record Sheet -->
<section class="bg-gray-50 dark:bg-gray-900 dark:text-white">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0">
        <div class="<?= cardClass() ?>">
          <div class="<?= formPadding() ?>">
            <a href="/" class="flex flex-col items-center justify-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
                <img class="w-8 h-8 mr-2" src="/images/logo/main-logo.png" alt="logo">
                Record Training Attendance
            </a>  
            
            <!-- Include training details -->
            <?php require '../src/includes/training_details.php'; ?>
            
            <!-- Attendance Sheet -->
            <form class="<?= formClass() ?>" method="post">
                <h3 class="mb-4 font-semibold text-heading">Please tick the box to mark player's attendance</h3>
                <!-- List Players and checkbox -->
                <ul class="w-48 select-none text-sm font-medium text-heading bg-neutral-primary-soft border border-default rounded-base">
                    <!-- No players -->
                    <?php if (empty($players)) : ?>
                        <li class="text-body">No players found.</li>
                    <!-- List Players -->
                    <?php else : ?>
                        <?php foreach ($players as $player) :?>
                            <!-- If has present attendance status then checked the box -->
                            <li class="w-full border-b border-default rounded-t-lg">
                                <div class="flex items-center ps-3 gap-6">
                                    <input type="checkbox"name="attended[]"
                                        value="<?= $player['member_id'] ?>"
                                        class="w-4 h-4 border mr-2 border-default-medium rounded-xs 
                                        bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft"
                                        <?= $player['attendance_status'] === 'Present' ? 'checked' : '' ?>>
                                    <label for=<?= $player['member_id']?> 
                                        class="w-full py-3 ms-2 text-sm font-medium text-heading">
                                        <?= $player['player_name'] ?>
                                    </label>
                                </div>
                            </li>
                        <?php endforeach ;?>
                    <?php endif ;?>
                </ul>
                <!-- Buttons -->
                <div class="grid gap-2 mb-6 md:grid-cols-2">
                    <!-- Clear Button -->
                    <button type="reset"
                        class="<?=secondaryBtn()?>">
                        Clear
                    </button>
                    
                    <!-- Submit Button -->
                    <button type="submit" 
                        class="<?=primaryBtn()?>">
                        Submit
                    </button>
                </div>
            </form>
        </div>
      </div>
    </div>
</section>
