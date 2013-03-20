<?php
//Include Common Files @1-2E9D03B8
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "contactForm.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

class clsRecordsaconsult { //saconsult Class @6-E1436864

//Variables @6-9E315808

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

//Class_Initialize Event @6-075DB5A2
    function clsRecordsaconsult($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record saconsult/Error";
        $this->DataSource = new clssaconsultDataSource($this);
        $this->ds = & $this->DataSource;
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "saconsult";
            $this->Attributes = new clsAttributes($this->ComponentName . ":");
            $CCSForm = explode(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->sacon_DOB = new clsControl(ccsTextBox, "sacon_DOB", "Sacon DOB", ccsDate, array("yyyy", "-", "mm", "-", "dd"), CCGetRequestParam("sacon_DOB", $Method, NULL), $this);
            $this->sacon_TOB = new clsControl(ccsTextBox, "sacon_TOB", "Sacon TOB", ccsDate, array("HH", ":", "nn", ":", "ss"), CCGetRequestParam("sacon_TOB", $Method, NULL), $this);
            $this->sacon_POB = new clsControl(ccsTextBox, "sacon_POB", "Sacon POB", ccsText, "", CCGetRequestParam("sacon_POB", $Method, NULL), $this);
            $this->sacon_email = new clsControl(ccsTextBox, "sacon_email", "Sacon Email", ccsText, "", CCGetRequestParam("sacon_email", $Method, NULL), $this);
            $this->sacon_question = new clsControl(ccsTextBox, "sacon_question", "Sacon Question", ccsText, "", CCGetRequestParam("sacon_question", $Method, NULL), $this);
            $this->sacon_Name = new clsControl(ccsTextBox, "sacon_Name", "sacon_Name", ccsText, "", CCGetRequestParam("sacon_Name", $Method, NULL), $this);
            $this->sacon_Name->Required = true;
            $this->Button_Update = new clsButton("Button_Update", $Method, $this);
            $this->Button_Insert = new clsButton("Button_Insert", $Method, $this);
            if(!$this->FormSubmitted) {
                if(!is_array($this->sacon_DOB->Value) && !strlen($this->sacon_DOB->Value) && $this->sacon_DOB->Value !== false)
                    $this->sacon_DOB->SetValue(time());
                if(!is_array($this->sacon_TOB->Value) && !strlen($this->sacon_TOB->Value) && $this->sacon_TOB->Value !== false)
                    $this->sacon_TOB->SetValue(time());
            }
        }
    }
//End Class_Initialize Event

//Initialize Method @6-D26BB444
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->DataSource->Parameters["urlPkey"] = CCGetFromGet("Pkey", NULL);
    }
//End Initialize Method

