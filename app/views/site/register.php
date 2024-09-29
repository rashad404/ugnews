<?php
use Helpers\Csrf;
use Helpers\Date;
?>

<main class="bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white shadow rounded-lg p-8">
            <h2 class="text-3xl font-extrabold text-gray-900 mb-8 text-center">
                <?=$lng->get('Registration')?>
            </h2>
            
            <form action="" method="POST" class="space-y-6">
                <input type="hidden" value="<?=Csrf::makeToken()?>" name="csrf_token" />
                
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700"><?=$lng->get('First name')?></label>
                        <input type="text" name="first_name" id="first_name" value="<?=$postData['first_name']?>" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700"><?=$lng->get('Last name')?></label>
                        <input type="text" name="last_name" id="last_name" value="<?=$postData['last_name']?>" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700"><?=$lng->get('E-mail')?></label>
                    <input type="email" name="email" id="email" value="<?=$postData['email']?>" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700"><?=$lng->get('Gender')?></label>
                    <select name="gender" id="gender" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option <?=($postData['gender'] == 0)?'selected':''?> value="0"><?=$lng->get('Not selected')?></option>
                        <option <?=($postData['gender'] == 1)?'selected':''?> value="1"><?=$lng->get('Male')?></option>
                        <option <?=($postData['gender'] == 2)?'selected':''?> value="2"><?=$lng->get('Female')?></option>
                    </select>
                </div>

                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                    <div>
                        <label for="country_code" class="block text-sm font-medium text-gray-700"><?=$lng->get('Country')?></label>
                        <select name="country_code" id="country_code" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <?php foreach ($countryList as $country_code => $country_name): ?>
                                <option <?=$postData['country_code']==$country_code ? 'selected' : ''?> value="<?=$country_code?>"><?=$country_name.' (+'.$country_code.')'?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700"><?=$lng->get('Phone number')?></label>
                        <input type="text" name="phone" id="phone" value="<?=$postData['phone']?>" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-3">
                    <div>
                        <label for="birth_month" class="block text-sm font-medium text-gray-700"><?=$lng->get('Birth month')?></label>
                        <select name="birth_month" id="birth_month" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <?php foreach (Date::getMonths3Code() as $month => $month_name): ?>
                                <option <?=$postData['birth_month'] == $month ? 'selected' : ''?> value="<?=$month?>"><?=$lng->get($month_name)?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="birth_day" class="block text-sm font-medium text-gray-700"><?=$lng->get('Day')?></label>
                        <select name="birth_day" id="birth_day" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <?php foreach (Date::getDays() as $day): ?>
                                <option <?=$postData['birth_day'] == $day ? 'selected' : ''?> value="<?=$day?>"><?=$day?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="birth_year" class="block text-sm font-medium text-gray-700"><?=$lng->get('Year')?></label>
                        <select name="birth_year" id="birth_year" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <?php $max_years = date("Y")-16; foreach (Date::getYears(1950,$max_years) as $year): ?>
                                <option <?=$postData['birth_year'] == $year ? 'selected' : ''?> value="<?=$year?>"><?=$year?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700"><?=$lng->get('Password')?></label>
                    <input type="password" name="password" id="password" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <?=$lng->get('Register')?>
                    </button>
                </div>
            </form>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">
                            <?=$lng->get('If you have account, then')?>
                        </span>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="login<?=$postData['return']?>" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-indigo-600 bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <?=$lng->get('Login')?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>