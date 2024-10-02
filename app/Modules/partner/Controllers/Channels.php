<?php
namespace Modules\partner\Controllers;

use Modules\partner\Models\ChannelsModel;
use Core\View;

class Channels extends CrudController
{
    public function __construct()
    {
        parent::__construct();
        $params = [
            'name' => 'channels',
            'searchFields' => ['id', 'title', 'text'],
            'title' => 'Your Channels', // We'll translate this in the view
            'position' => true,
            'status' => true,
            'actions' => true,
            'imageSizeX' => '730',
            'imageSizeY' => '450',
            'thumbSizeX' => '300',
            'thumbSizeY' => '300',
        ];
        $this->initializePartnerController(ChannelsModel::class, $params);
    }

    public function index()
    {
        $data = $this->getListData($this->partnerModel);
        $data['lng'] = $this->partnerLng;
        $data['params'] = $this->partnerParams;
        View::renderPartner($this->partnerParams['name'] . '/index', $data);
    }

    public function view($id)
    {
        $data['item'] = $this->partnerModel::getItem($id);
        $data['lng'] = $this->partnerLng;
        $data['params'] = $this->partnerParams;
        View::renderPartner($this->partnerParams['name'] . '/view', $data);
    }

    // If you need any channel-specific methods, add them here
    // For example:
    public function getActiveChannels()
    {
        return $this->partnerModel::getActiveChannels();
    }

    // Override add method if you need channel-specific logic
    public function add()
    {
        // Add any channel-specific logic here before calling parent method
        parent::add();
    }

    // Override update method if you need channel-specific logic
    public function update($id)
    {
        // Add any channel-specific logic here before calling parent method
        parent::update($id);
    }

    // You can add more channel-specific methods as needed
}