<?php
namespace Modules\partner\Controllers;

use Modules\partner\Models\SettingsModel;
use Helpers\Csrf;
use Helpers\Session;
use Core\View;

class Settings extends MyController
{
    protected $user_id;

    public function __construct()
    {
        parent::__construct();
        $params = [
            'name' => 'settings',
            'title' => 'Settings', // We'll translate this in the view
        ];
        $this->initializePartnerController(SettingsModel::class, $params);
        $this->user_id = Session::get('user_session_id');
    }

    public function defaults()
    {
        if (isset($_POST['csrf_token']) && Csrf::isTokenValid()) {
            $modelArray = $this->partnerModel::update();
            if (empty($modelArray['errors'])) {
                Session::setFlash('success', $this->partnerLng->get('Data has been saved successfully'));
            } else {
                Session::setFlash('error', $modelArray['errors']);
            }
        }
        
        $data['item'] = $this->partnerModel::getItem();
        $data['user_id'] = $this->user_id;
        $data['lng'] = $this->partnerLng;
        $data['params'] = $this->partnerParams;
        
        View::renderPartner($this->partnerParams['name'] . '/defaults', $data);
    }

    // Add more settings-specific methods as needed
    
    public function getGeneralSettings()
    {
        return $this->partnerModel::getGeneralSettings();
    }

    public function updateGeneralSettings()
    {
        if (isset($_POST['csrf_token']) && Csrf::isTokenValid()) {
            $result = $this->partnerModel::updateGeneralSettings($_POST);
            if ($result) {
                Session::setFlash('success', $this->partnerLng->get('General settings updated successfully'));
            } else {
                Session::setFlash('error', $this->partnerLng->get('Failed to update general settings'));
            }
        }
        $this->defaults(); // Redirect back to the main settings page
    }

    public function securitySettings()
    {
        $data['security_settings'] = $this->partnerModel::getSecuritySettings();
        $data['lng'] = $this->partnerLng;
        $data['params'] = $this->partnerParams;
        
        View::renderPartner($this->partnerParams['name'] . '/security_settings', $data);
    }

    public function updateSecuritySettings()
    {
        if (isset($_POST['csrf_token']) && Csrf::isTokenValid()) {
            $result = $this->partnerModel::updateSecuritySettings($_POST);
            if ($result) {
                Session::setFlash('success', $this->partnerLng->get('Security settings updated successfully'));
            } else {
                Session::setFlash('error', $this->partnerLng->get('Failed to update security settings'));
            }
        }
        $this->securitySettings(); // Redirect back to the security settings page
    }
}