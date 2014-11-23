<?php
	$_USR = $this->Session->read('Auth.User');
?>
<div class="wel">
	<h2>Admin Dashboard</h2>
	<?php echo $this->Html->link('Logout',array('controller'=>'users','action'=>'logout'),array('class'=>'logout'))?>
</div>
<div class="midtext">
    <form name="frmSearch" action="" method="get">
        <label>Search For :</label>
        <input name="term" type="Text" id="term" value="<?php echo @$this->request->query['term']?>">
        <input type="submit" value="Search">
    </form>
	<table>
        <thead>
            <th>Name</th>
            <th>City</th>
        </thead>
        <tbody>
            <?php
                if(!empty($users)){
                    foreach($users as $u){
            ?>
                    <tr>
                        <td><?php echo $u['User']['name']?></td>
                        <td><?php echo $u['User']['city']?></td>
                    </tr>
            <?php
                    }
                }
                else{
            ?>
            <tr><td colspan="2" align="center">No User Found</td></tr>
            <?php
                }
            ?>
        </tbody>
    </table>
</div>
<script>
    $( "#term" ).autocomplete({
      minLength: 1,
      source: "<?php echo Router::url(array('controller'=>'users','action'=>'search','admin'=>true))?>",
      select: function( event, ui ) {
				$( "#term" ).val( ui.item.res );
                $('#frmSearch').submit();
                return false;
        }
    })
    .data( "ui-autocomplete" )._renderItem = function( ul, item ) {
			return $( "<li>" )
				.append( "<a>" + item.res + "\t-\t<b>" + item.type + "</b></a>" )
				.appendTo( ul );
    };
    
</script>


