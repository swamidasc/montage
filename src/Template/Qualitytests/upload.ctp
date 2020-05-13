<script src="/js/dropzone.js"></script>


<?php if (isset($newurl)) { ?>
	<p><a href="<?=$newurl?>"><?=$newurl?></a></p>
<?php }?>


<form action="/qualitytests/upload/doit" method="post" enctype="multipart/form-data">
  <input type="file" name="file" /><br>
  <input type="submit" value="submit">
</form>
 

