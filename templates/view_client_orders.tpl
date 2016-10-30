{if $status != 'Reçue'}<div class="pageoptions"><p><span class="pageoptions warning">{$add_edit_cc_item} </span></p></div>{/if}
<div class="pageoptions"><p class="pageoptions">{$itemcount}&nbsp;{$itemsfound}</p></div>
<h3>Commande(s) de {$prenom} {$nom} </h3>
{if $itemcount > 0}
<table border="0" cellspacing="0" cellpadding="0" class="pagetable">
 <thead>
	<tr>
		<th>Commande N°</th>
		<th>Date</th>
		<th>Libellé</th>
		<th>Statut</th>
		<th>Prix</th>
		<th>Paiement</th>
		<th>Mode paiement</th>
		<th>Remarques</th>
		<th>Action</th>
	</tr>
 </thead>
 <tbody>
{foreach from=$items item=entry}
  <tr class="{$entry->rowclass}">
	<td>{$entry->commande_id}</td>
	<td>{$entry->date_created|date_format:"%d-%m-%Y"}</td>
    <td>{$entry->libelle_commande}</td>
    <td>{$entry->statut_commande}</td>
	<td>{$entry->prix_total}</td>
	<td>{$entry->paiement}</td>
	<td>{$entry->mode_paiement}</td>
	<td>{$entry->remarques}</td>		
	<td>{$entry->view}</td>
	<!--<td>{$entry->editlink}</td>
    <td>{$entry->deletelink}</td>-->
  </tr>
{/foreach}
 </tbody>
</table>
{/if}

