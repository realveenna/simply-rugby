<section class="bg-gray-50 dark:bg-gray-900">
  <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0">
      <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-xl xl:p-0 dark:bg-gray-800 dark:border-gray-700">
        <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
            <a href="/" class="flex flex-col items-center justify-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
                <img class="w-8 h-8 mr-2" src="./images/logo/main-logo.png" alt="logo">
                Account Registration
            </a>  
              <form class="space-y-4 md:space-y-6" method="post" action="/createAccount">
                <div>
                    <label for="email" class="<?= labelClass() ?>">Email Address</label>
                    <input type="email" name="email" id="email" 
                        value="<?php echo trim($email);?>"
                        class="<?= inputClass() ?>" placeholder="your@email.com" required="">
                    <div>
                        <p class="mt-2 text-sm font-xs text-red-500"><?php echo trim($emailErr);?></p>
                    </div>
                </div>
                <div>
                    <label for="rawPassword" class="<?= labelClass() ?>">Password</label>
                    <input type="password" name="rawPassword" id="rawPassword" 
                        placeholder="Enter Password" class="<?= inputClass() ?>" required="">
                    <div>
                        <p class="mt-2 text-sm font-xs text-red-500"><?php echo trim($rawPasswordErr);?></p>
                    </div>
                </div>
                <div>
                    <label for="rawConfirmPassword" class="<?= labelClass() ?>">Confirm Password</label>
                    <input type="password" name="rawConfirmPassword" id="rawConfirmPassword" 
                    placeholder="Enter Confirm Password" class="<?= inputClass() ?>" required="">
                    <div>
                        <p class="mt-2 text-sm font-xs text-red-500"><?php echo trim($rawConfirmPasswordErr);?></p>
                    </div> 
                </div>
                  <div class="flex items-start">
                      <div class="flex items-center h-5">
                        <input id="terms" aria-describedby="terms" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800" required="">
                      </div>
                      <div class="ml-3 text-sm">
                        <label for="terms" class="font-light text-gray-500 dark:text-gray-300">I accept the <a class="font-medium text-blue-600 hover:underline dark:text-blue-500" href="#">Terms and Conditions</a></label>
                      </div>
                  </div>
                  <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Create an account</button>
                  <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                      Already have an account? <a href="/login" class="font-medium text-blue-600 hover:underline dark:text-blue-500">Login here</a>
                  </p>
              </form>
          </div>
      </div>
  </div>
</section>