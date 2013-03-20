<?php
//Include Common Files @1-32CFB387
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "soniacon.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

class clsGridsaconsult { //saconsult class @2-5F781AD2

//Variables @2-09BF744E

    // Public variables
    public $ComponentType = "Grid";
    public $ComponentName;
    public $Visible;
    public $Errors;
    public $ErrorBlock;
    public $ds;
    public $DataSource;
    public $PageSize;
    public $IsEmpty;
    public $ForceIteration = false;
    public $HasRecord = false;
    public $SorterName = "";
    public $SorterDirection = "";
    public $PageNumber;
    public $RowNumber;
    public $ControlsVisible = array();

    public $CCSEvents = "";
    public $CCSEventResult;

    public $RelativePath = "";
    public $Attributes;

    // Grid Controls
    public $StaticControls;
    public $RowControls;
    public $Sorter_sacon_Name;
    public $Sorter_sacon_DOB;
    public $Sorter_sacon_phone;
    public $Sorter_sacon_email;
    public $Sorter_sacon_registration;
//End Variables

//Class_Initialize Event @2-7DC95C1E
    function clsGridsaconsult($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "saconsult";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid saconsult";
        $this->Attributes = new clsAttributes($this->ComponentName . ":");
        $this->DataSource = new clssaconsultDataSource($this);
        $this->ds = & $this->DataSource;
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 10;
        else
            $this->PageSize = intval($this->PageSize);
        if ($this->PageSize > 100)
            $this->PageSize = 100;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<BR>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        if ($this->PageNumber <= 0) $this->PageNumber = 1;
        $this->SorterName = CCGetParam("saconsultOrder", "");
        $this->SorterDirection = CCGetParam("saconsultDir", "");

        $this->sacon_Name = new clsControl(ccsLink, "sacon_Name", "sacon_Name", ccsText, "", CCGetRequestParam("sacon_Name", ccsGet, NULL), $this);
        $this->sacon_Name->Page = "";
        $this->sacon_DOB = new clsControl(ccsLabel, "sacon_DOB", "sacon_DOB", ccsDate, array("yyyy", "-", "mm", "-", "dd"), CCGetRequestParam("sacon_DOB", ccsGet, NULL), $this);
        $this->sacon_phone = new clsControl(ccsLabel, "sacon_phone", "sacon_phone", ccsText, "", CCGetRequestParam("sacon_phone", ccsGet, NULL), $this);
        $this->sacon_email = new clsControl(ccsLabel, "sacon_email", "sacon_email", ccsText, "", CCGetRequestParam("sacon_email", ccsGet, NULL), $this);
        $this->sacon_registration = new clsControl(ccsLabel, "sacon_registration", "sacon_registration", ccsInteger, "", CCGetRequestParam("sacon_registration", ccsGet, NULL), $this);
        $this->saconsult_Insert = new clsControl(ccsLink, "saconsult_Insert", "saconsult_Insert", ccsText, "", CCGetRequestParam("saconsult_Insert", ccsGet, NULL), $this);
        $this->saconsult_Insert->Parameters = CCGetQueryString("QueryString", array("Pkey", "ccsForm"));
        $this->saconsult_Insert->Page = "soniacon.php";
        $this->Sorter_sacon_Name = new clsSorter($this->ComponentName, "Sorter_sacon_Name", $FileName, $this);
        $this->Sorter_sacon_DOB = new clsSorter($this->ComponentName, "Sorter_sacon_DOB", $FileName, $this);
        $this->Sorter_sacon_phone = new clsSorter($this->ComponentName, "Sorter_sacon_phone", $FileName, $this);
        $this->Sorter_sacon_email = new clsSorter($this->ComponentName, "Sorter_sacon_email", $FileName, $this);
        $this->Sorter_sacon_registration = new clsSorter($this->ComponentName, "Sorter_sacon_registration", $FileName, $this);
        $this->Navigator = new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpCentered, $this);
        $this->Navigator->PageSizes = array("1", "5", "10", "25", "50");
    }
//End Class_Initialize Event

