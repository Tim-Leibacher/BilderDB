<div class="col-md-12">
<form name="user" class="form-horizontal form-condensed" action="<?php echo getValue("phpmodule"); ?>" method="post">
  <div class="form-group control-group">
	<label class="control-label col-md-offset-2 col-md-2" for="username">Username *</label>
	<div class="col-md-4">
	  <input type="text" class="form-control" id="username" name="username" value="<?php echo getHtmlValue("username"); ?>" />
	</div>
  </div>
  <div class="form-group control-group">
	<label class="control-label col-md-offset-2 col-md-2" for="password">Password</label>
	<div class="col-md-4">
	  <input type="password" class="form-control" id="password" name="password" value="<?php echo getHtmlValue("password"); ?>" />
	</div>
  </div>
  <div class="form-group control-group">
	<label class="control-label col-md-offset-2 col-md-2" for="rPassword">Repeat Password</label>
	<div class="col-md-4">
	  <input type="password" class="form-control" id="rPassword" name="rPassword" value="<?php echo getHtmlValue("rPassword"); ?>" />
	</div>
  </div>
  <div class="form-group control-group">
	<div class="col-md-offset-4 col-md-4">
	  <button type="submit" class="btn btn-success" name="speichern" id="speichern">speichern</button>
	  <a href="<?php echo $_SERVER['PHP_SELF']."?id=bilder"; ?>" class="btn btn-warning">abbrechen</a>
	  </div>
  </div>
</form>
</div>
<?php
  $meldung = getValue("meldung");
  if (strlen($meldung) > 0) echo "<div class='col-md-offset-2 col-md-6 alert alert-danger'>$meldung</div>";
?>
