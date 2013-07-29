<?php

include_once(__ROOT_DIR__ . "src/HashList.php");
foreach (glob(__ROOT_DIR__ . "src/CellGenerators/PurlCalculators/*.php") as $filename){
    include_once($filename);
}

class UniquePURLGenerator {
    private $firstnameField, $surnameField, $salutationField, $purlField;
    private $hashList;

    private $purlCalculators = array();

    function __construct($firstnameField, $surnameField, $salutationField, $purlField, $usedPurls = array()){
        /*$this->firstnameField = $firstnameField;
        $this->surnameField = $surnameField;
        $this->salutationField = $salutationField;*/
        $this->purlField = $purlField;

        $this->hashList = new HashList();
        foreach ($usedPurls as $purl){
            $this->hashList->add($purl);
        }

        $this->purlCalculators[] = new NameSurnameCalculator($firstnameField, $surnameField, $salutationField);
        $this->purlCalculators[] = new NameSCalculator($firstnameField, $surnameField, $salutationField);
        $this->purlCalculators[] = new NSurnameCalculator($firstnameField, $surnameField, $salutationField);
        $this->purlCalculators[] = new SalutationNameSurnameCalculator($firstnameField, $surnameField, $salutationField);
        $this->purlCalculators[] = new SalutationNameSCalculator($firstnameField, $surnameField, $salutationField);
        $this->purlCalculators[] = new SalutationNSurnameCalculator($firstnameField, $surnameField, $salutationField);
    }

    function generate($row){
        $return = $row;
        $purlHasBeenGenerated = false;

        foreach ($this->purlCalculators as $calculator){
            $purl = $calculator->calculate($row);

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