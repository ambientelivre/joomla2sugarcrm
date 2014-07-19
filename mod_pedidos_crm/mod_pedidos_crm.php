<?php
defined( '_JEXEC' ) or die;

echo "<h1>Pedidos Realizados</h1>";

# conesão caseira com o banco do sugar
$hostcrm = 'localhost';
$usercrm = 'ambientelivre';
$passcrm = 'sugarsql123';
$basecrm = 'homologacao_sugarcrm';
mysql_connect($hostcrm, $usercrm, $passcrm);
mysql_select_db($basecrm);

$user =& JFactory::getUser();
 
if ($user->id == 0) {
  echo 'Não há usuário logado.<br />';
} else {
  echo '<h2>Você está conectado como: ' . $user->username . '</h2>';
  //echo 'Real name: ' . $user->name . '<br />';
  //echo 'User ID  : ' . $user->id . '<br />';
}


$sql = "SELECT ac.id as cod_conta, ea.email_address as email, ac.name as conta FROM email_addresses as ea
    left join email_addr_bean_rel as rl on (ea.id = rl.email_address_id)
    left join accounts as ac on (rl.bean_id = ac.id)
where
    ea.email_address = '".$user->email."'
    AND rl.bean_module = 'Accounts'; ";
$res = mysql_query($sql);
$r = mysql_fetch_array($res);

if (!empty($r['cod_conta'])){

?>

<center>
<table width="80%">
	<!-- <tr><td colspan="3"><?php echo $r['first_name'].' '. $r['last_name']; ?></strong></td></tr> -->
	<tr><td colspan="3"><strong>Pedidos Realizados da conta: <?php echo $r['conta']; ?></strong></td></tr>
	<?php
		$sql2 = "SELECT ac.*, 
		aop.*,
		op.*  FROM accounts as ac
    left join accounts_opportunities as aop on(ac.id = aop.account_id)
    left join opportunities as op on (op.id = aop.opportunity_id)
where
   op.sales_stage = 'Closed Won'
   AND ac.id = '".$r['cod_conta']."';";
		$res2 = mysql_query($sql2);
		while($lead = mysql_fetch_array($res2)){
			echo '<tr><td>'.utf8_encode($lead['name']).'</td><td>'.utf8_encode($lead['description']).'</td><td>'.$lead['date_modified'];
		}
	?>
</table>
</center>
<?php
}else{
	echo 'Usuário sem acesso ao CRM!';
}
?>

