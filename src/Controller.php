<?php
namespace Test;
use Test\Database;
use Test\Base;

    class Controller extends Base
    {
        protected function render($view, $data = [])
        {
            
            extract($data);

            include '../src/includes/header.php';
            include '../src/includes/navbar.php';

        ?>
        <?php
            // On load clear success message
            $success = $_SESSION['success'] ?? "";
            unset($_SESSION['success']);

            // On load clear error message
            $sessionError = $_SESSION['error'] ?? "";
            unset($_SESSION['error']);

            $alertOn = (!empty($success) || !empty($sessionError));
        ?>
        <!-- add overflow-y-auto  div class -->
        <div id="main-content" class="relative w-full min-h-screen flex flex-col bg-gray-50 lg:ml-64 dark:bg-gray-900">
            <?php if($alertOn) :?>
                <div id="alertMessage" class="absolute w-11/12 md:w-1/2 z-30 top-5 left-1/2 -translate-x-1/2">
                    <?php if(!empty($sessionError)) :?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">An error has occurred:</strong>
                            <span class="block sm:inline"><?=$sessionError?></span>
                            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                            </span>
                        </div>
                    <?php endif ;?>
                    <?php if(!empty($success)) :?>
                        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
                                <strong class="font-bold">Success!</strong>
                                <span class="block sm:inline"><?=$success?></span>
                                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                    <svg class="fill-current h-6 w-6 text-blue-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                                </span>
                            </div>
                    <?php endif ;?>
                </div>
            <?php endif ;?>

            <main>
                <div class="max-w-7xl mx-auto px-4 pt-6 my-8">
                    <?php include "../src/Views/$view.php"; ?>
                </div>
            </main>
            <p class="my-10 text-sm text-center text-gray-500">
                &copy; 2019-2025 <a href="https://flowbite.com/" class="hover:underline" target="_blank">Flowbite.com</a>. All rights reserved.
            </p>
        </div>
        <?php
            include '../src/includes/footer.php';
        }
    }
?>