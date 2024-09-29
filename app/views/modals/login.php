<?php
use Helpers\Csrf;
use Helpers\Date;
?>

<div x-data="{ activeTab: 'login' }" class="bg-white rounded-lg shadow-xl max-w-md w-full mx-auto">
    <div class="flex border-b border-gray-200">
        <button @click="activeTab = 'login'" :class="{ 'border-b-2 border-blue-500': activeTab === 'login' }" class="flex-1 py-4 px-6 text-center text-gray-700 font-medium hover:text-blue-500 focus:outline-none">
            <?=$lng->get('Login')?>
        </button>
        <button @click="activeTab = 'register'" :class="{ 'border-b-2 border-blue-500': activeTab === 'register' }" class="flex-1 py-4 px-6 text-center text-gray-700 font-medium hover:text-blue-500 focus:outline-none">
            <?=$lng->get('Register')?>
        </button>
    </div>

    <div class="p-6">
        <!-- Login Form -->
        <form x-show="activeTab === 'login'" action="" method="POST" class="space-y-4">
            <input type="hidden" value="<?=Csrf::makeToken('_login')?>" name="csrf_token_login" />
            <input id="redirect_url_login" type="hidden" name="redirect_url"/>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700"><?=$lng->get('Login or E-mail')?></label>
                <input id="email" name="email" type="text" value="<?=$postData['email']?>" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700"><?=$lng->get('Password')?></label>
                <input id="password" name="password" type="password" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <div>
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <?=$lng->get('Login')?>
                </button>
            </div>
        </form>

        <!-- Register Form -->
        <form x-show="activeTab === 'register'" action="" method="POST" class="space-y-4">
            <input type="hidden" value="<?=Csrf::makeToken('_register')?>" name="csrf_token_register" />
            <input id="redirect_url_register" type="hidden" name="redirect_url"/>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700"><?=$lng->get('First name')?></label>
                    <input id="first_name" name="first_name" type="text" value="<?=$postData['first_name']?>" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700"><?=$lng->get('Last name')?></label>
                    <input id="last_name" name="last_name" type="text" value="<?=$postData['last_name']?>" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700"><?=$lng->get('E-mail')?></label>
                <input id="email" name="email" type="email" value="<?=$postData['email']?>" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700"><?=$lng->get('Gender')?></label>
                    <select id="gender" name="gender" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option <?=($postData['gender'] == 0)?'selected':''?> value="0"><?=$lng->get('Not selected')?></option>
                        <option <?=($postData['gender'] == 1)?'selected':''?> value="1"><?=$lng->get('Male')?></option>
                        <option <?=($postData['gender'] == 2)?'selected':''?> value="2"><?=$lng->get('Female')?></option>
                    </select>
                </div>
                <div>
                    <label for="country_code" class="block text-sm font-medium text-gray-700"><?=$lng->get('Country')?></label>
                    <select id="country_code" name="country_code" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <?php foreach ($countryList as $country_code=> $country_name): ?>
                            <option <?=($postData['country_code']==$country_code)?'selected':''?> value="<?=$country_code?>"><?=$country_name.' (+'.$country_code.')'?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700"><?=$lng->get('Phone number')?></label>
                <input id="phone" name="phone" type="text" value="<?=$postData['phone']?>" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label for="birth_month" class="block text-sm font-medium text-gray-700"><?=$lng->get('Birth month')?></label>
                    <select id="birth_month" name="birth_month" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <?php foreach (Date::getMonths3Code() as $month => $month_name): ?>
                            <option <?=($postData['birth_month'] == $month)?'selected':''?> value="<?=$month?>"><?=$lng->get($month_name)?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="birth_day" class="block text-sm font-medium text-gray-700"><?=$lng->get('Day')?></label>
                    <select id="birth_day" name="birth_day" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <?php foreach (Date::getDays() as $day): ?>
                            <option <?=($postData['birth_day'] == $day)?'selected':''?> value="<?=$day?>"><?=$day?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="birth_year" class="block text-sm font-medium text-gray-700"><?=$lng->get('Year')?></label>
                    <select id="birth_year" name="birth_year" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <?php $max_years = date("Y")-16; foreach (Date::getYears(1950,$max_years) as $year): ?>
                            <option <?=($postData['birth_year'] == $year)?'selected':''?> value="<?=$year?>"><?=$year?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700"><?=$lng->get('Password')?></label>
                <input id="password" name="password" type="password" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <div>
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <?=$lng->get('Register')?>
                </button>
            </div>
        </form>
    </div>

    <?php if (isset($data['postData']['google_client'])): ?>
    <div class="px-6 pb-6">
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-gray-500">
                    <?=$lng->get('Or continue with')?>
                </span>
            </div>
        </div>

        <div class="mt-6">
            <a href="<?=$data['postData']['google_client']->createAuthUrl()?>" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                <span class="sr-only">Sign in with Google</span>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z"/>
                </svg>
                <span class="ml-2"><?=$lng->get('Continue with Google')?></span>
            </a>
        </div>
    </div>
    <?php endif; ?>
</div>