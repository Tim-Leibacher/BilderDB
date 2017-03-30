<div class="col-md-12">
<form name="ort" class="form-horizontal form-condensed" action="<?php echo getValue("phpmodule"); ?>" method="post">
  <div class="form-group control-group">
	<label class="control-label col-md-offset-2 col-md-2" for="plz">PLZ *</label>
	<div class="col-md-4">
	  <input type="text" class="form-control" id="plz" name="plz" value="<?php echo getHtmlValue("plz"); ?>" />
	</div>
  </div>
  <div class="form-group control-group">
	<label class="control-label col-md-offset-2 col-md-2" for="ort">Ort</label>
	<div class="col-md-4">
	  <input type="text" class="form-control" id="ort" name="ort" value="<?php echo getHtmlValue("ort"); ?>" />
	</div>
  </div>
  <div class="form-group control-group">
	<div class="col-md-offset-4 col-md-4">
	  <button type="submit" class="btn btn-success" name="speichern" id="speichern">speichern</button>
	  <a href="<?php echo $_SERVER['PHP_SELF']."?id=ortliste"; ?>" class="btn btn-warning">abbrechen</a>
	  </div>
  </div>
</form>
</div>
<?php
  $meldung = getValue("meldung");
  if (strlen($meldung) > 0) echo "<div class='col-md-offset-2 col-md-6 alert alert-danger'>$meldung</div>";
?>
