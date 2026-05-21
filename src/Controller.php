<?php
namespace Test;
use Test\Base;

    class Controller extends Base
    {
        protected $user;
        protected $member_id;
        protected $rbac;

        public function __construct()
        {
            parent::__construct();
            
            $this->user = $_SESSION['user'] ?? null;
            $this->member_id = $this->user['member_id'] ?? null;
            $this->rbac = $_SESSION['rbac'] ?? null;
        }

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
                    <!-- Success Message -->
                    <?php if(!empty($success)) :?>
                        <div id="alert-1" class="flex sm:items-center p-4 mb-4 text-sm text-fg-brand-strong rounded-base bg-brand-softer" role="alert">
                            <svg class="w-4 h-4 shrink-0 mt-0.5 md:mt-0" aria-hidden="true" 
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" 
                                viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" 
                                stroke-linejoin="round" stroke-width="2" d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                            <span class="sr-only">Success Alert!</span>
                            <div class="ms-2 font-medium me-1">
                                <?= $success ?>
                            </div>
                                <button type="button" class="ms-auto -mx-1.5 -my-1.5 rounded focus:ring-2 focus:ring-brand-medium hover:bg-brand-soft inline-flex items-center justify-center h-8 w-8 shrink-0 shrink-0" data-dismiss-target="#alert-1" aria-label="Close">
                                <span class="sr-only">Close</span>
                                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/></svg>
                            </button>
                        </div>
                    <?php endif ;?>
                    
                    <!-- Error Message -->
                    <?php if(!empty($sessionError)) :?>
                        <div id="alert-2" class="flex sm:items-center p-4 mb-4 text-sm text-fg-danger-strong rounded-base bg-danger-soft" role="alert">
                            <svg class="w-4 h-4 shrink-0 mt-0.5 md:mt-0" aria-hidden="true" 
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" 
                                fill="none" viewBox="0 0 24 24"><path stroke="currentColor"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                            <span class="sr-only">Something went wrong!</span>
                            <div class="ms-2 font-medium me-1">
                                <?= $sessionError ?>
                            </div>
                            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-danger-soft text-fg-danger-strong rounded focus:ring-2 focus:ring-danger-medium p-1.5 hover:bg-danger-medium inline-flex items-center justify-center h-8 w-8 shrink-0 shrink-0" data-dismiss-target="#alert-2" aria-label="Close">
                                <span class="sr-only">Close</span>
                                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/></svg>
                            </button>
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
