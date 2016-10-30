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
	{$commande_id}
	{*$nom*}
	<div class="pageoverflow">
		<p class="pagetext">Date Commande :</p>
		<p class="pageinput">{$date_created}</p>
	</div>
	<div class="pageoverflow">
	  <p class="pagetext">Libellé:</p>
	  <p class="pageinput">{$libelle_commande}</p>
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
	<p class="pagetext">Paiement :</p>
	<p class="pageinput">{$paiement}</p>
	</div>
	<div class="pageoverflow">
	<p class="pagetext">Mode paiement :</p>
	<p class="pageinput">{$mode_paiement}</p>
	</div>
	<div class="pageoverflow">
	<p class="pagetext">Remarques :</p>
	<p class="pageinput">{$remarques}</p>
	</div>
  
{else}	
{$edit}{$edition}
	<div class="pageoverflow">
		<p class="pagetext">Date Commande :</p>
		<p class="pageinput">{$date_created}</p>
	</div>
	<div class="pageoverflow">
		<p class="pagetext">Client :</p>
		<p class="pageinput">{$nom}</p>
	</div>

	<div class="pageoverflow">
	  <p class="pagetext">Libellé:</p>
	  <p class="pageinput">{$libelle_commande}</p>
	</div>
	<div class="pageoverflow">
	  <p class="pagetext">Fournisseur:</p>
	  <p class="pageinput">{$fournisseur}</p>
	</div>
	<div class="pageoverflow">
		<p class="pagetext">Statut de la commande :{cms_help key='help_statut_recue'}</p>
		<p class="pageinput">{$statut_commande}</p>
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
