<?php
defined( '_JEXEC' ) or die;

echo "<h1>Emails do CRM! - Sugar connection 1.0</h1>";

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
	<tr><td colspan="3"><strong>Emails da conta:<?php echo $r['conta']; ?></strong></td></tr>
	<?php
		$sql2 = "SELECT 
    dc.* , dr.*, da.*
FROM 
    documents as dc
    left join document_revisions as dr on (dr.document_id = dc.id)
    left join documents_accounts as da on (da.document_id = dc.id)
where
    da.account_id = '".$r['cod_conta']."'
order by revision DESC
limit 1
	;";
		$res2 = mysql_query($sql2);
		while($lead = mysql_fetch_array($res2)){
			echo '<tr><td>'.$lead['date_modified'].'</td>
			<td>'.$lead['filename'].'</td>
			<td>'.$lead['revision'].'</td>
			</tr>
			<tr><td colspan="3"><a href="/var/www/homologacao/cache/upload/'.$lead['filename'].'">Download</a></td></tr>
			'
			;
		}
	?>
</table>
</center>
<?

$path = getcwd();
echo "O seu caminho absoluto é: ";
echo $path;
/*
$arquivo = " "; //AQUI VAI O NOME DO ARQUIVO PARA DOWNLOAD
$download_size = filesize($arquivo);
$filename = basename($arquivo);
header ("Content-type: application/txt");
header("Content-Length: $download_size");
header ("Content-disposition: attachment; filename=$filename;");
header ("Content-Description: Download File");
header("Content-Type: application/force-download");
readfile("$arquivo");
 * */
?>
<?php
}else{
	echo 'Usuário sem acesso ao CRM!';
}
?>

