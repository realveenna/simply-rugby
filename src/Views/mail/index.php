  <section class="bg-gray-50 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0">
        <div class="<?= cardClass() ?>">
          <div class="<?= formPadding() ?>">
            <a href="/" class="flex flex-col items-center justify-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
              <img class="w-8 h-8 mr-2" src="/images/logo/main-logo.png" alt="logo">
              Send a Mail
            </a>  
            <form class="<?= formClass() ?>" method="post">
                <!-- Select recipients -->
                <div>
                    <label class="<?= labelClass() ?>">
                        Recipient
                    </label>
                    <select class="<?= inputClass()?>"
                        autocomplete="role_name" name="role_name">
                        <option value="" disabled selected> Select Injury:</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= $role['role_name'] ?>"
                                <?= ((int)($_POST['role_name'] ?? 0) === (int)$role['role_name'])
                                     ? 'selected' : '' ?>>
                                <?= h($role['role_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="<?= smallError() ?>">
                        <?= h($error['role_name'] ?? '') ?>
                    </p>
                </div>
                 <!-- Message -->
                <div>
                    <label for="message" class="<?= labelClass() ?>">Enter Mail</label>
                    <textarea rows="8" 
                    name="message" id="message" 
                    placeholder="Enter Mail" 
                    class="<?= inputClass() ?>"><?= h($message ?? '') ?></textarea>
                    <div>
                    <p class="<?= smallError() ?>"><?php echo h($error['message'] ?? '');?></p>
                    </div>
                </div>
                


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