//Validate Method @6-D6D69074
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        if(strlen($this->sacon_email->GetText()) && !preg_match ("/^[\w\.-]{1,}\@([\da-zA-Z-]{1,}\.){1,}[\da-zA-Z-]+$/", $this->sacon_email->GetText())) {
            $this->sacon_email->Errors->addError($CCSLocales->GetText("CCS_MaskValidation", "Sacon Email"));
        }
        $Validation = ($this->sacon_DOB->Validate() && $Validation);
        $Validation = ($this->sacon_TOB->Validate() && $Validation);
        $Validation = ($this->sacon_POB->Validate() && $Validation);
        $Validation = ($this->sacon_email->Validate() && $Validation);
        $Validation = ($this->sacon_question->Validate() && $Validation);
        $Validation = ($this->sacon_Name->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->sacon_DOB->Errors->Count() == 0);
        $Validation =  $Validation && ($this->sacon_TOB->Errors->Count() == 0);
        $Validation =  $Validation && ($this->sacon_POB->Errors->Count() == 0);
        $Validation =  $Validation && ($this->sacon_email->Errors->Count() == 0);
        $Validation =  $Validation && ($this->sacon_question->Errors->Count() == 0);
        $Validation =  $Validation && ($this->sacon_Name->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @6-BC93C3F6
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->sacon_DOB->Errors->Count());
        $errors = ($errors || $this->sacon_TOB->Errors->Count());
        $errors = ($errors || $this->sacon_POB->Errors->Count());
        $errors = ($errors || $this->sacon_email->Errors->Count());
        $errors = ($errors || $this->sacon_question->Errors->Count());
        $errors = ($errors || $this->sacon_Name->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->DataSource->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @6-53630184
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
            if($this->Button_Update->Pressed) {
                $this->PressedButton = "Button_Update";
            } else if($this->Button_Insert->Pressed) {
                $this->PressedButton = "Button_Insert";
            }
        }
        $Redirect = ServerURL . "" . $FileName . "?" . CCGetQueryString("QueryString", array("ccsForm"));
        if($this->Validate()) {
            if($this->PressedButton == "Button_Update") {
                if(!CCGetEvent($this->Button_Update->CCSEvents, "OnClick", $this->Button_Update) || !$this->UpdateRow()) {
                    $Redirect = "";
                }
            } else if($this->PressedButton == "Button_Insert") {
                $Redirect = ServerURL . "" . "thankyou.php" . "?" . CCGetQueryString("QueryString", array("ccsForm"));
                if(!CCGetEvent($this->Button_Insert->CCSEvents, "OnClick", $this->Button_Insert) || !$this->InsertRow()) {
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

//InsertRow Method @6-E9D7F5E6
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert", $this);
        if(!$this->InsertAllowed) return false;
        $this->DataSource->sacon_DOB->SetValue($this->sacon_DOB->GetValue(true));
        $this->DataSource->sacon_TOB->SetValue($this->sacon_TOB->GetValue(true));
        $this->DataSource->sacon_POB->SetValue($this->sacon_POB->GetValue(true));
        $this->DataSource->sacon_email->SetValue($this->sacon_email->GetValue(true));
        $this->DataSource->sacon_question->SetValue($this->sacon_question->GetValue(true));
        $this->DataSource->sacon_Name->SetValue($this->sacon_Name->GetValue(true));
        $this->DataSource->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert", $this);
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @6-E485F016
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate", $this);
        if(!$this->UpdateAllowed) return false;
        $this->DataSource->sacon_DOB->SetValue($this->sacon_DOB->GetValue(true));
        $this->DataSource->sacon_TOB->SetValue($this->sacon_TOB->GetValue(true));
        $this->DataSource->sacon_POB->SetValue($this->sacon_POB->GetValue(true));
        $this->DataSource->sacon_email->SetValue($this->sacon_email->GetValue(true));
        $this->DataSource->sacon_question->SetValue($this->sacon_question->GetValue(true));
        $this->DataSource->sacon_Name->SetValue($this->sacon_Name->GetValue(true));
        $this->DataSource->Update();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterUpdate", $this);
        return (!$this->CheckErrors());
    }
//End UpdateRow Method

//Show Method @6-DC1AB048
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
                    $this->sacon_DOB->SetValue($this->DataSource->sacon_DOB->GetValue());
                    $this->sacon_TOB->SetValue($this->DataSource->sacon_TOB->GetValue());
                    $this->sacon_POB->SetValue($this->DataSource->sacon_POB->GetValue());
                    $this->sacon_email->SetValue($this->DataSource->sacon_email->GetValue());
                    $this->sacon_question->SetValue($this->DataSource->sacon_question->GetValue());
                    $this->sacon_Name->SetValue($this->DataSource->sacon_Name->GetValue());
                }
            } else {
                $this->EditMode = false;
            }
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->sacon_DOB->Errors->ToString());
            $Error = ComposeStrings($Error, $this->sacon_TOB->Errors->ToString());
            $Error = ComposeStrings($Error, $this->sacon_POB->Errors->ToString());
            $Error = ComposeStrings($Error, $this->sacon_email->Errors->ToString());
            $Error = ComposeStrings($Error, $this->sacon_question->Errors->ToString());
            $Error = ComposeStrings($Error, $this->sacon_Name->Errors->ToString());
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
        $this->Button_Update->Visible = $this->EditMode && $this->UpdateAllowed;
        $this->Button_Insert->Visible = !$this->EditMode && $this->InsertAllowed;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        $this->Attributes->Show();
        if(!$this->Visible) {
            $Tpl->block_path = $ParentPath;
            return;
        }

        $this->sacon_DOB->Show();
        $this->sacon_TOB->Show();
        $this->sacon_POB->Show();
        $this->sacon_email->Show();
        $this->sacon_question->Show();
        $this->sacon_Name->Show();
        $this->Button_Update->Show();
        $this->Button_Insert->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

} //End saconsult Class @6-FCB6E20C

class clssaconsultDataSource extends clsDBConnection1 {  //saconsultDataSource Class @6-30B55BFD

//DataSource Variables @6-16001DE7
    public $Parent = "";
    public $CCSEvents = "";
    public $CCSEventResult;
    public $ErrorBlock;
    public $CmdExecution;

    public $InsertParameters;
    public $UpdateParameters;
    public $wp;
    public $AllParametersSet;

    public $InsertFields = array();
    public $UpdateFields = array();

    // Datasource fields
    public $sacon_DOB;
    public $sacon_TOB;
    public $sacon_POB;
    public $sacon_email;
    public $sacon_question;
    public $sacon_Name;
//End DataSource Variables

//DataSourceClass_Initialize Event @6-35E5E294
    function clssaconsultDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Record saconsult/Error";
        $this->Initialize();
        $this->sacon_DOB = new clsField("sacon_DOB", ccsDate, array("yyyy", "-", "mm", "-", "dd"));
        
        $this->sacon_TOB = new clsField("sacon_TOB", ccsDate, array("HH", ":", "nn", ":", "ss"));
        
        $this->sacon_POB = new clsField("sacon_POB", ccsText, "");
        
