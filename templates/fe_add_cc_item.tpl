<div class="pageoverflow">
{form_start}

<input type="hidden" name="{$actionid}commande_number" value="{$commande_number}"/>

<input type="text" name="{$actionid}fournisseur" value="{$fournisseur}"/>
<input type="text" name="{$actionid}display" value="{$display}"/>
<input type="text" name="{$actionid}record_id" value="{$record_id}"/>
<p class="pagetext">Produits:</p>
        <p class="pageinput">
<select id="{$actionid}produits" name="{$actionid}produits">
	{html_options options=$produits}
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
