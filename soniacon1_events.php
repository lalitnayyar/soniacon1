<?php
//BindEvents Method @1-C776D2A1
function BindEvents()
{
    global $saconsult;
    $saconsult->CCSEvents["BeforeShowRow"] = "saconsult_BeforeShowRow";
}
//End BindEvents Method

//saconsult_BeforeShowRow @6-95C1ECAB
function saconsult_BeforeShowRow(& $sender)
{
    $saconsult_BeforeShowRow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $saconsult; //Compatibility
//End saconsult_BeforeShowRow

//Gallery Layout @9-6715D311
    $NumberOfColumns = $Component->Attributes->GetText("numberOfColumns");
    if (isset($Component->RowOpenTag))
        $Component->RowOpenTag->Visible = ($Component->RowNumber % $NumberOfColumns) == 1;
    if (isset($Component->AltRowOpenTag))
        $Component->AltRowOpenTag->Visible = ($Component->RowNumber % $NumberOfColumns) == 1;
    if (isset($Component->RowCloseTag))
        $Component->RowCloseTag->Visible = (($Component->RowNumber % $NumberOfColumns) == 0);
    if (isset($Component->AltRowCloseTag))
        $Component->AltRowCloseTag->Visible = (($Component->RowNumber % $NumberOfColumns) == 0);
    if (isset($Component->RowComponents))
        $Component->RowComponents->Visible = !$Component->ForceIteration;
    if (isset($Component->AltRowComponents))
        $Component->AltRowComponents->Visible = !$Component->ForceIteration;
    $Component->ForceIteration = (($Component->RowNumber >= $Component->PageSize) || !$Component->DataSource->has_next_record()) && ($Component->RowNumber % $NumberOfColumns);
//End Gallery Layout

//Close saconsult_BeforeShowRow @6-00D95AF5
    return $saconsult_BeforeShowRow;
}
//End Close saconsult_BeforeShowRow


?>
