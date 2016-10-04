<div class="pageoptions"><p><span class="pageoptions warning">{$add}</span></p></div>
<div class="pageoptions"><p class="pageoptions">{$itemcount}&nbsp;{$itemsfound}</p></div>
{if $itemcount > 0}
<table border="0" cellspacing="0" cellpadding="0" class="pagetable">
 <thead>
	<tr>
		<th>Id</th>
		<th>Libellé</th>
		<th>Catégorie</th>
		<th>Fournisseur</th>
		<th>Qté</th>
		<th>Epaiss/manche/taille</th>
		<th>Couleur</th>
		<th>Prix total</th>
		<th colspan="3">Actions</th>
	</tr>
 </thead>
 <tbody>
{foreach from=$items item=entry}
  <tr class="{$entry->rowclass}">
	<td>{$entry->id}</td>
	<td>{$entry->libelle_commande}</td>
    <td>{$entry->categorie_produit}</td>
	<td>{$entry->fournisseur}</td>
	<td>{$entry->quantite}</td>
	<td>{$entry->ep_manche_taille}</td>
	<td>{$entry->couleur}</td>
	<td>{$entry->prix_total}</td>
    <td>{$entry->deletelink}</td>
  </tr>
{/foreach}
 </tbody>
</table>
{/if}

