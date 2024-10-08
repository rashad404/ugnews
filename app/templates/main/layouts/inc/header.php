<?php
use Helpers\Url;
use Models\CountryModel;
?>
<header class="bg-white shadow" x-data="{ mobileMenuOpen: false, searchDropdownOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4 lg:py-6">
            <div class="flex items-center">
                <a href="/" class="flex-shrink-0">
                    <img class="h-10 w-auto sm:h-10" width="107" height="40" src="<?=Url::templatePath()?>/img/partner_logos/<?=$_PARTNER['header_logo']?>" alt="<?=PROJECT_NAME?> logo"/>
                </a>
                <div class="hidden lg:ml-6 lg:flex lg:space-x-6 pl-4">
                    <?php foreach (array_slice($data['menus'], 0, 5) as $menu): ?>
                        <a href="<?=$menu['url']?>" class="text-base font-medium text-gray-500 hover:text-gray-900">
                            <?=$menu['title_' . $data['def_language']]?>
                        </a>
                    <?php endforeach; ?>
                    <?php if (count($data['menus']) > 7): ?>
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="text-base font-medium text-gray-500 hover:text-gray-900 flex items-center">
                                <?=$lng->get('More')?> 
                                <svg class="ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute z-10 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5">
                                <?php foreach (array_slice($data['menus'], 7) as $menu): ?>
                                    <a href="<?=$menu['url']?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <?=$menu['title_' . $data['def_language']]?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="flex-1 flex items-center justify-center px-2 lg:ml-6 lg:justify-end">
                <div class="max-w-lg w-full lg:max-w-xs relative hidden md:block">
                    <label for="search" class="sr-only"><?=$lng->get('Search')?></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input id="header_search_input" name="search" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="<?=$lng->get('Channel or News')?>" type="search">
                    </div>
                    <div id="headerSearchDropDown" class="absolute z-10 mt-2 w-full bg-white shadow-lg rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto max-h-96" style="display: none;">
                        <!-- Search results will be populated here -->
                    </div>
                </div>
            </div>
            <div class="flex items-center">


                <div x-data="countryDropdown()" class="ml-3 relative">
                    <div>
                        <!-- Call loadCountries when opening the dropdown -->
                        <button @click="open = !open; if (open) loadCountries()" type="button" class="flex items-center text-base font-medium text-gray-500 hover:text-gray-900" id="region-menu" aria-expanded="false" aria-haspopup="true">
                            
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418" />
                            </svg>

                            <span class="hidden md:inline"><?= CountryModel::getCode($_SETTINGS['region']) ?></span>
                            <svg class="ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                    <div x-show="open" @click.away="open = false" class="z-50 origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="region-menu" style="max-height: 300px; overflow-y: auto;">
                        <div class="px-4 py-2 text-xs text-gray-500"><?= $lng->get('Select Region') ?>:</div>
                        <!-- Search Input -->
                        <div class="px-4 py-2">
                            <input type="text" x-model="searchQuery" placeholder="<?= $lng->get('Search') ?>..." class="block w-full px-2 py-1 text-sm border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Country List -->
                        <template x-if="filteredCountries().length > 0">
                            <div>
                                <template x-for="country in filteredCountries()" :key="country.id">
                                    <a :href="'set/region/' + country.id" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" x-text="country.name"></a>
                                </template>
                            </div>
                        </template>

                        <!-- Message if no country matches the search -->
                        <template x-if="filteredCountries().length === 0">
                            <div class="px-4 py-2 text-sm text-gray-500">No results found</div>
                        </template>
                    </div>
                </div>



    
    <div x-data="{ open: false }" class="ml-4 relative">
        <div>
            <button @click="open = !open" class="flex items-center text-base font-medium text-gray-500 hover:text-gray-900" id="user-menu" aria-expanded="false" aria-haspopup="true">
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <svg class="ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
        <div x-show="open" @click.away="open = false" class="z-50 origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu">
            <?php if ($userId > 0): ?>
                <a target="_blank" href="https://new.ug.news/user/dashboard" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem"><?= $lng->get('Dashboard') ?></a>
                <a href="https://new.ug.news/user/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem"><?= $lng->get('Profile') ?></a>
                <a href="/user_panel/logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem"><?= $lng->get('Logout') ?></a>
            <?php else: ?>
                <a href="https://new.ug.news/login" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem"><?= $lng->get('Sign in') ?></a>
                <a href="https://new.ug.news/register" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem"><?= $lng->get('Sign up') ?></a>
            <?php endif; ?>
        </div>
    </div>
</div>
            <div class="-mr-2 -my-2 md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                    <span class="sr-only">Open menu</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div x-show="mobileMenuOpen" class="md:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <?php foreach ($data['menus'] as $menu): ?>
                <a href="<?=$menu['url']?>" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                    <?=$menu['title_' . $data['def_language']]?>
                </a>
            <?php endforeach; ?>
            <?php if ($userId > 0): ?>
                <a href="/user_panel/profile" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                    <?= $lng->get('Profile') ?>
                </a>
                <a href="/user_panel/logout" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                    <?= $lng->get('Logout') ?>
                </a>
            <?php else: ?>
                <a href="/login" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                    <?= $lng->get('Sign in') ?>
                </a>
                <a href="/register" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                    <?= $lng->get('Sign up') ?>
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div id="mainContentOverlay" class="fixed inset-0 bg-black opacity-50 z-40" style="display: none;"></div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const headerSearchInput = document.getElementById('header_search_input');
        const headerSearchDropDown = document.getElementById('headerSearchDropDown');
        const mobileSearchResults = document.getElementById('mobileSearchResults');
        const mobileSearchResultsContent = document.getElementById('mobileSearchResultsContent');
        const mainContentOverlay = document.getElementById('mainContentOverlay');

        function performSearch(inputVal) {
            if (inputVal.length >= 1) {
                // Perform the AJAX request
                fetch("/ajax/search/" + encodeURIComponent(inputVal), {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: "text="
                })
                .then(response => response.text())
                .then(data => {
                    if (headerSearchDropDown) {
                        headerSearchDropDown.innerHTML = data;
                        headerSearchDropDown.style.display = 'block';
                    }
                })
                .catch(error => console.error('Error:', error));
            } else {
                if (headerSearchDropDown) {
                    headerSearchDropDown.style.display = 'none';
                }
            }
        }

        if (headerSearchInput) {
            headerSearchInput.addEventListener('input', function() {
                performSearch(this.value);
            });

            // Close dropdown when clicking outside on desktop
            document.addEventListener('click', function(event) {
                if (window.innerWidth >= 768 && headerSearchDropDown && !headerSearchInput.contains(event.target) && !headerSearchDropDown.contains(event.target)) {
                    headerSearchDropDown.style.display = 'none';
                }
            });
        }


        // Handle window resize
        window.addEventListener('resize', function() {
            if (headerSearchDropDown) {
                headerSearchDropDown.style.display = 'none';
            }
        });
    });
</script>


<script>
    function countryDropdown() {
        return {
            open: false,
            countries: [],  // Initialize countries as an empty array
            searchQuery: '',  // Initialize search query
            loadCountries() {
                if (this.countries.length === 0) {
                    fetch('/ajax/countries')  // Replace with your actual endpoint
                        .then(response => response.json())
                        .then(data => {
                            this.countries = data;
                        })
                        .catch(error => console.error('Error fetching countries:', error));
                }
            },
            // Filter countries based on the search query
            filteredCountries() {
                if (this.searchQuery === '') {
                    return this.countries;
                }
                return this.countries.filter(country => country.name.toLowerCase().includes(this.searchQuery.toLowerCase()));
            }
        };
    }
</script>