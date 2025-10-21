<?php

namespace FAQ\DataObjects;

use FAQ\Pages\FAQPage;
use SilverStripe\Control\Controller;
use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\DropdownField;

class FAQCategory extends DataObject
{

    private static string $table_name = "FAQCategory";
    
    private static array $db = [
        'Title' =>  'Text',
        'Sort'  =>  'Int',
        'URLSegment'    =>  'Text'
    ];
    
    private static array $belongs_many_many = [
        "FAQs"  =>  FAQ::class
    ];
    
    private static array $has_one = [
        'FAQPage' => FAQPage::class
    ];
    
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName([
           'Sort',
           'FAQPageID'
        ]);
        $fields->addFieldToTab('Root.Main', DropdownField::create('FAQPageID', 'FAQPage', FAQPage::get()));
        return $fields;
    }

    protected function onAfterWrite()
    {
        if($this->URLSegment != $this->PrepareURLSegment())
        {
            $this->URLSegment = $this->PrepareURLSegment();
            $this->write();
        }
    }

    private function PrepareURLSegment(): string
    {
        $link = $this->cleanLink(strtolower($this->Title));
        $count = 0;

        // Stelle sicher, dass der Link eindeutig ist
        while(FAQCategory::get()->filter('URLSegment', $link . ($count > 0 ? '-' . $count : ''))->exists()) {
            ++$count;
        }

        return $link . ($count > 0 ? '-' . $count : '');
    }

    private function cleanLink($string): ?string
    {
        // Entferne führende und nachfolgende Leerzeichen
        $string = trim($string);

        $replacements = [
            " " => "-", "ä" => "ae", "ü" => "ue", "ö" => "oe",
            "Ä" => "Ae", "Ü" => "Ue", "Ö" => "Oe", "ß" => "ss",
            "´" => "", "," => "", ":" => "", ";" => "",
            "/" => "", "(" => "", ")" => ""
        ];

        // Entferne typische Geschlechtskennzeichnungen wie (m/w/d)
        $string = preg_replace('/\b(m\/w\/d|m\/w|w\/m|d|f|div)\b/i', '', $string);

        // Ersetze alle definierten Zeichen
        $string = strtr($string, $replacements);

        // Entferne alle unzulässigen Zeichen
        $string = preg_replace('/[^A-Za-z0-9\-_]/', '', $string);

        // Entferne abschließende Bindestriche
        $string = rtrim($string, '-');

        // Ersetze doppelte Bindestriche oder Unterstriche durch einen einzigen
        $string = preg_replace('/-{2,}/', '-', $string);

        return preg_replace('/_{2,}/', '_', $string);
    }

    public function Link()
    {
        return Controller::join_links($this->FAQPage()->Link(), "category", $this->URLSegment);
    }

    public function sortedFAQs()
    {
        return $this->FAQs()->sort("Sort ASC");
    }

}
