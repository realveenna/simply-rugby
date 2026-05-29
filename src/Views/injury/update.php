  <section class="bg-gray-50 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0">
        <div class="<?= cardClass() ?>">
          <div class="<?= formPadding() ?>">
            <a href="/" class="flex flex-col items-center justify-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
              <img class="w-8 h-8 mr-2" src="/images/logo/main-logo.png" alt="logo">
              Update an Injury
            </a>  
            <form class="<?= formClass() ?>" method="post">
                <input type="hidden" name="player_injury_id" value="<?= (int)$data['player_injury_id']  ?? null ?>">
                <input type="hidden" name="member_id" value="<?= (int)$data['member_id'] ?? null ?>">
                <input type="hidden" name="training_session_id" value="<?= (int)$data['training_session_id'] ?? null ?>">
                <input type="hidden" name="match_id" value="<?= (int)$data['match_id'] ?? null ?>">
                <input type="hidden" name="injury_id" value="<?= (int)$data['injury_id'] ?? 0 ?>">
                

                <!-- Player -->  
                <div>
                    <label class="<?= labelClass() ?>">
                        Player
                    </label>
                    <input type="text" class="<?= inputClass() ?>" value="<?= h($data['player_name']) ?>" readonly>
                    <p class="<?= smallError() ?>">
                        <?= h($errors['member_id'] ?? '') ?>
                    </p>
                </div>

                <!-- Injuries -->
                <div>
                    <label class="<?= labelClass() ?>">
                        Injury
                    </label>
                    <input type="text" class="<?= inputClass() ?>" value="<?= h($data['injury_name']) ?>" readonly>
                    <p class="<?= smallError() ?>">
                        <?= h($errors['injury_id'] ?? '') ?>
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
                            <?= (h(($data['injury_status']) ?? '') === 'Active') ? 'selected' : '' ?>> 
                            Active
                        </option>
                        <option value="Recovering"
                            <?= (h(($data['injury_status']) ?? '') === 'Recovering') ? 'selected' : '' ?>>
                            Recovering
                        </option>
                        <option value="Recovered"
                            <?= (h(($data['injury_status']) ?? '') === 'Recovered') ? 'selected' : '' ?>>
                            Recovered
                        </option>
                    </select>
                    <p class="<?= smallError() ?>">
                        <?= h($errors['injury_status'] ?? '') ?>
                    </p>
                </div>
       
                <!-- Submit Button -->
                <div class="flex justify-end">
                  <button type="submit" name="submit" class="<?=primaryBtn()?> w-full">
                      Update
                  </button>
              </div>
            </form>
        </div>
      </div>
    </div>
  </section>
