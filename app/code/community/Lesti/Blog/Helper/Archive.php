<?php

class Lesti_Blog_Helper_Archive extends Mage_Core_Helper_Abstract
{
    protected $_archive = array();

    public function initArchive($year, $month, $controller)
    {
        // Init and load archive
        Mage::dispatchEvent('blog_controller_archive_init_before', array(
            'controller_action' => $controller
        ));

        if (! $year) {
            return false;
        }

        $archive = Mage::getModel('blog/archive');
        $archive->setYear($year);
        $archive->setMonth($month);

        // Register current data and dispatch final events
        Mage::register('blog_archive', $archive);

        try {
            Mage::dispatchEvent('blog_controller_archive_init', array('archive' => $archive));
            Mage::dispatchEvent('blog_controller_archive_init_after',
                array('archive' => $archive,
                    'controller_action' => $controller
                )
            );
        } catch (Mage_Core_Exception $e) {
            Mage::logException($e);
            return false;
        }
        
        return $archive;
    }

    public function getArchiveUrl($creationTime)
    {
        $url = Mage::app()->getStore()->getUrl(Mage::getStoreConfig(
            Lesti_Blog_Model_Post::XML_PATH_BLOG_GENERAL_ROUTER)) .
            Mage::helper('blog')->formatDate($creationTime,'yyyy') .'/' .
            Mage::helper('blog')->formatDate($creationTime,'MM');
        return $url;
    }

}
