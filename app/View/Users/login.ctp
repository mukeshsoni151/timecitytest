<script type="text/javascript">
window.fbAsyncInit = function() {
	FB.init({
	appId      : '<?php echo FB_APP_ID?>', // replace your app id here
	channelUrl : 'channel.html', 
	status     : true, 
	cookie     : true, 
	xfbml      : true  
	});
};
(function(d){
	var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
	if (d.getElementById(id)) {return;}
	js = d.createElement('script'); js.id = id; js.async = true;
	js.src = "//connect.facebook.net/en_US/all.js";
	ref.parentNode.insertBefore(js, ref);
}(document));

function FBLogin(){
	FB.login(function(response){
		if(response.authResponse){
			window.location.href = "<?php echo Router::url(array('controller'=>'users','action'=>'fblogin'))?>";
		}
	}, {scope: 'email,user_likes'});
}
</script>
<div id="login_cont">
    <h1>Login</h1>
    <div class="signup">
        <div class="user-reg">
            <?php echo $this->Session->flash('register');?>
			<h3>New User ? Register</h3>
            <?php echo $this->Form->create(null,array('url'=>array('controller'=>'users','action'=>'register')));?>
            <?php echo $this->Form->input('User.name');?>
            <?php echo $this->Form->input('User.city');?>
            <?php echo $this->Form->input('User.email');?>
            <?php echo $this->Form->input('User.password',array('type'=>'password'));?>
            <?php echo $this->Form->submit('Register');?>    
            <?php echo $this->Form->end();?>
        </div>
        
        
        <div class="user-login">
			<?php echo $this->Session->flash('login');?>
            <h3>Existing User ? Login</h3>
            <?php echo $this->Form->create(null,array('url'=>array('controller'=>'users','action'=>'login')));?>
            <?php echo $this->Form->input('User.email');?>
            <?php echo $this->Form->input('User.password',array('type'=>'password'));?>
            <?php echo $this->Form->submit('Login');?>    
            <?php echo $this->Form->end();?>
        </div>
    
    </div>
    <div class="fbsingup">
        <div>
            <?php
                echo $this->Html->image("fb.png", array("alt" => "Fb Login","onclick"=>'FBLogin()','class'=>'imgfb'));
            ?>
        </div>
    </div>
</div>