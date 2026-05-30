<section class="bg-gray-50 dark:bg-gray-900">
  <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0">
      <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-xl xl:p-0 dark:bg-gray-800 dark:border-gray-700">
        <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
            <a href="/" class="flex flex-col items-center justify-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
                <img class="w-8 h-8 mr-2" src="../images/logo/main-logo.png" alt="logo">
                Reset Password
            </a>  
              <form class="space-y-4 md:space-y-6" method="post" action="">
                <div>
                    <label for="resetEmail" class="<?= labelClass() ?>">Email Address</label>
                    <input type="email" name="resetEmail" 
                        value="<?php echo trim($resetEmail);?>"
                        class="<?= inputClass() ?>" placeholder="your@email.com" required>
                    <div>
                        <p class="mt-2 text-sm font-xs text-red-500"><?php echo trim($errors['emailErr']);?></p>
                    </div>
                </div>
                <div>
                    <label for="oldPassword" class="<?= labelClass() ?>">Current Password</label>
                    <input type="password" name="oldPassword" id="oldPassword" 
                        placeholder="Enter Current Password" class="<?= inputClass() ?>" required>
                    <div>
                        <p class="mt-2 text-sm font-xs text-red-500"><?php echo trim($errors['oldPassword']);?></p>
                    </div>
                </div>
                <div>
                    <label for="newPassword" class="<?= labelClass() ?>">New Password</label>
                    <input type="password" name="newPassword" id="newPassword" 
                    placeholder="Enter New Password" class="<?= inputClass() ?>" required>
                    <div>
                        <p class="mt-2 text-sm font-xs text-red-500"><?php echo trim($errors['newPassword']);?></p>
                    </div> 
                </div>
                  <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Confirm Password
                </button>
              </form>
          </div>
      </div>
  </div>
</section>