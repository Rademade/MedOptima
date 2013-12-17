<?php
class Zend_View_Helper_MakeTable {

    /**
     * @param $data
     * format:
     * array(
     *  row1 => array(td1, td2, ...),
     *  row2 => array(td1, td2, ...)
     * )
     * @return string
     */
    public function MakeTable($data) {
        $html = '<table class="table-condition-requirements">';
        foreach ($data as $rowData) {
            $html .= '<tr>';
            $i = 0;
            foreach ($rowData as $value) {
                ++$i;
                $html .= '<td class="' . ($i==1 ? 'first-td' : '') . '">' . $value . '</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</table>';
        return $html;
    }

}