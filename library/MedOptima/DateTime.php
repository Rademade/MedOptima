<?php
class MedOptima_DateTime extends DateTime {

    const DATE_FORMAT_GOST = 'd.m.Y';
    const DATE_FORMAT_MYSQL = 'Y-m-d';

    const DATETIME_FORMAT_GOST_SECONDS = 'd.m.Y H:i:s';
    const DATETIME_FORMAT_GOST_NO_SECONDS = 'd.m.Y H:i';
    const DATETIME_FORMAT_MYSQL = 'Y-m-d H:i:s';

    const TIME_FORMAT_GOST_SECONDS = 'H:i:s';
    const TIME_FORMAT_GOST_NO_SECONDS = 'H:i';

    const TIME_FORMAT_MYSQL_SECONDS = 'H:i:s';
    const TIME_FORMAT_MYSQL_NO_SECONDS = 'H:i';

    const DATETIME_FORMAT_GOOGLE_API = self::RFC3339;

    const WEEK_DAY_MONDAY = 1;
    const WEEK_DAY_TUESDAY = 2;
    const WEEK_DAY_WEDNESDAY = 3;
    const WEEK_DAY_THURSDAY = 4;
    const WEEK_DAY_FRIDAY = 5;
    const WEEK_DAY_SATURDAY = 6;
    const WEEK_DAY_SUNDAY = 7;

    const DEFAULT_TIME_ZONE = 'Europe/Kiev';

    private static $_monthNames = array(
        1 => 'Январь',
        2 => 'Февраль',
        3 => 'Март',
        4 => 'Апрель',
        5 => 'Май',
        6 => 'Июнь',
        7 => 'Июль',
        8 => 'Август',
        9 => 'Сентябрь',
        10 => 'Октябрь',
        11 => 'Ноябрь',
        12 => 'Декабрь'
    );

    private static $_prettyMonthNames = array(
        1 => 'января',
        2 => 'февраля',
        3 => 'марта',
        4 => 'апреля',
        5 => 'мая',
        6 => 'июня',
        7 => 'июля',
        8 => 'августа',
        9 => 'сентября',
        10 => 'октября',
        11 => 'ноября',
        12 => 'декабря'
    );

    private static $_weekDayNames = array(
        self::WEEK_DAY_MONDAY => 'Понедельник',
        self::WEEK_DAY_TUESDAY => 'Вторник',
        self::WEEK_DAY_WEDNESDAY => 'Среда',
        self::WEEK_DAY_THURSDAY => 'Четверг',
        self::WEEK_DAY_FRIDAY => 'Пятница',
        self::WEEK_DAY_SATURDAY => 'Суббота',
        self::WEEK_DAY_SUNDAY => 'Воскресенье'
    );
    
    public static function create($datetime = 'now') {
        return self::constructDateTime($datetime);
    }

    public static function createFromTimestamp($time) {
        $self = new self();
        $self->setTimestamp($time);
        $self->setTimezone(self::_getDefaultTimeZone());
        return $self;
    }

    public static function toGostDate($date) {
        return self::constructDateTime($date)->format(self::DATE_FORMAT_GOST);
    }

    public static function toGostPrettyDate($date) {
        $datetime = self::constructDateTime($date);
        return join(' ', [
            $datetime->format('j'),
            self::getPrettyMonthNames()[$datetime->format('n')],
            $datetime->format('Y')
        ]);
    }

    public static function toMysqlDate($date) {
        return self::constructDateTime($date)->format(self::DATE_FORMAT_MYSQL);
    }

    public static function toGostDatetime($datetime, $seconds = false) {
        $format = $seconds ? self::DATETIME_FORMAT_GOST_SECONDS : self::DATETIME_FORMAT_GOST_NO_SECONDS;
        return self::constructDateTime($datetime)->format($format);
    }

    public static function toMysqlDatetime($datetime) {
        return self::constructDateTime($datetime)->format(self::DATETIME_FORMAT_MYSQL);
    }