//Initialize Method @2-90E704C5
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->DataSource->PageSize = & $this->PageSize;
        $this->DataSource->AbsolutePage = & $this->PageNumber;
        $this->DataSource->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @2-60E15CE4
    function Show()
    {
        $Tpl = & CCGetTemplate($this);
        global $CCSLocales;
        if(!$this->Visible) return;

        $this->RowNumber = 0;

        $this->DataSource->Parameters["urls_sacon_Name"] = CCGetFromGet("s_sacon_Name", NULL);
        $this->DataSource->Parameters["urls_sacon_DOB"] = CCGetFromGet("s_sacon_DOB", NULL);
        $this->DataSource->Parameters["urls_sacon_POB"] = CCGetFromGet("s_sacon_POB", NULL);
        $this->DataSource->Parameters["urls_sacon_phone"] = CCGetFromGet("s_sacon_phone", NULL);
        $this->DataSource->Parameters["urls_sacon_email"] = CCGetFromGet("s_sacon_email", NULL);

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);


        $this->DataSource->Prepare();
        $this->DataSource->Open();
        $this->HasRecord = $this->DataSource->has_next_record();
        $this->IsEmpty = ! $this->HasRecord;
        $this->Attributes->Show();

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        if(!$this->Visible) return;

        $GridBlock = "Grid " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $GridBlock;


        if (!$this->IsEmpty) {
            $this->ControlsVisible["sacon_Name"] = $this->sacon_Name->Visible;
            $this->ControlsVisible["sacon_DOB"] = $this->sacon_DOB->Visible;
            $this->ControlsVisible["sacon_phone"] = $this->sacon_phone->Visible;
            $this->ControlsVisible["sacon_email"] = $this->sacon_email->Visible;
            $this->ControlsVisible["sacon_registration"] = $this->sacon_registration->Visible;
            while ($this->ForceIteration || (($this->RowNumber < $this->PageSize) &&  ($this->HasRecord = $this->DataSource->has_next_record()))) {
                $this->RowNumber++;
                if ($this->HasRecord) {
                    $this->DataSource->next_record();
                    $this->DataSource->SetValues();
                }
                $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/Row";
                $this->sacon_Name->SetValue($this->DataSource->sacon_Name->GetValue());
                $this->sacon_Name->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                $this->sacon_Name->Parameters = CCAddParam($this->sacon_Name->Parameters, "Pkey", $this->DataSource->f("Pkey"));
                $this->sacon_DOB->SetValue($this->DataSource->sacon_DOB->GetValue());
                $this->sacon_phone->SetValue($this->DataSource->sacon_phone->GetValue());
                $this->sacon_email->SetValue($this->DataSource->sacon_email->GetValue());
                $this->sacon_registration->SetValue($this->DataSource->sacon_registration->GetValue());
                $this->Attributes->SetValue("rowNumber", $this->RowNumber);
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                $this->Attributes->Show();
                $this->sacon_Name->Show();
                $this->sacon_DOB->Show();
                $this->sacon_phone->Show();
                $this->sacon_email->Show();
                $this->sacon_registration->Show();
                $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                $Tpl->parse("Row", true);
            }
        }
        else { // Show NoRecords block if no records are found
            $this->Attributes->Show();
            $Tpl->parse("NoRecords", false);
        }

        $errors = $this->GetErrors();
        if(strlen($errors))
        {
            $Tpl->replaceblock("", $errors);
            $Tpl->block_path = $ParentPath;
            return;
        }
        $this->Navigator->PageNumber = $this->DataSource->AbsolutePage;
        $this->Navigator->PageSize = $this->PageSize;
        if ($this->DataSource->RecordsCount == "CCS not counted")
            $this->Navigator->TotalPages = $this->DataSource->AbsolutePage + ($this->DataSource->next_record() ? 1 : 0);
        else
            $this->Navigator->TotalPages = $this->DataSource->PageCount();
        if (($this->Navigator->TotalPages <= 1 && $this->Navigator->PageNumber == 1) || $this->Navigator->PageSize == "") {
            $this->Navigator->Visible = false;
        }
        $this->saconsult_Insert->Show();
        $this->Sorter_sacon_Name->Show();
        $this->Sorter_sacon_DOB->Show();
        $this->Sorter_sacon_phone->Show();
        $this->Sorter_sacon_email->Show();
        $this->Sorter_sacon_registration->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @2-78CC97F6
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->sacon_Name->Errors->ToString());
        $errors = ComposeStrings($errors, $this->sacon_DOB->Errors->ToString());
        $errors = ComposeStrings($errors, $this->sacon_phone->Errors->ToString());
        $errors = ComposeStrings($errors, $this->sacon_email->Errors->ToString());
        $errors = ComposeStrings($errors, $this->sacon_registration->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End saconsult Class @2-FCB6E20C

class clssaconsultDataSource extends clsDBConnection1 {  //saconsultDataSource Class @2-30B55BFD

//DataSource Variables @2-17EAB2F3
    public $Parent = "";
    public $CCSEvents = "";
    public $CCSEventResult;
    public $ErrorBlock;
    public $CmdExecution;

    public $CountSQL;
    public $wp;


    // Datasource fields
    public $sacon_Name;
    public $sacon_DOB;
    public $sacon_phone;
    public $sacon_email;
    public $sacon_registration;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-C69959EA
    function clssaconsultDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid saconsult";
        $this->Initialize();
        $this->sacon_Name = new clsField("sacon_Name", ccsText, "");
        
        $this->sacon_DOB = new clsField("sacon_DOB", ccsDate, array("yyyy", "-", "mm", "-", "dd"));
        
        $this->sacon_phone = new clsField("sacon_phone", ccsText, "");
        
        $this->sacon_email = new clsField("sacon_email", ccsText, "");
        
        $this->sacon_registration = new clsField("sacon_registration", ccsInteger, "");
        

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @2-071C683F
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "sacon_registration";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_sacon_Name" => array("sacon_Name", ""), 
            "Sorter_sacon_DOB" => array("sacon_DOB", ""), 
            "Sorter_sacon_phone" => array("sacon_phone", ""), 
            "Sorter_sacon_email" => array("sacon_email", ""), 
            "Sorter_sacon_registration" => array("sacon_registration", "")));
    }
//End SetOrder Method

//Prepare Method @2-08B79813
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_sacon_Name", ccsText, "", "", $this->Parameters["urls_sacon_Name"], "", false);
        $this->wp->AddParameter("2", "urls_sacon_DOB", ccsDate, $DefaultDateFormat, $this->DateFormat, $this->Parameters["urls_sacon_DOB"], "", false);
        $this->wp->AddParameter("3", "urls_sacon_POB", ccsText, "", "", $this->Parameters["urls_sacon_POB"], "", false);
        $this->wp->AddParameter("4", "urls_sacon_phone", ccsText, "", "", $this->Parameters["urls_sacon_phone"], "", false);
        $this->wp->AddParameter("5", "urls_sacon_email", ccsText, "", "", $this->Parameters["urls_sacon_email"], "", false);
        $this->wp->Criterion[1] = $this->wp->Operation(opContains, "sacon_Name", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opEqual, "sacon_DOB", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsDate),false);
        $this->wp->Criterion[3] = $this->wp->Operation(opContains, "sacon_POB", $this->wp->GetDBValue("3"), $this->ToSQL($this->wp->GetDBValue("3"), ccsText),false);
        $this->wp->Criterion[4] = $this->wp->Operation(opContains, "sacon_phone", $this->wp->GetDBValue("4"), $this->ToSQL($this->wp->GetDBValue("4"), ccsText),false);
        $this->wp->Criterion[5] = $this->wp->Operation(opContains, "sacon_email", $this->wp->GetDBValue("5"), $this->ToSQL($this->wp->GetDBValue("5"), ccsText),false);
        $this->Where = $this->wp->opAND(
             false, $this->wp->opAND(
             false, $this->wp->opAND(
             false, $this->wp->opAND(
             false, 
             $this->wp->Criterion[1], 
             $this->wp->Criterion[2]), 
             $this->wp->Criterion[3]), 
             $this->wp->Criterion[4]), 
             $this->wp->Criterion[5]);
    }
//End Prepare Method

//Open Method @2-6E3A3AC7
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->CountSQL = "SELECT COUNT(*)\n\n" .
        "FROM saconsult";
        $this->SQL = "SELECT Pkey, sacon_Name, sacon_DOB, sacon_phone, sacon_email, sacon_registration \n\n" .
        "FROM saconsult {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        if ($this->CountSQL) 
            $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        else
            $this->RecordsCount = "CCS not counted";
        $this->query($this->OptimizeSQL(CCBuildSQL($this->SQL, $this->Where, $this->Order)));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @2-68EF4401
    function SetValues()
    {
        $this->sacon_Name->SetDBValue($this->f("sacon_Name"));
        $this->sacon_DOB->SetDBValue(trim($this->f("sacon_DOB")));
        $this->sacon_phone->SetDBValue($this->f("sacon_phone"));
        $this->sacon_email->SetDBValue($this->f("sacon_email"));
        $this->sacon_registration->SetDBValue(trim($this->f("sacon_registration")));
    }
//End SetValues Method

} //End saconsultDataSource Class @2-FCB6E20C

class clsRecordsaconsultSearch { //saconsultSearch Class @28-4B097EBD

//Variables @28-9E315808

    // Public variables
    public $ComponentType = "Record";
    public $ComponentName;
    public $Parent;
    public $HTMLFormAction;
    public $PressedButton;
    public $Errors;
    public $ErrorBlock;
    public $FormSubmitted;
    public $FormEnctype;
    public $Visible;
    public $IsEmpty;

    public $CCSEvents = "";
    public $CCSEventResult;

    public $RelativePath = "";

    public $InsertAllowed = false;
    public $UpdateAllowed = false;
    public $DeleteAllowed = false;
    public $ReadAllowed   = false;
    public $EditMode      = false;
    public $ds;
    public $DataSource;
    public $ValidatingControls;
    public $Controls;
    public $Attributes;

