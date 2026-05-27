<button x-data="{
    darkMode: localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
}"
    x-on:click="
        darkMode = !darkMode; 
        if (darkMode) {
            document.documentElement.classList.add('dark');
            localStorage.setItem('color-theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('color-theme', 'light');
        }
    "
    class="p-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-gray-200 dark:focus:ring-gray-700 rounded-full text-sm">

    <i x-show="darkMode" class="fa-solid fa-sun text-lg"></i>

    <i x-show="!darkMode" class="fa-solid fa-moon text-lg"></i>
</button>
