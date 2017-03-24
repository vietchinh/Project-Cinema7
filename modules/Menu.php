<!-- Section 1 - Create menu buttons from the database. -->
<!-- Date Creation: 18-3-2017 | Date Modification: 18-03-2017 -->
<!-- It fetch from the table menu in the database Cinema7. The fetch is an object and iterate the menu using foreach.-->
<!-- Fun Fact Section: The menu used to work by using GET -->
<form method="POST" id="navigationbarForm" class="displayIflex inheritHw justifyContentsa">
	<?php
		foreach(readMenudata($pdo, $level) as $key) {
			if (isset($_SESSION["level"]) && $key->PaginaNr == 2) {
				$text = "Film Reserveren";
			}
			else {
				$text = $key->Tekst;
			}
			?>
			<!-- Source: http://stackoverflow.com/questions/9073690/post-an-array-from-an-html-form-without-javascript -->
				<input type="submit" name="pageName[<?php echo $text; ?>]" value="<?php echo $text; ?>">
			<?php
		}
	?>
	<input type="submit" name="pageName[<?php echo $MenuInUitloggen; ?>]" value="<?php echo $MenuInUitloggen; ?>">
</form>