<?php
use Helpers\Csrf;
use Helpers\Date;
use Helpers\Url;
?>

<main class="bg-gray-100 py-10">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6"><?=$lng->get('Account info')?></h1>
        
        <form action="" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded-lg overflow-hidden">
            <input type="hidden" value="<?=Csrf::makeToken()?>" name="csrf_token" />
            
            <div class="p-6 space-y-6">
                <!-- Profile Picture -->
                <div class="flex flex-col items-center">
                    <div class="relative w-32 h-32 mb-4">
                        <img id="profilePicturePreview" class="w-full h-full object-cover rounded-full border-4 border-gray-200" 
                             src="<?= file_exists(Url::uploadPath() . 'users/' . $data['userId'] . '.jpg') 
                                 ? Url::uploadPath() . 'users/' . $data['userId'] . '.jpg?ref=' . rand(1111111, 9999999)
                                 : URL::templatePath() . '/img/profile_photo-02.png' ?>" 
                             alt="Profile Picture">
                        <label for="profile-picture" class="absolute bottom-0 right-0 bg-white rounded-full p-2 shadow-md cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </label>
                        <input type="file" name="file" id="profile-picture" class="hidden" onchange="readURL(this);">
                    </div>
                    <p class="text-sm text-gray-600"><?=$lng->get('Choose Photo')?></p>
                </div>

                <!-- Name Fields -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700"><?=$lng->get('First name')?></label>
                        <input id="first_name" name="first_name" type="text" value="<?=$postData['first_name']?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700"><?=$lng->get('Last name')?></label>
                        <input id="last_name" name="last_name" type="text" value="<?=$postData['last_name']?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <!-- Birthday Fields -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                    <div>
                        <label for="birth_month" class="block text-sm font-medium text-gray-700"><?=$lng->get('Birth month')?></label>
                        <select id="birth_month" name="birth_month" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <?php foreach (Date::getMonths3Code() as $month => $month_name): ?>
                                <option value="<?= $month ?>" <?= $postData['birth_month'] == $month ? 'selected' : '' ?>><?= $lng->get($month_name) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="birth_day" class="block text-sm font-medium text-gray-700"><?=$lng->get('Day')?></label>
                        <select id="birth_day" name="birth_day" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <?php foreach (Date::getDays() as $day): ?>
                                <option value="<?= $day ?>" <?= $postData['birth_day'] == $day ? 'selected' : '' ?>><?= $day ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="birth_year" class="block text-sm font-medium text-gray-700"><?=$lng->get('Year')?></label>
                        <select id="birth_year" name="birth_year" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <?php $max_years = date("Y")-16; foreach (Date::getYears(1950,$max_years) as $year): ?>
                                <option value="<?= $year ?>" <?= $postData['birth_year'] == $year ? 'selected' : '' ?>><?= $year ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Gender Field -->
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700"><?=$lng->get('Gender')?></label>
                    <select id="gender" name="gender" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="0" <?=$postData['gender'] == 0 ? 'selected' : ''?>><?=$lng->get('Not selected')?></option>
                        <option value="1" <?=$postData['gender'] == 1 ? 'selected' : ''?>><?=$lng->get('Male')?></option>
                        <option value="2" <?=$postData['gender'] == 2 ? 'selected' : ''?>><?=$lng->get('Female')?></option>
                    </select>
                </div>

                <!-- Country and Phone Fields -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="country_code" class="block text-sm font-medium text-gray-700"><?=$lng->get('Country')?></label>
                        <select id="country_code" name="country_code" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <?php foreach ($countryList as $country_code => $country_name): ?>
                                <option value="<?=$country_code?>" <?=$postData['country_code']==$country_code ? 'selected' : ''?>><?=$country_name.' (+'.$country_code.')'?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700"><?=$lng->get('Phone number')?></label>
                        <input id="phone" name="phone" type="text" value="<?=$postData['phone']?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <!-- Email Field (Disabled) -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700"><?=$lng->get('E-Mail')?></label>
                    <input id="email" type="email" value="<?=$postData['email']?>" disabled class="mt-1 block w-full bg-gray-100 border-gray-300 rounded-md shadow-sm text-gray-500">
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 text-right">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <?=$lng->get('Update')?>
                </button>
            </div>
        </form>
    </div>
</main>

<script>
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#profilePicturePreview').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}
</script>