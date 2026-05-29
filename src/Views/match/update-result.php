  <section class="bg-gray-50 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0">
        <div class="<?= cardClass() ?>">
          <div class="<?= formPadding() ?>">
            <a href="/" class="flex flex-col items-center justify-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
              <img class="w-8 h-8 mr-2" src="/images/logo/main-logo.png" alt="logo">
              Update Match Result
          </a>  
          <form class="<?= formClass() ?>" method="post" action="">
            <input type="hidden" name="match_id" value="<?= h($match['match_id'] ?? '') ?>">

            <!-- First Half -->
            <div>
                <h2 class="<?=  heading5()?>">First Half</h2>
                
                <!-- Team Scoring -->
                <div class="grid gap-2 mb-6 md:grid-cols-2">
                    <!-- Our Team -->
                    <div>
                        <label for="fh_our_points" class="<?= labelClass() ?>">
                            <?= h($match['squad_name'] ?? 'N/A') ?>
                        </label>
                        <input type="number" 
                            name="fh_our_points" 
                            value="<?= h($data['fh_our_points'] ?? $halves[0]['our_points'] ?? '') ?>"
                            id="fh_our_points" class="<?= inputClass() ?>" 
                            placeholder="Our Points"  />
                        <div>
                           <p class="<?= smallError() ?>"><?php echo h($error['fh_our_points'] ?? '');?></p>
                        </div>
                    </div>
                    <!-- Opponent Team -->
                    <div>
                        <label for="fh_opponent_points" class="<?= labelClass() ?>">
                            <?= h($match['opposition_team_name'] ?? 'N/A') ?>
                        </label>
                        <input type="number" 
                            name="fh_opponent_points" 
                            value="<?= h($data['fh_opponent_points'] ?? $halves[0]['opponent_points'] ?? '') ?>"
                            id="fh_opponent_points" class="<?= inputClass() ?>" 
                            placeholder="Opponent Points"  />
                        <div>
                           <p class="<?= smallError() ?>"><?php echo h($error['fh_opponent_points'] ?? '');?></p>
                        </div>
                    </div>
                </div>

                <!-- Our Team Comment -->
                <div>
                    <label for="fh_our_comments" class="<?= labelClass() ?>">Our Team Comment</label>
                    <textarea rows="4" 
                    name="fh_our_comments" id="fh_our_comments" 
                    placeholder="Enter Our Team Comment" 
                    class="<?= inputClass() ?>"><?= h($data['fh_our_comments'] ??  $halves[0]['our_comments'] ?? '');?></textarea>
                    <div>
                    <p class="<?= smallError() ?>">
                        <?php echo h($error['fh_our_comments'] ?? '');?>
                    </p>
                    </div>
                </div>
                <!-- Opponent Team Comment -->
                <div>
                    <label for="fh_opponent_comments" class="<?= labelClass() ?>">Opponent Team Comment</label>
                    <textarea rows="4" 
                    name="fh_opponent_comments" id="fh_opponent_comments" 
                    placeholder="Enter Opponent Comment" 
                    class="<?= inputClass() ?>"><?= h($data['fh_opponent_comments'] ?? $halves[0]['our_comments'] ??'');?></textarea>
                    <div>
                    <p class="<?= smallError() ?>">
                        <?php echo h($error['fh_opponent_comments'] ?? '');?>
                    </p>
                    </div>
                </div>
            </div>

            <!-- Second Half -->
            <div>
                <h2 class="<?=  heading5()?>">Second Half</h2>
                
                <!-- Team Scoring -->
                <div class="grid gap-2 mb-6 md:grid-cols-2">
                    <!-- Our Team -->
                    <div>
                        <label for="sh_our_points" class="<?= labelClass() ?>">
                            <?= h($match['squad_name'] ?? 'N/A') ?>
                        </label>
                        <input type="number" 
                            name="sh_our_points" 
                            value="<?= h($data['sh_our_points'] ?? $halves[1]['our_points'] ?? '') ?>"
                            id="sh_our_points" class="<?= inputClass() ?>" 
                            placeholder="Our Points"  />
                        <div>
                           <p class="<?= smallError() ?>"><?php echo h($error['sh_our_points'] ?? '');?></p>
                        </div>
                    </div>
                    <!-- Opponent Team -->
                    <div>
                        <label for="sh_opponent_points" class="<?= labelClass() ?>">
                            <?= h($match['opposition_team_name'] ?? 'N/A') ?>
                        </label>
                        <input type="number" 
                            name="sh_opponent_points" 
                            value="<?= h($data['sh_opponent_points'] ?? $halves[1]['opponent_points'] ?? '') ?>"
                            id="sh_opponent_points" class="<?= inputClass() ?>" 
                            placeholder="Opponent Points"  />
                        <div>
                           <p class="<?= smallError() ?>"><?php echo h($error['sh_opponent_points'] ?? '');?></p>
                        </div>
                    </div>
                </div>

                <!-- Our Team Comment -->
                <div>
                    <label for="sh_our_comments" class="<?= labelClass() ?>">Our Team Comment</label>
                    <textarea rows="4" 
                    name="sh_our_comments" id="sh_our_comments" 
                    placeholder="Enter Our Team Comment" 
                    class="<?= inputClass() ?>"><?php echo h($data['sh_our_comments'] ??  $halves[1]['our_comments'] ??'');?></textarea>
                    <div>
                        <p class="<?= smallError() ?>">
                            <?php echo h($error['sh_our_comments'] ?? '');?>
                        </p>
                    </div>
                </div>
                <!-- Opponent Team Comment -->
                <div>
                    <label for="sh_opponent_comments" class="<?= labelClass() ?>">Opponent Team Comment</label>
                    <textarea rows="4" 
                    name="sh_opponent_comments" id="sh_opponent_comments" 
                    placeholder="Enter Opponent Comment" 
                    class="<?= inputClass() ?>"><?php echo h($data['sh_opponent_comments'] ??  $halves[1]['opponent_comments'] ??'');?></textarea>
                    <div>
                    <p class="<?= smallError() ?>">
                        <?php echo h($error['sh_opponent_comments'] ?? '');?>
                    </p>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <?php if (hasPermission('update_match')) :?>
                <div class="grid gap-2 mb-6 md:grid-cols-2">
                    <!-- Reset Button -->
                        <button type="reset" 
                            class="<?=secondaryBtn()?>">
                            Clear
                        </button>
                    
                    <!-- Submit Button -->
                    <button type="submit" value="submit" 
                        class="<?=primaryBtn()?>">
                        Update Result
                    </button>
                </div>
            <?php endif; ?>
          </form>
            
        </div>
      </div>
    </div>
  </section>
