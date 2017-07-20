<!--<script type="text/javascript">
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
</script>
-->
<div class="pageoptions"><p><span class="pageoptions warning">{$add_edit_fournisseur} </span></p></div>
<div class="pageoptions"><p class="pageoptions">{$itemcount}&nbsp;{$itemsfound}</p></div>

{if $itemcount > 0}

<table cellpadding="0" class="pagetable cms_sortable tablesorter" id="articlelist">
 <thead>
	<tr>
		<th>Id</th>
		<th>Catalogue</th>
		<th>Description</th>
		<th>Actif</th>
		<th>Ordre dans liste</th>
		<th>Action</th>
	</tr>
 </thead>
 <tbody>
{foreach from=$items item=entry}
  <tr class="{$entry->rowclass}">
	<td>{$entry->id}</td>
	<td> {$entry->nom_fournisseur}</td>
	<td>({$entry->description})</td>
	<td>{$entry->actif}</td>
	<td>{$entry->ordre}</td>
	<td>{$entry->editlink}</td>
  </tr>
{/foreach}
 </tbody>
</table>

{/if}

