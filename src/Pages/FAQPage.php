<?php

namespace FAQ\Pages;

use Page;
use FAQ\DataObjects\FAQ;
use FAQ\DataObjects\FAQCategory;
use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\GridField\GridField;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;

class FAQPage extends Page
{
    private static $tablename = "FAQPage";

    private static $has_many = [
        'FAQCategories' => FAQCategory::class,
        'FAQs' => FAQ::class,
    ];
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        if (Config::inst()->get("FAQModuleConfig")["CategoriesEnabled"]) {
            $fields->addFieldToTab('Root.Kategorien',
                GridField::create('FAQCategories','Kategorien',FAQCategory::get()->sort("Sort ASC"),
                    GridFieldConfig_RecordEditor::create(20)->addComponent(new GridFieldOrderableRows("Sort"))
                )
            );
        }
        $fields->addFieldToTab('Root.Fragen',
            GridField::create('FAQs','Fragen',$this->FAQs()->sort("Sort ASC"),
                GridFieldConfig_RecordEditor::create(90)->addComponent(new GridFieldOrderableRows('Sort'))
            )
        );
        $this->extend("updateFAQPageCMSFields",$fields);
        return $fields;
    }
    public function SortedFAQCategories()
    {
        return $this->FAQCategories()->sort("Sort ASC");
    }
}
