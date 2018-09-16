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
</script>
<div class="pageoptions"><p class="pageoptions">{$itemcount}&nbsp;{$itemsfound}</p></div>
{if isset($formstart) }
<fieldset>
  <legend>Filtres</legend>
  {$formstart}
  <div class="pageoverflow">
	<p class="pagetext">Statut de la commande</p>
    <p class="pageinput">{$statut_commande} </p>
    <p class="pageinput">{$submitfilter}{$hidden|default:''}</p>
  </div>
  {$formend}
</fieldset>
{/if}
{if $itemcount > 0}
{*$form2start*}
<table cellpadding="0" class="pagetable cms_sortable tablesorter" id="articlelist">
 <thead>
	<tr>
		<th>Commande N°</th>
		<th>Nom</th>
		<th>Date</th>
		<th>Nb articles</th>
		<th>prix total</th>
		<th>Statut</th>
		<th>Paiement</th>		
		<th colspan="3" class="pageicon {literal}{sorter: false}{/literal}">Actions</th>
		<!--<th><input type="checkbox" id="selectall" name="selectall"></th>-->
	</tr>
 </thead>
 <tbody>
{foreach from=$items item=entry}
  <tr class="{$entry->rowclass}">
	<td>{$entry->commande_number}({$entry->commande_id}) - {$entry->fournisseur}</td>
    <td>{$entry->client}</td>
    <td>{$entry->date_created|date_format:"d/m/Y"}</td>
	<td>{$entry->nb_items}</td>
	<td>{$entry->prix_total}€</td>
	<td>{$entry->statut_commande}</td>
	<td>{if $entry->is_paid == false}<a href="{root_url}/admin/moduleinterface.php?mact=Paiements,m1_,add_edit_reglement,0&amp;m1_record_id={$entry->commande_number}&amp;__c={$smarty.cookies.__c}">{$shopping}</a>{else}{$entry->is_paid}{/if}</td>
	<td>{$entry->view}</td>
	<!--<td><input type="checkbox" name="{$actionid}sel[]" value="{$entry->commande_id}" class="select"></td>-->
  </tr>
{/foreach}
 </tbody>
</table>

<!-- SELECT DROPDOWN -->
<!--
<div class="pageoptions" style="float: right;">
<br/>{$actiondemasse}{$submit_massaction}
  </div>
{$form2end}
-->
{/if}

