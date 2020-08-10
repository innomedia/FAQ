<?php

namespace FAQ\DataObjects;

use FAQ\Pages\FAQPage;
use FAQ\DataObjects\FAQ;
use SilverStripe\ORM\DataObject;

class FAQCategory extends DataObject
{

    private static $tablename = "FAQCategory";
    private static $db = [
        'Title' =>  'Text',
        'Sort'  =>  'Int',
        'URLSegment'    =>  'Text'
    ];
    private static $belongs_many_many = [
        "FAQs"  =>  FAQ::class
    ];
    private static $has_one = [
        'FAQPage' => FAQPage::class
    ];
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName([
           'Sort',
           'FAQPageID'
        ]);
        return $fields;
    }
    public function Link()
    {
        return str_replace(["?stage=Stage"],"",$this->FAQPage()->Link())."category/".$this->URLSegment;
    }
    private function PrepareURLSegment()
    {
        return str_replace(["/","&","?",".",","],"",str_replace([" "],"-",$this->Title));
    }
    public function onAfterWrite()
    {
        if($this->URLSegment != $this->PrepareURLSegment())
        {
            $this->URLSegment = $this->PrepareURLSegment();
            $this->write();
        }
    }
}
