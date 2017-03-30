<table class="table table-striped table-condensed">
  <thead>
	<tr>
	  <th></th>
	  <th>PLZ</th>
	  <th>Ort</th>
	</tr>
  </thead>
  <tbody>
	<?php
	  foreach(getValue("data") as $row) {
		echo "<tr>
			  <td><a href='".getValue('phpmodule')."&oid=".$row['oid']."'>
			  <img src='../images/delete.png' border='no' onclick='return confdel();'></a></td>
			  <td><a href='".$_SERVER['PHP_SELF']."?id=orterfassen&action=edit&oid=".$row['oid']."'>".htmlTextAufbereiten($row['plz'])."</a></td>
			  <td>".htmlTextAufbereiten($row['ort'])."</td>
			  </tr>\n";
	  }
	?>
  </tbody>
</table>
<?php
  $meldung = getValue("meldung");
  if (strlen($meldung) > 0) echo "<div class='col-md-offset-2 col-md-6 alert alert-success'>$meldung</div>";
?>
