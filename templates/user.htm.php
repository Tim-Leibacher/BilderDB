<table class="table table-striped table-condensed">
        <thead>
        <tr>
            <th>Account Löschen</th>
            <th>Username</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $test = $_SESSION['username'];
        foreach(getValue("data") as $row) {
            if ($row['username'] == $_SESSION['username']){
            echo "<tr>
			  <td><a href='".getValue('phpmodule')."&uid=".$row['uid']."'>
			  <img src='../images/delete.png' border='no' onclick='return confdel();'></a></td>
			  <td><a href='".$_SERVER['PHP_SELF']."?id=register&action=edit&uid=".$row['uid']."'>".htmlTextAufbereiten($row['username'])."</a></td>
			  </tr>\n";
        }}
        ?>
        </tbody>
    </table>
<?php
$meldung = getValue("meldung");
if (strlen($meldung) > 0) echo "<div class='col-md-offset-2 col-md-6 alert alert-success'>$meldung</div>";
?>