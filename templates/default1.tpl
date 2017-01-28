<div class="pageoptions"><p class="pageoptions">{$fe_add_cc}</p></div>
<div class="pageoptions"><p class="pageoptions">{$itemcount}&nbsp;{$itemsfound}</p></div>
{if $itemcount > 0}
<h3>Mes Commandes</h3>
<table border="0" cellspacing="0" cellpadding="0" class="pagetable">
 <thead>
	<tr>
		<th>Date</th>
		<th>Statut</th>
		<th>Prix</th>
		<th>Paiement</th>
		<th>Mode paiement</th>
		<th>Détails</th>
	</tr>
 </thead>
 <tbody>
{foreach from=$items item=entry}
  <tr class="{$entry->rowclass}">
	<td>{$entry->date_created|date_format:"%d-%m-%Y"}</td>
    <td>{$entry->statut_commande}</td>
	<td>{$entry->prix_total}</td>
	<td>{$entry->paiement}</td>
	<td>{$entry->mode_paiement}</td>		
	<td>{$entry->view}</td>
	<td>{$entry->fe_delete}</td>
  </tr>
{/foreach}
 </tbody>
</table>
{/if}
{if isset($url_logout)}
<p><a href="{$url_logout}" title="{$mod->Lang('info_logout')}">{$mod->Lang('logout')}</a></p>
{/if}
{*$deconn*}