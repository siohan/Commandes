{*<pre>{$rowarray|var_dump}</pre>*}
{**}<div class="pageoverflow">
{$formstart}

{$record_id}
<h3>Qté - libellé - commande - prix - adhérent</h3>

{foreach from=$rowarray key=key item=entry}
<div class="pageoverflow">
    <p class="pageinput"><input type="checkbox"  name="m1_id_CF[{$key}]" id="m1_id_CF[{$key}]" {if $entry['participe'] ==1}checked='checked' {/if} value = '1'>{$entry['commande']} ({$entry['prix_total']}€) - {$entry['client']}</p>
  </div>
{/foreach}
  <div class="pageoverflow">
    <p class="pagetext">&nbsp;</p>
    <p class="pageinput">{$submit}{$cancel}</p>
  </div>
{$formend}
</div>
{**}