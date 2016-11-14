
<table border="0"  cellspacing="0" cellpadding="5">
	<tr>
		<td>
Name : <?php echo (isset($this->request->data['name'])?htmlspecialchars($this->request->data['name'],ENT_QUOTES):'')?>
</td>
</tr>
<tr>
<td>
Last Name : <?php echo (isset($this->request->data['last_name'])?htmlspecialchars($this->request->data['last_name'],ENT_QUOTES):'')?>
</td>
</tr>
<tr>
<td>
Orgaination : <?php echo (isset($this->request->data['organization'])?htmlspecialchars($this->request->data['organization'],ENT_QUOTES):'')?>
</td>
</tr>
<tr>
<td>
Email :<?php echo (isset($this->request->data['email'])?htmlspecialchars($this->request->data['email'],ENT_QUOTES):'')?>
</td>
</tr>
<tr>
<td>
Text : <?php echo (isset($this->request->data['text'])?nl2br(htmlspecialchars($this->request->data['text'],ENT_QUOTES)):'')?>
</td>
</tr>
<tr>
<td>
Reason :<?php echo (isset($this->request->data['reason'])?htmlspecialchars($this->request->data['reason'],ENT_QUOTES):'')?>
</td>
</tr>
<?php
if(isset($this->request->data['specify']) && $this->request->data['specify']!=''){
?>
<tr>
<td>
Specify:<?php echo (isset($this->request->data['specify'])?nl2br(htmlspecialchars($this->request->data['specify'],ENT_QUOTES)):'')?>
</td>
</tr>
<?php
}
?>
</table>
