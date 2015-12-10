<?php

Class Tables {

    public $Patch, $Build;

    public function __construct($Patch, $Build){
        $this->Patch = $Patch;
        $this->Build = $Build;
    }

    public function generateItemSetTable(){
        $SQLBuilder = new SQLBuilder($this->Patch, $this->Build, 'itemset');
        $SQLBuilder->addDataSource('ItemSet.dbc');
        $SQLBuilder->addDataSource('ItemSetSpell.dbc');
        $SQLBuilder->addRelation('freedomcore_itemset', 'freedomcore_itemsetspell', 'id', 'set_id', 'item10');
        $SQLBuilder->generateNewStructure();
        $SQLBuilder->generateCreationQuery();
        $SQLBuilder->populateDatabase();
        return $SQLBuilder;
    }
}

?>