<?php


function afficher()
{
    if(require("bd.php"))
    {
        $req=$access-> prepare("SELECT * FROM facture ORDER BY idFacture DESC");
        $req->execute();
        $data = $req -> fetchAll(PDO::FETCH_OBJ);
        return $data;
        $req->closeCursor();
    }
}
function supprimer($id)
{
     if(require("bd.php"))
    {
        $req=$access-> prepare("DELETE FROM facture WHERE idFacture=?");
        $req->execute(array($id));
       
}
}
if(isset($_GET['supprimer'])){
    $id = (int) $_GET['supprimer'];
    supprimer($id);
    header("Location: ../comptable/index.php");
}
?>