    // Class variables
//End Variables

//Class_Initialize Event @28-90EBD5CD
    function clsRecordsaconsultSearch($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record saconsultSearch/Error";
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "saconsultSearch";
            $this->Attributes = new clsAttributes($this->ComponentName . ":");
            $CCSForm = explode(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->Button_DoSearch = new clsButton("Button_DoSearch", $Method, $this);
            $this->s_sacon_Name = new clsControl(ccsTextBox, "s_sacon_Name", "Sacon Name", ccsText, "", CCGetRequestParam("s_sacon_Name", $Method, NULL), $this);
            $this->s_sacon_DOB = new clsControl(ccsTextBox, "s_sacon_DOB", "Sacon DOB", ccsDate, array("yyyy", "-", "mm", "-", "dd"), CCGetRequestParam("s_sacon_DOB", $Method, NULL), $this);
            $this->s_sacon_POB = new clsControl(ccsTextBox, "s_sacon_POB", "Sacon POB", ccsText, "", CCGetRequestParam("s_sacon_POB", $Method, NULL), $this);
            $this->s_sacon_phone = new clsControl(ccsTextBox, "s_sacon_phone", "Sacon Phone", ccsText, "", CCGetRequestParam("s_sacon_phone", $Method, NULL), $this);
            $this->s_sacon_email = new clsControl(ccsTextBox, "s_sacon_email", "Sacon Email", ccsText, "", CCGetRequestParam("s_sacon_email", $Method, NULL), $this);
        }
    }
//End Class_Initialize Event

//Validate Method @28-30638679
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_sacon_Name->Validate() && $Validation);
        $Validation = ($this->s_sacon_DOB->Validate() && $Validation);
        $Validation = ($this->s_sacon_POB->Validate() && $Validation);
        $Validation = ($this->s_sacon_phone->Validate() && $Validation);
        $Validation = ($this->s_sacon_email->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->s_sacon_Name->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_sacon_DOB->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_sacon_POB->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_sacon_phone->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_sacon_email->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @28-3433A769
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_sacon_Name->Errors->Count());
        $errors = ($errors || $this->s_sacon_DOB->Errors->Count());
        $errors = ($errors || $this->s_sacon_POB->Errors->Count());
        $errors = ($errors || $this->s_sacon_phone->Errors->Count());
        $errors = ($errors || $this->s_sacon_email->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @28-C2B3B185
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        if(!$this->FormSubmitted) {
            return;
        }

        if($this->FormSubmitted) {
            $this->PressedButton = "Button_DoSearch";
            if($this->Button_DoSearch->Pressed) {
                $this->PressedButton = "Button_DoSearch";
            }
        }
        $Redirect = "soniacon.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                $Redirect = "soniacon.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", array("Button_DoSearch", "Button_DoSearch_x", "Button_DoSearch_y")));
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick", $this->Button_DoSearch)) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @28-E9FD152E
    function Show()
    {
        global $CCSUseAmp;
        $Tpl = & CCGetTemplate($this);
        global $FileName;
        global $CCSLocales;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);


        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->s_sacon_Name->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_sacon_DOB->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_sacon_POB->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_sacon_phone->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_sacon_email->Errors->ToString());
            $Error = ComposeStrings($Error, $this->Errors->ToString());
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $CCSForm = $this->EditMode ? $this->ComponentName . ":" . "Edit" : $this->ComponentName;
        $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $CCSForm);
        $Tpl->SetVar("Action", !$CCSUseAmp ? $this->HTMLFormAction : str_replace("&", "&amp;", $this->HTMLFormAction));
        $Tpl->SetVar("HTMLFormName", $this->ComponentName);
        $Tpl->SetVar("HTMLFormEnctype", $this->FormEnctype);

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        $this->Attributes->Show();
        if(!$this->Visible) {
            $Tpl->block_path = $ParentPath;
            return;
        }

        $this->Button_DoSearch->Show();
        $this->s_sacon_Name->Show();
        $this->s_sacon_DOB->Show();
        $this->s_sacon_POB->Show();
        $this->s_sacon_phone->Show();
        $this->s_sacon_email->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End saconsultSearch Class @28-FCB6E20C

class clsRecordsaconsult1 { //saconsult1 Class @36-557D069F

//Variables @36-9E315808

    // Public variables
    public $ComponentType = "Record";
    public $ComponentName;
    public $Parent;
    public $HTMLFormAction;
    public $PressedButton;
    public $Errors;
    public $ErrorBlock;
    public $FormSubmitted;
    public $FormEnctype;
    public $Visible;
    public $IsEmpty;

    public $CCSEvents = "";
    public $CCSEventResult;

    public $RelativePath = "";

    public $InsertAllowed = false;
    public $UpdateAllowed = false;
    public $DeleteAllowed = false;
    public $ReadAllowed   = false;
    public $EditMode      = false;
    public $ds;
    public $DataSource;
    public $ValidatingControls;
    public $Controls;
    public $Attributes;

