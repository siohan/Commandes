<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
  $('#selectall').click(function(){
    var v = $(this).attr('checked');
    if( v == 'checked' ) {
      $('.select').attr('checked','checked');
    } else {
      $('.select').removeAttr('checked');
    }
  });
  $('.select').click(function(){
    $('#selectall').removeAttr('checked');
  });
  $('#toggle_filter').click(function(){
    $('#filter_form').dialog({
      modal: true,
      width: 'auto',
    });
  });
  {if isset($tablesorter)}
  if( typeof($.tablesorter) != 'undefined' ) $('#articlelist').tablesorter({ sortList:{$tablesorter} });
  {/if}
});
//]]>
</script><div class="pageoptions"><p><span class="pageoptions warning">{$add_edit_items} </span></p></div>
<div class="pageoptions"><p class="pageoptions">{$itemcount}&nbsp;{$itemsfound}</p></div>
{if $itemcount > 0}
{$form2start}
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
		<th colspan="2">Actions</th>
		<th><input type="checkbox" id="selectall" name="selectall"></th>
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
	<td><input type="checkbox" name="{$actionid}sel[]" value="{$entry->item_id}" class="select"></td>
  </tr>
{/foreach}
 </tbody>
</table>
<!-- SELECT DROPDOWN -->
<div class="pageoptions" style="float: right;">
<br/>{$actiondemasse}{$submit_massaction}
  </div>
{$form2end}
{/if}

