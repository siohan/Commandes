<div class="pageoptions"><p><span class="pageoptions warning">{$add} </span></p></div>
<div class="pageoptions"><p class="pageoptions">{$itemcount}&nbsp;{$itemsfound}</p></div>
{if $itemcount > 0}
<table border="0" cellspacing="0" cellpadding="0" class="pagetable">
 <thead>
	<tr>
		<th>Commande N°</th>
		<th>Date</th>
		<th>Fournisseur</th>
		<th>Statut</th>
		<th>Nb articles</th>
		<th>Total commande</th>
		<th colspan="3">Actions</th>
	</tr>
 </thead>
 <tbody>
{foreach from=$items item=entry}
  <tr class="{$entry->rowclass}">
	<td>{$entry->id_cf}</td>
    <td>{$entry->date_created|date_format:"d/m/Y"}</td>
	<td>{$entry->fournisseur}</td>
	<td>{$entry->statut_CF}</td>
	<td>{$entry->nb_items}</td>
	<td>{$entry->total_commande}</td>
	<td>{$entry->view}</td>
	<td>{$entry->editlink}</td>
    <td>{$entry->deletelink}</td>
  </tr>
{/foreach}
 </tbody>
</table>
{/if}

