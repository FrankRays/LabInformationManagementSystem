<?php
class PHPSendMail
{
	var $boundary = "345894369383";
	var $header = " ";
	var $body = " ";
	var $maillist = " ";

	//
	function buildheader($from,$to,$cc="",$bcc="")
	{
		$this->header = "Content-type: multipart/mixed; boundary = $this->boundary\r\n";
		$this->header .= "To:$to\r\n";
		$this->header .= "From:$from\r\n";
		$this->header .= "Cc:$cc\r\n";
		$this->header .= "Bcc:$bcc\r\n";
	}
	function buildmaillist($to,$cc=" ",$bcc=" ")
	{
		if($to != " ")
		{
			$this->maillist .= $to;
		}
		if($cc != " ")
		{
			$this->maillist .= ", ";
			$this->maillist .= $cc;
		}
		if($bcc != " ")
		{
			$this->maillist .= ", ";
			$this->maillist .= $bcc;
		}
	}
	function buildbody($htmltext = " ",$attachment=null)
	{
		if($htmltext != " ")
		{
			$this->body .= "--$boundary Content-type: text/html;charset=utf-8 Content-transfer-encoding:8bit $htmltext";
		}
		//
		if($attachment != null)
		{
			if($htmltext != " ")
			{
				$this->body .= "--$this->boundary";
			}
			$file = $attachment["tmp_name"];
			//
			$mimetype = $attachment["type"];
			//
			$filename = $attachment["name"];
			//
			$fp = fopen($file , "r");
			$read = fread($fp , filesize($file)); //
			$read = base64_encode($read); //
			$read = chunk_split($read); //
			$body .= "Content-type: $mimetype; name=$filename Content-disposition: $attachment; filename=$filename Content-transfer-encoding: base64 $read";
		}
		//
		$this->body .= "--$this->boundary";
	}

	function send($from ,$to ,$cc=" " ,$bcc=" " ,$subject=" " ,$htmltext=" " ,$attachment = null )
	{
		$this->buildheader($from,$to,$cc,$bcc);
		$this->buildmaillist($to,$cc,$bcc);
		$this->buildbody($htmltext,$attachment);
		//
		if(mail($this->maillist,$subject,$this->body,$this->header))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}
?>