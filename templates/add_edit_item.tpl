{literal}
<script>
 $(function() {
   $( "#m1_date_debut" ).datepicker({ dateFormat: "yy-mm-dd" });
 });
 </script>
{/literal}
<div class="pageoverflow">
{$formstart}
{$record_id}



<div class="pageoverflow">
  <p class="pagetext">Catégorie </p>
  <p class="pageinput">{$categorie}</p>
</div>
<div class="pageoverflow">
  <p class="pagetext">Libellé:</p>
  <p class="pageinput">{$libelle}</p>
</div>
<div class="pageoverflow">
  <p class="pagetext">Fournisseur :</p>
  <p class="pageinput">{$fournisseur}</p>
</div>
<div class="pageoverflow">
  <p class="pagetext">Référence catalogue:</p>
  <p class="pageinput">{$reference}</p>
</div>
<div class="pageoverflow">
	<p class="pagetext">Marque :</p>
	<p class="pageinput">{$marque}</p>
</div>
<div class="pageoverflow">
	<p class="pagetext">Prix unitaire :</p>
	<p class="pageinput">{$prix_unitaire}</p>
</div>
<div class="pageoverflow">
	<p class="pagetext">Réduction :</p>
	<p class="pageinput">{$reduction}</p>
</div>
<div class="pageoverflow">
	<p class="pagetext">Statut de l'article :</p>
	<p class="pageinput">{$statut_item}</p>
</div>
<div class="pageoverflow">
    <p class="pagetext">&nbsp;</p>
    <p class="pageinput">{$submit}{$cancel}</p>
  </div>
{$formend}
</div>
