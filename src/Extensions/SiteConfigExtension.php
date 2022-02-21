<?php

namespace Sunnysideup\CustomScript\Extensions;
use Sunnysideup\CustomScript\Model\CustomScript;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataExtension;

class SiteConfigExtension extends DataExtension
{
    private static $has_many = [
        'CustomScripts' => CustomScript::class,
    ];

    public function updateCMSFields(FieldList $fields)
    {
        $fields->DataFieldByName('CustomScript')
            ->sesDecription('
                Please use with extreme care and make sure you check your website straight after adding scripts for any errors.
                Please make sure to include the &lt;script&gt; tags'
            );
    }

    public function HeaderScripts()
    {
      return $this->getOwner()->CustomScripts()->filter(['InUse' => true, 'Position' => 'Header']);
    }

    public function FooterScripts()
    {
      return $this->getOwner()->CustomScripts()->filter(['InUse' => true, 'Position' => 'Footer']);
    }
}
