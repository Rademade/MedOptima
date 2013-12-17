<?php
class Zend_View_Helper_CutText {
	
	public function CutText($text, $to = 10) {
        $text = strip_tags($text);
		$length = strlen($text);
		return ($length > $to) ? //длина строки подходит
			(in_array(mb_substr($text, $to, 1, 'UTF-8'), array(' ', ',', '.', '', '<br>'))) ? //ищем пробел или , для обрезки строки
				mb_substr($text, 0, $to, 'UTF-8') . '...'
			:
				$this->CutText($text, $to + 1)
		: $text;	
	}
	
}