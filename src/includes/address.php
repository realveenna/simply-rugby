<?php
    use Test\Models\Address;    
    $countries = Address::getCountries();
?>

<?= h3("Address Information")?>

<div>
    <label for="line1" class="block mb-2 text-sm font-small text-gray-900 dark:text-white">Address Line 1 </label>
    <input type="text" name="line1" 
        placeholder="House number + Street"
        value="<?php echo h($line1);?>"
        class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
    <div>
        <p class="mt-2 text-sm font-xs text-red-500"><?php echo h($line1Err);?></p>
    </div>
</div>

<div>
    <label for="line2" class="block mb-2 text-sm font-small text-gray-900 dark:text-white">Address Line 2 </label>
    <input type="text" name="line2" 
        placeholder="Flat / Apartment (optional)"
        value="<?php echo h($line2);?>"
        class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
    <div>
        <p class="mt-2 text-sm font-xs text-red-500"><?php echo h($line2Err);?></p>
    </div>
</div>

<div>
    <label for="city" class="block mb-2 text-sm font-small text-gray-900 dark:text-white">City </label>
    <input type="text" name="city" 
        placeholder="Enter City"
        value="<?php echo h($city);?>"
        class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
    <div>
        <p class="mt-2 text-sm font-xs text-red-500"><?php echo h($cityErr);?></p>
    </div>
</div>

<div>
    <label for="postcode" class="block mb-2 text-sm font-small text-gray-900 dark:text-white">Postcode </label>
    <input type="text" name="postcode" 
        placeholder="Enter Postcode"
        value="<?php echo h($postcode);?>"
        class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
    <div>
        <p class="mt-2 text-sm font-xs text-red-500"><?php echo h($postcodeErr);?></p>
    </div>
</div>

<div>
    <label for="country" class="block mb-2 text-sm font-small text-gray-900 dark:text-white">Country </label>
     <select class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
        autocomplete="country" name="country">
        <option value="" disabled selected> Select Country:</option>
        <?php foreach ($countries as $c): ?>
            <option value="<?= $c ?>"
                <?php 
                    if($country === $c){
                        echo 'selected';
                    }
                ?>>
                <?= $c?>
            </option>
        <?php endforeach; ?>
    </select>
        <p class="mt-2 text-sm font-xs text-red-500"><?php echo h($countryErr);?></p>
</div>
