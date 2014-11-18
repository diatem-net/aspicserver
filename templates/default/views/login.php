<section role="login">
    <div class="content">
	<div class="welcome">
	    <?php echo __trad('login_welcome'); ?>
	</div>
    <form method="POST" action="">
	<input <?php if($GLOBALS['controler']->getError()){ echo 'class="erreur"'; } ?> type="text" name="login" placeholder="<?php echo __trad('login_form_login'); ?>">
	<input <?php if($GLOBALS['controler']->getError()){ echo 'class="erreur"'; } ?> type="password" name="password" placeholder="<?php echo __trad('login_form_password'); ?>">
	<button type="submit" class="btn btn-success">
	    <?php echo __trad('login_form_submit'); ?>
	    <i class="flaticon-ok5"></i>
	</button>
    </form>
    <?php if($GLOBALS['controler']->getError()){ 
	echo '<div class="small_erreur">'.$GLOBALS['controler']->getError().'</div>';
    }
    ?>
    </div>
    
</section>