<div class="pageoverflow">
{$formstart}
{$record_id}
{$commande_number}
{*$categorie_produit*}
{$fournisseur}


<div class="pageoverflow">
  <p class="pagetext">Catalogue</p>
  <p class="pageinput">{$nom_fournisseur}</p>
</div>
<div class="pageoverflow">
  <p class="pagetext">Description</p>
  <p class="pageinput">{$description}</p>
</div>
<div class="pageoverflow">
  <p class="pagetext">Actif</p>
  <p class="pageinput">{$actif}</p>
</div>
<div class="pageoverflow">
	<p class="pagetext">Ordre dans la liste :</p>
	<p class="pageinput">{$ordre}</p>
</div>
<!--<div class="pageoverflow">
	<p class="pagetext">Statut de l'article :</p>
	<p class="pageinput">{$statut_item}</p>
</div>-->
<div class="pageoverflow">
    <p class="pagetext">&nbsp;</p>
    <p class="pageinput">{$submit}{$cancel}</p>
  </div>
{$formend}
</div>
