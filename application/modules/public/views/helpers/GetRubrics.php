<?php
class Zend_View_Helper_GetRubrics {

    public function GetRubrics() {
        $view = Zend_Layout::getMvcInstance()->getView();
        /* @var Application_Model_Page_Vacancy_Rubric[] $pageRubrics */
        $pageRubrics = (new Application_Model_Page_Vacancy_Rubric_Search_Repository())->getShowedRubrics();
        $rubrics = array();
        foreach ($pageRubrics as $pageRubric) {
            $rubrics[$pageRubric->getId()] = $pageRubric->getContent()->getName();
        }
        array_unshift($rubrics, $view->translate->_('Все вакансии'));
        return $rubrics;
    }

}