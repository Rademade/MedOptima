<?php
class Application_Model_View_Button
    extends
        RM_View_Form_Field {

    const TPL = 'upload-button.phtml';

    public function __construct($desc, $name, $value) {
        parent::__construct($name, $desc, $value);
        RM_Head::getInstance()->getJS()->add('upload');
    }

    public function render($idLang) {
        $row = new RM_View_Form_Row();
        $row->setDesc($this->getDesc());
        $row->setHTML($this->getView()->partial(
            self::BASE_PATH . self::TPL,
            $this->addFieldData($idLang, array())
        ));
        return $this->renderRow( $row );
    }

}