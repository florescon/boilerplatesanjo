<div class="modal fade modal-small" id="modal-qr" tabindex="-1" role="dialog" aria-labelledby="modal-qr" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-body z-bigger">
				<div class="container-fluid">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<i class="uil uil-multiply"></i>
					</button>
					<div class="row justify-content-center">
						<div class="col-12 text-center">
							<h5 class="mb-3">QR del sitio</h5>
						</div>
						<div class="col-12 text-center">
		                    {!! QrCode::size(100)->color(0, 0, 255)->generate('https://sanjoseuniformes.com'); !!}

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
