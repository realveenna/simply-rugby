  <section class="bg-gray-50 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0">
        <div class="<?= cardClass() ?>">
          <div class="<?= formPadding() ?>">
            <a href="/" class="flex flex-col items-center justify-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
              <img class="w-8 h-8 mr-2" src="/images/logo/main-logo.png" alt="logo">
              Record an Injury
            </a>  
            <form class="<?= formClass() ?>" method="post">
                <input type="hidden" name="training_session_id" value="<?= h($training['training_session_id'] ?? null) ?>">
                <input type="hidden" name="match_id" value="<?= h($match['match_id'] ?? null) ?>">

               <!-- Player -->
                <div>
                    <label class="<?= labelClass() ?>">
                        Player
                    </label>
                    <select class="<?= inputClass() ?>" name="member_id">
                        <option value="" disabled selected> Select Player:</option>
                        <?php foreach ($presentPlayers as $player): ?>
                            <option value="<?= $player['member_id'] ?>"
                                <?= (((int)($_POST['member_id'] ?? 0)) === (int)$player['member_id'])
                                     ? 'selected' : '' ?>>
                                <?= h($player['player_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="<?= smallError() ?>">
                        <?= h($error['member_id'] ?? '') ?>
                    </p>
                </div>

                <!-- Injuries -->
                <div>
                    <label class="<?= labelClass() ?>">
                        Injury
                    </label>
                    <select class="<?= inputClass()?>"
                        autocomplete="injury_id" name="injury_id">
                        <option value="" disabled selected> Select Injury:</option>
                        <?php foreach ($injuries as $injury): ?>
                            <option value="<?= $injury['injury_id'] ?>"
                                <?= ((int)($_POST['injury_id'] ?? 0) === (int)$injury['injury_id'])
                                     ? 'selected' : '' ?>>
                                <?= h($injury['injury_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="<?= smallError() ?>">
                        <?= h($error['injury_id'] ?? '') ?>
                    </p>
                </div>

                <!-- Status -->
                <div>
                    <label class="<?= labelClass() ?>">
                        Status
                    </label>
                    <select class="<?= inputClass()?>"
                        autocomplete="injury_status" name="injury_status">
                        <option value="" disabled selected> Select Status:</option>
                        <option value="Active"
                            <?= (($_POST['injury_status'] ?? '') === 'Active') ? 'selected' : '' ?>> 
                            Active
                        </option>
                        <option value="Recovering"
                            <?= (($_POST['injury_status'] ?? '') === 'Recovering') ? 'selected' : '' ?>>
                            Recovering
                        </option>
                        <option value="Recovered"
                            <?= (($_POST['injury_status'] ?? '') === 'Recovered') ? 'selected' : '' ?>>
                            Recovered
                        </option>
                    </select>
                    <p class="<?= smallError() ?>">
                        <?= h($error['injury_status'] ?? '') ?>
                    </p>
                </div>
       
                <!-- Submit Button -->
                <div class="flex justify-end">
                  <button type="submit" name="submit" class="<?=primaryBtn()?>">
                      Confirm
                  </button>
              </div>
         
            </form>
        </div>
      </div>
    </div>
  </section>
