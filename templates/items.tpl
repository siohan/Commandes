<div class="pageoptions"><p><span class="pageoptions warning">{$add_edit_items} </span></p></div>
<div class="pageoptions"><p class="pageoptions">{$itemcount}&nbsp;{$itemsfound}</p></div>
{if $itemcount > 0}
<table border="0" cellspacing="0" cellpadding="0" class="pagetable">
 <thead>
	<tr>
		<th>Article N°</th>
		<th>Catégorie</th>
		<th>Fournisseur</th>
		<th>Référence</th>
		<th>Libellé</th>
		<th>Marque</th>
		<th>Prix catalogue</th>
		<th>Réduction (en %)</th>
		<th>Statut</th>
		<th colspan="3">Actions</th>
	</tr>
 </thead>
 <tbody>
{foreach from=$items item=entry}
  <tr class="{$entry->rowclass}">
	<td>{$entry->item_id}</td>
    <td>{$entry->categorie}</td>
	<td> {$entry->fournisseur}</td>
	<td>({$entry->reference})</td>
	<td>{$entry->libelle}</td>
	<td>{$entry->marque}</td>
	<td>{$entry->prix_unitaire}</td>
	<td>{$entry->reduction}</td>
	<td>{$entry->statut_item}</td>
	<!--<td>{$entry->view}</td>-->
	<td>{$entry->editlink}</td>
    <td>{$entry->deletelink}</td>
  </tr>
{/foreach}
 </tbody>
</table>
{/if}

