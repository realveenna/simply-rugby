<!-- Attendance Record Sheet -->
<section class="bg-gray-50 dark:bg-gray-900 dark:text-white">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0">
        <div class="<?= cardClass() ?>">
          <div class="<?= formPadding() ?>">
            <a href="/" class="flex flex-col items-center justify-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
                <img class="w-8 h-8 mr-2" src="/images/logo/main-logo.png" alt="logo">
                Record Training Attendance
            </a>  
            <!-- Training Session Details -->
            <div>
                <h2 class="mb-3 text-lg font-medium text-heading">Training Session Details:</h2>
                <ul class="space-y-4 text-left text-body">
                    <li class="flex items-center space-x-3 rtl:space-x-reverse">
                        <!-- Svg Icon -->
                        <svg class="w-[15px] h-[15px] mr-2 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-width="1.5" d="M4.5 17H4a1 1 0 0 1-1-1 3 3 0 0 1 3-3h1m0-3.05A2.5 2.5 0 1 1 9 5.5M19.5 17h.5a1 1 0 0 0 1-1 3 3 0 0 0-3-3h-1m0-3.05a2.5 2.5 0 1 0-2-4.45m.5 13.5h-7a1 1 0 0 1-1-1 3 3 0 0 1 3-3h3a3 3 0 0 1 3 3 1 1 0 0 1-1 1Zm-1-9.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z"/>
                        </svg>
                        <span class="font-medium text-heading mr-3">
                            Squad: 
                        </span>
                        <span> <?= h($training['squad_name']) ?></span>
                    </li>
                    <!-- Organized by -->
                    <li class="flex items-center space-x-3 rtl:space-x-reverse">
                        <svg class="w-[15px] h-[15px] mr-2 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.5713 5h7v9h-7m-6.00001-4-3 4.5m3-4.5v5m0-5h3.00001m0 0h5m-5 0v5m-3.00001 0h3.00001m-3.00001 0v5m3.00001-5v5m6-6 2.5 6m-3-6-2.5 6m-3-14.5c0 .82843-.67158 1.5-1.50001 1.5-.82843 0-1.5-.67157-1.5-1.5s.67157-1.5 1.5-1.5 1.50001.67157 1.50001 1.5Z"/>
                        </svg>
                        <span class="font-medium text-heading mr-3">
                            Organized by: 
                        </span>
                        <span> <?= h($training['coach_name']) ?></span>
                    </li>
                    <li class="flex items-center space-x-3 rtl:space-x-reverse">
                        <!-- Date-->
                        <svg class="w-[15px] h-[15px] mr-2 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 10h16M8 14h8m-4-7V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Z"/>
                        </svg>
                        <span class="font-medium text-heading mr-3">
                            Date: 
                        </span>
                        <span> <?= h($training['date']) ?></span>
                    </li>
                    <li class="flex items-center space-x-3 rtl:space-x-reverse">
                        <!-- Time -->
                        <svg class="w-[15px] h-[15px] mr-2 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                        <span class="font-medium text-heading mr-3">
                            Time: 
                        </span>
                        <span> <?= h($training['start_time']) .' - ' .h($training['end_time'])?></span>
                    </li>
                    <li class="flex items-center space-x-3 rtl:space-x-reverse">
                        <!-- Svg Icon -->
                        <svg class="w-[15px] h-[15px] mr-2 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                           <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-6 5h6m-6 4h6M10 3v4h4V3h-4Z"/>
                        </svg>
                        <span class="font-medium text-heading mr-3">
                            Activities & Skills: 
                        </span>
                        <span> <?= $training['skills_activities'] ?></span>
                    </li>
                </ul>
            </div>
            
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
                    <?php endif ;?> ?>
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
