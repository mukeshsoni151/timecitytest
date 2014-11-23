<?php
	$_USR = $this->Session->read('Auth.User');
?>
<div class="wel">
	<h2>Dashboard</h2>
	<?php echo $this->Html->link('Logout',array('controller'=>'users','action'=>'logout'),array('class'=>'logout'))?>
</div>

<div class="midtext">
	<p>Hi, welcome : <?php echo $_USR['email'];?></p>
</div>

