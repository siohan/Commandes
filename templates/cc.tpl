<div class="pageoptions"><p><span class="pageoptions warning">{$add} </span></p></div>
<div class="pageoptions"><p class="pageoptions">{$itemcount}&nbsp;{$itemsfound}</p></div>

{if $itemcount > 0}
<table border="0" cellspacing="0" cellpadding="0" class="pagetable">
 <thead>
	<tr>
		<th>Commande N°</th>
		<th>Nom (club)</th>
		<th>Date</th>
		<th>Nb articles</th>
		<th>prix total</th>
		<th>Statut</th>
		<th>Paiement</th>
		<th>Mode paiement</th>
		<th colspan="3">Actions</th>
	</tr>
 </thead>
 <tbody>
{foreach from=$items item=entry}
  <tr class="{$entry->rowclass}">
	<td>{$entry->commande_id}</td>
    <td>{$entry->nom} {$entry->prenom}-({$entry->club})</td>
    <td>{$entry->date_created|date_format:"d/m/Y"}</td>
	<td>{$entry->nb_items}</td>
	<td>{$entry->prix}</td>
	<td>{$entry->statut}</td>
	<td>{$entry->paiement}</td>
	<td>{$entry->mode_paiement}</td>
	<td>{$entry->view}</td>
	<td>{$entry->editlink}</td>
    <td>{$entry->deletelink}</td>
  </tr>
{/foreach}
 </tbody>
</table>
{/if}

