<?php
//BindEvents Method @1-FBD7E53D
function BindEvents()
{
    global $saconsult;
    $saconsult->CCSEvents["AfterInsert"] = "saconsult_AfterInsert";
}
//End BindEvents Method

//saconsult_AfterInsert @6-8A56B6F9
function saconsult_AfterInsert(& $sender)
{
    $saconsult_AfterInsert = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $saconsult; //Compatibility
//End saconsult_AfterInsert

//Custom Code @20-2A29BDB7
// -------------------------
    // Write your own code here.
// -------------------------
        ini_set("SMTP", "localhost");
        // email code
        //$from_email = "lalitnayyar@gmail.com";
        
        
        // Sender details
        
        $to =  $saconsult->sacon_email->GetText();
        $cc = "sonianayyar02@gmail.com";
        $bcc = "lalitnayyar@gmail.com";
        $from = "sonianayyar02@gmail.com";
        
        // email body
        
        $subject = "Request received on http://www.samridhiastrology.com";
		$message = "Hello " . $saconsult->sacon_Name->GetText().", \r\n\n";
		$message .= "\r\n";
		$message .= "Query :  ".$saconsult->sacon_question->GetText()."\r\n\n";
		$message .= "Date Of Birth  ".$saconsult->sacon_DOB->GetText()."\r\n\n";
		$message .= "Time Of Birth  ".$saconsult->sacon_TOB->GetText()."\r\n\n";
		$message .= "Place Of Birth ".$saconsult->sacon_POB->GetText()."\r\n\n";
		$message .= "\r\n";
		$message .= "We will revert back to you in next 4 hours. \r\n";
		$message .= "\r\n";
		$message .= "Thanks \r\n";
		$message .= "Sonia Nayyar \r\n";
		$message .= "Celebrity Astrologer \r\n";
		$message .= "http://www.samridhiastrology.com \r\n";
		$charset = "Windows-1252";
		$transferEncoding = "";
		$additional_headers = "From: $from\nReply-To: $from\nContent-Type: text/plain; charset=$charset\nContent-Transfer-Encoding: $transferEncoding";
		$additional_headers .= "BCC:".$bcc."\r\n";
		$additional_headers .= "CC:".$cc."\r\n";
		mail ($to,$subject, $message, $additional_headers);
        //mail($to_email,$subject,$message,$headers);
        // end email code
        ini_restore("SMTP");

//End Custom Code

//Close saconsult_AfterInsert @6-B370721C
    return $saconsult_AfterInsert;
}
//End Close saconsult_AfterInsert


?>
