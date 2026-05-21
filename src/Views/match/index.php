<section class="bg-gray-50 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0">
        <div class="<?= cardClassXLNoBg() ?>">
            <div class="<?= formPadding() ?>">
                <?= title('All', 'Matches') ?>
                <?php foreach ($matches as $matches):?>
                    <div>
                        <button class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">See more</button>
                    </div>
                <?php endforeach ;?>
            </div>
        </div>
    </div>
</section>
