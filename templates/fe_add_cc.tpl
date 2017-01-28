<h3>Etape 1 : choix du fournisseur </h3>
{form_start}
 
<input type="hidden" name="{$actionid}client" value="{$client}"/>
<input type="hidden" name="{$actionid}display" value="{$display}"/>
<input type="hidden" name="{$actionid}commande_number" value="{$commande_number}"/>
    
<div class="pageoverflow">


        <p class="pageinput">
           <select id="{$actionid}fournisseur" name="{$actionid}fournisseur">
			{html_options options=$fournisseur}{* selected=$fournisseur*}
			</select>
        </p>

</div>
<div class="pageoverflow">
     <p class="pageinput">
       <input type="submit" name="{$actionid}submit" value="{$mod->Lang('submit')}"/>
       <input type="submit" name="{$actionid}cancel" value="{$mod->Lang('cancel')}"/>
	</p> 
</div>     

{form_end}
