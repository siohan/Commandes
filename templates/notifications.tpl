{$start_form}
<div class="pageoverflow">
	<p class="pagetext">Email du gestionnaire de commandes</p>
	<p class="pageinput">{$input_adminemail}</p>
</div>
<fieldset>
<legend>Activation d'un compte</legend>
<div class="pageoverflow">
	<p class="pagetext">Le sujet du mail</p>
	<p class="pageinput">{$input_emailactivationsubject}</p>
</div>
<div class="pageoverflow">
	<p class="pagetext">Le corps du mail</p>
	<p class="pageinput">{$input_emailactivationbody}</p>
</div>
</fieldset>
<fieldset>
	<legend>Nouvelle commande</legend>
	<div class="pageoverflow">
		<p class="pagetext">Le sujet du mail</p>
		<p class="pageinput">{$input_newcommandsubject}</p>
	</div>
<div class="pageoverflow">
	<p class="pagetext">Le corps du mail</p>
	<p class="pageinput">{$input_emailnewcommandbody}</p>
</div>
</fieldset>
{$submit}
{$end_form}