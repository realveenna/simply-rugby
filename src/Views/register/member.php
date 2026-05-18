<!-- Register a member account -->
<section class="bg-gray-50 dark:bg-gray-900">
  <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0">
      <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-xl xl:p-0 dark:bg-gray-800 dark:border-gray-700">
        <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
            <a href="/" class="flex flex-col items-center justify-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
                <img class="w-8 h-8 mr-2" src="/images/logo/main-logo.png" alt="logo">
                Create a Member Account
            </a>  
            <form class="space-y-4 md:space-y-6" method="post" action="/register/member">
                <?= h3("Personal Details")?>
                <!-- First Name -->
                <div class="mb-6">
                    <label for="fname" class="<?= labelClass() ?>">First Name</label>
                    <input type="text" name="fname"
                        value="<?php echo h($data['fname'] ?? ''); ?>"
                        class="<?= inputClass() ?>" placeholder="John"  />
                     <div>
                        <p class="mt-2 text-sm font-xs text-red-500"><?php echo h($errors['fname'] ?? '');?></p>
                    </div>
                </div>
                <!-- Last Name -->
                <div class="mb-6">
                    <label for="lname" class="<?= labelClass() ?>">Last Name</label>
                    <input type="text" name="lname"
                        value="<?php echo h($data['lname'] ?? ''); ?>"
                        class="<?= inputClass() ?> " placeholder="Doe"  />
                     <div>
                        <p class="mt-2 text-sm font-xs text-red-500"><?php echo h($errors['lname'] ?? '');?></p>
                    </div>
                </div> 
                <!-- Date of Birth -->
                <div class="mb-6">
                    <label for="dobMember" class="<?= labelClass() ?>">Date of Birth</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-body" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 10h16m-8-3V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Zm3-7h.01v.01H8V13Zm4 0h.01v.01H12V13Zm4 0h.01v.01H16V13Zm-8 4h.01v.01H8V17Zm4 0h.01v.01H12V17Zm4 0h.01v.01H16V17Z"/></svg>
                        </div>
                        <input datepicker name="dob" type="text" value="<?= h($data['dob'] ?? '') ?>"
                         class="block w-full ps-9 pe-3 py-2.5 <?= inputClass()?>" placeholder="Select Birth Date">
                    </div>                     
                    <div>
                        <p class="mt-2 text-sm font-xs text-red-500"><?php echo h($errors['dob'] ?? '');?></p>
                    </div>
                </div> 
                <?= h3("Contact Details")?>
                <!-- Email -->
                <div>
                    <label for="email" class="<?= labelClass() ?>">Email Address</label>
                    <input type="email" name="email"
                        value="<?php echo h($data['email'] ?? ''); ?>"
                        class="<?= inputClass() ?>" placeholder="your@email.com">
                    <div>
                        <p class="<?= smallError() ?>"><?php echo h($errors['email'] ?? '');?></p>
                    </div>
                </div>

                <!-- Mobile Number -->
                <div>
                    <label for="mobileNum" class="<?= labelClass() ?>">Mobile Number</label>
                    <input type="tel" name="mobileNum"
                        pattern="^07\d{9}$" placeholder="07123456789"
                        value="<?php echo h($data['mobileNum'] ?? ''); ?>"
                        class="<?= inputClass() ?>" placeholder="Enter Mobile Number">
                    <div>
                        <p class="mt-2 text-sm font-xs text-red-500"><?php echo h($errors['mobileNum'] ?? '');?></p>
                    </div>
                </div>
                
                <!-- Submit Button -->
                  <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Create an Member Account</button>
              </form>
          </div>
      </div>
  </div>
</section>