    // Class variables
//End Variables

//Class_Initialize Event @36-8CE98C5F
    function clsRecordsaconsult1($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record saconsult1/Error";
        $this->DataSource = new clssaconsult1DataSource($this);
        $this->ds = & $this->DataSource;
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "saconsult1";
            $this->Attributes = new clsAttributes($this->ComponentName . ":");
            $CCSForm = explode(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->Button_Insert = new clsButton("Button_Insert", $Method, $this);
            $this->Button_Update = new clsButton("Button_Update", $Method, $this);
            $this->Button_Delete = new clsButton("Button_Delete", $Method, $this);
            $this->Button_Cancel = new clsButton("Button_Cancel", $Method, $this);
            $this->sacon_registration = new clsControl(ccsTextBox, "sacon_registration", "Sacon Registration", ccsInteger, "", CCGetRequestParam("sacon_registration", $Method, NULL), $this);
            $this->sacon_DTReg = new clsControl(ccsTextBox, "sacon_DTReg", "Sacon DTReg", ccsDate, array("yyyy", "-", "mm", "-", "dd", " ", "H", ":", "nn", ":", "ss"), CCGetRequestParam("sacon_DTReg", $Method, NULL), $this);
            $this->sacon_Name = new clsControl(ccsTextBox, "sacon_Name", "Sacon Name", ccsText, "", CCGetRequestParam("sacon_Name", $Method, NULL), $this);
            $this->sacon_DOB = new clsControl(ccsTextBox, "sacon_DOB", "Sacon DOB", ccsDate, array("yyyy", "-", "mm", "-", "dd"), CCGetRequestParam("sacon_DOB", $Method, NULL), $this);
            $this->sacon_TOB = new clsControl(ccsTextBox, "sacon_TOB", "Sacon TOB", ccsDate, array("H", ":", "nn", ":", "ss"), CCGetRequestParam("sacon_TOB", $Method, NULL), $this);
            $this->sacon_TOB->Required = true;
            $this->sacon_POB = new clsControl(ccsTextBox, "sacon_POB", "Sacon POB", ccsText, "", CCGetRequestParam("sacon_POB", $Method, NULL), $this);
            $this->sacon_phone = new clsControl(ccsTextBox, "sacon_phone", "Sacon Phone", ccsText, "", CCGetRequestParam("sacon_phone", $Method, NULL), $this);
            $this->sacon_email = new clsControl(ccsTextBox, "sacon_email", "Sacon Email", ccsText, "", CCGetRequestParam("sacon_email", $Method, NULL), $this);
            $this->sacon_address1 = new clsControl(ccsTextBox, "sacon_address1", "Sacon Address1", ccsText, "", CCGetRequestParam("sacon_address1", $Method, NULL), $this);
            $this->sacon_address2 = new clsControl(ccsTextBox, "sacon_address2", "Sacon Address2", ccsText, "", CCGetRequestParam("sacon_address2", $Method, NULL), $this);
            $this->sacon_DateOfMarriage = new clsControl(ccsTextBox, "sacon_DateOfMarriage", "Sacon Date Of Marriage", ccsDate, array("yyyy", "-", "mm", "-", "dd"), CCGetRequestParam("sacon_DateOfMarriage", $Method, NULL), $this);
            $this->sacon_noChildB = new clsControl(ccsTextBox, "sacon_noChildB", "Sacon No Child B", ccsInteger, "", CCGetRequestParam("sacon_noChildB", $Method, NULL), $this);
            $this->sacon_noChildG = new clsControl(ccsTextBox, "sacon_noChildG", "Sacon No Child G", ccsInteger, "", CCGetRequestParam("sacon_noChildG", $Method, NULL), $this);
            $this->sacon_education = new clsControl(ccsTextBox, "sacon_education", "Sacon Education", ccsText, "", CCGetRequestParam("sacon_education", $Method, NULL), $this);
            $this->sacon_profession = new clsControl(ccsTextBox, "sacon_profession", "Sacon Profession", ccsText, "", CCGetRequestParam("sacon_profession", $Method, NULL), $this);
            $this->sacon_reference = new clsControl(ccsTextBox, "sacon_reference", "Sacon Reference", ccsText, "", CCGetRequestParam("sacon_reference", $Method, NULL), $this);
            $this->sacon_question = new clsControl(ccsTextBox, "sacon_question", "Sacon Question", ccsMemo, "", CCGetRequestParam("sacon_question", $Method, NULL), $this);
            $this->sacon_question->Required = true;
            $this->sacon_notes = new clsControl(ccsTextBox, "sacon_notes", "Sacon Notes", ccsMemo, "", CCGetRequestParam("sacon_notes", $Method, NULL), $this);
            $this->sacon_notes->Required = true;
            if(!$this->FormSubmitted) {
                if(!is_array($this->sacon_DTReg->Value) && !strlen($this->sacon_DTReg->Value) && $this->sacon_DTReg->Value !== false)
                    $this->sacon_DTReg->SetValue(time());
                if(!is_array($this->sacon_DOB->Value) && !strlen($this->sacon_DOB->Value) && $this->sacon_DOB->Value !== false)
                    $this->sacon_DOB->SetValue(time());
                if(!is_array($this->sacon_TOB->Value) && !strlen($this->sacon_TOB->Value) && $this->sacon_TOB->Value !== false)
                    $this->sacon_TOB->SetValue(time());
            }
        }
    }
//End Class_Initialize Event

//Initialize Method @36-D26BB444
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->DataSource->Parameters["urlPkey"] = CCGetFromGet("Pkey", NULL);
    }
//End Initialize Method

