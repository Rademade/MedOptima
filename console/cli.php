<?php
require_once 'define.php';
try {
    $opts = new Zend_Console_Getopt(
      	array(
            'help' => 'Displays usage information',
            'menus' => 'Create restaurant menus',
            'stats' => 'Update all restaurant statistics',
            'rename_menu' => 'Rename all restaurant menu files',
            'resave_vacancies' => 'Resave vacancies',
            'sitemap' => 'Generate sitemap'
        )
    );
    $opts->parse();
} catch (Zend_Console_Getopt_Exception $e) {
    exit($e->getMessage() ."\n\n". $e->getUsageMessage());
}

if (isset($opts->help)) :
    echo $opts->getUsageMessage();
    exit;
endif;

if (isset($opts->menus)) :
    echo 'creating...';
    foreach (Application_Model_Restaurant::getList() as $restaurant) :
        $restaurantMenu = Application_Model_Restaurant_Menu::create($restaurant);
        $restaurantMenu->save();
    endforeach;
    die('done');
endif;

if (isset($opts->stats)) :
    $restaurantStatisticsUpdate = new Restoran_Service_Restaurant_StatisticsUpdater();
    $restaurantStatisticsUpdate->updateStatistics();
    die('done');
endif;

if (isset($opts->rename_menu)) :
    foreach (Application_Model_Restaurant_Menu::getList() as $restaurantMenu) :
        /* @var Application_Model_Restaurant_Menu $restaurantMenu */
        $restaurantMenu->generateFileName();
        $restaurantMenu->save();
        $restaurantMenuFile = new Application_Model_Restaurant_Menu_File($restaurantMenu);
        $restaurantMenuFile->renameFile();
    endforeach;
    die('done');
endif;

if (isset($opts->resave_vacancies)) :
    foreach (Application_Model_Page_Vacancy::getList() as $vacancy) :
        /* @var Application_Model_Page_Vacancy $vacancy */
        $vacancy->save();
    endforeach;
    die('done');
endif;

if (isset($opts->sitemap)) :
    $sitemap = new Restoran_Sitemap();
    $sitemap->initXml();
    $sitemap->saveXml();
    die('done');
endif;

echo "\n";
exit;