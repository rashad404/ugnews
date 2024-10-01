<?php
use Helpers\Url;
?>
<main class="bg-gray-50 min-h-screen">
    <section>
        <div class="container mx-auto py-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h1 class="text-3xl font-bold text-gray-800 mb-4">
                    <?=$lng->get("Data Deletion Instructions")?>
                </h1>
                <p class="text-gray-600 mb-6">
                    If you have used our app and would like to request the deletion of your data, please follow the instructions below.
                </p>
                <h2 class="text-2xl font-semibold text-gray-700 mb-4">Steps to Request Data Deletion</h2>
                <ol class="list-decimal pl-5 space-y-2 text-gray-600">
                    <li>Send an email to our support team at <strong>support@websiteca.com</strong>.</li>
                    <li>In the subject line, please include: <strong>Data Deletion Request</strong>.</li>
                    <li>In the body of the email, include the following information:
                        <ul class="list-disc pl-5 space-y-1 mt-2">
                            <li>Your full name</li>
                            <li>Your registered email address</li>
                            <li>The reason for requesting data deletion</li>
                        </ul>
                    </li>
                    <li>Our support team will review your request and delete your data within 7 business days.</li>
                </ol>
                <p class="mt-6 text-gray-600">
                    If you have any questions or concerns, feel free to reach out to us at <strong>support@ug.news</strong>.
                </p>
            </div>
        </div>
    </section>
</main>
