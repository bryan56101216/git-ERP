<?php


function afficher()
{
    if(require("bd.php"))
    {
        $req=$access-> prepare("SELECT * FROM projet ORDER BY idProjet DESC");
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
        $req=$access-> prepare("DELETE FROM projet WHERE idProjet=?");
        $req->execute(array($id));
       
}
}
if(isset($_GET['supprimer'])){
    $id = (int) $_GET['supprimer'];
    supprimer($id);
    header("Location: ../chef projet marketing/index.php");
}
?>
