{$start_form}
<div class="pageoverflow">
	<p class="pagetext">Email du gestionnaire de commandes</p>
	<p class="pageinput">{$input_adminemail}</p>
</div>

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
<fieldset>
	<legend>Changement de statut d'une commande</legend>
	<div class="pageoverflow">
		<p class="pagetext">Le sujet du mail</p>
		<p class="pageinput">{$input_newstatussubject}</p>
	</div>
<div class="pageoverflow">
	<p class="pagetext">Le corps du mail</p>
	<p class="pageinput">{$input_emailnewstatusbody}</p>
</div>
</fieldset>
{$submit}
{$end_form}