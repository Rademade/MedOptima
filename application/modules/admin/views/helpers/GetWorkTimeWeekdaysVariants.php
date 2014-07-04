<?php
class Zend_View_Helper_GetWorkTimeWeekdaysVariants
    extends
        Zend_View_Helper_Abstract {

	public function GetWorkTimeWeekdaysVariants() {
        return MedOptima_DateTime::getWeekdayNames() + [
            MedOptima_DateTime_WeekdayPeriod::EVEN_WEEKDAYS => 'Четные дни',
            MedOptima_DateTime_WeekdayPeriod::ODD_WEEKDAYS => 'Нечетные дни'
        ];
	}

}