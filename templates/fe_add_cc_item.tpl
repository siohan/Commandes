<div class="pageoverflow">
{form_start}
<input type="hidden" name="{$actionid}libelle_selected" value="{$actionid}{$libelle_selected}"/>
<input type="hidden" name="{$actionid}commande_number" value="{$commande_number}"/>

<input type="hidden" name="{$actionid}fournisseur" value="{$fournisseur}"/>
<input type="hidden" name="{$actionid}display" value="{$display}"/>
<input type="hidden" name="{$actionid}record_id" value="{$record_id}"/>
<p class="pagetext">Produits:</p>
        <p class="pageinput">
<select id="{$actionid}produits" name="{$actionid}produits">
	{html_options options=$produits selected=$libelle_selected}
</select>
<p class="pagetext">Epaisseur -Manche-Taille:</p>
        <p class="pageinput">
<input type="text" name="{$actionid}ep_manche_taille" value="{$ep_manche_taille}"/>
		</p>

<p class="pagetext">Couleur:</p>
        <p class="pageinput">
<input type="text" name="{$actionid}couleur" value="{$couleur}"/>
	</p>
</p>
<p class="pagetext">Quantité:</p>
        <p class="pageinput">
<input type="text" name="{$actionid}quantite" value="{$quantite}"/>
	</p>
</p>

<div class="pageoverflow">
     <p class="pageinput">
       <input type="submit" name="{$actionid}submit" value="{$mod->Lang('submit')}"/>
       <input type="submit" name="{$actionid}cancel" value="{$mod->Lang('cancel')}"/>
	</p> 
</div>
{form_end}
</div>
