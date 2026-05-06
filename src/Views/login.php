  <section class="bg-gray-50 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0">
        <div class="<?= cardClass() ?>">
          <div class="<?= formPadding() ?>">
            <a href="/" class="flex flex-col items-center justify-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
                <img class="w-8 h-8 mr-2" src="./images/logo/main-logo.png" alt="logo">
                Sign in to your account
            </a>  
            <form class="<?= formClass() ?>" method="post" action="/login">
              <div>
                <label for="email" class="<?= labelClass() ?>">Your email</label>
                <input type="email" name="email" id="email" 
                  value="<?php echo h($email);?>"
                  class="<?= inputClass() ?>" placeholder="your@email.com">
                <div>
                  <p class="<?= smallError() ?>"><?php echo h($emailErr);?></p>
                </div>
              </div>
              <div>
                <label for="rawPassword" class="<?= labelClass() ?>">Password</label>
                <input type="password" name="rawPassword" id="rawPassword" 
                    placeholder="Enter Password" class="<?= inputClass() ?>">
                <div>
                  <p class="<?= smallError() ?>"><?php echo h($rawPasswordErr);?></p>
                </div>
              </div>
              <button type="submit" class="<?=primaryBtn()?>">Sign in</button>
              </p>
              <?= small("
                  Don't have an account yet? <a href='/register' class='font-medium text-blue-600 hover:underline dark:text-blue-500'>Register</a>
              ")?>
            </form>
        </div>
      </div>
    </div>
  </section>
