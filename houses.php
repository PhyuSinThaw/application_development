<?php include('db_connect.php'); ?>

<div class="container-fluid">

	<div class="col-lg-12">
		<div class="row">
			<!-- FORM Panel -->
			<div class="col-md-4">
				<form action="" id="manage-house">
					<div class="card">
						<div class="card-header">
							House Form
						</div>
						<div class="card-body">
							<div class="form-group" id="msg"></div>
							<input type="hidden" name="id">
							<div class="form-group">
								<label class="control-label">House No</label>
								<input type="text" class="form-control" name="house_no" required="">
							</div>
							<div class="form-group">
								<label for="" class="control-label">Description</label>
								<textarea name="description" id="" cols="30" rows="4" class="form-control" required></textarea>
							</div>
							<div class="form-group">
								<label class="control-label">Category</label>
								<select name="category_id" id="" class="custom-select" required>
									<?php
									$categories = $conn->query("SELECT * FROM categories order by name asc");
									if ($categories->num_rows > 0) :
										while ($row = $categories->fetch_assoc()) :
									?>
											<option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
										<?php endwhile; ?>
									<?php else : ?>
										<option selected="" value="" disabled="">Please check the category list.</option>
									<?php endif; ?>
								</select>
							</div>
							<div class="form-group">
								<label class="control-label">City</label>
								<select name="city_id" id="" class="custom-select" required>
									<?php
									$city = $conn->query("SELECT * FROM city order by name asc");
									if ($city->num_rows > 0) :
										while ($row = $city->fetch_assoc()) :
									?>
											<option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
										<?php endwhile; ?>
									<?php else : ?>
										<option selected="" value="" disabled="">Please check the city list.</option>
									<?php endif; ?>
								</select>
							</div>
							<div class="form-group">
								<label class="control-label">Price</label>
								<input type="number" class="form-control text-right" name="price" step="any" required="">
							</div>
							<div class="form-group">
								<label class="control-label">Room</label>
								<input type="number" class="form-control text-right" name="room" step="any" required="">
							</div>
							<div class="form-group">
								<label class="control-label">Area</label>
								<input type="number" class="form-control text-right" name="area" step="any" required="">
							</div>
							<div class="form-group">
								<label class="control-label">Image</label>
								<input type="file" class="form-control-file" name="image" enctype="multipart/form-data">
							</div>
						</div>
						<div class="card-footer">
							<div class="row">
								<div class="col-md-12">
									<button class="btn btn-sm btn-primary col-sm-3 offset-md-3"> Save</button>
									<button class="btn btn-sm btn-default col-sm-3" type="reset"> Cancel</button>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">
						<b>House List</b>
					</div>
					<div class="card-body">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="text-center">House</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$i = 1;
								$house = $conn->query("SELECT h.*, c.name as cname, ci.name as cityname FROM houses h INNER JOIN categories c ON c.id = h.category_id INNER JOIN city ci ON ci.id = h.city_id ORDER BY h.id ASC");

								while ($row = $house->fetch_assoc()) :
								?>
									<tr>
										<td class="text-center"><?php echo $i++ ?></td>
										<td class="">
											<p>House #: <b><?php echo $row['house_no'] ?></b></p>
											<p><small>Description: <b><?php echo $row['description'] ?></b></small></p>
											<p><small>House Type: <b><?php echo $row['cname'] ?></b></small></p>
											<p><small>City: <b><?php echo $row['cityname'] ?></b></small></p>
											<p><small>Price: <b><?php echo number_format($row['price'], 2) ?></b></small></p>
											<p><small>Room: <b><?php echo number_format($row['room'], 2) ?></b></small></p>
											<p><small>Area: <b><?php echo number_format($row['area'], 2) ?></b></small></p>
											<p><small>Image:<br>
													<?php if (!empty($row['image'])) : ?>
														<img src="uploads/<?php echo $row['image'] ?>" alt="House Image" style="max-width: 100px; max-height: 100px;">
													<?php endif; ?>
											</small></p>
										</td>



										<td class="text-center">
											<button class="btn btn-sm btn-primary edit_house" type="button" data-id="<?php echo $row['id'] ?>" data-house_no="<?php echo $row['house_no'] ?>" data-description="<?php echo $row['description'] ?>" data-category_id="<?php echo $row['category_id'] ?>" data-city_id="<?php echo $row['city_id'] ?>" data-price="<?php echo $row['price'] ?>" data-room="<?php echo $row['room'] ?>" data-area="<?php echo $row['area'] ?>">Edit</button>
											<button class="btn btn-sm btn-danger delete_house" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
										</td>
									</tr>
								<?php endwhile; ?>
							</tbody>

						</table>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>

</div>
<style>
	td {
		vertical-align: middle !important;
	}

	td p {
		margin: unset;
		padding: unset;
		line-height: 1em;
	}
</style>
<script>
	$('#manage-house').on('reset', function(e) {
		$('#msg').html('')
	})
	$('#manage-house').submit(function(e) {
		e.preventDefault()
		start_load()
		$('#msg').html('')
		$.ajax({
			url: 'ajax.php?action=save_house',
			data: new FormData($(this)[0]),
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Data successfully saved", 'success')
					setTimeout(function() {
						location.reload()
					}, 1500)

				} else if (resp == 2) {
					$('#msg').html('<div class="alert alert-danger">House number already exist.</div>')
					end_load()
				}
			}
		})
	})
	$('.edit_house').click(function() {
		start_load();
		var cat = $('#manage-house');
		cat.get(0).reset();
		cat.find("[name='id']").val($(this).attr('data-id'));
		cat.find("[name='house_no']").val($(this).attr('data-house_no'));
		cat.find("[name='description']").val($(this).attr('data-description'));
		cat.find("[name='price']").val($(this).attr('data-price'));
		cat.find("[name='category_id']").val($(this).attr('data-category_id'));
		cat.find("[name='city_id']").val($(this).attr('data-city_id'));
		cat.find("[name='room']").val($(this).attr('data-room'));
		cat.find("[name='area']").val($(this).attr('data-area'));
		cat.find("[name='image']").val($(this).attr('data-image'));
		//cat.find("#current_image").attr("src", $(this).attr('data-image'));

		end_load();
	});



	$('.delete_house').click(function() {
		_conf("Are you sure to delete this house?", "delete_house", [$(this).attr('data-id')])
	})

	function delete_house($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_house',
			method: 'POST',
			data: {
				id: $id
			},
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Data successfully deleted", 'success')
					setTimeout(function() {
						location.reload()
					}, 1500)

				}
			}
		})
	}
	$('table').dataTable()
</script>