//Validate Method @36-6CC2077E
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->sacon_registration->Validate() && $Validation);
        $Validation = ($this->sacon_DTReg->Validate() && $Validation);
        $Validation = ($this->sacon_Name->Validate() && $Validation);
        $Validation = ($this->sacon_DOB->Validate() && $Validation);
        $Validation = ($this->sacon_TOB->Validate() && $Validation);
        $Validation = ($this->sacon_POB->Validate() && $Validation);
        $Validation = ($this->sacon_phone->Validate() && $Validation);
        $Validation = ($this->sacon_email->Validate() && $Validation);
        $Validation = ($this->sacon_address1->Validate() && $Validation);
        $Validation = ($this->sacon_address2->Validate() && $Validation);
        $Validation = ($this->sacon_DateOfMarriage->Validate() && $Validation);
        $Validation = ($this->sacon_noChildB->Validate() && $Validation);
        $Validation = ($this->sacon_noChildG->Validate() && $Validation);
        $Validation = ($this->sacon_education->Validate() && $Validation);
        $Validation = ($this->sacon_profession->Validate() && $Validation);
        $Validation = ($this->sacon_reference->Validate() && $Validation);
        $Validation = ($this->sacon_question->Validate() && $Validation);
        $Validation = ($this->sacon_notes->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->sacon_registration->Errors->Count() == 0);
        $Validation =  $Validation && ($this->sacon_DTReg->Errors->Count() == 0);
        $Validation =  $Validation && ($this->sacon_Name->Errors->Count() == 0);
        $Validation =  $Validation && ($this->sacon_DOB->Errors->Count() == 0);
        $Validation =  $Validation && ($this->sacon_TOB->Errors->Count() == 0);
        $Validation =  $Validation && ($this->sacon_POB->Errors->Count() == 0);
        $Validation =  $Validation && ($this->sacon_phone->Errors->Count() == 0);
        $Validation =  $Validation && ($this->sacon_email->Errors->Count() == 0);
        $Validation =  $Validation && ($this->sacon_address1->Errors->Count() == 0);
        $Validation =  $Validation && ($this->sacon_address2->Errors->Count() == 0);
        $Validation =  $Validation && ($this->sacon_DateOfMarriage->Errors->Count() == 0);
        $Validation =  $Validation && ($this->sacon_noChildB->Errors->Count() == 0);
        $Validation =  $Validation && ($this->sacon_noChildG->Errors->Count() == 0);
        $Validation =  $Validation && ($this->sacon_education->Errors->Count() == 0);
        $Validation =  $Validation && ($this->sacon_profession->Errors->Count() == 0);
        $Validation =  $Validation && ($this->sacon_reference->Errors->Count() == 0);
        $Validation =  $Validation && ($this->sacon_question->Errors->Count() == 0);
        $Validation =  $Validation && ($this->sacon_notes->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @36-55E21633
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->sacon_registration->Errors->Count());
        $errors = ($errors || $this->sacon_DTReg->Errors->Count());
        $errors = ($errors || $this->sacon_Name->Errors->Count());
        $errors = ($errors || $this->sacon_DOB->Errors->Count());
        $errors = ($errors || $this->sacon_TOB->Errors->Count());
        $errors = ($errors || $this->sacon_POB->Errors->Count());
        $errors = ($errors || $this->sacon_phone->Errors->Count());
        $errors = ($errors || $this->sacon_email->Errors->Count());
        $errors = ($errors || $this->sacon_address1->Errors->Count());
        $errors = ($errors || $this->sacon_address2->Errors->Count());
        $errors = ($errors || $this->sacon_DateOfMarriage->Errors->Count());
        $errors = ($errors || $this->sacon_noChildB->Errors->Count());
        $errors = ($errors || $this->sacon_noChildG->Errors->Count());
        $errors = ($errors || $this->sacon_education->Errors->Count());
        $errors = ($errors || $this->sacon_profession->Errors->Count());
        $errors = ($errors || $this->sacon_reference->Errors->Count());
        $errors = ($errors || $this->sacon_question->Errors->Count());
        $errors = ($errors || $this->sacon_notes->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->DataSource->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @36-288F0419
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        $this->DataSource->Prepare();
        if(!$this->FormSubmitted) {
            $this->EditMode = $this->DataSource->AllParametersSet;
            return;
        }

        if($this->FormSubmitted) {
            $this->PressedButton = $this->EditMode ? "Button_Update" : "Button_Insert";
            if($this->Button_Insert->Pressed) {
                $this->PressedButton = "Button_Insert";
            } else if($this->Button_Update->Pressed) {
                $this->PressedButton = "Button_Update";
            } else if($this->Button_Delete->Pressed) {
                $this->PressedButton = "Button_Delete";
            } else if($this->Button_Cancel->Pressed) {
                $this->PressedButton = "Button_Cancel";
            }
        }
        $Redirect = $FileName . "?" . CCGetQueryString("QueryString", array("ccsForm"));
        if($this->PressedButton == "Button_Delete") {
            if(!CCGetEvent($this->Button_Delete->CCSEvents, "OnClick", $this->Button_Delete) || !$this->DeleteRow()) {
                $Redirect = "";
            }
        } else if($this->PressedButton == "Button_Cancel") {
            if(!CCGetEvent($this->Button_Cancel->CCSEvents, "OnClick", $this->Button_Cancel)) {
                $Redirect = "";
            }
        } else if($this->Validate()) {
            if($this->PressedButton == "Button_Insert") {
                if(!CCGetEvent($this->Button_Insert->CCSEvents, "OnClick", $this->Button_Insert) || !$this->InsertRow()) {
                    $Redirect = "";
                }
            } else if($this->PressedButton == "Button_Update") {
                if(!CCGetEvent($this->Button_Update->CCSEvents, "OnClick", $this->Button_Update) || !$this->UpdateRow()) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
        if ($Redirect)
            $this->DataSource->close();
    }
//End Operation Method

//InsertRow Method @36-6DFE8500
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert", $this);
        if(!$this->InsertAllowed) return false;
        $this->DataSource->sacon_registration->SetValue($this->sacon_registration->GetValue(true));
        $this->DataSource->sacon_DTReg->SetValue($this->sacon_DTReg->GetValue(true));
        $this->DataSource->sacon_Name->SetValue($this->sacon_Name->GetValue(true));
        $this->DataSource->sacon_DOB->SetValue($this->sacon_DOB->GetValue(true));
        $this->DataSource->sacon_TOB->SetValue($this->sacon_TOB->GetValue(true));
        $this->DataSource->sacon_POB->SetValue($this->sacon_POB->GetValue(true));
        $this->DataSource->sacon_phone->SetValue($this->sacon_phone->GetValue(true));
        $this->DataSource->sacon_email->SetValue($this->sacon_email->GetValue(true));
        $this->DataSource->sacon_address1->SetValue($this->sacon_address1->GetValue(true));
        $this->DataSource->sacon_address2->SetValue($this->sacon_address2->GetValue(true));
        $this->DataSource->sacon_DateOfMarriage->SetValue($this->sacon_DateOfMarriage->GetValue(true));
        $this->DataSource->sacon_noChildB->SetValue($this->sacon_noChildB->GetValue(true));
        $this->DataSource->sacon_noChildG->SetValue($this->sacon_noChildG->GetValue(true));
        $this->DataSource->sacon_education->SetValue($this->sacon_education->GetValue(true));
        $this->DataSource->sacon_profession->SetValue($this->sacon_profession->GetValue(true));
        $this->DataSource->sacon_reference->SetValue($this->sacon_reference->GetValue(true));
        $this->DataSource->sacon_question->SetValue($this->sacon_question->GetValue(true));
        $this->DataSource->sacon_notes->SetValue($this->sacon_notes->GetValue(true));
        $this->DataSource->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert", $this);
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @36-9C4ADB76
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate", $this);
        if(!$this->UpdateAllowed) return false;
        $this->DataSource->sacon_registration->SetValue($this->sacon_registration->GetValue(true));
        $this->DataSource->sacon_DTReg->SetValue($this->sacon_DTReg->GetValue(true));
        $this->DataSource->sacon_Name->SetValue($this->sacon_Name->GetValue(true));
        $this->DataSource->sacon_DOB->SetValue($this->sacon_DOB->GetValue(true));
        $this->DataSource->sacon_TOB->SetValue($this->sacon_TOB->GetValue(true));
        $this->DataSource->sacon_POB->SetValue($this->sacon_POB->GetValue(true));
        $this->DataSource->sacon_phone->SetValue($this->sacon_phone->GetValue(true));
        $this->DataSource->sacon_email->SetValue($this->sacon_email->GetValue(true));
        $this->DataSource->sacon_address1->SetValue($this->sacon_address1->GetValue(true));
        $this->DataSource->sacon_address2->SetValue($this->sacon_address2->GetValue(true));
        $this->DataSource->sacon_DateOfMarriage->SetValue($this->sacon_DateOfMarriage->GetValue(true));
        $this->DataSource->sacon_noChildB->SetValue($this->sacon_noChildB->GetValue(true));
        $this->DataSource->sacon_noChildG->SetValue($this->sacon_noChildG->GetValue(true));
        $this->DataSource->sacon_education->SetValue($this->sacon_education->GetValue(true));
        $this->DataSource->sacon_profession->SetValue($this->sacon_profession->GetValue(true));
        $this->DataSource->sacon_reference->SetValue($this->sacon_reference->GetValue(true));
        $this->DataSource->sacon_question->SetValue($this->sacon_question->GetValue(true));
        $this->DataSource->sacon_notes->SetValue($this->sacon_notes->GetValue(true));
        $this->DataSource->Update();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterUpdate", $this);
        return (!$this->CheckErrors());
    }
//End UpdateRow Method

//DeleteRow Method @36-299D98C3
    function DeleteRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeDelete", $this);
        if(!$this->DeleteAllowed) return false;
        $this->DataSource->Delete();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterDelete", $this);
        return (!$this->CheckErrors());
    }
//End DeleteRow Method

