<?php

include_once(__ROOT_DIR__ . "src/HashList.php");
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

        $this->hashList = new HashList();
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
            "NameSurnameCalculator",
            "NameSCalculator",
            "NSurnameCalculator",
            "SalutationNameSurnameCalculator",
            "Name_SurnameCalculator",
            "Name_SCalculator",
            "N_SurnameCalculator",
            "SalutationNameSCalculator",
            "SalutationNSurnameCalculator",
            "SalutationName_SurnameCalculator",
            "Salutation_NameSurnameCalculator",
            "Salutation_Name_SurnameCalculator",
            "SalutationName_SCalculator",
            "Salutation_NameSCalculator",
            "Salutation_Name_SCalculator",
            "SalutationN_SurnameCalculator",
            "Salutation_NSurnameCalculator",
            "Salutation_N_SurnameCalculator",
            "SurnameNameCalculator",
            "SurnameNCalculator",
            "Surname_NameCalculator",
            "Surname_NCalculator",
            "SalutationSurnameNameCalculator",
            "SalutationSurname_NameCalculator",
            "Salutation_SurnameNameCalculator",
            "Salutation_Surname_NameCalculator",
            "SalutationSurnameNCalculator",
            "Salutation_SurnameNCalculator",
            "SalutationSurname_NCalculator",
            "Salutation_Surname_NCalculator",
        );

        foreach ($purlCalculators as $purlCalculator){
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