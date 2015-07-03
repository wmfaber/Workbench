<?php

echo $view;

?>
<div><p id='resultbox'></p></div>
<?php
echo $drop_user;
echo $drop_roles;

?>

<input type="submit" name="submit_role_user" class="btn btn-primary submit_role_user" value="toevoegen">
<input type=hidden id='nonce' value='<?php echo $nonce; ?>'></input>