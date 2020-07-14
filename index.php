<?php
include_once "scripts/sourcefeeds.php";
$cat = array();
foreach($sources as $source){
	$cat[] = $source->get_cat();
}
$cat = array_unique($cat);
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Latest News</title>
	<style>.navbar  {
		background-color: #efefef;
	}
	.container{
		padding-top:50px;
	}</style>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body onload="fetchFile()">
    <nav class="navbar navbar-expand-md navbar-light sticky-top">
        <a class="navbar-brand" href="/">
            News Aggregator
        </a>
    </nav>
	
	<div class = "container">
	<div class="row">
	<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
	<button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target="#categoriesModal">Change Categories</button>
	</div>
	</div>
	<div class="row" id="mainContent">
	</div>
	</div>
	<div class="modal fade" id="categoriesModal" tabindex="-1" role="dialog" aria-labelledby="categoriesModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalTitle">Select Categories</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	  <?php
	  foreach($cat as $category){
		  ?>
        <div class="checkbox">
			<label><input type="checkbox" name="categories" value="<?php echo $category; ?>" checked> <?php echo $category; ?></label>
		</div>
		<?php
	  }
		?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="fetchFile(false)">Update Feed</button>
      </div>
    </div>
  </div>
</div>
<footer class="page-footer font-small blue">
  <div class="footer-copyright text-center py-3">By Sanjit Dasgupta ©</div>
</footer>
	<input type="hidden" id="lastModified" value="Thu, 01 Jan 1970 00:00:01 GMT">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script src="feedreader.js"></script>
  </body>
</html>
