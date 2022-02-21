<?php



namespace Sunnysideup\CustomScript\Model;





use ExampleModelAdmin;

use SilverStripe\ORM\DataObject;

use SilverStripe\ORM\FieldType\DBField;

use SilverStripe\ORM\Filters\ExactMatchFilter;

use SilverStripe\ORM\Filters\PartialMatchFilter;

use SilverStripe\Security\Permission;

use SilverStripe\SiteConfig\SiteConfig;




class CustomScript  extends DataObject
{


    #######################
    ### Names Section
    #######################

    private static $singular_name = 'Custom Script';

    public function i18n_singular_name()
    {
        return _t(self::class.'.SINGULAR_NAME', 'Custom Script');
    }

    private static $plural_name = 'Custom Scripts';

    public function i18n_plural_name()
    {
        return _t(self::class.'.PLURAL_NAME', 'Custom Scripts');
    }

    private static $table_name = 'CustomScript';




    #######################
    ### Model Section
    #######################

    private static $db = [
        'Title' => 'Varchar',
        'Use' => 'Boolean',
        'Script' => 'Text',
        'Position' => 'Enum(array("Header", "Footer", "Header"))',
        'Sort' => 'Int'
    ];

    private static $has_one = [
        'SiteConfig' => SiteConfig::class
    ];


    #######################
    ### Further DB Field Details
    #######################








    private static $indexes = [
        'Sort' => true,
        'Title' => 'unique("Title")'
    ];

    private static $default_sort = [
        'Sort' => 'ASC'
    ];

    private static $required_fields = [
        'Title'
    ];

    private static $searchable_fields = [
        'Title' => PartialMatchFilter::class,
        'Use' => ExactMatchFilter::class
    ];


    #######################
    ### Field Names and Presentation Section
    #######################

    private static $summary_fields = [
        'Title' => 'Title',
        'Use.Nice' => 'Use',
        'Position' => 'Position'
    ];


    #######################
    ### Casting Section
    #######################


    #######################
    ### can Section
    #######################



    #######################
    ### write Section
    #######################




    public function validate()
    {
        $result = parent::validate();
        $fieldLabels = $this->FieldLabels();
        $indexes = $this->Config()->get('indexes');
        $requiredFields = $this->Config()->get('required_fields');
        if(is_array($requiredFields)) {
            foreach($requiredFields as $field) {
                $value = $this->$field;
                if(! $value) {
                    $fieldWithoutID = $field;
                    if(substr($fieldWithoutID, -2) === 'ID') {
                        $fieldWithoutID = substr($fieldWithoutID, 0, -2);
                    }
                    $myName = isset($fieldLabels[$fieldWithoutID]) ? $fieldLabels[$fieldWithoutID] : $fieldWithoutID;
                    $result->addError(
                        _t(
                            self::class.'.'.$field.'_REQUIRED',
                            $myName.' is required'
                        ),
                        'REQUIRED_'.self::class.'.'.$field
                    );
                }
                if (isset($indexes[$field]) && isset($indexes[$field]['type']) && $indexes[$field]['type'] === 'unique') {
                    $id = (empty($this->ID) ? 0 : $this->ID);
                    $count = self::get()
                        ->filter(array($field => $value))
                        ->exclude(array('ID' => $id))
                        ->count();
                    if($count > 0) {
                        $myName = $fieldLabels[$field];
                        $result->addError(
                            _t(
                                self::class.'.'.$field.'_UNIQUE',
                                $myName.' needs to be unique'
                            ),
                            'UNIQUE_'.self::class.'.'.$field
                        );
                    }
                }
            }
        }

        return $result;
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
        //...
    }

    public function onAfterWrite()
    {
        parent::onAfterWrite();
        //...
    }

    public function requireDefaultRecords()
    {
        parent::requireDefaultRecords();
        //...
    }


    #######################
    ### Import / Export Section
    #######################

    public function getExportFields()
    {
        //..
        return parent::getExportFields();
    }



    #######################
    ### CMS Edit Section
    #######################


    public function CMSEditLink()
    {
        $controller = Injector::inst($this->Config()->get('primary_model_admin_class'));

        return $controller->Link().$this->ClassName."/EditForm/field/".$this->ClassName."/item/".$this->ID."/edit";
    }

    public function CMSAddLink()
    {
        $controller = Injector::inst($this->Config()->get('primary_model_admin_class'));

        return $controller->Link().$this->ClassName."/EditForm/field/".$this->ClassName."/item/new";
    }


    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        //do first??
        $rightFieldDescriptions = $this->Config()->get('field_labels_right');
        if(is_array($rightFieldDescriptions) && count($rightFieldDescriptions)) {
            foreach($rightFieldDescriptions as $field => $desc) {
                $formField = $fields->DataFieldByName($field);
                if(! $formField) {
                    $formField = $fields->DataFieldByName($field.'ID');
                }
                if($formField) {
                    $formField->setDescription($desc);
                }
            }
        }
        //...

        return $fields;
    }


}
