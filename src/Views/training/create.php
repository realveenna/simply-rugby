<section class="bg-gray-50 dark:bg-gray-900">
  <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0">
      <div class="<?= cardClass() ?>">
        <div class="<?= formPadding() ?>">
          <a href="/" class="flex flex-col items-center justify-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
              <img class="w-8 h-8 mr-2" src="/images/logo/main-logo.png" alt="logo">
              Create a Training Session
          </a>  
          <form class="<?= formClass() ?>" method="post" action="">
            <input type="hidden" name="coach_member_id" value="<?= h($_SESSION['user']['member_id'] ?? null) ?>">
            <input type="hidden" name="training_session_id" value="<?= h($training['training_session_id'] ?? '') ?>">
            
            <!-- Squad Select -->
            <div>
                <label for="squad_id" class="<?= labelClass() ?>">Squad Name </label>
                <select class="<?= inputClass() ?>"
                    autocomplete="squad_id" name="squad_id">
                    <option value="" disabled selected> Select a Squad:</option>
                    <?php foreach ($squads as $squad): ?>
                        <option value="<?= $squad['squad_id'] ?>"
                            <?php 
                                $selectedSquad = $training['squad_id'] ?? '';
                                if($selectedSquad == $squad['squad_id']){
                                    echo 'selected';
                                }
                            ?>>
                            <?= h($squad['squad_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                  <p class="mt-2 text-sm font-xs text-red-500"><?php echo h($error['squad_id'] ?? '');?></p>
            </div>

            <!-- Skills Activities -->
            <div>
                <label for="skills_activities" class="<?= labelClass() ?>">Skills & Activities</label>
                <textarea rows="4" 
                  name="skills_activities" id="skills_activities" 
                  placeholder="Enter Training Skills and Activities" 
                  class="<?= inputClass() ?>"><?= h($training['skills_activities'] ?? '') ?></textarea>
                <div>
                  <p class="<?= smallError() ?>"><?php echo h($error['skills_activities'] ?? '');?></p>
                </div>
            </div>
            <!-- DATE -->
             <div>
                <label for="training_date" class="<?= labelClass() ?>">Date</label>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-body" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 10h16m-8-3V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Zm3-7h.01v.01H8V13Zm4 0h.01v.01H12V13Zm4 0h.01v.01H16V13Zm-8 4h.01v.01H8V17Zm4 0h.01v.01H12V17Zm4 0h.01v.01H16V17Z"/></svg>
                    </div>
                    <input datepicker name="training_date" id="training_dateRegister" type="text" value="<?php echo h($training['training_date'] ?? '');?>"
                    class="block w-full ps-9 pe-3 py-2.5 <?= inputClass()?>" placeholder="Select date">
                </div>
                <div>
                    <p class="<?= smallError() ?>"><?php echo h($error['training_date'] ?? '');?></p>
                </div>
            </div>

            <!-- TIME -->
            <div class="grid gap-2 mb-6 md:grid-cols-2">
              <div>
                <label for="start_time" class="block mb-2 text-sm font-medium text-heading">Select Start Time:</label>
                <div class="relative">
                    <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                        <svg class="w-4 h-4 text-body" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    </div>
                    <input type="time" 
                      name="start_time" 
                      id="start_time" 
                      value="<?= h($training['start_time'] ?? '') ?>"
                      class="block w-full p-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body" min="09:00" max="18:00" value="00:00" required />
                </div>
                <div>
                    <p class="<?= smallError() ?>"><?php echo h($error['start_time'] ?? '');?></p>
                </div>
              </div>
              <div>
              <label for="end_time" class="block mb-2 text-sm font-medium text-heading">Select End Time:</label>
                <div class="relative">
                    <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                        <svg class="w-4 h-4 text-body" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    </div>
                    <input type="time" name="end_time" id="end_time" 
                      value="<?= h($training['end_time'] ?? '') ?>"
                      class="block w-full p-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body" min="09:00" max="18:00" value="00:00" required />
                </div>
                <div>
                    <p class="<?= smallError() ?>"><?php echo h($error['end_time'] ?? '');?></p>
                </div>
              </div>
            </div>
            
            <div class="grid gap-2 mb-6 md:grid-cols-2">
                  <!-- Clear Button -->
                  <button type="reset"
                      class="<?=secondaryBtn()?>">
                      Clear
                  </button>
                  
                  <!-- Submit Button -->
                  <button type="submit" name="action" value="submit" 
                      class="<?=primaryBtn()?>">
                      Submit
                  </button>
              </div>
          </form>
      </div>
    </div>
  </div>
</section>
