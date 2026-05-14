<section class="bg-gray-50 dark:bg-gray-900">
  <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0">
      <div class="<?= cardClass() ?>">
        <div class="<?= formPadding() ?>">
            <a href="/" class="flex flex-col items-center justify-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
                <img class="w-8 h-8 mr-2" src="/images/logo/main-logo.png" alt="logo">
                Player Application Form 
            </a>  
           
            <!-- Player Details Form -->
            <?php if ($formNum == 1): ?>
                <form class="<?= formClass() ?>" method="post" action="/register">
                    <input type="hidden" name="form_num" value="1"> 
                    <?= h2("Player Details")?>
                    <!-- Fist name, last name, dob -->
                    <div>
                        <label for="fName" class="<?= labelClass() ?>">First Name</label>
                        <input type="text" name="fName" id="fName"
                            value="<?php echo h($fName);?>"
                            class="<?= inputClass()?>" placeholder="Enter First Name">
                        <div>
                            <p class="<?= smallError() ?>"><?php echo h($fNameErr);?></p>
                        </div>
                    </div>
                    <div>
                        <label for="lName" class="<?= labelClass() ?>">Last Name</label>
                        <input type="text" name="lName" id="lName"
                            value="<?php echo h($lName);?>"
                            class="<?= inputClass()?>" placeholder="Enter Last Name">
                        <div>
                            <p class="<?= smallError() ?>"><?php echo h($lNameErr);?></p>
                        </div>
                    </div>
                    <div>
                        <label for="dob" class="<?= labelClass() ?>">Date of Birth</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-body" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 10h16m-8-3V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Zm3-7h.01v.01H8V13Zm4 0h.01v.01H12V13Zm4 0h.01v.01H16V13Zm-8 4h.01v.01H8V17Zm4 0h.01v.01H12V17Zm4 0h.01v.01H16V17Z"/></svg>
                            </div>
                            <input datepicker name="dob" id="dobRegister" type="text" value="<?php echo h($dob);?>"
                            class="block w-full ps-9 pe-3 py-2.5 <?= inputClass()?>" placeholder="Select date">
                        </div>
                        <div>
                            <p class="<?= smallError() ?>"><?php echo h($dobErr);?></p>
                        </div>
                    </div>
                     <!-- Nickname -->
                    <div>
                        <label for="playerNickname" class="<?= labelClass() ?>">Nickname</label>
                        <input type="text" name="playerNickname" id="playerNickname" 
                            value="<?php echo h($playerNickname);?>"
                            class="<?= inputClass()?>" placeholder="Enter player nickname (optional)">
                    </div>
                    <!-- Height and Weight -->
                    <div class="grid gap-2 mb-6 md:grid-cols-2">
                        <div>
                            <label for="playerHeight" class="<?= labelClass() ?>">Height</label>
                            <input type="number" name="playerHeight" id="playerHeight" 
                                value="<?php echo h($playerHeight);?>"
                                class="<?= inputClass()?>" placeholder="Enter in cm">
                            <div>
                                <p class="<?= smallError() ?>"><?php echo h($playerHeightErr);?></p>
                            </div>
                        </div>
                        <div>
                            <label for="playerWeight" class="<?= labelClass() ?>">Weight</label>
                            <input type="number" name="playerWeight" id="playerWeight" 
                                value="<?php echo h($playerWeight);?>"
                                class="<?= inputClass()?>" placeholder="Enter in kg">
                            <div>
                                <p class="<?= smallError() ?>"><?php echo h($playerWeightErr);?></p>
                            </div>
                        </div>
                    </div>
                 
                    <!-- Prompt guardian if they have an exisint login details
                    <div id="if-existing-login">
                        <label for="if-existing-login" class="labelClass() ?>">
                            Do you have an existing parent account?
                        </label>
                        <div>
                            <div class="flex items-center mb-4">
                                <input id="existing-login" type="radio" value="1" name="existing-login" class="w-4 h-4 text-neutral-primary border-default-medium bg-neutral-secondary-medium rounded-full checked:border-brand focus:ring-2 focus:outline-none focus:ring-brand-subtle border border-default appearance-none">
                                <label for="existing-login" class="select-none ms-2 text-sm font-medium text-heading">Yes</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="no-existing-login" type="radio" value="0" name="existing-login" class="w-4 h-4 text-neutral-primary border-default-medium bg-neutral-secondary-medium rounded-full checked:border-brand focus:ring-2 focus:outline-none focus:ring-brand-subtle border border-default appearance-none">
                                <label for="no-existing-login" class="select-none ms-2 text-sm font-medium text-heading">No</label>
                            </div>
                        </div>
                    </div> -->


                    <!-- Next Button -->
                    <button type="submit" name="action" value="next" class="<?= primaryBtn() ?>">
                        Next
                    </button>
                </form>

            <!-- More Form Details -->
            <?php elseif ($formNum == 2): ?>
                <form class="<?= formClass() ?>" method="post" action="/register">
                    <input type="hidden" name="form_num" value="2"> 

                     <!-- Identify player squad -->
                     <input type="hidden" name="isJunior" value="<?= $isJunior ? 1 : 0 ?>">
                    
                    <!-- Keep input value from first form -->
                    <input type="hidden" name="fName" value="<?= h($fName) ?>">
                    <input type="hidden" name="lName" value="<?= h($lName) ?>">
                    <input type="hidden" name="dob" value="<?= h($dob) ?>">
                    <input type="hidden" name="playerNickname" value="<?= h($playerNickname) ?>">
                    <input type="hidden" name="playerHeight" value="<?= $playerHeight ?>">
                    <input type="hidden" name="playerWeight" value="<?= $playerWeight ?>">
  
                    <!-- If Senior Player prompt personal contact form -->
                    <?php if (!$isJunior): ?>
                        <!-- Contact Details -->
                        <?= h2("Contact Details")?>

                        <?php include '../src/includes/contact.php'?>
                        <?php include '../src/includes/address.php'?>
                    <?php endif ;?>	
                    
                     <!-- Nok Details -->
                    <?= h2($nok ." Details")?>
                    <?= h3("Personal Details")?>
                    <div>
                        <label for="nokFName" class="<?= labelClass() ?>">First Name</label>
                        <input type="text" name="nokFName" id="nokFName" 
                            value="<?php echo h($nokFName);?>"
                            class="<?= inputClass()?>" placeholder="<?=$nok?> First Name">
                        <div>
                            <p class="<?= smallError() ?>"><?php echo h($nokFNameErr);?></p>
                        </div>
                    </div>
                    
                    <div>
                        <label for="nokLName" class="<?= labelClass() ?>">Last Name</label>
                        <input type="text" name="nokLName" id="nokLName" 
                            value="<?php echo h($nokLName);?>"
                            class="<?= inputClass()?>" placeholder="<?=$nok?> Last Name">
                        <div>
                            <p class="<?= smallError() ?>"><?php echo h($nokLNameErr);?></p>
                        </div>
                    </div>
                    <div>
                        <label for="nokRelationship" class="<?= labelClass() ?>">Relationship</label>
                        <select class="<?= inputClass()?>"
                                autocomplete="nokRelationship" name="nokRelationship">
                                <option value="" disabled selected> Select Relationship:</option>
                                <?php foreach ($relationships as $r): ?>
                                    <option value="<?= $r ?>"
                                        <?php 
                                            if($nokRelationship === $r){
                                                echo 'selected';
                                            }
                                        ?>>
                                        <?= h($r) ?>
                                    </option>
                                <?php endforeach; ?>
                        </select>
                        <div>
                            <p class="<?= smallError() ?>"><?php echo h($nokRelationshipErr);?></p>
                        </div>
                    </div>

                    <!-- If junior player -->
                    <?php if ($isJunior): ?>
                        <!-- include address and mobile form for Nok above-->
                        <?php include '../src/includes/contact.php'?>
                        <?php include '../src/includes/address.php'?>

                        <div>
                              <label for="applyCoach" class="flex items-center mb-5">
                                <input id="applyCoach" aria-describedby="applyCoach" name="applyCoach" type="checkbox" value="1" 
                                class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800">
                                <p class="ms-2 text-sm text-small text-heading select-none">
                                    Please tick the box if you wish to apply for a coach position
                                </p>
                            </label>
                        </div>

                        <!-- Guardian 2 Details -->
                        <?= h2("Guardian 2 Details")?>
                        <?= h3("Personal Details")?>
                        <div>
                            <label for="nokFNameSecondary" class="<?= labelClass() ?>">First Name</label>
                            <input type="text" name="nokFNameSecondary" id="nokFNameSecondary" 
                                value="<?php echo h($nokFNameSecondary);?>"
                                class="<?= inputClass()?>" 
                                placeholder="Enter Guardian 2 First Name">
                            <div>
                                <p class="<?= smallError() ?>"><?php echo h($nokFNameSecondaryErr);?></p>
                            </div>
                        </div>

                        <div>
                            <label for="nokLNameSecondary" class="<?= labelClass() ?>">Last Name</label>
                            <input type="text" name="nokLNameSecondary" id="nokLNameSecondary" 
                                value="<?php echo h($nokLNameSecondary);?>"
                                class="<?= inputClass()?>" 
                                placeholder="Enter Guardian 2 Last Name">
                            <div>
                                <p class="<?= smallError() ?>"><?php echo h($nokLNameSecondaryErr);?></p>
                            </div>
                        </div>
                        <div>
                            <label for="nokRelationshipSecondary" class="<?= labelClass() ?>">Relationship</label>
                            <select class="<?= inputClass()?>"
                                autocomplete="nokRelationshipSecondary" name="nokRelationshipSecondary">
                                <option value="" disabled selected> Select Relationship:</option>
                                <?php foreach ($relationships as $r): ?>
                                    <option value="<?= $r ?>"
                                        <?php 
                                            if($nokRelationshipSecondary === $r){
                                                echo 'selected';
                                            }
                                        ?>>
                                        <?= h($r) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div>
                                <p class="<?= smallError() ?>"><?php echo h($nokRelationshipSecondaryErr);?></p>
                            </div>
                        </div>
                            
                        <!-- Guardian 2 Mobile Number -->
                        <div>
                            <label for="mobileNumSecondary" class="<?= labelClass() ?>">Mobile Number </label>
                            <input type="tel" name="mobileNumSecondary" 
                                value="<?php echo h($mobileNumSecondary);?>"
                                pattern="^07\d{9}$" placeholder="07123456789"
                                class="<?= inputClass()?>" 
                                pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="123-456-7890">
                            <div>
                                <p class="<?= smallError() ?>"><?php echo h($mobileNumSecondaryErr);?></p>
                            </div>
                        </div>

                        <!-- Guardian 2 Address -->
                        <div id="guardian2-address">
                            <div>
                            <label for="line1Secondary" class="<?= labelClass() ?>">Address Line 1 </label>
                            <input type="text" name="line1Secondary" 
                                placeholder="House number + Street"
                                value="<?php echo h($line1Secondary);?>"
                                class="<?= inputClass()?>" placeholder="Enter Line 1">
                            <div>
                                <p class="<?= smallError() ?>"><?php echo h($line1SecondaryErr);?></p>
                            </div>
                            </div>
                            <div>
                                <label for="line2Secondary" class="<?= labelClass() ?>">Address Line 2 </label>
                                <input type="text" name="line2Secondary" 
                                    placeholder="Flat / Apartment (optional)"
                                    value="<?php echo h($line2Secondary);?>"
                                    class="<?= inputClass()?>" placeholder="Enter Last Name">
                                <div>
                                    <p class="<?= smallError() ?>"><?php echo h($line2SecondaryErr);?></p>
                                </div>
                            </div>

                            <div>
                                <label for="citySecondary" class="<?= labelClass() ?>">City </label>
                                <input type="text" name="citySecondary" 
                                    placeholder="Enter City"
                                    value="<?php echo h($citySecondary);?>"
                                    class="<?= inputClass()?>" placeholder="Enter Last Name">
                                <div>
                                    <p class="<?= smallError() ?>"><?php echo h($citySecondaryErr);?></p>
                                </div>
                            </div>

                            <div>
                                <label for="postcodeSecondary" class="<?= labelClass() ?>">Postcode </label>
                                <input type="text" name="postcodeSecondary" 
                                    placeholder="Enter Postcode"
                                    value="<?php echo h($postcodeSecondary);?>"
                                    class="<?= inputClass()?>" placeholder="Enter Last Name">
                                <div>
                                    <p class="<?= smallError() ?>"><?php echo h($postcodeSecondaryErr);?></p>
                                </div>
                            </div>

                            <div>
                                <label for="countrySecondary" class="<?= labelClass() ?>">Country </label>
                                <select class="<?= inputClass()?>"
                                    autocomplete="country" name="countrySecondary">
                                    <option value="" disabled selected> Select Country:</option>
                                    <?php foreach ($countries as $c): ?>
                                        <option value="<?= $c ?>"
                                            <?php 
                                                if($countrySecondary === $c){
                                                    echo 'selected';
                                                }
                                            ?>>
                                            <?= $c?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                    <p class="<?= smallError() ?>"><?php echo h($countrySecondaryErr);?></p>
                            </div>
                        </div>
                        <div>
                            <label for="sameAddress" class="flex items-center mb-5">
                                <input id="sameAddress" type="checkbox" name="sameAddress" value="1"
                                 class="w-4 h-4 bg-gray-50 border border-gray-300 text-gray-900 rounded-lg border border-default-medium rounded-xs">
                                <p class="ms-2 text-sm text-small text-heading select-none">
                                    Use Same Address.</p>
                            </label>
                        </div>
                    <?php else: ?>
                        <!-- Prompt for Contact Mobile Number -->
                        <div>
                            <label for="mobileNumSecondary" class="<?= labelClass() ?>">Mobile Number </label>
                            <input type="tel" name="mobileNumSecondary" 
                                value="<?php echo h($mobileNumSecondary);?>"
                                pattern="^07\d{9}$" placeholder="07123456789"
                                class="<?= inputClass()?>" 
                                pattern="[0-9]{11}" placeholder="071234567891">
                            <div>
                                <p class="<?= smallError() ?>"><?php echo h($mobileNumSecondaryErr);?></p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Medical Data -->
                    <?= h2("Medical Data")?>
                    <!-- Allergies -->
                    <div>
                        <label for="allergies"
                            class="<?= labelClass() ?>"> 
                            Allergies </label>
                        <select multiple id="allergies" name="allergies[]"
                            class="<?= inputClass()?>">
                            <option disabled>Select Allergy</option>
                            <option value="0"> None </option>
                            
                            <!-- List medical condition -->
                            <?php foreach ($allergies as $a): ?>
                                <option value="<?= $a['allergy_id'] ?>"
                                    <?= in_array($a['allergy_id'], $allergy) ? 'selected' : '' ?>>
                                    <?= h($a['allergy_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- Current Medical Condition -->
                    <div>
                        <label for="currentCondition"
                            class="<?= labelClass() ?>"> 
                            Current Medical Condition </label>
                        <select multiple id="currentCondition" name="currentCondition[]"
                            class="<?= inputClass()?>">
                            <option disabled>Select Current Medical Conditions</option>
                            <option value="0"> None </option>
                             
                            <!-- List medical condition -->
                            <?php foreach ($medicalInformation as $c): ?>
                                <option value="<?= $c['condition_id'] ?>"
                                    <?= in_array($c['condition_id'], $currentCondition) ? 'selected' : '' ?>>
                                    <?= h($c['condition_name']) ?>
                                </option>
                            <?php endforeach; ?>

                        </select>
                    </div>
                    <!-- Past Medical Condition -->
                    <div>
                        <label for="pastCondition"
                            class="<?= labelClass() ?>"> 
                            Past Medical Condition </label>
                        <select multiple id="pastCondition" name="pastCondition[]"
                            class="<?= inputClass()?>">
                            <option disabled>Select Past Medical Conditions</option>
                            <option value="0"> None </option>

                            <!-- List medical condition -->
                            <?php foreach ($medicalInformation as $c): ?>
                                <option value="<?= $c['condition_id'] ?>"
                                    <?= in_array($c['condition_id'], $pastCondition) ? 'selected' : '' ?>>
                                    <?= h($c['condition_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Doctor Details -->
                    <div>
                        <label for="doctor" class="<?= labelClass() ?>">Doctor Full Name</label>
                        <input type="text" name="doctor" id="doctor"
                            value="<?php echo h($doctor);?>"
                            class="<?= inputClass()?>" placeholder="Enter Doctor's Name">
                        <div>
                            <p class="<?= smallError() ?>"><?php echo h($doctorErr);?></p>
                        </div>
                    </div>
                    <div>
                        <label for="doctorNum" class="<?= labelClass() ?>">Doctor Contact Number</label>
                        <input type="text" name="doctorNum" id="doctorNum"
                            value="<?php echo h($doctorNum);?>"
                            class="<?= inputClass()?>" placeholder="Enter First Name">
                        <div>
                            <p class="<?= smallError() ?>"><?php echo h($doctorNumErr);?></p>
                        </div>
                    </div>
                    <!-- Doctor Address -->
                    <div>
                        <label for="line1Doctor" class="<?= labelClass() ?>">Address Line 1</label>
                        <input type="text" name="line1Doctor" 
                            placeholder="House number + Street"
                            value="<?php echo h($line1Doctor);?>"
                            class="<?= inputClass()?>" placeholder="Enter Line 1">
                        <div>
                            <p class="<?= smallError() ?>"><?php echo h($line1DoctorErr);?></p>
                        </div>
                    </div>
                    <div>
                        <label for="line2Doctor" class="<?= labelClass() ?>">Address Line 2 </label>
                        <input type="text" name="line2Doctor" 
                            placeholder="Flat / Apartment (optional)"
                            value="<?php echo h($line2Doctor);?>"
                            class="<?= inputClass()?>" placeholder="Enter Last Name">
                        <div>
                            <p class="<?= smallError() ?>"><?php echo h($line2DoctorErr);?></p>
                        </div>
                    </div>

                    <div>
                        <label for="cityDoctor" class="<?= labelClass() ?>">City </label>
                        <input type="text" name="cityDoctor" 
                            placeholder="Enter City"
                            value="<?php echo h($cityDoctor);?>"
                            class="<?= inputClass()?>" placeholder="Enter Last Name">
                        <div>
                            <p class="<?= smallError() ?>"><?php echo h($cityDoctorErr);?></p>
                        </div>
                    </div>

                    <div>
                        <label for="postcodeDoctor" class="<?= labelClass() ?>">Postcode </label>
                        <input type="text" name="postcodeDoctor" 
                            placeholder="Enter Postcode"
                            value="<?php echo h($postcodeDoctor);?>"
                            class="<?= inputClass()?>" placeholder="Enter Last Name">
                        <div>
                            <p class="<?= smallError() ?>"><?php echo h($postcodeDoctorErr);?></p>
                        </div>
                    </div>

                    <div>
                        <label for="countryDoctor" class="<?= labelClass() ?>">Country </label>
                        <select class="<?= inputClass()?>"
                            autocomplete="country" name="countryDoctor">
                            <option value="" disabled> Select Country:</option>
                            <?php foreach ($countries as $c): ?>
                                <option value="<?= $c ?>"
                                    <?php 
                                        if($countryDoctor === $c){
                                            echo 'selected';
                                        }
                                    ?>>
                                    <?= $c?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                            <p class="<?= smallError() ?>"><?php echo h($countryDoctorErr);?></p>
                    </div>
                    <div class="grid gap-2 mb-6 md:grid-cols-2">
                        <!-- Back Button -->
                        <button type="submit" name="action" value="back"
                            class="<?=secondaryBtn()?>">
                            Back
                        </button>
                        
                        <!-- Submit Button -->
                        <button type="submit" name="action" value="submit" 
                            class="<?=primaryBtn()?>">
                            Submit
                        </button>
                    </div>
                </form>
            <?php endif; ?>
      </div>
  </div>
</section>

