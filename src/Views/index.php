<section class="bg-gray-50 dark:bg-gray-900">
  <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0">
      <!-- Logo -->
      <a href="/" class="flex flex-col items-center justify-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
          <img class="w-8 h-8 mr-2" src="./images/logo/main-logo.png" alt="logo">
      </a>  
      
      <div class="<?= cardClassXL() ?> flex flex-col gap-6 p-4">
        <!-- Simply Rugby -->
        <?= title('Welcome to Simply','Rugby!') ?>


        <div>
            <?= titleLeftSmall('Where Passion Meets Rugby') ?>
        
            <?= p("
              Based in the heart of Glasgow, Simply Rugby Club is more than just a rugby club - it's a community built on teamwork, respect, dedication,
              and enjoyment of the game. We welcome players of all ages and abilities, from young beginners taking
              their first steps onto the pitch to experienced players looking to develop their skills and compete at 
              the highest level.Our club provides structured training sessions, competitive fixtures, and development
              opportunities across all age groups. With qualified coaches, modern facilities, and a 
              supportive environment, we aim to help every player reach their full potential both on and off 
              the field.
            ") ?>
		</div>
        

        <div>
            <?= titleLeftSmall('Join Our Rugby Family') ?>

            <?= p("
                At Simply Rugby Club, we welcome players and families of all ages and abilities. Whether you're new
                to rugby or an experienced player, you'll find a friendly, inclusive, and supportive environment. 
                Junior players are especially encouraged to join, develop their skills, make new friends, and enjoy
                the game in a safe and enjoyable setting, while parents are always welcome to be part of our rugby
                community and support their children's journey.
            ")?>
		</div>

        <a href="register" class="<?= primaryBtn() ?>  w-auto ml-auto py-6">
          Register Now!
        </a>

      </div>
  </div>
</section>


