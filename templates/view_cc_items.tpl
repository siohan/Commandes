{if $status != 'Reçue'}<div class="pageoptions"><p><span class="pageoptions warning">{$add} </span></p></div>{/if}
<div class="pageoptions"><p class="pageoptions">{$itemcount}&nbsp;{$itemsfound}</p></div>

{if $itemcount > 0}
<table border="0" cellspacing="0" cellpadding="0" class="pagetable">
 <thead>
	<tr>
		<th>Date</th>
		<th>Client</th>
		<th>Commande N°</th>
		<th>Libellé</th>
		<th>Fournisseur</th>
		<th>Quantité</th>
		<th>Prix total</th>
		<th>Statut</th>
		<th colspan="2">Actions</th>
	</tr>
 </thead>
 <tbody>
{foreach from=$items item=entry}
  <tr class="{$entry->rowclass}">
	<td>{$entry->date_created|date_format:"%d-%m-%Y"}</td>
	<td>{$entry->client}</td>
	<td>{$entry->commande_number}</td>
    <td>{$entry->libelle_commande} - {$entry->ep_manche_taille} {$entry->couleur}</td>
    <td>{$entry->fournisseur}</td>
	<td>{$entry->quantite}</td>
	<td>{$entry->prix_total}</td>
	<td>{if $entry->statut=='0'}Non commandé{elseif $entry->statut=='1'}Envoyée{elseif $entry->statut == '2'}Reçue{elseif $entry->statut =='3'}Payée{elseif $entry->statut=='4'}Payée et déstockée{/if}</td>	
	
	<td>{$entry->view}</td>
	<td>{$entry->editlink}</td>
    <td>{$entry->deletelink}</td>
  </tr>
{/foreach}
 </tbody>
</table>
{/if}

