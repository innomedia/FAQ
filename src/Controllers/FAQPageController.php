<?php

namespace FAQ\Pages;
use PageController;
use SilverStripe\Dev\Debug;
use FAQ\DataObjects\FAQCategory;
use SilverStripe\Core\Config\Config;

class FAQPageController extends PageController
{
    private static $allowed_actions = array(
		'category'
    );

	public function category() {
		if(Config::inst()->get("FAQModuleConfig")["CategoriesEnabled"]) {
			return $this->renderCategory($this->request->latestParam('ID'));
		}
		return $this->httpError(404);
    }
    private function renderCategory($URLSegment)
    {
        $cat = FAQCategory::get()->filter("URLSegment",$URLSegment)->first();
        $templateData = [];
        if($cat != null)
        {
            $templateData = array(
                'Questions'	=> $cat->FAQs(),
                'Title'			=> $this->Title . (($archive_) ? ' - ' . _t('Core.ARCHIVE', 'Archive') : null)
            );
        }
        
        return $this->customise($templateData)->renderWith(array('FAQ/Pages/FAQsCategory', 'Page'));
    } 
}