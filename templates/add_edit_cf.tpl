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
	{*$edit*}
	{$edition}
{$record_id}
	
	
{$date_created}
	<div class="pageoverflow">
	<p class="pagetext">fournisseur :</p>
	<p class="pageinput">{$fournisseur}</p>
	</div>
	
  
{else}	
{$edit}{$edition}
	{$date_created}



	<div class="pageoverflow">
		<p class="pagetext">Fournisseur :</p>
		<p class="pageinput">{$fournisseur}</p>
	</div>
{/if}
<div class="pageoverflow">
    <p class="pagetext">&nbsp;</p>
    <p class="pageinput">{$submit}{$cancel}</p>
  </div>
{$formend}
</div>
