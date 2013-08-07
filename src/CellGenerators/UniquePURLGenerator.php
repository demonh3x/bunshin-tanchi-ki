<?php

include_once(__ROOT_DIR__ . "src/FilteredHashList.php");
foreach (glob(__ROOT_DIR__ . "src/CellGenerators/PurlCalculators/*.php") as $filename){
    include_once($filename);
}

include_once(__ROOT_DIR__ . "src/HashCalculators/RowFilter.php");
include_once(__ROOT_DIR__ . "src/HashCalculators/Filters/FilterGroup.php");
foreach (glob(__ROOT_DIR__ . "src/HashCalculators/Filters/*Filter.php") as $filename){
    include_once($filename);
}

class UniquePURLGenerator {
    private $purlField;
    private $hashList;
    private $cleaningFilter;

    private $purlCalculators = array();

    function __construct($firstnameField, $surnameField, $salutationField, $purlField, $usedPurls = array()){
        $this->purlField = $purlField;

        $this->hashList = new FilteredHashList(new LowercaseFilter());
        foreach ($usedPurls as $purl){
            $this->hashList->add($purl);
        }

        $this->initCleaningFilters($firstnameField, $surnameField, $salutationField);
        $this->initPurlCalculators($firstnameField, $surnameField, $salutationField);
    }

    private function initCleaningFilters($firstnameField, $surnameField, $salutationField){
        $this->cleaningFilter = new RowFilter();
        $this->cleaningFilter->setFilter(
            FilterGroup::create(
                new TrimFilter(),
                new UppercaseFirstLetterFilter()
            ),
            $salutationField
        );
        $this->cleaningFilter->setFilter(
            FilterGroup::create(
                new TrimFilter(),
                new SubstituteAccentsFilter(),
                new OnlyLettersFilter(),
                new FirstNameFilter()
            ),
            $firstnameField
        );
        $this->cleaningFilter->setFilter(
            FilterGroup::create(
                new TrimFilter(),
                new SubstituteAccentsFilter(),
                new OnlyLettersFilter(),
                new NoSpacesFilter()
            ),
            $surnameField
        );
    }

    private function initPurlCalculators($firstnameField, $surnameField, $salutationField){
        $purlCalculators = array(
            "NameSurname",
            "NameS",
            "NSurname",
            "SalutationNameSurname",
            "Name_Surname",
            "Name_S",
            "N_Surname",
            "SalutationNameS",
            "SalutationNSurname",
            "SalutationName_Surname",
            "Salutation_NameSurname",
            "Salutation_Name_Surname",
            "SalutationName_S",
            "Salutation_NameS",
            "Salutation_Name_S",
            "SalutationN_Surname",
            "Salutation_NSurname",
            "Salutation_N_Surname",
            "SurnameName",
            "SurnameN",
            "Surname_Name",
            "Surname_N",
            "SalutationSurnameName",
            "SalutationSurname_Name",
            "Salutation_SurnameName",
            "Salutation_Surname_Name",
            "SalutationSurnameN",
            "Salutation_SurnameN",
            "SalutationSurname_N",
            "Salutation_Surname_N",
            "NameSurnameSalutation",
            "NameSSalutation",
            "NSurnameSalutation",
            "Name_Surname_Salutation",
            "Name_S_Salutation",
            "N_Surname_Salutation",
            "NameSurname_Salutation",
            "SurnameNameSalutation",
            "Surname_Name_Salutation",
            "SurnameName_Salutation",
            "SurnameNSalutation",
            "SurnameN_Salutation",
            "Surname_N_Salutation",

            "NameSurname_1",
            "NameS_1",
            "NSurname_1",
            "SalutationNameSurname_1",
            "Name_Surname_1",
            "Name_S_1",
            "N_Surname_1",
            "SalutationNameS_1",
            "SalutationNSurname_1",
            "SalutationName_Surname_1",

            "Salutation_NameSurname_1",
            "Salutation_Name_Surname_1",
            "SalutationName_S_1",
            "Salutation_NameS_1",
            "Salutation_Name_S_1",
            "SalutationN_Surname_1",
            "Salutation_NSurname_1",
            "Salutation_N_Surname_1",

            "SurnameName_1",
            "SurnameN_1",
            "Surname_Name_1",
            "Surname_N_1",
        );

        foreach ($purlCalculators as $purlCalculator){
            $purlCalculator .= "Calculator";
            $this->purlCalculators[] = new $purlCalculator($firstnameField, $surnameField, $salutationField);
        }
    }

    function generate($row){
        $return = $row;
        $purlHasBeenGenerated = false;

        foreach ($this->purlCalculators as $calculator){
            $purl = $calculator->calculate(
                $this->cleaningFilter->applyTo($row)
            );

            if (!$this->hashList->contains($purl)){
                $return[$this->purlField] = $purl;
                $this->hashList->add($purl);

                $purlHasBeenGenerated = true;
                break;
            }
        }

        if (!$purlHasBeenGenerated){
            throw new Exception(
                "Couldn't generate a purl for the row, all the options already taken."
            );
        }

        return $return;
    }
}