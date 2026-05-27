  <section class="bg-gray-50 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0">
        <div class="<?= cardClass() ?>">
          <div class="<?= formPadding() ?>">
            <a href="/" class="flex flex-col items-center justify-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
              <img class="w-8 h-8 mr-2" src="/images/logo/main-logo.png" alt="logo">
              Player Membership Renewal
            </a>  
            <form class="<?= formClass() ?> flex flex-col gap-2" method="post">
                <input type="hidden" name="member_id" value="<?= $player['member_id'] ?>">

                <p class="block mb-2.5 text-lg font-medium text-heading">Do you want to renew your membership for next season?</p>
                <div class="space-y-1">
                    <p class="text-md text-heading">
                        Player Name: <?= h($player['first_name'] .' '. $player['last_name']  )?>
                    </p>
                    <p class="text-md text-heading">
                        Current Squad: <?= h($player['squad_name'] )?>
                    </p>
                    <p class="text-md text-heading">
                        Current Season: <?= h($player['season'] )?>
                    </p>
                </div>
                <!-- If section is not senior ask for consent -->
                <?php if($player['section_id'] !== 3):?>
                <div class="flex items-start mb-6">
                    <div class="flex items-center h-5">
                    <input id="consent" name="consent" 
                        type="checkbox" value="1" class="w-4 h-4 border border-default-medium rounded-xs bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft" required />
                    </div>
                    <label for="consent" class="ms-2 text-sm  text-heading">
                         I confirm that I am the parent/guardian and give consent for renewal.
                    </label>
                    <div>
                        <p class="mt-2 text-sm font-xs text-red-500"><?= $error['consent'] ?? '' ?></p>
                    </div>
                </div>
                <?php endif;?>

                <!-- Buttons -->
                <div class="grid gap-2 mb-6 md:grid-cols-2">
                  <!-- Decline Button -->
                  <button type="submit" name="renewal" value="decline"
                      class="<?=secondaryBtn()?>">
                        Decline Renewal
                  </button>
                  
                  <!-- Renew Button -->
                  <button type="submit" name="renewal" value="renew"
                      class="<?=primaryBtn()?>">
                      Renew Membership
                  </button>
                </div>
            </form>
        </div>
      </div>
    </div>
  </section>
