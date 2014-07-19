<?php
defined( '_JEXEC' ) or die;

echo "<h1>Informações de Cadastro do CRM! - Sugar connection 1.0</h1>";

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

/*TODO: Adicionar condição OR para filtrar o email de acesso aos dados para 
 * o email da conta 
 * ou o email do contato
 * Quando um contato estiver conectado somente ver os dados dele
 * Quando o email da conta estiver conectado poderá ver os dados de todos os contatos relacionados a conta
 * 
 */
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
	<tr><td colspan="5"><strong>Conta: <?php echo $r['conta']; ?></strong></td></tr>
	
	<?php
	$sql2 = "select 
    ac.*,
    acc.*,
    act.*,
    ct.*
from 
    accounts as ac
    left join accounts_contacts as acc on (acc.account_id = ac.id)
    left join accounts_cstm as act on (act.id_c = ac.id)
    left join contacts as ct on (acc.contact_id = ct.id)
where
    ac.id =  '".$r['cod_conta']."';";
		$res2 = mysql_query($sql2);
		while($lead = mysql_fetch_array($res2)){
			echo '
	<table width="80%" border="0">
		<tr>
			<td>Razão social</td>
			<td>'.$lead['razao_social_c'].'</td>
			<td></td>
			<td>Atividade</td>
			<td>'.$lead['descricao_atividade_c'].'</td>
		</tr>
		<tr>
			<td>CNPJ/CPF</td>
			<td>'.$lead['cnpj_cpf_c'].'</td>
			<td></td>
			<td>IE</td>
			<td>'.$lead['inscricao_estadual_c'].'</td>
		</tr>
		<tr>
			<td>Website</td>
			<td>'.$lead['website'].'</td>
			<td></td>
			<td>Telefone</td>
			<td>'.$lead['phone_office'].'</td>
		</tr>
		<tr>
			<th colspan="5"><strong>Endereços</strong></th>
		</tr>
		<tr>
			<th colspan="2">Endereço de cobrança</th>
			<th></th>
			<th colspan="2">Endereço de entrega</th>
		</tr>
		<tr>
			<td>Rua</td>
			<td>'.$lead['billing_address_street'].'</td>
			<td></td>
			<td>Rua</td>
			<td>'.$lead['shipping_address_street'].'</td>
		</tr>
		<tr>
			<td>Cidade</td>
			<td>'.$lead['billing_address_city'].'</td>
			<td></td>
			<td>Cidade</td>
			<td>'.$lead['shipping_address_city'].'</td>
		</tr>
		<tr>
			<td>Estado</td>
			<td>'.$lead['billing_address_state'].'</td>
			<td></td>
			<td>Estado</td>
			<td>'.$lead['shipping_address_state'].'</td>
		</tr>
		<tr>
			<td>CEP</td>
			<td>'.$lead['billing_address_postalcode'].'</td>
			<td></td>
			<td>CEP</td>
			<td>'.$lead['shipping_address_postalcode'].'</td>
		</tr>
		<tr>
			<td>País</td>
			<td>'.$lead['billing_address_country'].'</td>
			<td></td>
			<td>País</td>
			<td>'.$lead['shipping_address_country'].'</td>
		</tr>
		<tr>
			<th colspan="5"><strong>Contatos</strong></th>
		</tr>
		<tr>
			<td>Nome:</td>
			<td>'.utf8_encode($lead['first_name']).' '.utf8_encode($lead['last_name']).'</td>
			<td></td>
			<td>Titulo</td>
			<td>'.$lead['title'].'</td>
		</tr>
		<tr>
			<td>Telefone comercial:</td>
			<td>'.$lead['phone_work'].'</td>
			<td></td>
			<td>Telefone celular</td>
			<td>'.$lead['phone_mobile'].'</td>
		</tr>
		<tr>
			<td>Endereço:</td>
			<td>'.$lead['primary_address_street'].'</td>
			<td></td>
			<td>CEP</td>
			<td>'.$lead['primary_address_postalcode'].'</td>
		</tr>
		<tr>
			<td>cidade:</td>
			<td>'.$lead['primary_address_city'].'</td>
			<td></td>
			<td>estado:</td>
			<td>'.$lead['primary_address_state'].'</td>
		</tr>
		<tr>
			<td>país:</td>
			<td>'.$lead['primary_address_country'].'</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		';
		}
	?>
</table>
</center>
<?php
}else{
	echo 'Usuário sem acesso ao CRM!';
}
?>

