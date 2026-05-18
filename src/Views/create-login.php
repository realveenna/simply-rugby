<!-- If member_id is set-->
<section class="bg-gray-50 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0">
        <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-xl xl:p-0 dark:bg-gray-800 dark:border-gray-700">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <a href="/" class="flex flex-col items-center justify-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
                    <img class="w-8 h-8 mr-2" src="/images/logo/main-logo.png" alt="logo">
                    Creating Login Details
                </a>  
                <form class="space-y-4 md:space-y-6" method="post" action="/create-login?member_id=<?= h($data['member_id'] ?? '') ?>">
                    <!-- Hidden Inputs -->
                    <input type="hidden" name="email" value="<?php echo h($data['email'] ?? ''); ?>">
                    <input type="hidden" name="member_id" value="<?php echo h($data['member_id'] ?? ''); ?>">
                    <div>
                        <label for="email" class="<?= labelClass() ?>">Email Address</label>
                        <input type="email"  value="<?php echo h($data['email'] ?? ''); ?>"
                            class="<?= inputClass() ?>" placeholder="<?php echo h($data['email'] ?? ''); ?>" readonly>
                        <div>
                            <p class="<?= smallError() ?>"><?php echo h($errors['email'] ?? '');?></p>
                        </div>
                    </div>
                    <div>
                        <label for="rawPassword" class="<?= labelClass() ?>">Password</label>
                        <input type="password" name="rawPassword" id="rawPassword" 
                            value="<?php echo h($data['rawPassword'] ?? ''); ?>"
                            placeholder="Enter Password" class="<?= inputClass() ?>">
                        <div>
                            <p class="<?= smallError() ?>"><?php echo ($errors['rawPassword'] ?? '');?></p>
                        </div>
                    </div>
                    <div>
                        <label for="rawConfirmPassword" class="<?= labelClass() ?>">Confirm Password</label>
                        <input type="password" name="rawConfirmPassword" id="rawConfirmPassword" 
                            value="<?php echo h($data['rawConfirmPassword'] ?? ''); ?>"
                            placeholder="Enter Confirm Password" class="<?= inputClass() ?>">
                        <div>
                            <p class="<?= smallError() ?>"><?php echo ($errors['rawConfirmPassword'] ?? '');?></p>
                        </div> 
                    </div>
                    <!-- Role Selection -->
                    <div>
                        <label for="selectedRole" class="block mb-2.5 text-sm font-medium text-heading dark:text-white">Select an option</label>
                        <select id="selectedRole" name="selectedRole" class="block w-full px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body">
                            <option value="">Select a Role</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= (int)$role['role_id'] ?>"
                                    <?php 
                                        if((int)$data['selectedRole'] === (int)$role['role_id']){
                                            echo 'selected';
                                        }
                                    ?>>
                                    <?= h($role['role_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div>
                            <p class="<?= smallError() ?>"><?php echo h($errors['selectedRole'] ?? '');?></p>
                        </div>
                    </div>
                    <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Create an Member Account</button>
                    <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                        Already have an account? <a href="/login" class="font-medium text-blue-600 hover:underline dark:text-blue-500">Login here</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>

