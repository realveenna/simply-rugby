<!-- If member_id is set-->
<section class="bg-gray-50 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0">
        <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-xl xl:p-0 dark:bg-gray-800 dark:border-gray-700">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <a href="/" class="flex flex-col items-center justify-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
                    <img class="w-8 h-8 mr-2" src="/images/logo/main-logo.png" alt="logo">
                    Creating Login Details
                </a>  
                <form class="space-y-4 md:space-y-6" method="post" action="">
                    
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

                    <!-- Section selection for fixture and section secretary -->
                    <div id="showSection" class="hidden">
                        <div class="flex items-center mb-4">
                            <input id="juniorSection" type="radio" value="2" name="selectSection" class="w-4 h-4 text-neutral-primary border-default-medium bg-neutral-secondary-medium rounded-full checked:border-brand focus:ring-2 focus:outline-none focus:ring-brand-subtle border border-default appearance-none">
                            <label for="juniorSection" class="select-none ms-2 text-sm font-medium text-heading">Junior Section</label>
                        </div>
                        <div class="flex items-center">
                            <input checked id="seniorSection" type="radio" value="1" name="selectSection" class="w-4 h-4 text-neutral-primary border-default-medium bg-neutral-secondary-medium rounded-full checked:border-brand focus:ring-2 focus:outline-none focus:ring-brand-subtle border border-default appearance-none">
                            <label for="seniorSection" class="select-none ms-2 text-sm font-medium text-heading">Senior Section</label>
                        </div>
                        <div>
                            <p class="<?= smallError() ?>"><?php echo h($errors['section'] ?? '');?></p>
                        </div>
                    </div> 

                    <!-- Squad selection for coaches -->
                    <div id="showSquad" class="hidden">
                        <label for="selectedSquad" class="block mb-2.5 text-sm font-medium text-heading dark:text-white">Select an option</label>
                        <select id="selectedSquad" name="selectedSquad" class="block w-full px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body">
                            <option value="">Select a Squad</option>
                            <?php foreach ($squads as $squad): ?>
                                <option value="<?= (int)$squad['squad_id'] ?>"
                                    <?php 
                                        if((int)$data['selectedSquad'] === (int)$squad['squad_id']){
                                            echo 'selected';
                                        }
                                    ?>>
                                    <?= h($squad['squad_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div>
                            <p class="<?= smallError() ?>"><?php echo h($errors['selectedSquad'] ?? '');?></p>
                        </div>
                    </div> 

                    <!-- Button -->
                    <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Create an Member Account</button>
                    <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                        Already have an account? <a href="/login" class="font-medium text-blue-600 hover:underline dark:text-blue-500">Login here</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
  
// On change of selected role, identify role and show hidden divs
const selectedRole = document.getElementById('selectedRole');
const showSection = document.getElementById('showSection');
const showSquad = document.getElementById('showSquad'); 

selectedRole.addEventListener('change', function () {
    // Show section selection for fixture and section secretary
    if (this.value == 3 || this.value == 4) {
        showSection.classList.remove('hidden');
    }else{
        showSection.classList.add('hidden');
    }

    // Show squad selection for coaches
    if(this.value == 5) {
        showSquad.classList.remove('hidden');
    }else{
        showSquad.classList.add('hidden');
    }
});

</script>

