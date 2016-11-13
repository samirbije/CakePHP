
<table >
	<tr>
		<td>
Name : <?php echo (($this->request->data['name'])?htmlspecialchars($this->request->data['name'],ENT_QUOTES):'')?>
</td>
<td>
Last Name : <?php echo (($this->request->data['name'])?htmlspecialchars($this->request->data['last_name'],ENT_QUOTES):'')?>
</td>
<td>
Orgaination : <?php echo (($this->request->data['name'])?htmlspecialchars($this->request->data['organization'],ENT_QUOTES):'')?>
</td>
<td>
Email :<?php echo (($this->request->data['name'])?htmlspecialchars($this->request->data['email'],ENT_QUOTES):'')?>
</td>
<td>
Text : <?php echo (($this->request->data['name'])?htmlspecialchars($this->request->data['text'],ENT_QUOTES):'')?>
</td>
<td>
Reason :<?php echo (($this->request->data['name'])?htmlspecialchars($this->request->data['reason'],ENT_QUOTES):'')?>
</td>
<td>
Specify:<?php echo (($this->request->data['name'])?htmlspecialchars($this->request->data['specify'],ENT_QUOTES):'')?>
</td>
</table>