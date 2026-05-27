<section class="bg-gray-50 dark:bg-gray-900">
  <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0">
      <div class="<?= cardClass() ?>">
        <div class="<?= formPadding() ?>">
          <a href="/" class="flex flex-col items-center justify-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
              <img class="w-8 h-8 mr-2" src="/images/logo/main-logo.png" alt="logo">
              Create a Match
          </a>  
          <form class="<?= formClass() ?>" method="post" action="">
            <input type="hidden" name="match_id" value="<?= h($match->match_id ?? '') ?>">
            
            <!-- Squad Select -->
            <div>
                <label for="squad_id" class="<?= labelClass() ?>">Squad Name </label>
                <select class="<?= inputClass() ?>"
                    autocomplete="squad_id" name="squad_id">
                    <option value="" disabled selected> Select a Squad:</option>
                    <!-- If there is only one squad in the list set it to default -->
                    <?php
                        if(count($squads) === 1){
                            $match->squad_id = $squads[0]['squad_id'];
                        }
                    ?>
                    <!-- If may squads, list all -->
                    <?php foreach ($squads as $squad): ?>
                        <option value="<?= $squad['squad_id'] ?>"
                            <?php 
                                // Set selected squad
                                $selectedSquad = $match->squad_id ?? '';
                                if($selectedSquad == $squad['squad_id']){
                                    echo 'selected';
                                }
                            ?>>
                            <?= h($squad['squad_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                  <p class="mt-2 text-sm font-xs text-red-500"><?php echo $error['squad_id'] ?? '';?></p>
            </div>

            <!-- Opposition Team Name -->
            <div>
                <label for="opposition_team_name" class="<?= labelClass() ?>">Your Opposition Team</label>
                <input type="text" 
                    name="opposition_team_name" id="opposition_team_name" 
                    value="<?= h($match->opposition_team_name) ?? '';?>"
                    class="<?= inputClass() ?>" placeholder="Opposition Team Name">
                <div>
                  <p class="<?= smallError() ?>"><?php echo $error['opposition_team'] ?? '';?></p>
                </div>
            </div>

            <!-- Match Venue -->
            <div>
                <label for="match_venue" class="<?= labelClass() ?>">Match Venue</label>
                <!-- Home or Away -->
                <div class="grid gap-2 mb-6 md:grid-cols-2">
                    <div class="flex items-center ps-4 border p-2 border-default bg-neutral-primary-soft rounded-base">
                        <input checked id="home" type="radio" value="home" name="match_venue"
                            <?= ($match->match_venue ?? '') === 'home' ? 'checked' : '' ?>
                            class="w-4 h-4 text-neutral-primary border-default-medium bg-neutral-secondary-medium rounded-full checked:border-brand focus:ring-2 focus:outline-none focus:ring-brand-subtle border border-default appearance-none">
                        <label for="home" class="w-full py-4 select-none ms-2 text-sm font-medium text-heading">
                            Home
                        </label>
                    </div>
                    <div class="flex items-center ps-4 border p-2 border-default bg-neutral-primary-soft rounded-base">
                        <input id="away" type="radio" value="away" name="match_venue" 
                            <?= ($match->match_venue ?? '') === 'away' ? 'checked' : '' ?>
                            class="w-4 h-4 text-neutral-primary border-default-medium bg-neutral-secondary-medium rounded-full checked:border-brand focus:ring-2 focus:outline-none focus:ring-brand-subtle border border-default appearance-none">
                        <label for="away" class="w-full py-4 select-none ms-2 text-sm font-medium text-heading">
                            Away
                        </label>
                    </div>
                </div>
                <div>
                  <p class="<?= smallError() ?>"><?php echo h($error['match_venue'] ?? '');?></p>
                </div>
            </div>

            <!-- DATE -->
             <div>
                <label for="date" class="<?= labelClass() ?>">Date</label>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-body" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 10h16m-8-3V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Zm3-7h.01v.01H8V13Zm4 0h.01v.01H12V13Zm4 0h.01v.01H16V13Zm-8 4h.01v.01H8V17Zm4 0h.01v.01H12V17Zm4 0h.01v.01H16V17Z"/></svg>
                    </div>
                    <input datepicker  datepicker-min-date="<?= date('m/d/Y') ?>" 
                        name="match_date" type="text" 
                        value="<?php echo h(date('m/d/Y', strtotime($match->match_date)) ?? '');?>"
                    class="block w-full ps-9 pe-3 py-2.5 <?= inputClass()?>" placeholder="Select date">
                </div>
                <div>
                    <p class="<?= smallError() ?>"><?php echo h($error['match_date'] ?? '');?></p>
                </div>
            </div>

            <!-- KICK OFF TIME -->
            <div>
                <label for="kick_off_time" class="block mb-2 text-sm font-medium text-heading">Select Start Time:</label>
                <div class="relative">
                    <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                        <svg class="w-4 h-4 text-body" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    </div>
                    <input type="time" 
                        name="kick_off_time" 
                        id="kick_off_time" 
                        value="<?= h($match->kick_off_time ?? '') ?>"
                        class="block w-full p-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body" min="09:00" max="18:00" value="00:00" required />
                </div>
                <div>
                    <p class="<?= smallError() ?>"><?php echo h($error['kick_off_time'] ?? '');?></p>
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
