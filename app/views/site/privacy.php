<?php
use Helpers\Url;
?>
<main class="bg-gray-50 min-h-screen">
    <section>
        <div class="container mx-auto py-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h1 class="text-3xl font-bold text-gray-800 mb-6"><?=$lng->get("Privacy Policy")?></h1>
                <p class="text-gray-600 mb-4">
                    This Privacy Policy governs the manner in which <?=$_PARTNER['website']?> collects, uses, maintains, and discloses information collected from users (each, a "User") of the https://<?=$_PARTNER['website']?> website ("Site") and all <?=$_PARTNER['website']?> mobile applications ("Applications"). This privacy policy applies to the Site and all products and services offered by <?=$_PARTNER['website']?>.
                </p>
                
                <h3 class="text-2xl font-semibold text-gray-700 mt-6 mb-4">Personal Identification Information</h3>
                <p class="text-gray-600 mb-6">
                    We collect User information when you register on <?=$_PARTNER['website']?> website or app.
                </p>

                <h3 class="text-2xl font-semibold text-gray-700 mt-6 mb-4">Web Browser Cookies</h3>
                <p class="text-gray-600 mb-6">
                    Our Site and Applications may use "cookies" to enhance User experience. User's web browser places cookies on their hard drive for record-keeping purposes and sometimes to track information about them. User may choose to set their web browser to refuse cookies or alert them when cookies are being sent. If they do so, note that some parts of the Site or the Application may not function properly.
                </p>

                <h3 class="text-2xl font-semibold text-gray-700 mt-6 mb-4">How We Collect Information</h3>
                <ul class="list-disc pl-6 text-gray-600 space-y-2">
                    <li>
                        We may use various technologies to collect and store technical information about website activity, such as cookies, pixel tags, log files, web beacons, or similar technologies. This information is anonymous and de-identified. Below are some of the technologies we may use:
                        <ul class="list-disc pl-6 mt-2 space-y-2">
                            <li><strong>Pixel Tags:</strong> A type of technology placed on a website with cookies to enable tracking activity on websites.</li>
                            <li><strong>Web Beacon:</strong> A technique used to track who is reading a web page or email, when, and from which computer.</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Mobile Device Opt-Out:</strong> You may opt-out of receiving targeted ads on mobile devices using platform-level controls available through your device settings. Consult your device manufacturer’s instructions for more details.
                    </li>
                    <li>
                        <strong>iOS Devices:</strong> On Apple devices, update to iOS 6.0 or higher, and set "Limit Ad Tracking" to "ON" in Settings -> Privacy -> Advertising. You can also reset the Advertising Identifier in these settings.
                    </li>
                    <li>
                        <strong>Android Devices:</strong> On Android, open Google Settings -> Ads -> "Opt out of interest-based ads" or reset your device’s advertising ID through Google Settings.
                    </li>
                </ul>

                <h3 class="text-2xl font-semibold text-gray-700 mt-6 mb-4">Security</h3>
                <p class="text-gray-600 mb-6">
                    The security of your Personal Information is important to us, but no method of transmission over the Internet or electronic storage is 100% secure. While we strive to protect your Personal Information, we cannot guarantee its absolute security.
                </p>

                <h3 class="text-2xl font-semibold text-gray-700 mt-6 mb-4">Change of Ownership</h3>
                <p class="text-gray-600 mb-6">
                    In the event of bankruptcy, merger, acquisition, or sale of assets, your personal data may be transferred as part of the transaction. This Privacy Policy will apply to your data transferred to the new entity.
                </p>

                <h3 class="text-2xl font-semibold text-gray-700 mt-6 mb-4">Changes to This Privacy Policy</h3>
                <p class="text-gray-600 mb-4">
                    We may revise this Privacy Policy from time to time. The most current version will govern our processing of your personal data and will be available at <a href="https://<?=$_PARTNER['website']?>/privacy-policy" class="text-blue-600 underline">https://<?=$_PARTNER['website']?>/privacy-policy</a>. By continuing to use our services, you agree to be bound by the revised Privacy Policy.
                </p>
                <p class="text-gray-600 mt-6"><strong>Effective:</strong> Jan 1, 2020</p>
                <p class="text-gray-600 mb-6"><strong>Last updated:</strong> Feb 12, 2020</p>
            </div>
        </div>
    </section>
</main>
