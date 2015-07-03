<nav class="navbar-fluid navbar-inverse">
<div class="container">

<div class="navbar-header">
  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
  <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
  <a class="navbar-brand" href="<?php echo $this->config->item('base_url'); ?>"><?php echo $portal['title']; ?></a>
</div>
	
<div id="navbar" class="collapse navbar-collapse">
  <ul class="nav navbar-nav">
    <?php 
    error_reporting(E_ALL);
    foreach($menu as $row)
    { 
    	if(count($row) == 1)
    	{
    		foreach($row as $key => $value)
	    	{ 
	    		if($this->lang->line('menu_'.strtolower($value)) == ''){
	    		$name =  $value;
	    		}else{
	    		$name = $this->lang->line('menu_'.strtolower($value));
	    		}
	    		if(strtolower($value) == strtolower($page_title))
		  		{
  					echo "<li class='active'><a href='".strtolower($key)."' >".$name."</a></li>";
  				}
  				else
  				{
  					if( $this->lang->line('menu_'.strtolower($value)) == ''){
  						echo "<li><a href='".strtolower($key)."'>".$name."</a></li>";
  					}else{
  					echo "<li><a href='".strtolower($key)."'>".$name."</a></li>";
  				}
  				}
	    	}
    	}
    	else
    	{
    		echo '<li class="dropdown"><a href="#" data-toggle="dropdown" class="dropdown-toggle">'.reset($row).' <b class="caret"></b></a><ul class="dropdown-menu">';
    		foreach($row as $key => $value)
	    	{
	    		if(strtolower($value) == strtolower($page_title))
		  		{
  					echo "<li class='active'><a href='".$key."' >".$this->lang->line('menu_'.strtolower($value))."</a></li>";
  				}
  				else
  				{
  					echo "<li><a href='".$key."'>".$this->lang->line('menu_'.strtolower($value))."</a></li>";
  				}
	    	}
    		echo '</ul></li>';
    	}
	  }
	  ?>
  </ul>
</div>
</div>
</nav>