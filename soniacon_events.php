<?php
//BindEvents Method @1-34834DD8
function BindEvents()
{
    global $saconsult1;
    $saconsult1->Button_Insert->CCSEvents["OnClick"] = "saconsult1_Button_Insert_OnClick";
}
//End BindEvents Method

//saconsult1_Button_Insert_OnClick @38-1EAE01CB
function saconsult1_Button_Insert_OnClick(& $sender)
{
    $saconsult1_Button_Insert_OnClick = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $saconsult1; //Compatibility
//End saconsult1_Button_Insert_OnClick

//Custom Code @66-2A29BDB7
// -------------------------
    // Write your own code here.
// -------------------------
        ini_set("SMTP", "localhost");
        // email code
        //$from_email = "lalitnayyar@gmail.com";
        
        
        // Sender details
        
        $to =  $saconsult->sacon_email->GetText();
        //$cc = "sonianayyar02@gmail.com";
        $bcc = "lalitnayyar@gmail.com";
        $from = "sonianayyar02@gmail.com";
        
        // email body
        
      
        $subject = "AutoGen!!Added New Clinet in System http://www.samridhiastrology.com Client: ". $saconsult->sacon_Name->GetText();
		$message = "Registration No       : " . $saconsult->sacon_registration->GetText().", \r\n\n";
		$message .= "Date of Registration : " . $saconsult->sacon_DTReg->GetText().", \r\n\n";
		$message .= "Client Name          : " . $saconsult->sacon_Name->GetText().", \r\n\n";
		$message .= "Date of Birth        : " . $saconsult->sacon_DOB->GetText().", \r\n\n";
		$message .= "Place of Birth       : " . $saconsult->sacon_POB->GetText().", \r\n\n";
		$message .= "Contact Number       : " . $saconsult->sacon_phone->GetText().", \r\n\n";
		$message .= "Email                : " . $saconsult->sacon_email->GetText().", \r\n\n";
		$message .= "Client Name          : " . $saconsult->sacon_address1->GetText().", \r\n\n";
		$message .= "Client Address1      : " . $saconsult->sacon_address2->GetText().", \r\n\n";
		$message .= "Client Address2      : " . $saconsult->sacon_DateOfMarriage->GetText().", \r\n\n";
		$message .= "Childs               : Boy" . $saconsult->sacon_noChildB->GetText()."Girl". $saconsult->sacon_noChildG->GetText().", \r\n\n";
		$message .= "Client Profession    : " . $saconsult->sacon_profession->GetText().", \r\n\n";
		$message .= "Client Reference     : " . $saconsult->sacon_reference->GetText().", \r\n\n";
		$message .= "Client Question      : " . $saconsult->sacon_question->GetText().", \r\n\n";
		$message .= "\r\n";
		$message .= "***** AutoGemerated Message ***** \r\n";
		$message .= "http://www.samridhiastrology.com \r\n";
		$charset = "Windows-1252";
		$transferEncoding = "";
		$additional_headers = "From: $from\nReply-To: $from\nContent-Type: text/plain; charset=$charset\nContent-Transfer-Encoding: $transferEncoding";
		$additional_headers .= "BCC:".$bcc."\r\n";
		//$additional_headers .= "CC:".$cc."\r\n";
		mail ($to,$subject, $message, $additional_headers);
        //mail($to_email,$subject,$message,$headers);
        // end email code
        ini_restore("SMTP");

//End Custom Code


//End Custom Code

//Close saconsult1_Button_Insert_OnClick @38-F71B401A
    return $saconsult1_Button_Insert_OnClick;
}
//End Close saconsult1_Button_Insert_OnClick


?>