    public static function toGostTime($time, $seconds = false) {
        $format = $seconds ? self::TIME_FORMAT_GOST_SECONDS : self::TIME_FORMAT_GOST_NO_SECONDS;
        return self::constructDateTime($time)->format($format);
    }

    public static function toMySqlTime($time, $seconds = false) {
        $format = $seconds ? self::TIME_FORMAT_MYSQL_SECONDS : self::TIME_FORMAT_MYSQL_NO_SECONDS;
        return self::constructDateTime($time)->format($format);
    }

    public static function timeToSeconds($time, $delimiter = ':') {
        $tokens = explode($delimiter, $time);
        $seconds = 0;
        $multiplier = 3600;
        for ($idx = 0; $idx < count($tokens); ++$idx) {
            $seconds += (int)$tokens[$idx] * $multiplier;
            $multiplier /= 60;
        }
        return $seconds;
    }

    public static function secondsToTime($seconds, $delimiter = ':', $addSeconds = false) {
        if ($seconds >= 24 * 3600 || $seconds < 0) {
            return false;
        }
        $date = new DateTime(); //RM_TODO static attribute
        $date->setTime(0, 0, $seconds);
        return $date->format('H' . $delimiter . 'i' . ($addSeconds ? $delimiter . 's' : ''));
    }

    public static function getCurrentHoursOffset($offset) {
        if ($offset < 0) {
            return false;
        } else {
            return (new DateTime())->add(new DateInterval('PT' . $offset . 'H'))->format('H') . ':00';
        }
    }

    public static function getMonthNames() {
        return self::$_monthNames;
    }

    public static function getPrettyMonthNames() {
        return self::$_prettyMonthNames;
    }
    
    public static function getWeekdayNames() {
        return self::$_weekDayNames;
    }

    public static function currentTimestamp() {
        return self::create()->getTimestamp();
    }

    public function getMonth() {
        return $this->format('m');
    }

    public function getMonthName() {
        $num = (int)$this->getMonth();
        return self::$_monthNames[$num] ? self::$_monthNames[$num] : '';
    }

    public function getYear() {
        return $this->format('Y');
    }

    public function getMysqlDate() {
        return $this->format(self::DATE_FORMAT_MYSQL);
    }

    public function getGostDate() {
        return $this->format(self::DATE_FORMAT_GOST);
    }

    public function getGostTime() {
        return $this->format(self::TIME_FORMAT_GOST_NO_SECONDS);
    }

    public function getGostDatetime() {
        return $this->format(self::DATETIME_FORMAT_GOST_NO_SECONDS);
    }

    public function getGoogleApiDatetime() {
        return $this->format(self::DATETIME_FORMAT_GOOGLE_API);
    }

    public function getWeekday() {
        return date('N', $this->getTimestamp());
    }

    /**
     * @return MedOptima_DateTime
     */
    public function addMonth() {
        return $this->add(new DateInterval('P1M'));
    }

    /**
     * @param $minutes
     * @return MedOptima_DateTime
     */
    public function addMinutes($minutes) {
        return $this->add(new DateInterval('PT' . $minutes . 'M'));
    }

    public function addSeconds($seconds) {
        return $this->add(new DateInterval('PT' . $seconds . 'S'));
    }

    public function getTimeAsSeconds() {
        $clone = clone $this;
        $timestamp = $clone->getTimestamp();
        $clone->setTime(0, 0);
        return $timestamp - $clone->getTimestamp();
    }

    private static function _getDefaultTimeZone() {
        return new DateTimeZone(self::DEFAULT_TIME_ZONE); //RM_TODO static
    }

    private static function _checkTime($hours, $minutes, $seconds) {
        $between = function ($value, $min, $max) {
            return $min <= $value && $value <= $max;
        };
        if (!$between($hours, 0, 23)) {
            throw new Exception('Wrong hours');
        }
        if (!$between($minutes, 0, 59)) {
            throw new Exception('Wrong minutes');
        }
        if (!$between($seconds, 0, 59)) {
            throw new Exception('Wrong seconds');
        }
    }

    private static function constructDateTime($date) {
        return new self($date, self::_getDefaultTimeZone());
    }
    
}