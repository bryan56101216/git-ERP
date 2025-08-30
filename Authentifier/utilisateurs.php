<?php


function afficher()
{
    if(require("bd.php"))
    {
        $req=$access-> prepare("SELECT * FROM utilisateurs ORDER BY id DESC");
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
        $req=$access-> prepare("DELETE FROM utilisateurs WHERE id=?");
        $req->execute(array($id));
       
}
}
if(isset($_GET['supprimer'])){
    $id = (int) $_GET['supprimer'];
    supprimer($id);
    header("Location: ../administrateur/index.php");
}
?>
