{*<pre>{$rowarray|var_dump}</pre>*}
{**}<div class="pageoverflow">
{$formstart}

<div class="pageoverflow">
    <p class="pagetext">Commande Fournisseur N°:</p>
    <p class="pageinput">{$record_id}</p>
</div>
<p>Qté- libellé commande</p>

{foreach from=$rowarray key=key item=entry}
<div class="pageoverflow">
    <p class="pageinput"><input type="checkbox"  name="m1_id_CF[{$key}]" id="m1_id_CF[{$key}]" {if $entry['participe'] ==1}checked='checked' {/if} value = '1'>{$entry['commande']} ({$entry['prix_total']}€)</p>
  </div>
{/foreach}
  <div class="pageoverflow">
    <p class="pagetext">&nbsp;</p>
    <p class="pageinput">{$submit}{$cancel}</p>
  </div>
{$formend}
</div>
{**}