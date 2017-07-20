<div class="pageoptions"><p><span class="pageoptions warning">{$add} </span></p></div>
<div class="pageoptions"><p class="pageoptions">{$itemcount}&nbsp;{$itemsfound}</p></div>
{if $itemcount > 0}
<table border="0" cellspacing="0" cellpadding="0" class="pagetable">
 <thead>
	<tr>
		<th>Client N°</th>
		<th>Nom (club)</th>
		<th>Nb commandes</th>
		<th>Action</th>
	</tr>
 </thead>
 <tbody>
{foreach from=$items item=entry}
  <tr class="{$entry->rowclass}">
	<td>{$entry->client_id}</td>
    <td>{$entry->joueur}</td>
	<td>{$entry->nb_commandes}</td>
	<td>{$entry->view}</td>
  </tr>
{/foreach}
 </tbody>
</table>
{/if}

