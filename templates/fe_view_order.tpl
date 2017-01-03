<div class="pageoptions"><p class="pageoptions">{$itemcount}&nbsp;{$itemsfound}</p></div>
<div class="pageoptions"><p class="pageoptions">{$lienRetour}</p></div>
{if $user_validation ==0}<div class="pageoptions"><p class="pageoptions">{$new_command}</p></div>{/if}
{if $itemcount > 0}

<h3> Détails de ma commande {$commande_num}</h3>
<table border="0" cellspacing="0" cellpadding="0" class="pagetable">
 <thead>
	<tr>
		<th>Date</th>
		<th>Fournisseur </th>
		<th>Libellé</th>
		<th>Quantité</th>
		<th>Prix total</th>
	</tr>
 </thead>
 <tbody>
{foreach from=$items item=entry}
  <tr class="{$entry->rowclass}">
	<td>{$entry->date_created|date_format:"%d-%m-%Y"}</td>
	<td>{$entry->fournisseur}</td>
    <td>{$entry->libelle_commande} {$entry->ep_manche_taille} {$entry->couleur}</td>
	<td>{$entry->quantite}</td>
	<td>{$entry->prix_total}</td>
	<td>{$entry->edit}</td>
	<td>{$entry->delete}</td>	
	
  </tr>
{/foreach}
 </tbody>
</table>
<p class="search-button">{if $user_validation ==0}{$validate}{/if}</p>
{/if}

