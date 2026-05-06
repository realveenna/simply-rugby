<!-- Email -->
<div>
    <label for="email" class="<?= labelClass() ?>">Email Address</label>
    <input type="email" name="email"
        value="<?php echo h($email);?>"
        class="<?= inputClass() ?>" placeholder="your@email.com">
    <div>
        <p class="<?= smallError() ?>"><?php echo h($emailErr);?></p>
    </div>
</div>

<!-- Phone Number -->
<div>
    <label for="mobileNum" class="block mb-2 text-sm font-small text-gray-900 dark:text-white">Mobile Number </label>
    <input type="tel" name="mobileNum"
        pattern="^07\d{9}$" placeholder="07123456789"
        value="<?php echo h($mobileNum);?>"
        class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Enter Last Name">
    <div>
        <p class="mt-2 text-sm font-xs text-red-500"><?php h($mobileNumErr);?></p>
    </div>
</div>