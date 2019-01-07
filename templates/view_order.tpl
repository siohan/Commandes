{if $status != 'Reçue'}<div class="pageoptions"><p><span class="pageoptions warning">{$add_edit_cc_item} </span></p></div>{/if}
<div class="pageoptions"><p class="pageoptions">{$itemcount}&nbsp;{$itemsfound}</p></div>
<h3>Commande de {$nom} du {$date_created|date_format:'%d/%m/%Y'}</h3>
{if $itemcount > 0}
<table border="0" cellspacing="0" cellpadding="0" class="pagetable">
 <thead>
	<tr>
		<th>Commande N°</th>
		<th>Article N°</th>
		<th>Date</th>
		<th>Libellé</th>
		<th>Fournisseur</th>
		<th>Quantité</th>
		<th>Prix total</th>
	</tr>
 </thead>
 <tbody>
{foreach from=$items item=entry}
  <tr class="{$entry->rowclass}">
	<td>{$entry->commande_number}</td>
	<td>{$entry->item_id}</td>
	<td>{$entry->date_created|date_format:"%d-%m-%Y"}</td>
    <td>{$entry->libelle_commande} {$entry->ep_manche_taille} {$entry->couleur}</td>
    <td>{$entry->fournisseur}</td>
	<td>{$entry->quantite}</td>
	<td>{$entry->prix_total}</td>	
  </tr>
{/foreach}
 </tbody>
</table>
{/if}

