<?php
    // function sanitize($data) {
    //     $data = trim($data);
    //     $data = stripslashes($data);
    //     $data = htmlspecialchars($data);
    //     return $data;
    // }

    function h($data){
        $data = htmlspecialchars($data);
        return $data;
    }

    function isLoggedIn() {
        return isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true;
    }

    function calcAge($dob){
        // Validate age
        $date = new DateTime();
       
        $birthday = trim($dob);
        $birthday_obj = new DateTime($birthday);

        $diff = $date->diff($birthday_obj);
        $yearGap = $diff->y;
        
        return $yearGap;
    }

    function ifEmpty($value, $message){
        return empty($value) ? $message : '';
    }

    function trimPost($value){
        if (!isset($_POST[$value])) {
            return '';
        }
        return trim($_POST[$value]);
    }

    function h1($text) {
        return "<h1 class='text-2xl md:text-3xl font-semibold text-gray-900 dark:text-white'>$text</h1>";
    }

    function h2($text) {
        return "<h2 class='text-lg md:text-xl mb-4 font-medium text-gray-900 dark:text-white'>$text</h2>";
    }

    function h3($text) {
        return "<h3 class='text-base md:text-lg mb-2 font-medium text-gray-800 dark:text-white'>$text</h3>";
    }

    function h4($text) {
        return "<h4 class='text-sm md:text-base font-medium text-gray-700 dark:text-gray-200'>$text</h4>";
    }

    function h5($text) {
        return "<h5 class='text-sm font-medium text-gray-600 dark:text-gray-300'>$text</h5>";
    }

    function h6($text) {
        return "<h6 class='text-xs font-medium text-gray-500 dark:text-gray-400'>$text</h6>";
    }
    function p($text) {
        return "<p class='text-sm text-gray-700 dark:text-gray-300'>$text</p>";
    }
    function small($text) {
        return "<p class='text-sm font-light text-gray-500 dark:text-gray-400'>$text</p>";
    }
    function smallError() {
        return "mt-2 text-sm font-xs text-red-500";
    }


    function primaryBtn(){
        return "
            w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none 
            focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 
            dark:hover:bg-blue-700 dark:focus:ring-blue-800
    ";}

    function secondaryBtn() {
        return "
            text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 
            focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded-lg 
            text-sm px-5 py-2.5 text-center dark:bg-gray-800 dark:text-white dark:border-gray-600 
            dark:hover:bg-gray-700 dark:focus:ring-gray-700
    ";}

    function inputClass(){
        return "bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body
    ";}


    function labelClass(){
        return "block mb-2.5 text-base font-base text-heading dark:text-white";
    }

    function formPadding(){
        return "p-6 space-y-4 md:space-y-6 sm:p-8";
    }


    function cardClass(){
        return "w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-xl xl:p-0 
            dark:bg-gray-800 dark:border-gray-700";
    }

    function centerContainer() {
        return "flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0";
    }
    function formClass() {
        return "space-y-4 md:space-y-6";
    }
    function badgeBlue(){
        return "bg-brand-softer border border-brand-subtle text-fg-brand-strong text-xs font-medium px-1.5 py-0.5 rounded-full";
    }
    function badgeGray(){
        return "bg-neutral-secondary-medium border border-default-medium text-heading text-xs font-medium px-1.5 py-0.5 rounded-full";
    }
    
    function alert($session,$message, $view){
         // There is an error in the form
        $_SESSION[$session] = $message;
        header("Location: $view");
        exit;
    }
?>