        $this->sacon_email = new clsField("sacon_email", ccsText, "");
        
        $this->sacon_question = new clsField("sacon_question", ccsText, "");
        
        $this->sacon_Name = new clsField("sacon_Name", ccsText, "");
        

        $this->InsertFields["sacon_DOB"] = array("Name" => "sacon_DOB", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->InsertFields["sacon_TOB"] = array("Name" => "sacon_TOB", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->InsertFields["sacon_POB"] = array("Name" => "sacon_POB", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["sacon_email"] = array("Name" => "sacon_email", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["sacon_question"] = array("Name" => "sacon_question", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["sacon_Name"] = array("Name" => "sacon_Name", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["sacon_DOB"] = array("Name" => "sacon_DOB", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->UpdateFields["sacon_TOB"] = array("Name" => "sacon_TOB", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->UpdateFields["sacon_POB"] = array("Name" => "sacon_POB", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["sacon_email"] = array("Name" => "sacon_email", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["sacon_question"] = array("Name" => "sacon_question", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["sacon_Name"] = array("Name" => "sacon_Name", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
    }
//End DataSourceClass_Initialize Event

//Prepare Method @6-ED398D8A
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

//Open Method @6-D478B841
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

//SetValues Method @6-908808A1
    function SetValues()
    {
        $this->sacon_DOB->SetDBValue(trim($this->f("sacon_DOB")));
        $this->sacon_TOB->SetDBValue(trim($this->f("sacon_TOB")));
        $this->sacon_POB->SetDBValue($this->f("sacon_POB"));
        $this->sacon_email->SetDBValue($this->f("sacon_email"));
        $this->sacon_question->SetDBValue($this->f("sacon_question"));
        $this->sacon_Name->SetDBValue($this->f("sacon_Name"));
    }
//End SetValues Method

//Insert Method @6-1373460B
    function Insert()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert", $this->Parent);
        $this->InsertFields["sacon_DOB"]["Value"] = $this->sacon_DOB->GetDBValue(true);
        $this->InsertFields["sacon_TOB"]["Value"] = $this->sacon_TOB->GetDBValue(true);
        $this->InsertFields["sacon_POB"]["Value"] = $this->sacon_POB->GetDBValue(true);
        $this->InsertFields["sacon_email"]["Value"] = $this->sacon_email->GetDBValue(true);
        $this->InsertFields["sacon_question"]["Value"] = $this->sacon_question->GetDBValue(true);
        $this->InsertFields["sacon_Name"]["Value"] = $this->sacon_Name->GetDBValue(true);
        $this->SQL = CCBuildInsert("saconsult", $this->InsertFields, $this);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert", $this->Parent);
        }
    }
//End Insert Method

//Update Method @6-4DA2E0E1
    function Update()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $Where = "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate", $this->Parent);
        $this->UpdateFields["sacon_DOB"]["Value"] = $this->sacon_DOB->GetDBValue(true);
        $this->UpdateFields["sacon_TOB"]["Value"] = $this->sacon_TOB->GetDBValue(true);
        $this->UpdateFields["sacon_POB"]["Value"] = $this->sacon_POB->GetDBValue(true);
        $this->UpdateFields["sacon_email"]["Value"] = $this->sacon_email->GetDBValue(true);
        $this->UpdateFields["sacon_question"]["Value"] = $this->sacon_question->GetDBValue(true);
        $this->UpdateFields["sacon_Name"]["Value"] = $this->sacon_Name->GetDBValue(true);
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

} //End saconsultDataSource Class @6-FCB6E20C

//Initialize Page @1-3A0C6AE6
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
$TemplateFileName = "contactForm.html";
$BlockToParse = "main";
$TemplateEncoding = "CP1252";
$ContentType = "text/html";
$PathToRoot = "./";
$Charset = $Charset ? $Charset : "windows-1252";
//End Initialize Page

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-CBD7C192
$DBConnection1 = new clsDBConnection1();
$MainPage->Connections["Connection1"] = & $DBConnection1;
$Attributes = new clsAttributes("page:");
$Attributes->SetValue("pathToRoot", $PathToRoot);
$MainPage->Attributes = & $Attributes;

// Controls
$saconsult = new clsRecordsaconsult("", $MainPage);
$MainPage->saconsult = & $saconsult;
$saconsult->Initialize();

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

//Execute Components @1-FEE005AC
$saconsult->Operation();
//End Execute Components

//Go to destination page @1-ACE905C1
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBConnection1->close();
    header("Location: " . $Redirect);
    unset($saconsult);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-B66B29EA
$saconsult->Show();
$Tpl->block_path = "";
$Tpl->Parse($BlockToParse, false);
if (!isset($main_block)) $main_block = $Tpl->GetVar($BlockToParse);
$main_block = CCConvertEncoding($main_block, $FileEncoding, $TemplateEncoding);
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-96A3847A
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBConnection1->close();
unset($saconsult);
unset($Tpl);
//End Unload Page


?>