//Show Method @36-018C1211
    function Show()
    {
        global $CCSUseAmp;
        $Tpl = & CCGetTemplate($this);
        global $FileName;
        global $CCSLocales;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);


        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;
        if($this->EditMode) {
            if($this->DataSource->Errors->Count()){
                $this->Errors->AddErrors($this->DataSource->Errors);
                $this->DataSource->Errors->clear();
            }
            $this->DataSource->Open();
            if($this->DataSource->Errors->Count() == 0 && $this->DataSource->next_record()) {
                $this->DataSource->SetValues();
                if(!$this->FormSubmitted){
                    $this->sacon_registration->SetValue($this->DataSource->sacon_registration->GetValue());
                    $this->sacon_DTReg->SetValue($this->DataSource->sacon_DTReg->GetValue());
                    $this->sacon_Name->SetValue($this->DataSource->sacon_Name->GetValue());
                    $this->sacon_DOB->SetValue($this->DataSource->sacon_DOB->GetValue());
                    $this->sacon_TOB->SetValue($this->DataSource->sacon_TOB->GetValue());
                    $this->sacon_POB->SetValue($this->DataSource->sacon_POB->GetValue());
                    $this->sacon_phone->SetValue($this->DataSource->sacon_phone->GetValue());
                    $this->sacon_email->SetValue($this->DataSource->sacon_email->GetValue());
                    $this->sacon_address1->SetValue($this->DataSource->sacon_address1->GetValue());
                    $this->sacon_address2->SetValue($this->DataSource->sacon_address2->GetValue());
                    $this->sacon_DateOfMarriage->SetValue($this->DataSource->sacon_DateOfMarriage->GetValue());
                    $this->sacon_noChildB->SetValue($this->DataSource->sacon_noChildB->GetValue());
                    $this->sacon_noChildG->SetValue($this->DataSource->sacon_noChildG->GetValue());
                    $this->sacon_education->SetValue($this->DataSource->sacon_education->GetValue());
                    $this->sacon_profession->SetValue($this->DataSource->sacon_profession->GetValue());
                    $this->sacon_reference->SetValue($this->DataSource->sacon_reference->GetValue());
                    $this->sacon_question->SetValue($this->DataSource->sacon_question->GetValue());
                    $this->sacon_notes->SetValue($this->DataSource->sacon_notes->GetValue());
                }
            } else {
                $this->EditMode = false;
            }
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->sacon_registration->Errors->ToString());
            $Error = ComposeStrings($Error, $this->sacon_DTReg->Errors->ToString());
            $Error = ComposeStrings($Error, $this->sacon_Name->Errors->ToString());
            $Error = ComposeStrings($Error, $this->sacon_DOB->Errors->ToString());
            $Error = ComposeStrings($Error, $this->sacon_TOB->Errors->ToString());
            $Error = ComposeStrings($Error, $this->sacon_POB->Errors->ToString());
            $Error = ComposeStrings($Error, $this->sacon_phone->Errors->ToString());
            $Error = ComposeStrings($Error, $this->sacon_email->Errors->ToString());
            $Error = ComposeStrings($Error, $this->sacon_address1->Errors->ToString());
            $Error = ComposeStrings($Error, $this->sacon_address2->Errors->ToString());
            $Error = ComposeStrings($Error, $this->sacon_DateOfMarriage->Errors->ToString());
            $Error = ComposeStrings($Error, $this->sacon_noChildB->Errors->ToString());
            $Error = ComposeStrings($Error, $this->sacon_noChildG->Errors->ToString());
            $Error = ComposeStrings($Error, $this->sacon_education->Errors->ToString());
            $Error = ComposeStrings($Error, $this->sacon_profession->Errors->ToString());
            $Error = ComposeStrings($Error, $this->sacon_reference->Errors->ToString());
            $Error = ComposeStrings($Error, $this->sacon_question->Errors->ToString());
            $Error = ComposeStrings($Error, $this->sacon_notes->Errors->ToString());
            $Error = ComposeStrings($Error, $this->Errors->ToString());
            $Error = ComposeStrings($Error, $this->DataSource->Errors->ToString());
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $CCSForm = $this->EditMode ? $this->ComponentName . ":" . "Edit" : $this->ComponentName;
        $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $CCSForm);
        $Tpl->SetVar("Action", !$CCSUseAmp ? $this->HTMLFormAction : str_replace("&", "&amp;", $this->HTMLFormAction));
        $Tpl->SetVar("HTMLFormName", $this->ComponentName);
        $Tpl->SetVar("HTMLFormEnctype", $this->FormEnctype);
        $this->Button_Insert->Visible = !$this->EditMode && $this->InsertAllowed;
        $this->Button_Update->Visible = $this->EditMode && $this->UpdateAllowed;
        $this->Button_Delete->Visible = $this->EditMode && $this->DeleteAllowed;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        $this->Attributes->Show();
        if(!$this->Visible) {
            $Tpl->block_path = $ParentPath;
            return;
        }

        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->Button_Cancel->Show();
        $this->sacon_registration->Show();
        $this->sacon_DTReg->Show();
        $this->sacon_Name->Show();
        $this->sacon_DOB->Show();
        $this->sacon_TOB->Show();
        $this->sacon_POB->Show();
        $this->sacon_phone->Show();
        $this->sacon_email->Show();
        $this->sacon_address1->Show();
        $this->sacon_address2->Show();
        $this->sacon_DateOfMarriage->Show();
        $this->sacon_noChildB->Show();
        $this->sacon_noChildG->Show();
        $this->sacon_education->Show();
        $this->sacon_profession->Show();
        $this->sacon_reference->Show();
        $this->sacon_question->Show();
        $this->sacon_notes->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

} //End saconsult1 Class @36-FCB6E20C

class clssaconsult1DataSource extends clsDBConnection1 {  //saconsult1DataSource Class @36-3E7E422D

//DataSource Variables @36-7BD5B3E4
    public $Parent = "";
    public $CCSEvents = "";
    public $CCSEventResult;
    public $ErrorBlock;
    public $CmdExecution;

    public $InsertParameters;
    public $UpdateParameters;
    public $DeleteParameters;
    public $wp;
    public $AllParametersSet;

    public $InsertFields = array();
    public $UpdateFields = array();

    // Datasource fields
    public $sacon_registration;
    public $sacon_DTReg;
    public $sacon_Name;
    public $sacon_DOB;
    public $sacon_TOB;
    public $sacon_POB;
    public $sacon_phone;
    public $sacon_email;
    public $sacon_address1;
    public $sacon_address2;
    public $sacon_DateOfMarriage;
    public $sacon_noChildB;
    public $sacon_noChildG;
    public $sacon_education;
    public $sacon_profession;
    public $sacon_reference;
    public $sacon_question;
    public $sacon_notes;
//End DataSource Variables

//DataSourceClass_Initialize Event @36-C537FDC7
    function clssaconsult1DataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Record saconsult1/Error";
        $this->Initialize();
        $this->sacon_registration = new clsField("sacon_registration", ccsInteger, "");
        
        $this->sacon_DTReg = new clsField("sacon_DTReg", ccsDate, array("yyyy", "-", "mm", "-", "dd", " ", "H", ":", "nn", ":", "ss"));
        
        $this->sacon_Name = new clsField("sacon_Name", ccsText, "");
        
        $this->sacon_DOB = new clsField("sacon_DOB", ccsDate, array("yyyy", "-", "mm", "-", "dd"));
        
        $this->sacon_TOB = new clsField("sacon_TOB", ccsDate, array("HH", ":", "nn", ":", "ss"));
        
        $this->sacon_POB = new clsField("sacon_POB", ccsText, "");
        
        $this->sacon_phone = new clsField("sacon_phone", ccsText, "");
        
        $this->sacon_email = new clsField("sacon_email", ccsText, "");
        
        $this->sacon_address1 = new clsField("sacon_address1", ccsText, "");
        
