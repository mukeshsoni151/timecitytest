<div class="">
    <h2>Administrator Login</h2>
    <?php echo $this->Form->create('User',array('url'=>array('controller'=>'users','action'=>'login','admin'=>true)));?>
        <?php echo $this->Form->input('User.email');?>
        <?php echo $this->Form->input('User.password',array('type'=>'password'));?>
        <?php echo $this->Form->submit('Sing In');?>
    <?php echo $this->Form->end();?>
</div>
