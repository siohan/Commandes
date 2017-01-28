<div class="pageoptions"><p><span class="pageoptions warning">{$add} - {$import_from_ping} </span></p></div>
<div class="pageoptions"><p class="pageoptions">{$itemcount}&nbsp;{$itemsfound}</p></div>
{if $itemcount > 0}
<table border="0" cellspacing="0" cellpadding="0" class="pagetable">
 <thead>
	<tr>
		<th>Client N°</th>
		<th>Nom (club)</th>
		<th>Nb commandes</th>
		<th>email</th>
		<th>Compte activé ?</th>
		<th>Tél</th>
		<th>Portable</th>
		<th colspan="4">Actions</th>
	</tr>
 </thead>
 <tbody>
{foreach from=$items item=entry}
  <tr class="{$entry->rowclass}">
	<td>{$entry->client_id}</td>
    <td>{$entry->nom} {$entry->prenom}-({$entry->club})</td>
	<td>{$entry->nb_commandes}</td>
	<td>{$entry->email}</td>
	<td>{$entry->push_customer}</td>
	<td>{$entry->tel}</td>
	<td>{$entry->portable}</td>
	<td>{$entry->view}</td>
	<td>{$entry->editlink}</td>
    <td>{$entry->deletelink}</td>
  </tr>
{/foreach}
 </tbody>
</table>
{/if}