        $this->sacon_address2 = new clsField("sacon_address2", ccsText, "");
        
        $this->sacon_DateOfMarriage = new clsField("sacon_DateOfMarriage", ccsDate, $this->DateFormat);
        
        $this->sacon_noChildB = new clsField("sacon_noChildB", ccsInteger, "");
        
        $this->sacon_noChildG = new clsField("sacon_noChildG", ccsInteger, "");
        
        $this->sacon_education = new clsField("sacon_education", ccsText, "");
        
        $this->sacon_profession = new clsField("sacon_profession", ccsText, "");
        
        $this->sacon_reference = new clsField("sacon_reference", ccsText, "");
        
        $this->sacon_question = new clsField("sacon_question", ccsMemo, "");
        
        $this->sacon_notes = new clsField("sacon_notes", ccsMemo, "");
        

        $this->InsertFields["sacon_registration"] = array("Name" => "sacon_registration", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["sacon_DTReg"] = array("Name" => "sacon_DTReg", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->InsertFields["sacon_Name"] = array("Name" => "sacon_Name", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["sacon_DOB"] = array("Name" => "sacon_DOB", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->InsertFields["sacon_TOB"] = array("Name" => "sacon_TOB", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->InsertFields["sacon_POB"] = array("Name" => "sacon_POB", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["sacon_phone"] = array("Name" => "sacon_phone", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["sacon_email"] = array("Name" => "sacon_email", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["sacon_address1"] = array("Name" => "sacon_address1", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["sacon_address2"] = array("Name" => "sacon_address2", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["sacon_DateOfMarriage"] = array("Name" => "sacon_DateOfMarriage", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->InsertFields["sacon_noChildB"] = array("Name" => "sacon_noChildB", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["sacon_noChildG"] = array("Name" => "sacon_noChildG", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["sacon_education"] = array("Name" => "sacon_education", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["sacon_profession"] = array("Name" => "sacon_profession", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["sacon_reference"] = array("Name" => "sacon_reference", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["sacon_question"] = array("Name" => "sacon_question", "Value" => "", "DataType" => ccsMemo, "OmitIfEmpty" => 1);
        $this->InsertFields["sacon_notes"] = array("Name" => "sacon_notes", "Value" => "", "DataType" => ccsMemo, "OmitIfEmpty" => 1);
        $this->UpdateFields["sacon_registration"] = array("Name" => "sacon_registration", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["sacon_DTReg"] = array("Name" => "sacon_DTReg", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->UpdateFields["sacon_Name"] = array("Name" => "sacon_Name", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["sacon_DOB"] = array("Name" => "sacon_DOB", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->UpdateFields["sacon_TOB"] = array("Name" => "sacon_TOB", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->UpdateFields["sacon_POB"] = array("Name" => "sacon_POB", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["sacon_phone"] = array("Name" => "sacon_phone", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["sacon_email"] = array("Name" => "sacon_email", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["sacon_address1"] = array("Name" => "sacon_address1", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["sacon_address2"] = array("Name" => "sacon_address2", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["sacon_DateOfMarriage"] = array("Name" => "sacon_DateOfMarriage", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->UpdateFields["sacon_noChildB"] = array("Name" => "sacon_noChildB", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["sacon_noChildG"] = array("Name" => "sacon_noChildG", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["sacon_education"] = array("Name" => "sacon_education", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["sacon_profession"] = array("Name" => "sacon_profession", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["sacon_reference"] = array("Name" => "sacon_reference", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["sacon_question"] = array("Name" => "sacon_question", "Value" => "", "DataType" => ccsMemo, "OmitIfEmpty" => 1);
        $this->UpdateFields["sacon_notes"] = array("Name" => "sacon_notes", "Value" => "", "DataType" => ccsMemo, "OmitIfEmpty" => 1);
    }
//End DataSourceClass_Initialize Event

//Prepare Method @36-ED398D8A
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlPkey", ccsInteger, "", "", $this->Parameters["urlPkey"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "Pkey", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = 
             $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @36-D478B841
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "SELECT * \n\n" .
        "FROM saconsult {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        $this->PageSize = 1;
        $this->query($this->OptimizeSQL(CCBuildSQL($this->SQL, $this->Where, $this->Order)));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @36-52D3665C
    function SetValues()
    {
        $this->sacon_registration->SetDBValue(trim($this->f("sacon_registration")));
        $this->sacon_DTReg->SetDBValue(trim($this->f("sacon_DTReg")));
        $this->sacon_Name->SetDBValue($this->f("sacon_Name"));
        $this->sacon_DOB->SetDBValue(trim($this->f("sacon_DOB")));
        $this->sacon_TOB->SetDBValue(trim($this->f("sacon_TOB")));
        $this->sacon_POB->SetDBValue($this->f("sacon_POB"));
        $this->sacon_phone->SetDBValue($this->f("sacon_phone"));
        $this->sacon_email->SetDBValue($this->f("sacon_email"));
        $this->sacon_address1->SetDBValue($this->f("sacon_address1"));
        $this->sacon_address2->SetDBValue($this->f("sacon_address2"));
        $this->sacon_DateOfMarriage->SetDBValue(trim($this->f("sacon_DateOfMarriage")));
        $this->sacon_noChildB->SetDBValue(trim($this->f("sacon_noChildB")));
        $this->sacon_noChildG->SetDBValue(trim($this->f("sacon_noChildG")));
        $this->sacon_education->SetDBValue($this->f("sacon_education"));
        $this->sacon_profession->SetDBValue($this->f("sacon_profession"));
        $this->sacon_reference->SetDBValue($this->f("sacon_reference"));
        $this->sacon_question->SetDBValue($this->f("sacon_question"));
        $this->sacon_notes->SetDBValue($this->f("sacon_notes"));
    }
//End SetValues Method

//Insert Method @36-B59E2328
    function Insert()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert", $this->Parent);
        $this->InsertFields["sacon_registration"]["Value"] = $this->sacon_registration->GetDBValue(true);
        $this->InsertFields["sacon_DTReg"]["Value"] = $this->sacon_DTReg->GetDBValue(true);
        $this->InsertFields["sacon_Name"]["Value"] = $this->sacon_Name->GetDBValue(true);
        $this->InsertFields["sacon_DOB"]["Value"] = $this->sacon_DOB->GetDBValue(true);
        $this->InsertFields["sacon_TOB"]["Value"] = $this->sacon_TOB->GetDBValue(true);
        $this->InsertFields["sacon_POB"]["Value"] = $this->sacon_POB->GetDBValue(true);
        $this->InsertFields["sacon_phone"]["Value"] = $this->sacon_phone->GetDBValue(true);
        $this->InsertFields["sacon_email"]["Value"] = $this->sacon_email->GetDBValue(true);
        $this->InsertFields["sacon_address1"]["Value"] = $this->sacon_address1->GetDBValue(true);
        $this->InsertFields["sacon_address2"]["Value"] = $this->sacon_address2->GetDBValue(true);
        $this->InsertFields["sacon_DateOfMarriage"]["Value"] = $this->sacon_DateOfMarriage->GetDBValue(true);
        $this->InsertFields["sacon_noChildB"]["Value"] = $this->sacon_noChildB->GetDBValue(true);
        $this->InsertFields["sacon_noChildG"]["Value"] = $this->sacon_noChildG->GetDBValue(true);
        $this->InsertFields["sacon_education"]["Value"] = $this->sacon_education->GetDBValue(true);
        $this->InsertFields["sacon_profession"]["Value"] = $this->sacon_profession->GetDBValue(true);
        $this->InsertFields["sacon_reference"]["Value"] = $this->sacon_reference->GetDBValue(true);
        $this->InsertFields["sacon_question"]["Value"] = $this->sacon_question->GetDBValue(true);
        $this->InsertFields["sacon_notes"]["Value"] = $this->sacon_notes->GetDBValue(true);
        $this->SQL = CCBuildInsert("saconsult", $this->InsertFields, $this);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert", $this->Parent);
        }
    }
//End Insert Method

//Update Method @36-FFDACE50
    function Update()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $Where = "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate", $this->Parent);
        $this->UpdateFields["sacon_registration"]["Value"] = $this->sacon_registration->GetDBValue(true);
        $this->UpdateFields["sacon_DTReg"]["Value"] = $this->sacon_DTReg->GetDBValue(true);
        $this->UpdateFields["sacon_Name"]["Value"] = $this->sacon_Name->GetDBValue(true);
        $this->UpdateFields["sacon_DOB"]["Value"] = $this->sacon_DOB->GetDBValue(true);
        $this->UpdateFields["sacon_TOB"]["Value"] = $this->sacon_TOB->GetDBValue(true);
        $this->UpdateFields["sacon_POB"]["Value"] = $this->sacon_POB->GetDBValue(true);
        $this->UpdateFields["sacon_phone"]["Value"] = $this->sacon_phone->GetDBValue(true);
        $this->UpdateFields["sacon_email"]["Value"] = $this->sacon_email->GetDBValue(true);
        $this->UpdateFields["sacon_address1"]["Value"] = $this->sacon_address1->GetDBValue(true);
        $this->UpdateFields["sacon_address2"]["Value"] = $this->sacon_address2->GetDBValue(true);
        $this->UpdateFields["sacon_DateOfMarriage"]["Value"] = $this->sacon_DateOfMarriage->GetDBValue(true);
        $this->UpdateFields["sacon_noChildB"]["Value"] = $this->sacon_noChildB->GetDBValue(true);
        $this->UpdateFields["sacon_noChildG"]["Value"] = $this->sacon_noChildG->GetDBValue(true);
        $this->UpdateFields["sacon_education"]["Value"] = $this->sacon_education->GetDBValue(true);
        $this->UpdateFields["sacon_profession"]["Value"] = $this->sacon_profession->GetDBValue(true);
        $this->UpdateFields["sacon_reference"]["Value"] = $this->sacon_reference->GetDBValue(true);
        $this->UpdateFields["sacon_question"]["Value"] = $this->sacon_question->GetDBValue(true);
        $this->UpdateFields["sacon_notes"]["Value"] = $this->sacon_notes->GetDBValue(true);
        $this->SQL = CCBuildUpdate("saconsult", $this->UpdateFields, $this);
        $this->SQL .= strlen($this->Where) ? " WHERE " . $this->Where : $this->Where;
        if (!strlen($this->Where) && $this->Errors->Count() == 0) 
            $this->Errors->addError($CCSLocales->GetText("CCS_CustomOperationError_MissingParameters"));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate", $this->Parent);
        }
    }
//End Update Method

//Delete Method @36-B5E6EB36
    function Delete()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $Where = "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete", $this->Parent);
        $this->SQL = "DELETE FROM saconsult";
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        if (!strlen($this->Where) && $this->Errors->Count() == 0) 
            $this->Errors->addError($CCSLocales->GetText("CCS_CustomOperationError_MissingParameters"));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete", $this->Parent);
        }
    }
//End Delete Method

} //End saconsult1DataSource Class @36-FCB6E20C

//Initialize Page @1-376BCCA3
// Variables
$FileName = "";
$Redirect = "";
$Tpl = "";
$TemplateFileName = "";
$BlockToParse = "";
$ComponentName = "";
$Attributes = "";

// Events;
$CCSEvents = "";
$CCSEventResult = "";
$TemplateSource = "";

$FileName = FileName;
$Redirect = "";
$TemplateFileName = "soniacon.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$ContentType = "text/html";
$PathToRoot = "./";
$Charset = $Charset ? $Charset : "windows-1252";
//End Initialize Page

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-9ADDCCD6
$DBConnection1 = new clsDBConnection1();
$MainPage->Connections["Connection1"] = & $DBConnection1;
$Attributes = new clsAttributes("page:");
$Attributes->SetValue("pathToRoot", $PathToRoot);
$MainPage->Attributes = & $Attributes;

// Controls
$saconsult = new clsGridsaconsult("", $MainPage);
$saconsultSearch = new clsRecordsaconsultSearch("", $MainPage);
$saconsult1 = new clsRecordsaconsult1("", $MainPage);
$MainPage->saconsult = & $saconsult;
$MainPage->saconsultSearch = & $saconsultSearch;
$MainPage->saconsult1 = & $saconsult1;
$saconsult->Initialize();
$saconsult1->Initialize();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize", $MainPage);

if ($Charset) {
    header("Content-Type: " . $ContentType . "; charset=" . $Charset);
} else {
    header("Content-Type: " . $ContentType);
}
//End Initialize Objects

//Initialize HTML Template @1-FA3E6D4A
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView", $MainPage);
$Tpl = new clsTemplate($FileEncoding, $TemplateEncoding);
if (strlen($TemplateSource)) {
    $Tpl->LoadTemplateFromStr($TemplateSource, $BlockToParse, "CP1252");
} else {
    $Tpl->LoadTemplate(PathToCurrentPage . $TemplateFileName, $BlockToParse, "CP1252");
}
$Tpl->SetVar("CCS_PathToRoot", $PathToRoot);
$Tpl->block_path = "/$BlockToParse";
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow", $MainPage);
$Attributes->SetValue("pathToRoot", "");
$Attributes->Show();
//End Initialize HTML Template

//Execute Components @1-5EA99150
$saconsult1->Operation();
$saconsultSearch->Operation();
//End Execute Components

//Go to destination page @1-8A1B7B49
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBConnection1->close();
    header("Location: " . $Redirect);
    unset($saconsult);
    unset($saconsultSearch);
    unset($saconsult1);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-E0E4F5EE
$saconsult->Show();
$saconsultSearch->Show();
$saconsult1->Show();
$Tpl->block_path = "";
$Tpl->Parse($BlockToParse, false);
if (!isset($main_block)) $main_block = $Tpl->GetVar($BlockToParse);
$main_block = CCConvertEncoding($main_block, $FileEncoding, $TemplateEncoding);
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-35F19C99
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBConnection1->close();
unset($saconsult);
unset($saconsultSearch);
unset($saconsult1);
unset($Tpl);
//End Unload Page


?>
