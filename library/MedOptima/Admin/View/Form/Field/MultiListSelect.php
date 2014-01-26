<?php
class MedOptima_Admin_View_Form_Field_MultiListSelect
    extends
        RM_View_Form_Field {

    private $_selectData = [];
    private $_selectCount;

    const TPL = 'multi-list-select.phtml';

    public function __construct($desc, $name, $selectCount, $selectData) {
        parent::__construct($name, $desc, '');
        $this->_selectData = $selectData;
        $this->_selectCount = (int)$selectCount ?: 2;
        Head::getInstance()->getJS()->add('multi-list');
    }

    public function render($idLang) {
        $row = new RM_View_Form_Row();
        $row->setDesc( $this->getDesc() );
        $row->setHTML( $this->getView()->partial(
            self::BASE_PATH . self::TPL,
            $this->addFieldData($idLang, array(
                'selectData' => $this->_selectData,
                'selectCount' => $this->_selectCount
            ) )
        ));
        return $this->renderRow($row);
    }

}