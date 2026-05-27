<?php
    use Test\Auth;
    use Test\Controllers\Error;

    // Helper Authentication function to check if user is authorized else redirect
    function authorize($permission)
    {
        return Auth::authorize($permission);
    }

    // Helper Authentication function to check if user has permission
    function hasPermission($permission)
    {
        return Auth::hasPermission($permission);
    }

    // Helper Authentication function to check is user has role name
    function hasRole($roleName)
    {
        return Auth::hasRole($roleName);
    }
    // User is a Club Chairperson or Membership Secretary
    function isAdmin()
    {
        return Auth::isAdmin();
    }

    // User has section access
    function hasSectionAccess($section_id)
    {
        return Auth::hasSectionAccess($section_id);
    }
    // User has squad access
    function hasSquadAccess($squad_id)
    {
        return Auth::hasSquadAccess($squad_id);
    }

    // User is the owner
    function isOwner($currentUser, $member_id)
    {
        return Auth::isOwner($currentUser, $member_id);
    }

    // User has access to individual player (Parent) 
    function hasPlayerAccess($member_id)
    {
        return Auth::hasPlayerAccess($member_id);
    }

    // Function for error redirect
    function abort($response_code, $message = null)
    {
        http_response_code($response_code);

        // Default messages
        switch ($response_code) {
            case 403:
                $title = 'Access Denied';
                $message = $message ?? 'You do not have permission to access this page or resource.';
                break;
            case 404:
                $title = 'Page Not Found';
                $message = $message ?? 'This page does not exist.';
                break;
            case 500:
                $title = 'Internal Server Error';
                $message = $message ?? 'Ooops! Something went wrong.';
                break;
            default:
                $title = 'Unknown Error';
                $message = $message ?? 'Somethingg went terribly wrong.';
        }

        // Redirect
        $error = new Error();
        $error->index($response_code, $title, $message);
        exit;
    }

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
    

    // Format date for db
    function formatDate($date)
    {
        return date('Y-m-d', strtotime($date));
    }

    // Format date for display 
    // THU 30 MAY
    function formatDateDisplay($date)
    {
        return strtoupper(date('D, d M Y', strtotime($date)));
    }

    // Date must be in future
    function isFutureDate($date)
    {
        $selectedDate = strtotime($date);
        $today = strtotime(date('Y-m-d'));
        return $selectedDate > $today;
    }

    // Date is today
    function isToday($date)
    {
        $selectedDate = strtotime($date);
        $today = strtotime(date('Y-m-d'));
        return $selectedDate === $today;
    }

    // Format time
    function formatTime($time){
        return date('g:i A', strtotime($time));
    }

    // POST input validation if empty
    function ifEmpty($value, $message){
        return empty($value) ? $message : '';
    }

    // Trim POST input value
    function trimPost($value){
        if (!isset($_POST[$value])) {
            return '';
        }
        return trim($_POST[$value]);
    }



    // Page Title
    function title($text,$underline){
        return"
        <div class='flex flex-col items-center'>
            <h1 class='mb-4 text-4xl font-semibold tracking-tight text-heading md:text-5l xxl:text-6xl'>
                    $text 
                <span class='underline underline-offset-3 decoration-4 decoration-brand'>
                    $underline
                </span>
            </h1>
        </div>";
    }
    function titleLeft($text,$underline){
        return"
        <div class='flex flex-col items-start text-left'>
            <h2 class='mb-3 mt-6 text-3xl font-semibold tracking-tight text-heading md:text-3l lg:text-4xl'>
                    $text 
                <span class='underline underline-offset-3 decoration-4 decoration-brand'>
                    $underline
                </span>
            </h2>
        </div>";
    }
    function titleLeftBrand($text){
        return"
        <div class='flex flex-col items-start text-left'>
            <h2 class='mb-3 text-2xl font-semibold tracking-tight text-brand md:text-2l lg:text-3xl'>
                $text 
            </h2>
        </div>";
    }
     function titleLeftSmall($text){
        return"
        <div class='flex flex-col mt-6 items-start text-left'>
            <h3 class='mb-3 text-2xl font-semibold tracking-tight text-heading md:text-2l lg:text-2xl'>
                    $text 
            </h3>
        </div>";
    }

    function heading4(){
        return "mb-2 text-2xl font-semibold tracking-tight text-heading";
    }
    function heading5(){
        return "mb-2 text-xl font-semibold tracking-tight text-heading";
    }

 
    function underline(){
        return "underline underline-offset-3 decoration-4 decoration-brand";
    }
    function heading1(){
        return "underline underline-offset-3 decoration-4 decoration-brand";
    }
    function h1($text) {
        return "<h1 class='mb-4 text-4xl font-semibold tracking-tight text-heading md:text-4xl lg:text-5xl dark:text-white'>$text</h1>";
    }

    function h2($text) {
        return "<h2 class='text-lg md:text-xl mb-4 font-medium text-gray-900 dark:text-white'>$text</h2>";
    }
    function h2Center($text) {
        return "<h2 class='text-lg  text-center md:text-xl mb-4 font-medium text-gray-900 dark:text-white'>$text</h2>";
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
            text-white bg-brand box-border border border-transparent  hover:bg-transparent 
            hover:border-brand hover:text-brand focus:ring-1 hover:bg-brand/80
            focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 
            focus:outline-none
    ";}
    function primaryBtnNoBG(){
        return "
            text-brand bg-transparent border border-brand hover:bg-brand-brand hover:text-white 
            hover:border-transparent focus:ring-4 focus:ring-brand-medium hover:bg-brand/80
            shadow-xs font-medium leading-5 rounded-base
            text-sm px-4 py-2.5 focus:outline-none
    ";}
    function dangerBtn(){
        return "
            text-red-600 flex mx-auto items-center justify-center
            hover:text-white border border-red-600 hover:bg-red-600/70
            focus:ring-4 focus:outline-none focus:ring-red-300
            font-medium rounded-lg text-sm px-5 py-2.5
            dark:border-red-500 dark:text-red-500
            dark:hover:text-white dark:hover:bg-red-600
            dark:focus:ring-red-900 w-full"
    ;}

    function secondaryBtn() {
        return "
        flex items-center justify-center py-2.5 px-5 text-sm font-medium 
        text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 
        hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-100 
        dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 
        dark:hover:text-white dark:hover:bg-gray-700
           
    ";}

     // text-body bg-neutral-secondary-medium box-border border border-default-medium 
            // hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 
            // focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm 
            // px-4 py-2.5 focus:outline-none

            
    // function dangerBtn() {
    //     return "
    //         text-white bg-danger box-border border border-transparent hover:bg-danger-strong 
    //         focus:ring-4 focus:ring-danger-medium shadow-xs font-medium leading-5 rounded-base text-sm 
    //         px-4 py-2.5 focus:outline-none
    // ";}

    function inputClass(){
        return "bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body
    ";}


    function labelClass(){
        return "block mb-2.5 text-base font-base text-heading dark:text-white";
    }

    function formPadding(){
        return "p-2 space-y-4 md:space-y-6 sm:p-8";
    }

    function cardClass(){
        return "w-full max-w-2xl p-4 mb-4 bg-white border border-gray-200 rounded-lg 
            shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800";
    }
    function cardClassXL(){
        return "w-full max-w-5xl p-4 mb-4 bg-white border border-gray-200 rounded-lg 
            shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800";
    }

    function cardClassXLNoBg(){
        return "w-full max-w-5xl rounded-lg xl:p-0
            dark:bg-gray-800 ";
    }

    function centerContainer() {
        return "flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0";
    }
    function formClass() {
        return "space-y-4 md:space-y-6";
    }
    function badgeBlue(){
        return "bg-brand-soft border inline-flex border-blue-300 text-fg-brand-strong text-xs font-medium px-1.5 py-0.5 rounded";
    }
    function badgeGray(){
        return "bg-neutral-secondary-medium  inline-flex  border border-default-medium text-heading text-xs font-medium px-1.5 py-0.5 rounded-full";
    }
    function badgeSuccess(){
        return "bg-success-soft border inline-flex  border-success-subtle text-fg-success-strong text-xs font-medium px-1.5 py-0.5 rounded";
    }
    function badgeWarning(){
        return"bg-warning-soft border inline-flex  border-warning-subtle text-fg-warning text-xs font-medium px-1.5 py-0.5 rounded";
    }
    function badgeDanger(){
        return "bg-danger-soft border inline-flex  border-danger-subtle text-fg-danger-strong text-xs font-medium px-1.5 py-0.5 rounded";
    }
    
    function alert($session,$message, $view){
         // There is an error in the form
        $_SESSION[$session] = $message;
        header("Location: $view");
        exit;
    }

    function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        $pass = implode($pass);

        return $pass; 
    }

    function hashPassword($rawPassword){
        $salt ="4g£yc7!L(";
        return md5($rawPassword.$salt);
    }
?>