

I have created an PHP Mailer for sending form inputs to my email adress.
For some reason the Email is send properly but it is not arriving at my inbox / spam.


<?php 
error_reporting(E_ALL ^ E_NOTICE); 


if(isset($_POST['submitted'])) {


    if(trim($_POST['contactName']) === '') {
        $nameError =  'Sie haben ihren Namen vergessen.'; 
        $hasError = true;
    } else {
        $name = trim($_POST['contactName']);
    }

    if(trim($_POST['betreff']) === '') {
        $betrError = 'Sie haben ihr Betreff - Anliegen vergessen.'; 
        $hasError = true;
    } else {
        $betreff = trim($_POST['betreff']);
    }

    $telefon = trim($_POST['telefon']);
    $company = trim($_POST['company']);


    if(trim($_POST['email']) === '')  {
        $emailError = 'Sie haben ihre Email Adresse vergessen.';
        $hasError = true;
    } else if (!preg_match("/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/i", trim($_POST['email']))) {
        $emailError = 'Sie haben ihre Email Adresse falsch eingegeben.';
        $hasError = true;
    } else {
        $email = trim($_POST['email']);
    }


    if(trim($_POST['comments']) === '') {
        $commentError = 'Sie haben ihre Nachricht vergessen.';
        $hasError = true;
    } else {
        if(function_exists('stripslashes')) {
            $comments = utf8_encode(stripslashes(trim($_POST['comments'])));
        } else {
            $comments = trim($_POST['comments']);
        }
    }


    if(!isset($hasError)) {

        $emailTo = 'email@email.de';
        $subject = ' - '.$name.' - '.$betreff;
        $sendCopy = trim($_POST['sendCopy']);
        $body = "\n\nDies ist eine Email von ihrem Kontakt Formular auf der Webseite http://www.test.de /\nAlle uebermittelten Daten des Benutzers durch das Formular finden sie unterhalb dieses Textes.\n\nFirma - Unternehmen : $company\n\nName - Ansprechpartner : $name \n\nEmail-Adresse : $email \n\nTelefon-Nr. : $telefon \n\nBetreff - Anliegen : $betreff\n\nNachricht des Nutzers: $comments\n\n";
        $headers = "From: $email\r\nReply-To: $email\r\nReturn-Path: $email\r\n";

        mail($emailTo, $subject, $body, $headers);



        $emailSent = true;
    }
}
?>

This is the code for the PHP Handling.


<?php if(isset($emailSent) && $emailSent == true) { ?>
<span class="okay">Ihre E-Mail wurde erfolgreich Ãœbermittelt. Vielen Dank fÃ¼r Ihr Interesse.</span><br><br>
<?php } else { ?>
<?php if(isset($hasError) || isset($captchaError) ) { ?>
 <span class="failed">Fehler bei der Ãœbertragung der E-Mail! Bitte prÃ¼fen Sie Ihre Eingaben.</span><br><br>
<?php } ?>


<form style="margin-left:50px;" action="contact.php" method="post"> 
<label class="screen-reader-text label">- Firma -</label>
<br>
<input type="text" size="30" name="company" id="company" value="<?php if(isset($_POST['company'])) echo $_POST['comnpany'];?>" class="formblock" placeholder="Ihr Unternehmen">
<label class="screen-reader-text label">- Name - <strong class="error">*</strong></label>
<br>
<?php if($nameError != '') { ?>
<span class="error"><?php echo $nameError;?></span>
<?php } ?>
<input type="text" size="30" name="contactName" id="contactName" value="<?php if(isset($_POST['contactName'])) echo $_POST['contactName'];?>" class="formblock" placeholder="Name - Ansprechpartner">
<label class="screen-reader-text label">- Betreff - <strong class="error">*</strong></label>
<br>
<?php if($betrError != '') { ?>
<span class="error"><?php echo $betrError;?></span>
<?php } ?>
<input type="text" size="30" name="betreff" id="betreff" value="<?php if(isset($_POST['betreff'])) echo $_POST['betreff'];?>" class="formblock" placeholder="Hier ihr Betreff - Anliegen">
<label class="screen-reader-text label">- Telefon -</label>
<br>
<input type="text" size="30" name="telefon" id="telefon" value="<?php if(isset($_POST['telefon'])) echo $_POST['telefon'];?>" class="formblock" placeholder="+49 12345 678910">
<label class="screen-reader-text label">- Email-Adresse&nbsp;
    <strong class="error">*</strong></label>
<br>
<?php if($emailError != '') { ?>
<span class="error"><?php echo $emailError;?></span>
<?php } ?>
<input type="text" size="30" name="email" id="email" value="<?php if(isset($_POST['email']))  echo $_POST['email'];?>" class="formblock" placeholder="alex@mustermann.de">
<label class="screen-reader-text label">- Nachricht - <strong class="error">*</strong></label>
<br>
<?php if($commentError != '') { ?>
<span class="error"><?php echo $commentError;?></span>
<?php } ?>
<textarea name="comments" id="commentsText" class="formblock text textarea" placeholder="Hinterlassen sie hier ihre Nachricht..."><?php if(isset($_POST['comments'])) { if(function_exists('stripslashes')) { echo stripslashes($_POST['comments']); } else { echo $_POST['comments']; } } ?></textarea>
<button class="formblock" name="submit" type="submit">Email Senden</button>
<input type="hidden" name="submitted" id="submitted" value="true">
<?php } ?>

</form>

<script type="text/javascript">
(function() {
    ie_placeholder(document.getElementsByTagName('input'));
    ie_placeholder(document.getElementsByTagName('textarea'));
    function ie_placeholder (fields) {
        for (var i = 0; i < fields.length; i++) {
            var field = fields[i];
            field.onfocus = function () {
                if (this.value == this.placeholder) {
                    this.style.color = '';
                    this.value = '';
                }
            };
            field.onblur = function () {
                if (this.value == '' && this.placeholder != null) {
                    this.style.color = 'silver';
                    this.value = this.placeholder;
                }
            };
            field.onblur();
        }
    }
})();
</script>

<script type="text/javascript">
<!--//--><![CDATA[//><!--
$(document).ready(function() {
$('form#contact-us').submit(function() {
$('form#contact-us .error').remove();
var hasError = false;
$('.requiredField').each(function() {
if($.trim($(this).val()) == '') {
var labelText = $(this).prev('label').text();
$(this).parent().append('<br><br>Sie haben ihre '+labelText+'. vergessen.');
$(this).addClass('inputError');
hasError = true;
} else if($(this).hasClass('email')) {
var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
if(!emailReg.test($.trim($(this).val()))) {
var labelText = $(this).prev('label').text();
$(this).parent().append('<br><br>Sie haben eine Falsche '+labelText+' Adresse angegeben.');
$(this).addClass('inputError');
hasError = true;
}
}
});
if(!hasError) {
var formInput = $(this).serialize();
$.post($(this).attr('action'),formInput, function(data){
$('form#contact-us').slideUp("fast", function() {                  
$(this).before('<br><br><strong>Danke !</strong>Ihre Email wurde erfolgreich Ãœbermittelt.');
});
});
}
return false;   
});
});
//-->!]]>
</script>

Were is the issue ? because the Script works fine as it is showing in my browser ?
