<!-- BEGIN switch_xs_enabled -->
<?php

//
// This will convert simple select box to nicer one with <optgroup> tags.
//

$str = $this->vars['S_JUMPBOX_SELECT'];
$options = array();
// getting header
$pos = strpos($str, '<option');
if($pos > 0)
{
	$start = substr($str, 0, $pos);
	$str = substr($str, $pos, strlen($str));
}
else
{
	$start = 0;
}
// getting footer
$str = strrev($str);
$pos = strpos($str, strrev('</option>'));
if($pos > 0)
{
	$end = strrev(substr($str, 0, $pos));
	$str = substr($str, $pos, strlen($str));
}
else
{
	$end = '';
}
$str = trim(strrev($str));
// getting all options
while(strlen($str) > 0)
{
	$pos = strpos($str, '</option>');
	if($pos > 0)
	{
		$pos += 9;
		$item = trim(substr($str, 0, $pos));
		$str = trim(substr($str, $pos, strlen($str)));
	}
	else
	{
		$item = $str;
		$str = '';
	}
	$value = '';
	$text = '';
	$selected = false;
	$pos = strpos($item, '>') + 1;
	// getting text
	$text = substr($item, $pos, strlen($item));
	$item = substr($item, 0, $pos);
	$pos = strpos($text, '<');
	if($pos)
	{
		$text = substr($text, 0, $pos);
	}
	// checking if item is selected
	if(strpos($str1, ' selected'))
	{
		$selected = true;
	}
	// getting value
	$pos = strpos($item, 'value="');
	if($pos)
	{
		$pos += 7;
		$str1 = substr($item, $pos, strlen($item));
		$pos = strpos($str1, '"');
		$value = substr($str1, 0, $pos);
	}
	$options[] = array(
		'text'		=> $text,
		'value'		=> $value,
		'selected'	=> $selected
		);
}

$text = $start;
$group = 0;
for($i=0; $i<count($options); $i++)
{
	$item = $options[$i];
	if(($item['value'] == -1) && ($item['text'] === '&nbsp;'))
	{
		if($group)
		{
			$text .= '</optgroup>';
			$group = false;
		}
		if(count($options) - $i >= 2)
		{
			$item = $options[$i+1];
			$i+=2;
			$group = true;
			$text .= '<optgroup label="' . $item['text'] . '">';
		}
	}
	else
	{
		$text .= '<option value="' . $item['value'] . '"';
		if($item['selected'])
		{
			$text .= ' selected="selected"';
		}
		$text .= '>' . $item['text'] . '</option>';
	}
}
if($group)
{
	$text .= '</optgroup>';
}
$text .= $end;
$this->vars['S_JUMPBOX_SELECT'] = $text;

?>
<!-- END switch_xs_enabled -->
<table cellspacing="0" cellpadding="0" border="0">
<tr> 
<form method="get" name="jumpbox" action="{S_JUMPBOX_ACTION}" onsubmit="if(document.jumpbox.f.value == -1){return false;}">
	<td nowrap="nowrap">{S_JUMPBOX_SELECT}&nbsp;<input type="submit" value="{L_GO}" class="liteoption" />&nbsp;</td>
</form>
</tr>
</table>