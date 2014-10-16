<?php
// function to output form and hold previously entered values.

function user_form() {
    // if vars aren't set, set them as empty.
    // (prevents "notice" errors showing for those who have them enabled)
    if (!isset($_POST['first_name'])) $_POST['first_name'] = '';
    if (!isset($_POST['second_name'])) $_POST['second_name'] = '';
    if (!isset($_POST['age'])) $_POST['age'] = '';
    if (!isset($_POST['location'])) $_POST['location'] = '';
    if (!isset($_POST['gender'])) $_POST['gender'] = '';
    // now to output the form HTML.
    echo '<p>All fields are required.</p>';
    echo '<form action="'.htmlspecialchars($_SERVER['PHP_SELF']).'" method="post">';
    echo '<table border="0" cellspacing="4" cellpadding="0">';
    echo '<tr><td>First name:</td><td>
      <input type="text" name="first_name" value="'.htmlspecialchars($_POST['first_name']).'" size="20"></td></tr>';
    echo '<tr><td>Second name:</td><td>
      <input type="text" name="second_name" value="'.htmlspecialchars($_POST['second_name']).'" size="20"></td></tr>';
    echo '<tr><td>Age:</td><td>
      <input type="text" name="age" value="'.htmlspecialchars($_POST['age']).'" size="20"></td></tr>';
    echo '<tr><td>Location:</td><td>
      <input type="text" name="location" value="'.htmlspecialchars($_POST['location']).'" size="20"></td></tr>';
    // let's make the gender input a select box
    // you can see how we pre-select an option
    echo '<tr><td>Gender:</td><td><select name="gender">';
    echo '<option value="male"';
    if (strtolower($_POST['gender']) == 'male' || empty($_POST['gender'])) echo ' selected="selected"';
    echo '>Male</option>';
    echo '<option value="female"';
    if (strtolower($_POST['gender']) == 'female') echo ' selected="selected"';
    echo '>Female</option>';
    echo '</select></td></tr>';
    echo '<tr><td colspan="2"><input type="submit" value="Submit" name="submit"></td></tr>';
    echo '</table>';
    echo '</form>';
}

?>