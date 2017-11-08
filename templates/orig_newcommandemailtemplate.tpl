{* Email envoyé au gestionnaire des commandes *}
{* liste des variables disponibles *}
<h3>Nouvelle commande</h3>
{if $itemcount > 0}
<h3>Détails de la commande N° {$commande_number}</h3>
<table border="0" cellspacing="0" cellpadding="0" class="pagetable">
 <thead>
	<tr>
		<th>Fournisseur</th>
		<th>Catégorie de produit</th>
		<th>Article</th>
		<th>Quantité</th>
		<th>Epaisseur, manche, taille</th>
		<th>Couleur</th>
		<th>Prix total article</th>
	</tr>
 </thead>
 <tbody>
{foreach from=$items item=entry}
  <tr class="{$entry->rowclass}">
	<td>{$entry->fournisseur}</td>
	<td>{$entry->categorie_produit}</td>
	<td>{$entry->libelle_commande}</td>
	<td>{$entry->quantite}</td>
	<td>{$entry->ep_manche_taille}</td>
	<td>{$entry->couleur}</td>
	<td>{$entry->prix_total}</td>	
  </tr>
{/foreach}
 </tbody>
</table>
{/if}