<!doctype html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/styles/main.css"> 
    <link rel="stylesheet" href="/css/output.css">
    <link rel="canonical" href="https://flowbite-admin-dashboard.vercel.app/">
  </head>
  
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    
    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark')
    }
</script>