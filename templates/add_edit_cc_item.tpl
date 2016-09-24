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
{$commande_id}
{*$categorie_produit*}


<div class="pageoverflow">
  <p class="pagetext">Libellé:</p>
  <p class="pageinput">{$libelle_commande}</p>
</div>
<div class="pageoverflow">
  <p class="pagetext">Epaisseur Manche Taille:</p>
  <p class="pageinput">{$ep_manche_taille}</p>
</div>
<div class="pageoverflow">
  <p class="pagetext">Couleur:</p>
  <p class="pageinput">{$couleur}</p>
</div>
<div class="pageoverflow">
	<p class="pagetext">Quantité :</p>
	<p class="pageinput">{$quantite}</p>
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
