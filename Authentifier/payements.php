<?php


function afficherPayement()
{
    if(require("bd.php"))
    {
        $req=$access-> prepare("SELECT * FROM payements ORDER BY idPayement DESC");
        $req->execute();
        $data = $req -> fetchAll(PDO::FETCH_OBJ);
        return $data;
        $req->closeCursor();
    }
}
function supprimerPayement($id)
{
     if(require("bd.php"))
    {
        $req=$access-> prepare("DELETE FROM payements WHERE idPayement=?");
        $req->execute(array($id));
       
}
}
if(isset($_GET['supprimer'])){
    $id = (int) $_GET['supprimer'];
    supprimer($id);
    header("Location: ../comptable/index.php");
}
?>
