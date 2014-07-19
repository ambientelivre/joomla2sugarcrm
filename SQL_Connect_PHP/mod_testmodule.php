<?php
defined( '_JEXEC' ) or die;

$db = JFactory::getDBO();

/* You need to select valid SQL entry here for your SQL Database */
$sql = "SELECT hotel_name, cost_per_night FROM #__hotel_listing"; 

$db->setQuery($sql); 

$rows = $db->loadObjectList();

?>
<table>
<?php foreach ($rows as $row): ?>
	<tr><td><?php echo $row->hotel_name ?></strong>:</td><td>$<?php echo $row->cost_per_night ?></td></tr>
<?php endforeach ?>
</table>