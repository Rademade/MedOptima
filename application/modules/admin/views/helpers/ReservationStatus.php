<?php
use Application_Model_Restaurant_Reservation as Reservation;

class Zend_View_Helper_ReservationStatus
    extends
        Zend_View_Helper_Abstract {

    public static $nameForStatusList = null;
    public static $classForStatusList = array(
        Reservation::STATUS_WAIT => 'wait',
        Reservation::STATUS_ACCEPTED => 'confirmed',
        Reservation::STATUS_DECLINED_BY_CLIENT => 'cancelled',
        Reservation::STATUS_DECLINED_BY_RESTAURATEUR => 'cancelled',
        Reservation::STATUS_COMPLETED => 'done'
    );

	public function ReservationStatus() {
        return $this;
	}

    public function getName(Reservation $reservation) {
        $status = $reservation->getStatus();
        return isset(self::getNameForStatusList()[$status]) ?
            self::getNameForStatusList()[$status] : 'UNKNOWN';
    }

    public function getClass(Reservation $reservation) {
        $status = $reservation->getStatus();
        return isset(self::$classForStatusList[$status]) ?
            self::$classForStatusList[$status] : '';
    }

    public function getNameForStatusList() {
        if ( !self::$nameForStatusList ) {
            $view = Zend_Layout::getMvcInstance()->getView();
            self::$nameForStatusList = array(
                Reservation::STATUS_WAIT => $view->translate->_('Ожидаем ответ заведения...'),
                Reservation::STATUS_ACCEPTED => $view->translate->_('Подтвержден'),
                Reservation::STATUS_DECLINED_BY_CLIENT => $view->translate->_('Отменен клиентом'),
                Reservation::STATUS_DECLINED_BY_RESTAURATEUR => $view->translate->_('Отменен администрацией заведения'),
                Reservation::STATUS_COMPLETED => $view->translate->_('Успешно выполнен')
            );
        }
        return self::$nameForStatusList;
    }

}