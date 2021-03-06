<?php $this->load->view('partials/header'); ?> 
<?php $this->load->view('partials/menu'); ?>

<div class="container">
	<h4 class="modal-title">Reset Your Oulu Fab Lab Password</h4>
	<hr/>
	<p>Submit your email address and we'll send you a link to reset your password.</p>
	<form name="login" method="post"
		action="<?php echo base_url();?>user/forget_password">
	<input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
	<p>Email</p>
	<p><input type="email" class="form-control" name="email" id="email"
				style="width: 200px;" required="" autofocus=""
				placeholder="Email" /></p>
	<p><button type="submit" class='btn btn-success'>Reset password</button></p>
	</form>
	
</div>

<?php $this->load->view('partials/footer'); ?>