<?php

namespace FAQ\DataObjects;

use FAQ\Pages\FAQPage;
use FAQ\DataObjects\FAQCategory;
use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\TextField;
use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\CheckboxSetField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;


class FAQ extends DataObject
{
    private static string $table_name = "FAQ";
    
    private static array $db = [
        "Question"  =>  'Text',
        'Answer'    =>  'HTMLText',
        'Sort'      =>  'Int'
    ];
    
    private static array $summary_fields = [
        'Question',
        'Answer'
    ];
    
    private static array $field_labels = [
        "Question"  =>  'Frage',
        "Answer"    =>  'Antwort'
    ];
    
    private static array $many_many = [
        'FAQCategories' =>  FAQCategory::class
    ];
    
    private static array $has_one = [
        'FAQPage' => FAQPage::class
    ];
    
    public function Title()
    {
        return $this->Question;
    }
    
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->addFieldToTab('Root.Main', TextField::create('Question','Frage'));
        $fields->addFieldToTab('Root.Main', HTMLEditorField::create('Answer','Antwort'));

        if (Config::inst()->get("FAQModuleConfig")["CategoriesEnabled"]) {
            $fields->addFieldToTab(
                'Root.Main',
                CheckboxSetField::create(
                    'FAQCategories',
                    'Kategorien',
                    $this->getCategories(),
                    $this->FAQCategories()
                )
            );
        }

        return $fields;
    }

    public function getCategories(){
        $categories = FAQCategory::get()->filter('FAQPageID', $this->FAQPageID);
        $this->extend('updateCategories', $categories);
        return $categories;
    }
}
