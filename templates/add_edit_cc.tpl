{literal}
<script>
 $(function() {
   $( "#m1_date_debut" ).datepicker({ dateFormat: "yy-mm-dd" });
 });
 </script>
{/literal}
<div class="pageoverflow">

{$formstart}
{if $edit == '1'}
	{$edit}
	{$edition}
	{$record_id}
	{$commande_number}
	{*$nom*}
	{*$libelle_commande*}
	<div class="pageoverflow">
		<p class="pagetext">Date Commande :</p>
		<p class="pageinput">{$date_created}</p>
	</div>

	
	
	
	{if $statut != '1'}
		<div class="pageoverflow">
			<p class="pagetext">Statut de la commande :</p>
			<p class="pageinput">{$statut_commande}</p>
		</div>
	{else}
		{$statut_commande}
	{/if}
	
	

	<div class="pageoverflow">
	<p class="pagetext">Remarques :</p>
	<p class="pageinput">{$remarques}</p>
	</div>
  
{else}	
{$edit}{$edition}{$commande_number}
	<div class="pageoverflow">
		<p class="pagetext">Date Commande :</p>
		<p class="pageinput">{$date_created}</p>
	</div>
	<div class="pageoverflow">
		<p class="pagetext">Client :</p>
		<p class="pageinput">{$nom}</p>
	</div>
	<div class="pageoverflow">
	  <p class="pagetext">Fournisseur:</p>
	  <p class="pageinput">{$fournisseur}</p>
	</div>
	<div class="pageoverflow">
		<p class="pagetext">Remarques :</p>
		<p class="pageinput">{$remarques}</p>
	</div>
{/if}
<div class="pageoverflow">
    <p class="pagetext">&nbsp;</p>
    <p class="pageinput">{$submit}{$cancel}</p>
</div>
{$formend}
</div>
