<?php
class MedOptima_Admin_View_Form_Field_GoogleLink
    extends
        RM_View_Form_Field {

    const TPL = 'google-link.phtml';

    /**
     * @var Application_Model_Medical_Doctor
     */
    private $_doctor;

    /**
     * @var Application_Model_Api_Google_AccessToken
     */
    private $_accessToken;

    /**
     * @var MedOptima_Service_Google_Config
     */
    private $_client;

    public function __construct(Application_Model_Medical_Doctor $doctor) {
        parent::__construct('', '', '');
        $this->_doctor = $doctor;
        $this->_accessToken = Application_Model_Api_Google_AccessToken::getByDoctor($this->_doctor);
        if (!$this->_accessToken) {
            $this->_client = MedOptima_Service_Google_Config::getCalendarClient($this->_doctor->getId());
        }
    }

    public function render($idLang) {
        $row = new RM_View_Form_Row();
        $row->setDesc($this->getDesc());
        $row->setHTML($this->getView()->partial(
            static::BASE_PATH . static::TPL,
            $this->addFieldData($idLang, array(
                'isLinked' => $this->_accessToken instanceof Application_Model_Api_Google_AccessToken,
                'linkUrl' => $this->_client ? $this->_client->createAuthUrl() : '',
                'unlinkUrl' => $this->getView()->url(['idDoctor' => $this->_doctor->getId()], 'admin-unlink-google-account')
            ))
        ));
        return $this->renderRow($row);
    }

}