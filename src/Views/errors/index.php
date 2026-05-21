<section class="bg-white dark:bg-gray-900">
    <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
        <div class="mx-auto max-w-screen-sm text-center">
            <!-- HTTP Response Code -->
            <h1 class="mb-4 text-7xl tracking-tight font-extrabold lg:text-9xl text-brand dark:text-primary-500">
                <?= $code ?? 'Unknown Code' ?>
            </h1>

            <!-- Error Title -->
            <p class="mb-4 text-3xl tracking-tight font-bold text-gray-900 md:text-4xl dark:text-white">
                <?= h($title ?? 'Unknown Error') ?>
            </p>
            
            <!-- Error Description -->
            <p class="mb-4 text-lg font-light text-gray-500 dark:text-gray-400">
                <?= $message ?? 'Something went terribly wrong' ?>
            </p>
            
            <a href="/"
                class="inline-flex text-white bg-brand hover:bg-brand-strong focus:ring-4 focus:outline-none focus:ring-brand-medium font-medium rounded-lg text-sm px-5 py-2.5 text-center my-4">
                Back to Homepage
            </a>
        </div>
    </div>
</section>