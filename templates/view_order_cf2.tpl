<div class="pageoptions"><p class="pageoptions">{$itemcount}&nbsp;{$itemsfound}</p></div>
{if $itemcount > 0}

<table cellpadding="0" class="pagetable cms_sortable tablesorter" id="articlelist">
 <thead>
	<tr>
		<th>Quantité</th>
		<th>Libellé commande</th>
		<th>prix total</th>
	</tr>
 </thead>
 <tbody>
{foreach from=$items item=entry}
  <tr class="{$entry->rowclass}">
	<td>{$entry->quantite}</td>
    <td>{$entry->commande}</td>
	<td>{$entry->prix_total}€</td>
  </tr>
{/foreach}
 </tbody>
</table>

{/if}

