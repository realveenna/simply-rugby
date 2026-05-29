<!-- Squad Table -->
<section class="bg-gray-50 dark:bg-gray-900 dark:text-white">

    <?= title($title ?? 'All', 'Trainings') ?>

    <table class="datatable">
        <thead>
            <tr>
                <th class="text-center">
                    <span class="flex items-center">
                        Squad Name 
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                </th>
                <th class="text-center">
                    <span class="flex items-center">
                        Organized by 
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                </th>
                <th class="text-center">
                    <span class="flex items-center">
                        Date
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                </th>
                <th class="text-center">
                    <span class="flex items-center">
                        Time
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                </th>
                <th class="text-center long-text">
                    <span class="flex items-center">
                        Skills & Activities
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                </th>
                <th class="text-center">
                    <span class="flex items-center">
                        Status
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                </th>
                <th class="text-center">
                    <span class="flex items-center">
                        Action
                    </span>
                </th>
            </tr>
        </thead>

        <tbody>
            <?php foreach($trainings as $training): ?>
            <tr>
                <td class="font-medium text-heading whitespace-nowrap text-center">
                    <?= h($training['squad_name']) ?> 
                </td>
                <td class="text-center"><?= h($training['coach_name']) ?></td>
                <td class="text-center"><?= h($training['date']) ?></td>
                <td class="text-center">
                    <?= formatTime(h($training['start_time'])) .' - '. formatTime(h($training['end_time'])) ?>
                </td>
                <td class="text-center long-text">
                    <?= h($training['skills_activities'])?>
                </td>
                <!-- Badge Status for Training -->
                <td class="text-center">
                    <!-- Update Attendance -->
                    <?php if($training['status'] === 'Attendance'): ?>
                        <span class="<?= badgeWarning()?>">
                            Update Attendance
                        </span>

                    <!-- Record Skills -->
                    <?php elseif ($training['status'] === 'Skills' ): ?>
                        <span class="<?= badgeWarning() ?>">
                            Record Skills
                        </span>

                    <!-- Everything completed -->
                    <?php elseif($training['status'] === 'Completed'): ?>
                        <span class="<?= badgeSuccess()?>">
                            Completed
                        </span>

                    <!-- Future Date -->
                    <?php elseif($training['status'] === 'Upcoming'): ?>
                        <span class="<?= badgeBlue()?>">
                            Upcoming
                        </span>
                    <?php endif; ?>
                    
                </td>
                <!-- Action Button -->
                <td class="text-center">
                    <button id="trainingListAction<?=h($training['training_session_id'])?>" 
                        data-dropdown-toggle="trainingListDots<?=h($training['training_session_id'])?>" class="text-heading bg-neutral-primary box-border border border-transparent hover:bg-neutral-secondary-medium focus:ring-4 focus:ring-neutral-tertiary font-medium leading-5 rounded-base text-sm p-2 focus:outline-none" type="button"> 
                        <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-width="3" d="M6 12h.01m6 0h.01m5.99 0h.01"/></svg>
                    </button>

                    <!-- Dropdown menu -->
                    <!-- Permission Action Buttons -->
                    <div id="trainingListDots<?=h($training['training_session_id'])?>" class="min-w-max z-10 hidden bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg w-44 dark:divide-gray-600">
                        <form method="post" action="/training/view?training_session_id=<?= h($training['training_session_id']) ?>">
                            <ul class="p-2 text-sm text-body font-medium" aria-labelledby="trainingListAction<?=h($training['training_session_id'])?>">
                            <!-- record_attendance -->
                            <!-- Date is today or past - -->
                            <?php if(hasPermission('record_attendance') && $training['status'] === 'Attendance'): ?>
                                <li>
                                    <a href="/training/record_attendance?training_session_id=<?=h(($training['training_session_id']))?>" 
                                        class="inline-flex items-center text-brand hover:text-brand-medium justify-center w-full p-2 hover:bg-neutral-tertiary-medium rounded">
                                        Record Attendance
                                    </a>  
                                </li>   
                            <?php endif; ?>

                            <!-- record_player_skills -->
                            <!-- Date is today or past and all attendance is marked - -->
                            <?php if(hasPermission('record_player_skills') && $training['status'] === 'Skills'): ?>
                                <?php if((int)$training['pending_count'] === 0): ?>
                                    <li>
                                        <a href="/training/record_player_skills?training_session_id=<?=h(($training['training_session_id']))?>" 
                                            class="inline-flex items-center text-brand hover:text-brand-medium justify-center w-full p-2 hover:bg-neutral-tertiary-medium rounded">
                                            Record Player Skills
                                        </a>  
                                    </li>   
                                <?php endif; ?>
                            <?php endif; ?>

                            <!-- record_injury -->
                            <!-- attendance is completed -->
                            <?php if(hasPermission('record_injury')): ?>
                                <?php if (((int)$training['pending_count'] === 0) && !isFutureDate($training['date'])):?>
                                    <li>
                                        <a href="/injury?training_session_id=<?= $training['training_session_id']?>" 
                                            class="inline-flex items-center justify-center text-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded-md">
                                            Record Injury
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endif; ?>

                            <!-- view_training_session -->
                            <?php if(hasPermission('view_training_session')): ?>
                                <li>
                                    <a href="/training/view?training_session_id=<?=h(($training['training_session_id']))?>" 
                                        class="inline-flex items-center justify-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                                        View More
                                    </a>  
                                </li>   
                            <?php endif; ?>

                            <!-- update_training_session -->
                            <?php if(hasPermission('update_training_session')): ?>
                                <li>
                                    <a href="/training/update?training_session_id=<?=h(($training['training_session_id']))?>" 
                                        class="inline-flex items-center justify-center w-full p-2 hover:bg-neutral-tertiary-medium hover:text-heading rounded">
                                        Update
                                    </a>
                                </li>
                            <?php endif; ?>

                            <!-- delete_training_session -->
                            <?php if(hasPermission('delete_training_session')): ?>
                                <li>
                                    <button type="submit" name="action" value="delete"
                                        class="flex justify-center items-center text-center w-full p-2 text-fg-danger hover:bg-neutral-tertiary-medium rounded-md">
                                        Delete
                                    </button>
                                </li>
                            <?php endif; ?>
                            </ul>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
